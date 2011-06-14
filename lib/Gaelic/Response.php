<?php

namespace Gaelic;

class Response
{
    function render ( )
    {
    }


    function __toString ( )
    {
        return $this->render();
    }
} // END Response
