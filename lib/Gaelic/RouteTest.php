<?php

namespace Gaelic;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    function setUp ( )
    {
        require_once 'Route.php';

        $this->fixture = new Route('^/path/to/resource', 'SomeHandler');
    }

    function provide_uri ( )
    {
        return array(
            array( '/', false ),
            array( '/path/', false ),
            array( '/path/to/resource/', true ),
            array( '/path/to/resource', true ),
        );
    }

    /**
     * @dataProvider provide_uri
     */
    function test_match ( $uri, $expected )
    {
        $this->assertSame($expected, $this->fixture->match($uri));
    }
} // END RouteTest_simple
