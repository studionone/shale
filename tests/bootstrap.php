<?php
/**
 * PHPUnit test-suite bootstrap
 */
require_once(__DIR__ . '/../vendor/autoload.php');

use Tracy\Debugger;
Debugger:$strictMode = true;
Debugger::enable(Debugger::DEVELOPMENT, __DIR__ . '/logs');
