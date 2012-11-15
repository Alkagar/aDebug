<?php
    class ADebug
    {
        public static function dump($var)
        {
            $args = func_get_args();
            foreach($args as $arg) {
                var_dump($arg);
            }
        }

        public static function ndump($namedSection, $var)
        {
            $args = array_slice(func_get_args(), 1);
            var_dump($namedSection);
            foreach($args as $arg) {
                var_dump($arg);
            }
        }

    }
