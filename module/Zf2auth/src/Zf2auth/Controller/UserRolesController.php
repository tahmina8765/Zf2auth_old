<?php

namespace Zf2auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zf2auth\Entity\User_roles;
use Zf2auth\Form\User_rolesForm;
use Zf2auth\Form\User_rolesSearchForm;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
use Application\Controller\AppController;

class UserRolesController extends AppController
{

    protected $user_rolesTable;

    public function getUser_rolesTable()
    {
        if (!$this->user_rolesTable) {
            $sm                    = $this->getServiceLocator();
            $this->user_rolesTable = $sm->get('Zf2auth\Table\User_rolesTable');
        }
        return $this->user_rolesTable;
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
        $searchform = new User_rolesSearchForm();
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
            if (!empty($formdata['user_id'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('user_id', '%' . $formdata['user_id'] . '%')
                );
            }
            if (!empty($formdata['role_id'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('role_id', '%' . $formdata['role_id'] . '%')
                );
            }
        }
        if (!empty($where)) {
            $select->where($where);
        }


        $user_roles   = $this->getUser_rolesTable()->fetchAll($select);
        $totalRecord  = $user_roles->count();
        $itemsPerPage = 2;

        $user_roles->current();
        $paginator = new Paginator(new paginatorIterator($user_roles));
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
            'pageAction'  => 'user_roles',
            'form'        => $searchform,
            'totalRecord' => $totalRecord
        ));
    }

    public function addAction()
    {
        $form = new User_rolesForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $user_roles = new User_roles();
            $form->setInputFilter($user_roles->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $user_roles->exchangeArray($form->getData());
                $this->getUser_rolesTable()->saveUser_roles($user_roles);

                // Redirect to list of user_roless
                return $this->redirect()->toRoute('user_roles');
            }
        }
        return array('form' => $form);
    }

    // Add content to this method:
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('user_roles', array(
                        'action' => 'add'
            ));
        }
        $user_roles = $this->getUser_rolesTable()->getUser_roles($id);

        $form = new User_rolesForm();
        $form->bind($user_roles);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($user_roles->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getUser_rolesTable()->saveUser_roles($form->getData());

                // Redirect to list of user_roless
                return $this->redirect()->toRoute('user_roles');
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
            return $this->redirect()->toRoute('user_roles');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {

            $id = (int) $request->getPost('id');
            $this->getUser_rolesTable()->deleteUser_roles($id);


            // Redirect to list of user_roless
            return $this->redirect()->toRoute('user_roles');
        }

        return array(
            'id'         => $id,
            'user_roles' => $this->getUser_rolesTable()->getUser_roles($id)
        );
    }

}
