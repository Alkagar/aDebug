<?php
    require_once 'PHPUnit/Autoload.php';

    class AutoLoader
    {
        public static $dirs;

        public static function registerDirectory($directory)
        {
            self::$dirs[] = $directory;
        }

        public static function loadClass($className) 
        {
            foreach(self::$dirs as $directory) {
                $filePath = str_replace('_', '/', $className) . '.php';
                if(file_exists($directory . DIRECTORY_SEPARATOR . $filePath)) {
                    require_once($filePath);
                    return;
                }            
            }
        }
    }

    AutoLoader::registerDirectory(__DIR__);
    spl_autoload_register(array('AutoLoader', 'loadClass'));
