<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Nicovogelaar\Paginator;

return array(
    'abstract_factories' => array(                                          
        'Nicovogelaar\Paginator\Service\AbstractDoctrinePaginatorFactory',                            
    ),
    'invokables' => array(
        'Nicovogelaar\Paginator\Listener\PaginatorListener' => 'Nicovogelaar\Paginator\Listener\PaginatorListener',
    ),
);