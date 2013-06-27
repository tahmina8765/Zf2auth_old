<?php

namespace Zf2auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zf2auth\Entity\Resources;
use Zf2auth\Form\ResourcesForm;
use Zf2auth\Form\ResourcesSearchForm;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
use Application\Controller\AppController;

class ResourcesController extends AppController
{

    protected $resourcesTable;

    public function getResourcesTable()
    {
        if (!$this->resourcesTable) {
            $sm                   = $this->getServiceLocator();
            $this->resourcesTable = $sm->get('Zf2auth\Table\ResourcesTable');
        }
        return $this->resourcesTable;
    }

    public function searchAction()
    {

        $request = $this->getRequest();

        $url = 'index';

        if ($request->isPost()) {
            $formdata    = (array) $request->getPost();
            $search_data = array();
            foreach ($formdata as $key => $value) {
                if ($key != 'submit') {
                    if (!empty($value)) {
                        $search_data[$key] = $value;
                    }
                }
            }
            if (!empty($search_data)) {
                $search_by = json_encode($search_data);
                $url .= '/search_by/' . $search_by;
            }
        }
        $this->redirect()->toUrl($url);
    }

    public function indexAction()
    {
        $searchform = new ResourcesSearchForm();
        $searchform->get('submit')->setValue('Search');

        $select = new Select();

        $order_by  = $this->params()->fromRoute('order_by') ?
                $this->params()->fromRoute('order_by') : 'id';
        $order     = $this->params()->fromRoute('order') ?
                $this->params()->fromRoute('order') : Select::ORDER_ASCENDING;
        $page      = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        $select->order($order_by . ' ' . $order);
        $search_by = $this->params()->fromRoute('search_by') ?
                $this->params()->fromRoute('search_by') : '';


        $where    = new \Zend\Db\Sql\Where();
        $formdata = array();
        if (!empty($search_by)) {
            $formdata = (array) json_decode($search_by);
            if (!empty($formdata['name'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('name', '%' . $formdata['name'] . '%')
                );
            }
        }
        if (!empty($where)) {
            $select->where($where);
        }


        $resources    = $this->getResourcesTable()->fetchAll($select);
        $totalRecord  = $resources->count();
        $itemsPerPage = 2;

        $resources->current();
        $paginator = new Paginator(new paginatorIterator($resources));
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(7);

        $searchform->setData($formdata);

        return new ViewModel(array(
            'search_by'   => $search_by,
            'order_by'    => $order_by,
            'order'       => $order,
            'page'        => $page,
            'paginator'   => $paginator,
            'pageAction'  => 'resources',
            'form'        => $searchform,
            'totalRecord' => $totalRecord
        ));
    }

    public function addAction()
    {
        $form = new ResourcesForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $resources = new Resources();
            $form->setInputFilter($resources->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $resources->exchangeArray($form->getData());
                $this->getResourcesTable()->saveResources($resources);

                // Redirect to list of resourcess
                return $this->redirect()->toRoute('resources');
            }
        }
        return array('form' => $form);
    }

    // Add content to this method:
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('resources', array(
                        'action' => 'add'
            ));
        }
        $resources = $this->getResourcesTable()->getResources($id);

        $form = new ResourcesForm();
        $form->bind($resources);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($resources->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getResourcesTable()->saveResources($form->getData());

                // Redirect to list of resourcess
                return $this->redirect()->toRoute('resources');
            }
        }

        return array(
            'id'   => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('resources');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {

            $id = (int) $request->getPost('id');
            $this->getResourcesTable()->deleteResources($id);


            // Redirect to list of resourcess
            return $this->redirect()->toRoute('resources');
        }

        return array(
            'id'        => $id,
            'resources' => $this->getResourcesTable()->getResources($id)
        );
    }

}
