<?php

return [
    'view_manager' => [
        'template_path_stack' => [
            OMEKA_PATH . '/modules/HideSiteProperties/view',
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => OMEKA_PATH . '/modules/HideSiteProperties/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'HideSiteProperties\Controller\Admin\Index' => 'HideSiteProperties\Controller\Admin\IndexController',
        ],
    ],
    'form_elements' => [
        'factories' => [
            'HideSiteProperties\Form\ConfigForm' => 'HideSiteProperties\Service\Form\ConfigFormFactory',
        ],
    ],
    'navigation' => [
        'site' => [
            [
                'label' => 'HideSiteProperties', // @translate
                'route' => 'admin/site/slug/hide-site-properties/default',
                'action' => 'index',
                'useRouteMatch' => true,
                'pages' => [
                    [
                        'route' => 'admin/site/slug/hide-site-properties/default',
                        'visible' => false,
                    ],
                ],
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'site' => [
                        'child_routes' => [
                            'slug' => [
                                'child_routes' => [
                                    'hide-site-properties' => [
                                        'type' => 'Literal',
                                        'options' => [
                                            'route' => '/hide-site-properties',
                                            'defaults' => [
                                                '__NAMESPACE__' => 'HideSiteProperties\Controller\Admin',
                                                'controller' => 'index',
                                                'action' => 'index',
                                            ],
                                        ],
                                        'may_terminate' => true,
                                        'child_routes' => [
                                            'default' => [
                                                'type' => 'Segment',
                                                'options' => [
                                                    'route' => '/:action',
                                                    'constraints' => [
                                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
