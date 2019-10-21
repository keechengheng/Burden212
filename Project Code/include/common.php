<?php
session_start();

spl_autoload_register(function($class) {
 
    require_once "$class.php"; 
  
});

function isEmpty($var) {
    if (isset($var) && is_array($var))
        foreach ($var as $key => $value) {
            if (empty($value)) {
               unset($var[$key]);
            }
        }

    if (empty($var))
        return TRUE;
}

function isMissingOrEmpty($name) {
    if (!isset($_REQUEST[$name])) {
        return "$name cannot be empty";
    }

    // client did send the value over
    $value = $_REQUEST[$name];
    if (empty($value)) {
        return "$name cannot be empty";
    }
}

?>