<?php
    require_once 'ADebugConfig.php';
    class ADebug
    {
        private static $_level = 0;

        private static function _iterateOverElements($element, $elementName)
        {
            if(is_object($element)) {
                self::_printObject($element, $elementName);
                self::_iterateOverObject($element);
                return false;
            }
            if(is_array($element)) {
                self::_printArray($element, $elementName);
                self::_iterateOverArray($element);
                return false;
            }
            return true;
        }

        private static function _getTabulator()
        {
            if(ADebugConfig::isCli()) {
                return self::_getTabulatorWithPrefix('   ');
            } else {
                //return self::_getTabulatorWithPrefix('&nbsp;&nbsp;&nbsp;');
                return (self::$_level * 20) . 'px';
            }
        }

        private static function _getTabulatorWithPrefix($prefix = '   ')
        {
            $i = 0;
            $tabulator = '';
            while($i++ < self::$_level) {
                $tabulator .= $prefix;
            }
            return $tabulator;
        }

        private static function _iterateOverObject($element)
        {
            self::$_level++;
            $reflect = new ReflectionClass($element);
            //by default get all properties, add some parameters in config to change this
            $properties   = $reflect->getProperties();
            $allProperties = array();

            foreach ($properties as $property) {
                $propertyName = $property->getName();
                $property->setAccessible(true);
                $allProperties[$propertyName] = $property->getValue($element);
            } 
            foreach($element as $propertyName => $propertyValue) {
                if(!isset($allProperties[$propertyName])) {
                    $allProperties[$propertyName] = $propertyValue;
                }
            }
            foreach($allProperties as $propertyName => $propertyValue) {
                if(self::_iterateOverElements($propertyValue, $propertyName)) {
                    self::_printPrimitive($propertyValue, $propertyName);
                }
            }
            self::$_level--;
        }

        private static function _iterateOverArray($element)
        {
            self::$_level++;
            foreach($element as $elementKey => $subElement) {
                if(self::_iterateOverElements($subElement, $elementKey)) {
                    self::_printPrimitive($subElement, $elementKey);
                }
            } 
            self::$_level--;
        }

        private static function _printPrimitive($element, $elementName)
        {
            if(ADebugConfig::isCli()) {
                self::_printPrimitiveCli($element, $elementName);
            } else {
                self::_printPrimitiveWeb($element, $elementName);
            }
        }

        private static function _printPrimitiveWeb($element, $elementName)
        {
            $tabulator = self::_getTabulator();
            $level = self::$_level;
            echo 
            "<div class='adebug-element level-$level' style='padding-left:$tabulator; white-space:pre; font-family:monospace;'>" .
            $elementName . ' => ' .
            '(' . gettype($element) . ') ' .
            $element .
            "</div>";
        }

        private static function _printPrimitiveCli($element, $elementName)
        {
            echo 
            self::_getTabulator() . 
            $elementName . ' => ' .
            '(' . gettype($element) . ') ' .
            $element .
            PHP_EOL;
        }

        private static function _printArray($element, $elementName) 
        {
            if(ADebugConfig::isCli()) {
                self::_printArrayCli($element, $elementName);
            } else {
                self::_printArrayWeb($element, $elementName);
            }
        }

        private static function _printArrayWeb($element, $elementName) 
        {
            $tabulator = self::_getTabulator();
            $level = self::$_level;
            echo 
            "<div class='adebug-element level-$level' style='padding-left:$tabulator; white-space:pre; font-family:monospace;'>" .
            $elementName . ' => ' .
            '(' . gettype($element) . ') ' .
            "</div>";
        }

        private static function _printArrayCli($element, $elementName) 
        {
            echo 
            self::_getTabulator() . 
            $elementName . ' => ' .
            '(' . gettype($element) . ')' .
            ': ' .
            PHP_EOL; 
        }

        private static function _printObject($element, $elementName) 
        {
            if(ADebugConfig::isCli()) {
                self::_printObjectCli($element, $elementName);
            } else {
                self::_printObjectWeb($element, $elementName);
            }
        }

        private static function _printObjectWeb($element, $elementName) 
        {
            $tabulator = self::_getTabulator();
            $level = self::$_level;
            echo 
            "<div class='adebug-element level-$level' style='padding-left:$tabulator; white-space:pre; font-family:monospace;'>" .
            $elementName . ' => ' .
            '(' . get_class($element) . ') ' .
            "</div>";
        }

        private static function _printObjectCli($element, $elementName) 
        {
            echo 
            self::_getTabulator() . 
            $elementName . ' => ' .
            '(' . get_class($element) . ')' .
            ': ' .
            PHP_EOL; 
        }

        public static function dump($var)
        {
            $args = func_get_args();
            foreach($args as $key => $arg) {
                self::_iterateOverElements($arg,$key);
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
