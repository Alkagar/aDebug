<?php

    class ADebugConfig
    {
        public static $enabled = false;

        public static $enabledCli = false;

        public static $enabledWeb = false;

        private static $options = null; 

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

        private static function _initializeOptions()
        {
            if(is_null(self::$options)) {
                self::$options = new StdClass();
                self::$options->enabled = self::$enabled;
                self::$options->enabledCli = self::$enabledCli;
                self::$options->enabledWeb = self::$enabledWeb;
            }
        }

        public static function config($optionName, $optionValue)
        {
            self::_initializeOptions();
            if(isset(self::$options->$optionName)) {
                self::$options->$optionName = $optionValue;
                if(isset(self::$optionName)) {
                    self::$optionName = $optionValue;
                }
            }
        }
    }
