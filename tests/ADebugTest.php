<?php

    require_once 'PHPUnit/Autoload.php';
    require_once __DIR__ . '/../ADebug.php';

    class ADebugTest extends PHPUnit_Framework_TestCase
    {
        function ADebugTest() 
        {
        }

        function setUp() 
        { }

        public function testOneDump() 
        {
            $this->assertTrue(true);
        }

        public function testTwoDump() 
        {
            $this->assertTrue(false);
        }
    }

