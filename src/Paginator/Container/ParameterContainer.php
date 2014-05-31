<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Paginator\Container;

/**
 * ParameterContainer
 * 
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class ParameterContainer
{
    /**
     * Parameters
     *
     * @var array
     */
    protected $parameters = array();

    /**
     * Request parameter names
     *
     * @var array
     */
    protected $names = array(
        'page'           => 'page',
        'sort_field'     => 'sort',
        'sort_direction' => 'direction',
        'query'          => 'query',
        'filters'        => 'filter'
    );

    /**
     * Constructor
     *
     * @param array $parameters Parameters
     */
    public function __construct(array $parameters = null)
    {
        if (null !== $parameters) {
            $this->addParameters($parameters);
        }
    }

    /**
     * Add parameters
     *
     * @param array $parameters Parameters
     *
     * @return ParameterContainer
     */
    public function addParameters(array $parameters)
    {
        foreach ($parameters as $name => $value) {
            $this->addParameter($name, $value);
        }

        return $this;
    }

    /**
     * Add parameter
     *
     * @param string $name  Name
     * @param string $value Value
     *
     * @return ParameterContainer
     */
    public function addParameter($name, $value)
    {
        if (in_array($name, $this->names)) {
            $this->parameters[$name] = $value;
        }

        return $this;
    }

    /**
     * Get parameter for the specified parameter name
     *
     * @param string $name    Internal parameter name
     * @param mixed  $default Default return value
     *
     * @return mixed
     */
    public function getParameter($name, $default = null)
    {
        $name = $this->getName($name);

        if (array_key_exists($name, $this->parameters)) {
            $parameter = $this->parameters[$name];
        }

        return isset($parameter) ? $parameter : $default;
    }

    /**
     * Get page number
     *
     * @param mixed $default Default return value
     *
     * @return integer
     */
    public function getPage($default = null)
    {
        return $this->getParameter('page', $default);
    }

    /**
     * Get sort field
     *
     * @param mixed $default Default return value
     *
     * @return string
     */
    public function getSortField($default = null)
    {
        return $this->getParameter('sort_field', $default);
    }

    /**
     * Get sort direction
     *
     * @param mixed $default Default return value
     *
     * @return string
     */
    public function getSortDirection($default = null)
    {
        return $this->getParameter('sort_direction', $default);
    }

    /**
     * Get query parameter
     *
     * @param mixed $default Default return value
     *
     * @return string
     */
    public function getQuery($default = null)
    {
        return $this->getParameter('query', $default);
    }

    /**
     * Get filters
     *
     * @param mixed $default Default return value
     *
     * @return array
     */
    public function getFilters($default = array())
    {
        $filters = $this->getParameter('filters', $default);

        if (!is_array($filters)) {
            $filters = array();
        }

        return $filters;
    }

    /**
     * Get request parameter name
     * 
     * @param string $key Internal parameter name
     * 
     * @return string
     * 
     * @throws Exception
     */
    public function getName($key)
    {
        if (!array_key_exists($key, $this->names)) {
            throw new Exception('The parameter name with key "' . $key . '" not exists');
        }

        return $this->names[$key];
    }

    /**
     * Set request parameter name
     *
     * @param string $key  Internal parameter name
     * @param string $name Request parameter name
     *
     * @return ParameterContainer
     * 
     * @throws Exception
     */
    public function setName($key, $name)
    {
        if (!array_key_exists($key, $this->names)) {
            throw new Exception('The parameter name with key "' . $key . '" not exists');
        }

        $this->names[$key] = $name;

        return $this;
    }
}