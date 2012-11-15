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
* void  | ADebug::dump($variable1, $variable2, $variable3 ...);
* void  | ADebug::ndump($namedSection, $variable1, $variable2, $variable3 ...);
* void  | ADebug::enable();
* void  | ADebug::disable();
* void  | ADebug::allowCli($bool);
* void  | ADebug::allowWeb($bool);
* array | ADebug::getErrors();
* bool  | ADebug::config($option, $value);
    * enabled true, false
    * allowWeb true, false
    * allowCli true, false
* bool ADebugUtil::isCli();
* bool ADebugUtil::isWeb();
```
