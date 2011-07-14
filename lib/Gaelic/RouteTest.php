<?php

namespace Gaelic;

class MockCalledException extends \Exception { }

class MockHandler extends Handler
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

        $this->fixture = new Route('/path/to/resource', '\Gaelic\MockHandler');
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
        $this->assertEquals('\Gaelic\MockHandler', $this->fixture->getHandler());
    }


    function test_invoke ( )
    {
        $route = $this->fixture; // Thanks PHP... :[

        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue(
                Request::METHOD_GET
            ))
        ; // END expects

        $this->setExpectedException('\Gaelic\MockCalledException');

        $route($this->request, $this->response);
    }
} // END RouteTest_simple
