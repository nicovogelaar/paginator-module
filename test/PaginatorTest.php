<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace NicovogelaarTest\Paginator;

use Zend\Paginator\Adapter\ArrayAdapter;
use PHPUnit_Framework_TestCase as TestCase;
use Nicovogelaar\Paginator\Paginator;

/**
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class PaginatorTest extends TestCase
{
    public function testSetData()
    {
        $data = array('page' => 5, 'sort' => 'id', 'direction' => 'desc');

        $paginator = new Paginator($this->getAdapterMock());
        $paginator->setData($data);

        $parameters = $paginator->getParameters();

        $this->assertEquals($data, $paginator->getData());
        $this->assertEquals(5, $parameters->getPage());
        $this->assertEquals('id', $parameters->getSortField());
        $this->assertEquals('desc', $parameters->getSortDirection());
    }

    public function testGetParameters()
    {
        $paginator = new Paginator($this->getAdapterMock());

        $parameters = $paginator->getParameters();

        $this->assertInstanceOf('Nicovogelaar\Paginator\Container\ParameterContainer', $parameters);
    }

    public function testAddFilter()
    {
        $paginator = new Paginator($this->getAdapterMock());

        $field = 'f.bar';
        $type = Paginator::FILTER_TYPE_CONTAINS;

        $paginator->addFilter('foo', $field, $type);

        $filters = $paginator->getFilters();

        $this->assertTrue($filters->has('foo'));

        $filter = $filters->get('foo');

        $this->assertTrue(isset($filter['field']));
        $this->assertEquals($field, $filter['field']);
        $this->assertTrue(isset($filter['type']));
        $this->assertEquals($type, $filter['type']);
    }

    public function testGetFilters()
    {
        $paginator = new Paginator($this->getAdapterMock());

        $filters = $paginator->getFilters();

        $this->assertInstanceOf('Nicovogelaar\Paginator\Container\FilterContainer', $filters);
    }

    public function testAddSorting()
    {
        $paginator = new Paginator($this->getAdapterMock());

        $field = 'f.bar';
        $label = 'Bar';

        $paginator->addSorting('foo', $field, $label);

        $sortings = $paginator->getSortings();

        $this->assertTrue($sortings->has('foo'));

        $sorting = $sortings->get('foo');

        $this->assertTrue(isset($sorting['field']));
        $this->assertEquals($field, $sorting['field']);
        $this->assertTrue(isset($sorting['label']));
        $this->assertEquals($label, $sorting['label']);
    }

    public function testGetSortings()
    {
        $paginator = new Paginator($this->getAdapterMock());

        $sortings = $paginator->getSortings();

        $this->assertInstanceOf('Nicovogelaar\Paginator\Container\SortingContainer', $sortings);
    }

    public function testGetPaginator()
    {
        $adapterMock = $this->getAdapterMock();

        $paginatorAdapter = new ArrayAdapter(array());

        $adapterMock->expects($this->once())
                    ->method('getPaginatorAdapter')
                    ->will($this->returnValue($paginatorAdapter));

        $paginator = new Paginator($adapterMock);
        $zendPaginator = $paginator->getPaginator();

        $this->assertInstanceOf('Zend\Paginator\Paginator', $zendPaginator);
    }

    public function getAdapterMock()
    {
        return $this->getMockBuilder('Nicovogelaar\Paginator\Adapter\DoctrineAdapter')
            ->disableOriginalConstructor()
            ->getMock();
    }
}