<?php

namespace Test;

use \Gaelic\Request as Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    function setUp ( )
    {
        $_POST['a'] = 1;

        $_GET['a'] = 2;

        $this->fixture = new Request;
    }

    function test_parameter_accessors ( )
    {
        $this->fixture->foo = 'bar';

        $this->assertEquals('bar', $this->fixture->foo);
        $this->assertEquals('bar', $this->fixture['foo']);

        $this->fixture['bar'] = 'bat';

        $this->assertEquals('bat', $this->fixture['bar']);
        $this->assertEquals('bat', $this->fixture->bar);
    }


    function test_request_accessors ( )
    {
        $_SERVER['REQUEST_URI'] = '/path/to/some/resource/';

        $this->assertEquals($_SERVER['REQUEST_URI'], $this->fixture->getUri());
    }


    function test_values_from_GET_should_hide_POST_values ( )
    {
        $this->assertTrue(isset($this->fixture->a),
            'A value for "a" was set in the GET and POST arrays.'
        );

        $this->assertEquals($_GET['a'], $this->fixture->a,
            'The value in GET obscures the value in POST.'
        );

        unset($this->fixture->a);

        $this->assertEquals($_POST['a'], $this->fixture->a,
            'Calling "unset()" drops the GET value, revealing the POST value.'
        );

        $this->fixture->a = 3;

        $this->assertEquals(3, $this->fixture->a,
            'Local assignments merely obscure the set values.'
        );

        unset($this->fixture->a);

        $this->assertEquals($_POST['a'], $this->fixture->a,
            'Dropping the local value reveals the POST value again.'
        );
    }


    function test_changing_super_globals_does_nothing ( )
    {
        $this->assertEquals($_GET['a'], $this->fixture->a);

        $_GET['a'] = 'foo';

        $this->assertNotEquals($_GET['a'], $this->fixture->a,
            'The reference arrays are copied upon instantiation, so modifications ' .
            'after the fact are irrelevant.'
        );

        $this->fixture = new Request;

        $this->assertEquals($_GET['a'], $this->fixture->a,
            'Creating a new instance of the Request gets the new value in GET, ' .
            'but it would be impractical to replace the Request object.'
        );
    }
} // END RequestTest
