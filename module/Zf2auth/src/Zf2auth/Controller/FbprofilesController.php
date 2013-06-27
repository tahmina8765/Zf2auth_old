<?php

namespace Zf2auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zf2auth\Entity\Fbprofiles;
use Zf2auth\Form\FbprofilesForm;
use Zf2auth\Form\FbprofilesSearchForm;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;

class FbprofilesController extends AbstractActionController
{

    protected $fbprofilesTable;

    public function getFbprofilesTable()
    {
        if (!$this->fbprofilesTable) {
            $sm                    = $this->getServiceLocator();
            $this->fbprofilesTable = $sm->get('Zf2auth\Table\FbprofilesTable');
        }
        return $this->fbprofilesTable;
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
        $searchform = new FbprofilesSearchForm();
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
            if (!empty($formdata['first_name'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('first_name', '%' . $formdata['first_name'] . '%')
                );
            }
            if (!empty($formdata['last_name'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('last_name', '%' . $formdata['last_name'] . '%')
                );
            }
            if (!empty($formdata['link'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('link', '%' . $formdata['link'] . '%')
                );
            }
            if (!empty($formdata['username'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('username', '%' . $formdata['username'] . '%')
                );
            }
            if (!empty($formdata['gender'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('gender', '%' . $formdata['gender'] . '%')
                );
            }
            if (!empty($formdata['timezone'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('timezone', '%' . $formdata['timezone'] . '%')
                );
            }
            if (!empty($formdata['locale'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('locale', '%' . $formdata['locale'] . '%')
                );
            }
            if (!empty($formdata['verified'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('verified', '%' . $formdata['verified'] . '%')
                );
            }
            if (!empty($formdata['updated_time'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('updated_time', '%' . $formdata['updated_time'] . '%')
                );
            }
        }
        if (!empty($where)) {
            $select->where($where);
        }


        $fbprofiles   = $this->getFbprofilesTable()->fetchAll($select);
        $totalRecord  = $fbprofiles->count();
        $itemsPerPage = 2;

        $fbprofiles->current();
        $paginator = new Paginator(new paginatorIterator($fbprofiles));
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
            'pageAction'  => 'fbprofiles',
            'form'        => $searchform,
            'totalRecord' => $totalRecord
        ));
    }

    public function addAction()
    {
        $form = new FbprofilesForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $fbprofiles = new Fbprofiles();
            $form->setInputFilter($fbprofiles->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $fbprofiles->exchangeArray($form->getData());
                $this->getFbprofilesTable()->saveFbprofiles($fbprofiles);

                // Redirect to list of fbprofiless
                return $this->redirect()->toRoute('fbprofiles');
            }
        }
        return array('form' => $form);
    }

    // Add content to this method:
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('fbprofiles', array(
                        'action' => 'add'
            ));
        }
        $fbprofiles = $this->getFbprofilesTable()->getFbprofiles($id);

        $form = new FbprofilesForm();
        $form->bind($fbprofiles);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($fbprofiles->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getFbprofilesTable()->saveFbprofiles($form->getData());

                // Redirect to list of fbprofiless
                return $this->redirect()->toRoute('fbprofiles');
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
            return $this->redirect()->toRoute('fbprofiles');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {

            $id = (int) $request->getPost('id');
            $this->getFbprofilesTable()->deleteFbprofiles($id);


            // Redirect to list of fbprofiless
            return $this->redirect()->toRoute('fbprofiles');
        }

        return array(
            'id'         => $id,
            'fbprofiles' => $this->getFbprofilesTable()->getFbprofiles($id)
        );
    }

}
