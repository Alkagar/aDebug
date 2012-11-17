<?php
    class ADebugUtil
    {
        public static $enabled = false;

        public static $allowedCli = false;
        public static $allowedWeb = false;

        public static function isCli()
        {
            return PHP_SAPI === 'cli';
        }

        public static function isWeb()
        {
            return PHP_SAPI !== 'cli';
        }
    }
