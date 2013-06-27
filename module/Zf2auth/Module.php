<?php

namespace Zf2auth;

use Zf2auth\Entity\Users;
use Zf2auth\Table\UsersTable;
use Zf2auth\Entity\Roles;
use Zf2auth\Table\RolesTable;
use Zf2auth\Entity\UserRoles;
use Zf2auth\Table\UserRolesTable;
use Zf2auth\Entity\Resources;
use Zf2auth\Table\ResourcesTable;
use Zf2auth\Entity\RoleResources;
use Zf2auth\Table\RoleResourcesTable;
use Zf2auth\Entity\Fbprofiles;          // <-- For Facebok
use Zf2auth\Table\FbprofilesTable;      // <-- For Facebok
use Zf2auth\Entity\Zf2AuthStorage;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class Module
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'Login_widget' => function ($helperPluginManager) {
                    $serviceLocator       = $helperPluginManager->getServiceLocator();
                    $viewHelper           = new View\Helper\Loginhelper();
                    $viewHelper->setServiceLocator($serviceLocator);
                    return $viewHelper;
                },
                'Registration_widget' => function ($helperPluginManager) {
                    $serviceLocator          = $helperPluginManager->getServiceLocator();
                    $viewHelper              = new View\Helper\Registrationhelper();
                    $viewHelper->setServiceLocator($serviceLocator);
                    return $viewHelper;
                },
                'Zf2authIdentity_widget' => function ($helperPluginManager) {
                    $serviceLocator = $helperPluginManager->getServiceLocator();
                    $viewHelper     = new View\Helper\Zf2authIdentityhelper();
                    $viewHelper->setServiceLocator($serviceLocator);
                    return $viewHelper;
                }
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zf2auth\Table\UsersTable' => function($sm) {
                    $dbAdapter                 = $sm->get('Zend\Db\Adapter\Adapter');
                    $table                     = new UsersTable($dbAdapter);
                    return $table;
                },
                'Zf2auth\Table\RolesTable' => function($sm) {
                    $dbAdapter                     = $sm->get('Zend\Db\Adapter\Adapter');
                    $table                         = new RolesTable($dbAdapter);
                    return $table;
                },
                'Zf2auth\Table\UserRolesTable' => function($sm) {
                    $dbAdapter                     = $sm->get('Zend\Db\Adapter\Adapter');
                    $table                         = new UserRolesTable($dbAdapter);
                    return $table;
                },
                'Zf2auth\Table\ResourcesTable' => function($sm) {
                    $dbAdapter                         = $sm->get('Zend\Db\Adapter\Adapter');
                    $table                             = new ResourcesTable($dbAdapter);
                    return $table;
                },
                'Zf2auth\Table\RoleResourcesTable' => function($sm) {
                    $dbAdapter                      = $sm->get('Zend\Db\Adapter\Adapter');
                    $table                          = new RoleResourcesTable($dbAdapter);
                    return $table;
                },
                'Zf2auth\Table\FbprofilesTable' => function($sm) {   // <-- For Facebok
                    $dbAdapter                     = $sm->get('Zend\Db\Adapter\Adapter');
                    $table                         = new FbprofilesTable($dbAdapter);
                    return $table;
                },
                'Zf2auth\Model\Zf2AuthStorage' => function($sm) {
                    return new \Zf2auth\Model\Zf2AuthStorage('zf2authSession');
                },
                'AuthService' => function($sm) {
                    //password hashed with md5
                    $dbAdapter          = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'users', 'username', 'password', 'MD5(?)');

                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('Zf2auth\Model\Zf2AuthStorage'));

                    return $authService;
                },
            ),
        );
    }

}
