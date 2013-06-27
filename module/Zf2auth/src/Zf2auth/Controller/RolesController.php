<?php

namespace Zf2auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zf2auth\Entity\Roles;
use Zf2auth\Form\RolesForm;
use Zf2auth\Form\RolesSearchForm;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
use Application\Controller\AppController;

class RolesController extends AppController
{

    protected $rolesTable;

    public function getRolesTable()
    {
        if (!$this->rolesTable) {
            $sm               = $this->getServiceLocator();
            $this->rolesTable = $sm->get('Zf2auth\Table\RolesTable');
        }
        return $this->rolesTable;
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
        $searchform = new RolesSearchForm();
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


        $roles        = $this->getRolesTable()->fetchAll($select);
        $totalRecord  = $roles->count();
        $itemsPerPage = 2;

        $roles->current();
        $paginator = new Paginator(new paginatorIterator($roles));
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
            'pageAction'  => 'roles',
            'form'        => $searchform,
            'totalRecord' => $totalRecord
        ));
    }

    public function addAction()
    {
        $form = new RolesForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $roles = new Roles();
            $form->setInputFilter($roles->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $roles->exchangeArray($form->getData());
                $this->getRolesTable()->saveRoles($roles);

                // Redirect to list of roless
                return $this->redirect()->toRoute('roles');
            }
        }
        return array('form' => $form);
    }

    // Add content to this method:
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('roles', array(
                        'action' => 'add'
            ));
        }
        $roles = $this->getRolesTable()->getRoles($id);

        $form = new RolesForm();
        $form->bind($roles);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($roles->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getRolesTable()->saveRoles($form->getData());

                // Redirect to list of roless
                return $this->redirect()->toRoute('roles');
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
            return $this->redirect()->toRoute('roles');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {

            $id = (int) $request->getPost('id');
            $this->getRolesTable()->deleteRoles($id);


            // Redirect to list of roless
            return $this->redirect()->toRoute('roles');
        }

        return array(
            'id'    => $id,
            'roles' => $this->getRolesTable()->getRoles($id)
        );
    }

}
