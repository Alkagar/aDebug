# aDebug

## Overview

A little tool for debuging PHP scripts

Dump will be displayd at the bottom of the page in colored, hidable box. Dump
should show file name, line in file (name if used with named section) and
presented as tree structure. Data will be ordered by time of use (first used
will be higher).

Tool will allow to disable itself. It will automaticly disable all it's calls
in whole application - then you don't need to remove all dump function - they
just will not be displayed.


## Structure

```
* void  | aDebug::dump($variable1, $variable2, $variable3 ...);
* void  | aDebug::ndump($namedSection, $variable1, $variable2, $variable3 ...);
* void  | aDebug::enable();
* void  | aDebug::disable();
* void  | aDebug::allowCli($bool);
* void  | aDebug::allowWeb($bool);
* array | aDebug::getErrors();
* bool  | aDebug::config($option, $value);
    * enabled true, false
    * allowWeb true, false
    * allowCli true, false
* bool aDebugUtil::isCli();
```
