<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace NicovogelaarTest\Paginator\Container;

use PHPUnit_Framework_TestCase as TestCase;
use Nicovogelaar\Paginator\Container\FilterContainer;
use Nicovogelaar\Paginator\Exception;
use Nicovogelaar\Paginator\Paginator;

/**
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class FilterContainerTest extends TestCase
{
    public function testAdd()
    {
        $field = 'f.bar';

        $container = new FilterContainer();
        $container->add('foo', $field);

        $this->assertCount(1, $container);
        $this->assertCount(1, $container->all());

        $this->assertTrue($container->has('foo'));

        $container->add('bar', 'b.baz', Paginator::FILTER_TYPE_CONTAINS);

        $this->assertCount(2, $container);

        $filter = $container->get('foo');

        $this->assertTrue(isset($filter['field']));
        $this->assertEquals($field, $filter['field']);
        $this->assertTrue(isset($filter['type']));
        $this->assertEquals(Paginator::FILTER_TYPE_STARTS_WITH, $filter['type']);

        $filter = $container->get('bar');

        $this->assertEquals(Paginator::FILTER_TYPE_CONTAINS, $filter['type']);
    }

    public function testGet()
    {
        $container = new FilterContainer();
        $container->add('foo', 'f.bar');

        $filter = $container->get('foo');

        $this->assertTrue(is_array($filter));
    }

    public function testGetWhenFilterNotExists()
    {
        $container = new FilterContainer();

        $message = null;

        try {
            $container->get('foo');
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals('Filter with key "foo" not exists', $message);
    }

    public function testHas()
    {
        $container = new FilterContainer();
        $container->add('foo', 'f.bar');

        $this->assertTrue($container->has('foo'));
        $this->assertFalse($container->has('bar'));
    }
}