<?php


if (!function_exists('shortClass')) {
    function shortClass($class)
    {
        $payload = explode('\\', $class);
        return array_pop($payload);
    }
}