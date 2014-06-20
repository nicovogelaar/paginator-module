<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
return array(
    'view_helpers' => array(
        'invokables' => array(
            'paginator' => 'Nicovogelaar\Paginator\View\Helper\PaginatorHelper',
            'paginatorSorting' => 'Nicovogelaar\Paginator\View\Helper\PaginatorSortingHelper',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'template_map' => array(
            'slide-paginator' => __DIR__ . '/../view/paginator/partial/slide-paginator.phtml',
        ),
    ),
);
