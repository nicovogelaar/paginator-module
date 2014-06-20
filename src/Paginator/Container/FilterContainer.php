<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Nicovogelaar\Paginator\Container;

use Countable;
use Paginator\Exception;
use Paginator\Paginator;

/**
 * FilterContainer
 * 
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class FilterContainer implements Countable
{
    /**
     * Filters
     * 
     * @var array
     */
    protected $filters = array();

    /**
     * Add filter
     *
     * @param string $key   Key
     * @param string $field Field
     * @param string $type  Type
     *
     * @return FilterContainer
     */
    public function add($key, $field, $type = null)
    {
        if (null === $type) {
            $type = Paginator::FILTER_TYPE_STARTS_WITH;
        }

        $this->filters[$key] = array(
            'field' => $field,
            'type' => $type
        );

        return $this;
    }

    /**
     * Get filters
     * 
     * @return array
     */
    public function all()
    {
        return $this->filters;
    }

    /**
     * Get filter
     *
     * @param string $key Key
     *
     * @return array
     * 
     * @throws Exception
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new Exception('Filter with key "' . $key . '" not exists');
        }

        return $this->filters[$key];
    }

    /**
     * Checks if filter exists
     *
     * @param string $key Key
     *
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $this->filters);
    }

    /**
     * Count filters
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->filters);
    }
}