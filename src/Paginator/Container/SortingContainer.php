<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Paginator\Container;

use Countable;
use Paginator\Exception;

/**
 * SortingContainer
 * 
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class SortingContainer implements Countable
{
    /**
     * Sortings
     * 
     * @var array
     */
    protected $sortings = array();

    /**
     * Add sorting
     *
     * @param string $key   Key
     * @param string $field Field
     * @param string $label Label
     *
     * @return SortingContainer
     */
    public function add($key, $field, $label = null)
    {
        if (null === $label) {
            $label = $key;
        }

        $this->sortings[$key] = array(
            'field' => $field,
            'label' => $label
        );

        return $this;
    }

    /**
     * Get sortings
     * 
     * @return array
     */
    public function all()
    {
        return $this->sortings;
    }

    /**
     * Get sorting
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
            throw new Exception('Sorting with key "' . $key . '" not exists');
        }

        return $this->sortings[$key];
    }

    /**
     * Checks if sorting exists
     *
     * @param string $key Key
     *
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $this->sortings);
    }

    /**
     * Count sortings
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->sortings);
    }
}