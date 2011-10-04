<?php

namespace Test;

use \Gaelic\App as App;

use \Gaelic\Registry as Registry;

class AppTest extends \PHPUnit_Framework_TestCase
{
    function setUp ( )
    {
        // We'll need this in a couple of tests...
        $this->rootpath = realpath('.');
    }


    function tearDown ( )
    {
        Registry::reset();
    }


    function test_load ( )
    {
        $classname = '\Gaelic\Exception\TestError';

        $this->assertFalse(class_exists($classname, false));

        App::load($classname);

        $this->assertTrue(class_exists($classname, false));
    }


    function provide_params_for_init ( )
    {
        return array(
            array( array() ),
            array( array( 'ROOT_PATH' => '' ) ),
            array( array( 'ROOT_PATH' => 'not/a/path' ) ),
        );
    }

    /**
     * @dataProvider provide_params_for_init
     */
    function test_init_no_ROOT_PATH ( $params = array() )
    {
        $this->setExpectedException('\Gaelic\Exception\ConfigError');

        App::init($params);
    }


    function test_init_bare ( )
    {
        $app = App::init($this->rootpath);

        $this->assertSame(Registry::get(App::REGISTRY_KEY), $app,
            'The App Singleton should be stored in the Registry now'
        );
    }


    function test_init_twice ( )
    {
        $this->test_init_bare();

        $this->setExpectedException('\Gaelic\Exception\RegistryLockError');

        $this->test_init_bare();
    }


    function test_init_with_include_path ( )
    {
        $paths = array( 'etc', 'app' );

        $include_path = explode(PATH_SEPARATOR, get_include_path());

        foreach ( $paths as $path ) $this->assertNotContains(
            "{$this->rootpath}/{$path}", $include_path,
            get_include_path()
        );

        App::init(array(
            'ROOT_PATH' => $this->rootpath,
            'include_path' => $paths,
        ));

        $include_path = explode(PATH_SEPARATOR, get_include_path());

        foreach ( $paths as $path ) $this->assertContains(
            "{$this->rootpath}/{$path}", $include_path,
            get_include_path()
        );
    }


    function setup_request_and_response ( )
    {
        $request = $this->getMock('\Gaelic\Request');
        $request->expects($this->atLeastOnce())
            ->method('getUri')
            ->will($this->returnValue('/'));

        $response = $this->getMock('\Gaelic\Response', array('render'));
        $response->expects($this->once())
            ->method('render')
            ->will($this->returnValue(
                $result = 'FOOBAR'
            ));
    }


    function test_run_with_MockHandler ( )
    {
        $request = $this->getMock('\Gaelic\Request');
        $request->expects($this->atLeastOnce())
            ->method('getUri')
            ->will($this->returnValue('/'));

        $response = $this->getMock('\Gaelic\Response', array('render'));
        $response->expects($this->once())
            ->method('render')
            ->will($this->returnValue(
                $result = 'FOOBAR'
            ));

        ob_start();

        App::init($this->rootpath)->addRoute(
            new \Gaelic\Route('/', function( \Gaelic\Request $request ) use ( $response ){
                return $response;
            })
        )->run($request, $response);

        $this->assertEquals($result, ob_get_clean());
    }
} // END AppTest
