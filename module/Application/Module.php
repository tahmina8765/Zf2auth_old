<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;    // <- Added for ACL

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        /**
         * Added for ACL
         */
        $this->initAcl($e);
        $e->getApplication()->getEventManager()->attach('route', array($this, 'checkAcl'));
    }

    public function initAcl(MvcEvent $e)
    {

        $acl         = new \Zend\Permissions\Acl\Acl();
        $application = $e->getApplication();
        $services    = $application->getServiceManager();

        $this->rolesTable         = $services->get('Zf2auth\Table\RolesTable');
        $this->resourcesTable     = $services->get('Zf2auth\Table\ResourcesTable');
        $this->roleResourcesTable = $services->get('Zf2auth\Table\RoleResourcesTable');


        $roles     = $this->rolesTable->fetchAll();
        $resources = $this->resourcesTable->fetchAll();

        foreach ($resources as $resource) {
            $acl->addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource->name));
        }

        foreach ($roles as $role) {
            $role_id   = $role->id;
            $role_name = ($role->name);

            $role = new \Zend\Permissions\Acl\Role\GenericRole($role_name);
            $acl->addRole($role_name);

            if ($role_name == 'Administrator') {
                $acl->allow($role_name);
            } else {
                $role_resources   = $this->roleResourcesTable->getResourcesBasedRole($role_id);
                $allowd_resources = array();
                foreach ($role_resources as $row) {
                    $allowd_resources[] = $row;
                    $acl->allow($role_name, $row->resource_name);
                }
//                echo "<pre>";
//                print_r($allowd_resources);
//                die();
            }
        }
        $e->getViewModel()->acl = $acl;
    }

    public function checkAcl(MvcEvent $e)
    {
        $route = $e->getRouteMatch()->getMatchedRouteName();

        $Zf2AuthStorage = new \Zf2auth\Model\Zf2AuthStorage;
        $userRole       = $Zf2AuthStorage->getRole();




//        $userRole = 'guest';
//
        if (!$e->getViewModel()->acl->hasResource($route) || !$e->getViewModel()->acl->isAllowed($userRole, $route)) {
            $response = $e->getResponse();
            //location to page or what ever
            $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl() . '/404');
            $response->setStatusCode(303);
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ZF2AuthService' => function($sm) {
                    //My assumption, you've alredy set dbAdapter
                    //and has users table with columns : user_name and pass_word
                    //that password hashed with md5
                    // $dbAdapter          = $sm->get('Zend\Db\Adapter\Adapter');
                    // $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'users', 'username', 'password', 'MD5(?)');

                    $authService = new AuthenticationService();
                    // $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('Zf2auth\Model\Zf2AuthStorage'));

                    return $authService;
                },
            ),
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
