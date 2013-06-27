<?php

namespace Zf2auth;

return array(
    'controllers'  => array(
        'invokables' => array(
            'Zf2auth\Controller\Users'         => 'Zf2auth\Controller\UsersController',
            'Zf2auth\Controller\Roles'         => 'Zf2auth\Controller\RolesController',
            'Zf2auth\Controller\UserRoles'     => 'Zf2auth\Controller\UserRolesController',
            'Zf2auth\Controller\Resources'     => 'Zf2auth\Controller\ResourcesController',
            'Zf2auth\Controller\RoleResources' => 'Zf2auth\Controller\RoleResourcesController',
            'Zf2auth\Controller\Fbprofiles'    => 'Zf2auth\Controller\FbprofilesController', // <-- For Facebok
        ),
    ),
    'router'       => array(
        'routes' => array(
            'users'          => array(
                'type'          => 'segment',
                'options'       => array(
                    'route'    => '/users',
//                    'constraints' => array (
//                        'action'   => '(?!\bpage\b)(?!\border_by\b)(?!\bsearch_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
//                        'id'       => '[0-9]+',
//                        'page'     => '[0-9]+',
//                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                        'order'    => 'ASC|DESC',
//                    ),
                    'defaults' => array(
                        'controller' => 'Zf2auth\Controller\Users',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'search'          => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'       => '/search[/:id][/page/:page][/order_by/:order_by][/:order][/search_by/:search_by]',
                            'constraints' => array(
                                'id'       => '[0-9]+',
                                'page'     => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order'    => 'ASC|DESC',
                            ),
                            'defaults'    => array(
                                'action' => 'search',
                            ),
                        ),
                    ),
                    'index'           => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'       => '/index[/:id][/page/:page][/order_by/:order_by][/:order][/search_by/:search_by]',
                            'constraints' => array(
                                'id'       => '[0-9]+',
                                'page'     => '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'order'    => 'ASC|DESC',
                            ),
                            'defaults'    => array(
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'add'             => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/add',
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                    'edit'            => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/edit[/:id]',
                            'defaults' => array(
                                'action' => 'edit',
                            ),
                        ),
                    ),
                    'delete'          => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/delete',
                            'defaults' => array(
                                'action' => 'delete',
                            ),
                        ),
                    ),
                    'registration'    => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/registration',
                            'defaults' => array(
                                'action' => 'registration',
                            ),
                        ),
                    ),
                    'login'           => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/login',
                            'defaults' => array(
                                'action' => 'login',
                            ),
                        ),
                    ),
                    'logout'          => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/logout',
                            'defaults' => array(
                                'action' => 'logout',
                            ),
                        ),
                    ),
                    'authenticate'    => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/authenticate',
                            'defaults' => array(
                                'action' => 'authenticate',
                            ),
                        ),
                    ),
                    'confirmEmail'    => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/confirmEmail[/:id][/:email_check_code]',
                            'defaults' => array(
                                'action' => 'confirmEmail',
                            ),
                        ),
                    ),
                    'forget-password' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/forget-password',
                            'defaults' => array(
                                'action' => 'forgetPassword',
                            ),
                        ),
                    ),
                    'change-password' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/change-password',
                            'defaults' => array(
                                'action' => 'changePassword',
                            ),
                        ),
                    ),
                ),
            ),
            'roles'          => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/roles[/:action][/:id][/page/:page][/order_by/:order_by][/:order][/search_by/:search_by]',
                    'constraints' => array(
                        'action'   => '(?!\bpage\b)(?!\border_by\b)(?!\bsearch_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'       => '[0-9]+',
                        'page'     => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order'    => 'ASC|DESC',
                    ),
                    'defaults'    => array(
                        'controller' => 'Zf2auth\Controller\Roles',
                        'action'     => 'index',
                    ),
                ),
            ),
            'user_roles'     => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/user_roles[/:action][/:id][/page/:page][/order_by/:order_by][/:order][/search_by/:search_by]',
                    'constraints' => array(
                        'action'   => '(?!\bpage\b)(?!\border_by\b)(?!\bsearch_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'       => '[0-9]+',
                        'page'     => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order'    => 'ASC|DESC',
                    ),
                    'defaults'    => array(
                        'controller' => 'Zf2auth\Controller\UserRoles',
                        'action'     => 'index',
                    ),
                ),
            ),
            'resources'      => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/resources[/:action][/:id][/page/:page][/order_by/:order_by][/:order][/search_by/:search_by]',
                    'constraints' => array(
                        'action'   => '(?!\bpage\b)(?!\border_by\b)(?!\bsearch_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'       => '[0-9]+',
                        'page'     => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order'    => 'ASC|DESC',
                    ),
                    'defaults'    => array(
                        'controller' => 'Zf2auth\Controller\Resources',
                        'action'     => 'index',
                    ),
                ),
            ),
            'role_resources' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/role_resources[/:action][/:id][/page/:page][/order_by/:order_by][/:order][/search_by/:search_by]',
                    'constraints' => array(
                        'action'   => '(?!\bpage\b)(?!\border_by\b)(?!\bsearch_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'       => '[0-9]+',
                        'page'     => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order'    => 'ASC|DESC',
                    ),
                    'defaults'    => array(
                        'controller' => 'Zf2auth\Controller\RoleResources',
                        'action'     => 'index',
                    ),
                ),
            ),
            'fbprofiles'     => array(// <-- For Facebok
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/fbprofiles[/:action][/:id][/page/:page][/order_by/:order_by][/:order][/search_by/:search_by]',
                    'constraints' => array(
                        'action'   => '(?!\bpage\b)(?!\border_by\b)(?!\bsearch_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'       => '[0-9]+',
                        'page'     => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order'    => 'ASC|DESC',
                    ),
                    'defaults'    => array(
                        'controller' => 'Zf2auth\Controller\Fbprofiles',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'users'          => __DIR__ . '/../view',
            'roles'          => __DIR__ . '/../view',
            'user_roles'     => __DIR__ . '/../view',
            'resources'      => __DIR__ . '/../view',
            'role_resources' => __DIR__ . '/../view',
            'fbprofiles'     => __DIR__ . '/../view',
        ),
        'template_map'        => array(
            'paginator-slide'      => __DIR__ . '/../view/layout/slidePaginator.phtml',
            'partial/login'        => __DIR__ . '/../view/partial/login.phtml',
            'partial/registration' => __DIR__ . '/../view/partial/registration.phtml',
        ),
    ),
);
