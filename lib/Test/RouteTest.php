<?php

namespace Test;

use \Gaelic\Request as Request;

use \Gaelic\Response as Response;

use \Gaelic\Route as Route;

class MockCalledException extends \Exception { }

class MockHandler extends \Gaelic\Handler
{
    function __invoke ( )
    {
        throw new MockCalledException;
    }
}

class RouteTest extends \PHPUnit_Framework_TestCase
{
    function setUp ( )
    {
        $this->request = $this->getMock('\Gaelic\Request');

        $this->response = $this->getMock('\Gaelic\Response');

        $this->fixture = new Route('/path/to/resource', '\Test\MockHandler');
    }


    function setup_request ( )
    {
        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue(
                Request::METHOD_GET
            ))
        ; // END expects
    }


    function provide_uri ( )
    {
        return array(
            array( '', false ),
            array( '/', false ),
            array( '/path/', false ),
            array( '/path/to/resource/', true ),
            array( '/path/to/resource', true ),
            array( '/some/other/path/to/resource/', false ),
            array( '/path/to/resource/relationship/', false ),
        );
    }

    /**
     * @dataProvider provide_uri
     */
    function test_match ( $uri, $expected )
    {
        $this->assertSame($expected, $this->fixture->match($uri));
    }


    function test_getHandler ( )
    {
        $this->assertEquals('\Test\MockHandler', $this->fixture->getHandler());
    }


    function test_run ( )
    {
        $this->setup_request();

        $this->setExpectedException('\Test\MockCalledException');

        $this->fixture->run($this->request, $this->response);
    }


    function test_invoke ( )
    {
        $this->setup_request();

        $this->setExpectedException('\Test\MockCalledException');

        $route = $this->fixture and $route(
            $this->request, $this->response
        );
    }
} // END RouteTest_simple
