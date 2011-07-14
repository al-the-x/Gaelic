<?php

namespace Gaelic;

/**
 * The "App" class contains the autoloader function used by Gaelic, so we need
 * to "require()" the class definition explicitly to remove that dependency.
 */
require_once 'App.php';

class AppTest extends \PHPUnit_Framework_TestCase
{
    function tearDown ( )
    {
        require_once 'Registry.php';

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
        $path = realpath('.');

        $this->assertFileExists($path);

        $app = App::init(realpath('.'));

        $this->assertInstanceOf('\Gaelic\App', $app);

        $this->assertSame(Registry::get(App::REGISTRY_KEY), $app);
    }


    function test_init_twice ( )
    {
        $this->test_init_bare();

        $this->setExpectedException('\Gaelic\Exception\RegistryLockError');

        $this->test_init_bare();
    }


    function test_init_with_include_path ( )
    {
        $rootpath = realpath('.');

        $paths = array( 'etc', 'app' );

        $include_path = explode(PATH_SEPARATOR, get_include_path());

        foreach ( $paths as $path ) $this->assertNotContains(
            "$rootpath/$path", $include_path
        );

        App::init(array(
            'ROOT_PATH' => $rootpath,
            'include_path' => $paths,
        ));

        $include_path = explode(PATH_SEPARATOR, get_include_path());

        foreach ( $paths as $path ) $this->assertContains(
            "$rootpath/$path", $include_path,
            get_include_path()
        );
    }


    function test_run ( )
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

        $route = $this->getMock('\Gaelic\Route', array(), array(
            $request, $response
        ));

        $route->expects($this->any())
            ->method('match')
            ->will($this->returnValue(true));

        $route->expects($this->any())
            ->method('run')
            ->with($request, $response)
            ->will($this->returnValue($response));

        ob_start();

        App::init(realpath('.'))->run($request, $response);

        $this->assertEquals($result, ob_get_clean());
    }
} // END AppTest
