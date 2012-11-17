<?php

    class ADebugUtil
    {
        public static $enabled = false;

        public static $enabledCli = false;

        public static $enabledWeb = false;

        public static function isCli()
        {
            return PHP_SAPI === 'cli';
        }

        public static function isWeb()
        {
            return PHP_SAPI !== 'cli';
        }

        public static function enable()
        { 
            self::$enabled = true;
        }

        public static function disable()
        { 
            self::$enabled = false;
        }

        public static function enableCli()
        { 
            self::$enabledCli = true;
        }

        public static function enableWeb()
        {
            self::$enabledWeb= true;
        }

        public static function disableCli()
        {
            self::$enabledCli = false;
        }

        public static function disableWeb()
        { 
            self::$enabledWeb = false;
        }
    }
