<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Paginator\Adapter;

use Zend\Paginator\Adapter\AdapterInterface as ZendAdapterInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Paginator\Paginator;
use Paginator\Container\FilterContainer;

/**
 * DoctrineAdapter
 * 
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class DoctrineAdapter implements AdapterInterface
{
    /**
     * Query builder
     * 
     * @var QueryBuilder
     */
    protected $qb;

    /**
     * Contructor
     *
     * @param QueryBuilder $qb Query builder
     */
    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }

    /**
     * Get paginator adapter
     *
     * @return ZendAdapterInterface
     */
    public function getPaginatorAdapter()
    {
        return new PaginatorAdapter(new ORMPaginator($this->qb->getQuery()));
    }

    /**
     * Get the Query builder
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->qb;
    }

    /**
     * Set the Query builder
     *
     * @param QueryBuilder $qb Query builder
     *
     * @return DoctrineAdapter
     */
    public function setQueryBuilder(QueryBuilder $qb)
    {
        $this->qb = $qb;

        return $this;
    }

    /**
     * Apply sorting
     *
     * @param string $field     Sort field
     * @param string $direction Sort direction
     *
     * @return void
     */
    public function applySorting($field, $direction)
    {
        $this->qb->orderBy($field, strtoupper($direction));
    }

    /**
     * Apply global filter on all fields
     * 
     * @param FilterContainer $filters Available filters
     * @param string          $value   Value
     * 
     * @return void
     */
    public function applyGlobalFilter(FilterContainer $filters, $value)
    {
        $this->qb->andWhere($this->buildWhere($filters, $value));
    }

    /**
     * Apply filter
     * 
     * @param FilterContainer $filters Available filters
     * @param string          $value   Value
     * @param string          $field   Field name
     * 
     * @return void
     */
    public function applyFilter(FilterContainer $filters, $value, $field)
    {
        $this->qb->andWhere($this->buildWhere($filters, $value, $field));
    }

    /**
     * Build where clause
     * 
     * @param FilterContainer $filters Available filters
     * @param string          $value   Value
     * @param string          $field   Field name
     * 
     * @return void
     */
    protected function buildWhere(FilterContainer $filters, $value, $field = null)
    {
        $where = '';

        foreach ($filters->all() as $key => $filter) {
            if (null !== $field && $field != $filter['field']) {
                continue;
            }

            if (null === $field) {
                $key .= '_';
            }

            switch ($filter['type']) {
                case Paginator::FILTER_TYPE_STARTS_WITH:
                    $comparisonOperator = 'LIKE';
                    $this->qb->setParameter($key, $value . '%');
                    break;
                case Paginator::FILTER_TYPE_ENDS_WITH:
                    $comparisonOperator = 'LIKE';
                    $this->qb->setParameter($key, '%' . $value);
                    break;
                case Paginator::FILTER_TYPE_CONTAINS:
                    $comparisonOperator = 'LIKE';
                    $this->qb->setParameter($key, '%' . $value . '%');
                    break;
                case Paginator::FILTER_TYPE_EQUALS:
                    // no break
                default:
                    $comparisonOperator = '=';
                    $this->qb->setParameter($key, $value);
                    break;
            }

            $where .= ' OR ' . $filter['field'] . ' '
                . $comparisonOperator . ' :' . $key;
        }

        return substr($where, 4);
    }
}
