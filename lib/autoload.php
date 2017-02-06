<?php
/**
 * @author AppGenerator v1.4.6(https://github.com/leplutonien/app_generator)
 */
function autoload($class)
{
    require str_replace('\\', '/', $class) . '.php';
}

spl_autoload_register('autoload');
