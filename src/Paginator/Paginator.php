<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Paginator;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Paginator\Adapter\AdapterInterface as ZendAdapterInterface;
use Zend\Paginator\Paginator as ZendPaginator;
use Paginator\Adapter\AdapterInterface;
use Paginator\Container\FilterContainer;
use Paginator\Container\ParameterContainer;
use Paginator\Container\SortingContainer;

/**
 * Paginator
 * 
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class Paginator implements EventManagerAwareInterface
{
    /**
     * Filter equals
     * 
     * @var string
     */
    const FILTER_TYPE_EQUALS = 'equals';

    /**
     * Filter contains
     * 
     * @var string
     */
    const FILTER_TYPE_CONTAINS = 'contains';

    /**
     * Filter starts with
     * 
     * @var string
     */
    const FILTER_TYPE_STARTS_WITH = 'starts_with';

    /**
     * Filter ends with
     * 
     * @var string
     */
    const FILTER_TYPE_ENDS_WITH = 'ends_with';

    /**
     * Event manager
     * 
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * Zend paginator
     *
     * @var ZendPaginator
     */
    protected $paginator;

    /**
     * Adapter
     *
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Request data
     *
     * @var array
     */
    protected $data;

    /**
     * Parameter container
     *
     * @var ParameterContainer
     */
    protected $parameters;

    /**
     * Filter container
     *
     * @var FilterContainer
     */
    protected $filters;

    /**
     * Sorting container
     *
     * @var SortingContainer
     */
    protected $sortings;

    /**
     * Constructor
     *
     * @param AdapterInterface $adapter Adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->parameters = new ParameterContainer();
        $this->filters = new FilterContainer();
        $this->sortings = new SortingContainer();

        $this->init();
    }

    /**
     * Initialize
     * 
     * @return void
     */
    public function init()
    {
    }

    /**
     * Get the Zend Paginator instance
     *
     * @return Paginator
     */
    public function __invoke()
    {
        return $this->getPaginator();
    }

    /**
     * Get the Zend Paginator instance
     *
     * @return Paginator
     */
    public function getPaginator()
    {
        if (null === $this->paginator) {
            $this->paginator = $this->create();
        }

        return $this->paginator;
    }

    /**
     * Creates and returns an instance of the Zend Paginator
     * 
     * @return ZendPaginator
     */
    protected function create()
    {
        $evm = $this->getEventManager();

        $params = array('paginator' => $this);

        $evm->trigger('create.pre', $this, $params);

        $paginator = new ZendPaginator($this->adapter->getPaginatorAdapter());
        $paginator->setCurrentPageNumber($this->parameters->getPage());

        $evm->trigger('create.post', $this, $params);

        return $paginator;
    }

    /**
     * Get adapter
     * 
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the request data and adds the data to the parameter container
     *
     * @param array $data Request data
     *
     * @return Paginator
     */
    public function setData($data)
    {
        $this->data = $data;
        $this->parameters->addParameters($data);

        return $this;
    }

    /**
     * Get the parameter container
     *
     * @return ParameterContainer
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Add a filter to the filter container
     *
     * @param string $key   Key
     * @param string $field Field
     * @param string $type  Filter type
     *
     * @return Paginator
     */
    public function addFilter($key, $field, $type = null)
    {
        $this->filters->add($key, $field, $type);

        return $this;
    }

    /**
     * Get the filter container
     * 
     * @return FilterContainer
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Add a sorting to the sorting container
     *
     * @param string $key   Key
     * @param string $field Field
     * @param string $label Label
     *
     * @return Paginator
     */
    public function addSorting($key, $field, $label = null)
    {
        $this->sortings->add($key, $field, $label);

        return $this;
    }

    /**
     * Get the sorting container
     * 
     * @return SortingContainer
     */
    public function getSortings()
    {
        return $this->sortings;
    }

    /**
     * Set event manager
     * 
     * @param EventManagerInterface $eventManager Event manager
     * 
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->addIdentifiers(array(
            __CLASS__,
            get_called_class()
        ));

        $this->eventManager = $eventManager;
    }

    /**
     * Get event manager
     * 
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }
}