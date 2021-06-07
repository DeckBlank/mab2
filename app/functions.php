<?php

define('ENV', require(__DIR__ . '/../env.php'));

function __getResourceURL($type, $resource){
    $staticDir  = (ENV['ENV'] == 'dev') ? 'temp/' : '';

    if ($type == 'css') {
        return "/static/{$staticDir}css/{$resource}";
    } elseif ($type == 'js') {
        return "/static/{$staticDir}js/{$resource}";
    }
}

require_once( __DIR__ . '/../vendor/autoload.php' );
require_once( __DIR__ . '/functions/routes.php' );


/**
 * --------------------------------------------------------------------------
 * Functions
 * --------------------------------------------------------------------------
 *
 */
array_map(function ($file) {
    require_once get_theme_file_path("functions/") . "{$file}.php";
}, ['helpers', 'setup', 'enqueues', 'filters', 'acf', 'login', 'api/main']);

array_map(function ($file) {
    require_once get_theme_file_path("functions/") . "{$file}.php";
}, ['admin/pages/reports/main', 'admin/pages/enrollments/main']);


/**
 * --------------------------------------------------------------------------
 * Post Types
 * --------------------------------------------------------------------------
 *
 */
array_map(function ($file) {
    require_once get_theme_file_path("registers/post-types/") . "{$file}.php";
}, __autoload_functions_by_dir('/registers/post-types'));


/**
 * --------------------------------------------------------------------------
 * Taxonomies
 * --------------------------------------------------------------------------
 *
 */
array_map(function ($file) {
    require_once get_theme_file_path("registers/taxonomies/") . "{$file}.php";
}, __autoload_functions_by_dir('/registers/taxonomies'));
