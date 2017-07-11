<?php


function classes_autoload($class_name) {
    if (is_file('Get/' . $class_name . '.php')) {
        require_once 'Get/' . $class_name . '.php';
    }
}
spl_autoload_register("classes_autoload");