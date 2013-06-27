<?php

namespace Zf2auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
use Application\Controller\AppController;

class Zf2authAppController extends AppController
{

    public $storage;
    public $authservice;
    public $usersTable;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @return type
     */
    public function getSessionStorage()
    {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()->get('Zf2auth\Model\Zf2AuthStorage');
        }

        return $this->storage;
    }

    /**
     *
     * @return type
     */
    public function getAuthService()
    {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }

    /**
     *
     * @return type
     */
    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm               = $this->getServiceLocator();
            $this->usersTable = $sm->get('Zf2auth\Table\UsersTable');
        }
        return $this->usersTable;
    }

}

