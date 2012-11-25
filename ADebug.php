<?php
    require_once 'ADebugConfig.php';
    class ADebug
    {
        private static $_dump = '';
        private static $_level = 0;

        private static function _iterateOverElements($element, $elementName, $root = false)
        {
            $class = $root ? 'root' : '';
            if(is_object($element)) {
                self::$_dump .= "<div class='container $class'>";
                    self::$_dump .= self::htmlElement('div', self::_printObject($element, $elementName), array('class' => array('title')));
                    self::_iterateOverObject($element);
                    self::$_dump .= '</div>';
                return false;
            }
            if(is_array($element)) {
                self::$_dump .= "<div class='container $class'>";
                    self::$_dump .= self::htmlElement('div', self::_printArray($element, $elementName), array('class' => array('title')));
                    self::_iterateOverArray($element);
                    self::$_dump .= '</div>';
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
                    self::$_dump .= self::_printPrimitive($propertyValue, $propertyName);
                }
            }
            self::$_level--;
        }

        private static function _iterateOverArray($element)
        {
            self::$_level++;
            foreach($element as $elementKey => $subElement) {
                if(self::_iterateOverElements($subElement, $elementKey)) {
                    self::$_dump .= self::_printPrimitive($subElement, $elementKey);
                }
            } 
            self::$_level--;
        }

        private static function _printPrimitive($element, $elementName)
        {
            if(ADebugConfig::isCli()) {
                self::_printPrimitiveCli($element, $elementName);
            } else {
                return self::_printPrimitiveWeb($element, $elementName);
            }
        }

        private static function _printPrimitiveWeb($element, $elementName)
        {
            $tabulator = self::_getTabulator();
            $level = self::$_level;

            $value = $elementName . ' => ' .  '(' . gettype($element) . ') ' . $element;
            $options = self::_prepareOptionsForElement($tabulator, $level);
            return self::htmlElement('div', $value, $options);
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
                return self::_printArrayWeb($element, $elementName);
            }
        }

        private static function _printArrayWeb($element, $elementName) 
        {
            $tabulator = self::_getTabulator();
            $level = self::$_level;

            $value = $elementName . ' => ' .  '(' . gettype($element) . ') ';
            $options = self::_prepareOptionsForElement($tabulator, $level);
            return self::htmlElement('div', $value, $options);
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
                return self::_printObjectWeb($element, $elementName);
            }
        }

        private static function _printObjectWeb($element, $elementName) 
        {
            $tabulator = self::_getTabulator();
            $level = self::$_level;
            $value = $elementName . ' => ' .  '(' . get_class($element) . ') ';
            $options = self::_prepareOptionsForElement($tabulator, $level);
            return self::htmlElement('div', $value, $options);
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
                self::_iterateOverElements($arg,$key, true);
            }
            echo self::$_dump;
        }

        public static function ndump($namedSection, $var)
        {
            $args = array_slice(func_get_args(), 1);
            var_dump($namedSection);
            foreach($args as $arg) {
                var_dump($arg);
            }
        }

        private static function htmlElement($tag, $value, $options) 
        {
            $preparedOptions = '';
            foreach($options as $key => $values) {
                if($key == 'class') {
                    $preparedOptions .= " class='";
                    foreach($values as $k => $v) {
                        $preparedOptions .= "$v ";
                    }
                    $preparedOptions .= "'";
                } else if($key == 'style') {
                    $preparedOptions .= " style='";
                    foreach($values as $k => $v) {
                        $preparedOptions .= "$k:$v; ";
                    }
                    $preparedOptions .= "'";
                } else {
                    $preparedOptions .= " $key='$value' ";
                }
            }
            return "<$tag $preparedOptions>$value</$tag>";
        }

        private static function _prepareOptionsForElement($tabulator, $level)
        {
            $color = 'rgb(' . (100 + (10 * $level)) . ', '. (100 + (10 * $level)) .', '. (100 + (10 * $level)) .')';
            $options = array(
                'class' => array(
                    'adebug-element', 
                    "element-level-$level"
                ),
                'style' => array(
                    //'padding-left' => $tabulator,
                    //'background-color' => $color,
                    //'white-space' => 'pre',
                    //'height' => '25px',
                    //'font-family' => 'monospace'
                ),
            );
            return $options;
        }
    }
