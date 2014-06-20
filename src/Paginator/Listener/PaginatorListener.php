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

/**
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class PaginatorListener implements ListenerAggregateInterface
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
            'Paginator\Paginator',
            'create.pre',
            array($this, 'applyFilters'),
            10
        );

        $this->listeners[] = $sharedEvents->attach(
            'Paginator\Paginator',
            'create.pre',
            array($this, 'applySorting'),
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
     * Applies the filters
     * 
     * @param Event $event Event
     * 
     * @return void
     */
    public function applyFilters(Event $event)
    {
        $paginator = $event->getTarget();

        $container = $paginator->getFilters();

        if (count($container) < 1) {
            return;
        }

        $adapter = $paginator->getAdapter();
        $parameters = $paginator->getParameters();
        $query = $parameters->getQuery();

        if ('' != $query) {
            $adapter->applyGlobalFilter($container, $query);
        }

        $filters = $parameters->getFilters();

        foreach ($filters as $field => $value) {
            if ('' != $value && $container->has($field)) {
                $filter = $container->get($field);
                $adapter->applyFilter($container, $value, $filter['field']);
            }
        }
    }

    /**
     * Applies the sorting
     * 
     * @param Event $event Event
     * 
     * @return void
     */
    public function applySorting(Event $event)
    {
        $paginator = $event->getTarget();

        $container = $paginator->getSortings();
        $adapter = $paginator->getAdapter();
        $parameters = $paginator->getParameters();

        $field = $parameters->getSortField();
        $direction = $parameters->getSortDirection();

        if (null !== $field && $container->has($field)) {
            $direction = strtoupper($direction);

            if (!in_array($direction, array('ASC', 'DESC'))) {
                $direction = 'ASC';
            }

            $sorting = $container->get($field);

            $adapter->applySorting($sorting['field'], $direction);
        }
    }

}