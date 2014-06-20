<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Nicovogelaar\Paginator\Form;

use Zend\Form\Form;
use Nicovogelaar\Paginator\Container\FilterContainer;

/**
 * FilterForm
 * 
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class FilterForm extends Form
{
    /**
     * Filters
     * 
     * @var FilterContainer
     */
    protected $filters;

    /**
     * Accepted options for FilterForm:
     * - filters: a container with the available filters. The filters will be
     *            added as text fields to the filter fieldset.
     *
     * @param array|Traversable $options Options
     * 
     * @return FilterForm
     */
    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($options['filters'])) {
            $this->setFilters($options['filters']);
        }

        return $this;
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function init()
    {
        $this->setAttribute('method', 'get');

        $this->add(
            array(
                'name' => 'query',
                'type' => 'Text',
            )
        );

        if ($this->filters && count($this->filters)) {
            $this->add(new FilterFieldset($this->filters));
        }

        $this->add(
            array(
                'name' => 'search',
                'type' => 'Submit',
                'options' => array(
                    'label' => 'Search',
                ),
            )
        );
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
     * Set the filter container
     *
     * @param FilterContainer $filters Filter conainer
     *
     * @return FilterForm
     */
    public function setFilters(FilterContainer $filters)
    {
        $this->filters = $filters;

        return $this;
    }
}
