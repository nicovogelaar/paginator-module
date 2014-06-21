<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Nicovogelaar\Paginator\Listener;

use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Stdlib\CallbackHandler;
use Nicovogelaar\Paginator\Paginator;

/**
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class CrudControllerListener implements ListenerAggregateInterface
{
    /**
     * Listeners
     * 
     * @var CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();

        $this->listeners[] = $sharedEvents->attach(
            'Nicovogelaar\CrudController\Mvc\Controller\AbstractCrudController',
            'list',
            array($this, 'setData'),
            10
        );
    }

    /**
     * {@inheritdoc}
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Sets the query parameters to the paginator
     * 
     * @param Event $event Event
     * 
     * @return void
     */
    public function setData(Event $event)
    {
        $paginator = $event->getParam('paginator');

        if ($paginator instanceof Paginator) {
            $controller = $event->getTarget();

            $paginator->setData($controller->params()->fromQuery());
        }
    }
}