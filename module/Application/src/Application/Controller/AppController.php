<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;

class AppController extends AbstractActionController {

    protected $currentUser;
    protected $tableRefs;
    protected $authservice;
    public $error        = array ();
    public $confMessage;
    public $itemsPerPage = 10;
    public $pageRange    = 7;
    public $vm;

    public function __construct() {
        // $this->confMessage = new \Zend\Config\Config(include ROOT_PATH . '/config/message.config.php');

    }

    public function getZF2AuthService() {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('ZF2AuthService');
        }

        return $this->authservice;

    }

    protected function getCurrentUser() {
        if ($this->getZF2AuthService()->hasIdentity()) {
            return ($this->getZF2AuthService()->getIdentity());
            // return $this->redirect()->toRoute('users', array ('action' => 'index'));
        }

    }

    protected function getUserRegistrationStatus() {
        $redirect    = '';
        $currentUser = $this->getCurrentUser();
        if (empty($currentUser['country_id'])) {
            $redirect = 'profiles/regstep2';
        } else if (!empty($currentUser['email_check_code'])) {
            $redirect = 'profiles/regstep3';
        } else if (!$this->getActivityTable()->checkFirstActivityByUserId($currentUser->id)) {
            $redirect = 'profiles/regstep4';
        } else {
            $redirect = 'web_app/user_home';
        }
        return $redirect;

    }

    protected function redirectIfSuccessful(\Closure $execute, array $options) {
        $succeed = $execute();

        if ($succeed) {
            $this->flashMessenger()->addMessage(array ('success' => $options['messages']['success']));
            return $this->redirectTo($options['forwarders']['success']);
        } else {
            $msgOrCallable = $options['messages']['error'];
            if (is_callable($msgOrCallable)) {
                $errMessage = $msgOrCallable();
            } else {
                $errMessage = $msgOrCallable;
            }

            $this->flashMessenger()->addMessage(array ('error' => $errMessage));

            return $this->redirectTo($options['forwarders']['error']);
        }

    }

    protected function redirectTo($route) {
        if (':back' != $route)
            return $this->redirect()->toRoute($route);
        else {
            return $this->redirect()->toUrl($_SERVER['HTTP_REFERER']);
        }

    }

    public function paginator($records, $pageaction, $search = '', $order_by = '', $order = '', $itemsPerPage = 0, $page = 0) {
        $totalRecord  = $records->count();
        $itemsPerPage = ($itemsPerPage > 0) ? $itemsPerPage : $this->itemsPerPage;
        $pageRange    = $this->pageRange;
        $page         = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : $page;

        $records->current();
        $paginator = new Paginator(new paginatorIterator($records));
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange($pageRange);
        return array (
            'paginator' => $paginator,
            'params'    => array (
                'totalRecord' => $totalRecord,
                'page'        => $page,
                'search_by'   => $search,
                'order_by'    => $order_by,
                'order'       => $order,
                'pageAction'  => $pageaction,),
        );

    }

    public function getBasePath() {
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        return $renderer->basePath();

    }

}

