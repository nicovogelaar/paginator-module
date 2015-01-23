<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Nicovogelaar\Paginator\Form;

use Zend\Form\Fieldset;
use Nicovogelaar\Paginator\Container\FilterContainer;

/**
 * FilterFieldset
 * 
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class FilterFieldset extends Fieldset
{
    /**
     * Constructor
     * 
     * @param FilterContainer $filters Filters
     */
    public function __construct(FilterContainer $filters)
    {
        parent::__construct('filter');

        foreach ($filters->all() as $key => $filter) {
            $this->add(
                array(
                    'name' => $key,
                    'type' => 'Text',
                )
            );
        }
    }
}
