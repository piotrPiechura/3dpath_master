<?php
/**
 * Function '__autoload' is used by PHP to load class definition if not class is not yet defined.
 * It does not work for external libraries like Smarty.
 */
set_include_path(substr(__FILE__, 0, -strlen('php/core/autoload/__autoload.function.php')));
require_once('php/core/autoload/CoreAutoload.class.php');

function __autoload($className) {
	require_once(CoreAutoload::getClassPath($className));
}
?>