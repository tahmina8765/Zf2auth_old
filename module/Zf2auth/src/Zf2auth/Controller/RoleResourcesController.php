<?php

namespace Zf2auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zf2auth\Entity\RoleResources;
use Zf2auth\Form\RoleResourcesForm;
use Zf2auth\Form\RoleResourcesSearchForm;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
use Application\Controller\AppController;

class RoleResourcesController extends AppController
{

    protected $role_resourcesTable;

    public function getRole_resourcesTable()
    {
        if (!$this->role_resourcesTable) {
            $sm                        = $this->getServiceLocator();
            $this->role_resourcesTable = $sm->get('Zf2auth\Table\RoleResourcesTable');
        }
        return $this->role_resourcesTable;
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
        $searchform = new RoleResourcesSearchForm();
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
            if (!empty($formdata['role_id'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('role_id', '%' . $formdata['role_id'] . '%')
                );
            }
            if (!empty($formdata['resource_id'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('resource_id', '%' . $formdata['resource_id'] . '%')
                );
            }
        }
        if (!empty($where)) {
            $select->where($where);
        }


        $role_resources = $this->getRole_resourcesTable()->fetchAll($select);
        $totalRecord    = $role_resources->count();
        $itemsPerPage   = 2;

        $role_resources->current();
        $paginator = new Paginator(new paginatorIterator($role_resources));
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
            'pageAction'  => 'role_resources',
            'form'        => $searchform,
            'totalRecord' => $totalRecord
        ));
    }

    public function addAction()
    {
        $form = new RoleResourcesForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $role_resources = new RoleResources();
            $form->setInputFilter($role_resources->getInputFilter());
            $formData       = $request->getPost();
            $form->setData($formData);
            if ($form->isValid()) {
                $role_resources->exchangeArray($form->getData());
                if (!empty($formData['resource_id'])) {
                    $this->getRole_resourcesTable()->saveRole_resources($role_resources);
                } else {
                    $this->getRole_resourcesTable()->saveAll_role_resources($role_resources);
                }

                // Redirect to list of role_resourcess
                return $this->redirect()->toRoute('role_resources');
            }
        }


        /**
         * Get Role List
         */
        $roleOptions = array();
        $roles       = $this->getServiceLocator()->get("Zf2auth\Table\RolesTable")->fetchAll();
        if (count($roles) > 0) {
            foreach ($roles as $row)
                $roleOptions[$row->id] = $row->name;
        }
        $form->get('role_id')->setOptions(array('value_options' => $roleOptions));


        /**
         * Get Resources List
         */
        $resourceOptions = array();
        $resources       = $this->getServiceLocator()->get("Zf2auth\Table\ResourcesTable")->fetchAll();
        if (count($resources) > 0) {
            $resourceOptions[0]        = 'All Resources';
            foreach ($resources as $row)
                $resourceOptions[$row->id] = $row->name;
        }
        $form->get('resource_id')->setOptions(array('value_options' => $resourceOptions));


        return array('form' => $form);
    }

    // Add content to this method:
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('role_resources', array(
                        'action' => 'add'
            ));
        }
        $role_resources = $this->getRole_resourcesTable()->getRole_resources($id);

        $form = new RoleResourcesForm();
        $form->bind($role_resources);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($role_resources->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getRole_resourcesTable()->saveRole_resources($form->getData());

                // Redirect to list of role_resourcess
                return $this->redirect()->toRoute('role_resources');
            }
        }

        /**
         * Get Role List
         */
        $roleOptions = array();
        $roles       = $this->getServiceLocator()->get("Zf2auth\Entity\RolesTable")->fetchAll();
        if (count($roles) > 0) {
            foreach ($roles as $row)
                $roleOptions[$row->id] = $row->name;
        }
        $form->get('role_id')->setOptions(array('value_options' => $roleOptions));


        /**
         * Get Resources List
         */
        $resourceOptions = array();
        $resources       = $this->getServiceLocator()->get("Zf2auth\Entity\ResourcesTable")->fetchAll();
        if (count($resources) > 0) {
            foreach ($resources as $row)
                $resourceOptions[$row->id] = $row->name;
        }
        $form->get('resource_id')->setOptions(array('value_options' => $resourceOptions));

        return array(
            'id'   => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('role_resources');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {

            $id = (int) $request->getPost('id');
            $this->getRole_resourcesTable()->deleteRole_resources($id);


            // Redirect to list of role_resourcess
            return $this->redirect()->toRoute('role_resources');
        }

        return array(
            'id'             => $id,
            'role_resources' => $this->getRole_resourcesTable()->getRole_resources($id)
        );
    }

}
