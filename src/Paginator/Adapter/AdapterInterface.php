<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Paginator\Adapter;

use Zend\Paginator\Adapter\AdapterInterface as ZendAdapterInterface;
use Paginator\Container\FilterContainer;

/**
 * AdapterInterface
 * 
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
interface AdapterInterface
{
    /**
     * Get paginator adapter
     *
     * @return ZendAdapterInterface
     */
    public function getPaginatorAdapter();

    /**
     * Apply sorting
     *
     * @param string $field     Sort field
     * @param string $direction Sort direction
     *
     * @return void
     */
    public function applySorting($field, $direction);

    /**
     * Apply global filter
     *
     * @param FilterContainer $filters Available filters
     * @param string          $value   Value
     *
     * @return void
     */
    public function applyGlobalFilter(FilterContainer $filters, $value);

    /**
     * Apply filter
     *
     * @param FilterContainer $filters Available filters
     * @param string          $value   Value
     * @param string          $field   Field name
     *
     * @return void
     */
    public function applyFilter(FilterContainer $filters, $value, $field);
}
