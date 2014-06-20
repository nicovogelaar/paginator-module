<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Nicovogelaar\Paginator;

return array(
    'abstract_factories' => array(                                          
        'Paginator\Service\AbstractDoctrinePaginatorFactory',                            
    ),
    'invokables' => array(
        'Paginator\Listener\PaginatorListener' => 'Paginator\Listener\PaginatorListener',
    ),
);