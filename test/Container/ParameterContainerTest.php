<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace NicovogelaarTest\Paginator\Container;

use PHPUnit_Framework_TestCase as TestCase;
use Nicovogelaar\Paginator\Container\ParameterContainer;
use Nicovogelaar\Paginator\Exception;
use Nicovogelaar\Paginator\Paginator;

/**
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class ParameterContainerTest extends TestCase
{
    public function testAddParameters()
    {
        $filter = array(
            'foo' => 'bar',
            'bar' => 'baz'
        );

        $parameters = array(
            'page' => 2,
            'sort' => 'foo',
            'direction' => 'desc',
            'query' => 'test',
            'filter' => $filter
        );

        $container = new ParameterContainer();
        $container->addParameters($parameters);

        $this->assertEquals(2, $container->getPage());
        $this->assertEquals('foo', $container->getSortField());
        $this->assertEquals('desc', $container->getSortDirection());
        $this->assertEquals('test', $container->getQuery());
        $this->assertEquals($filter, $container->getFilters());
    }

    public function testSetName()
    {
        $newName = 'sortField';

        $container = new ParameterContainer();
        $container->setName('sort_field', $newName);

        $this->assertEquals($newName, $container->getName('sort_field'));
    }

    public function testSetNameWhenParameterNameNotExists()
    {
        $container = new ParameterContainer();

        $message = null;

        try {
            $container->setName('sort_field_', 'sortField');
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals('The parameter name with key "sort_field_" not exists', $message);
    }

    public function testGetName()
    {
        $container = new ParameterContainer();

        $name = $container->getName('sort_field');

        $this->assertEquals('sort', $name);
    }

    public function testGetNameWhenParameterNameNotExists()
    {
        $container = new ParameterContainer();

        $message = null;

        try {
            $container->getName('sort_field_');
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals('The parameter name with key "sort_field_" not exists', $message);
    }
}