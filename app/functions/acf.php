<?php

$config = require get_theme_file_path('config/base.php');

/**
 * --------------------------------------------------------------------------
 * ACF | Google maps - Set API KEY
 * --------------------------------------------------------------------------
 *
 * */
add_filter('acf/fields/google_map/api', function($api) use ($config) {
    $api['key'] = $config['GOOGLE_API_KEY'];

    return $api;
});

/**
 * --------------------------------------------------------------------------
 * ACF | Save fields JSON
 * --------------------------------------------------------------------------
 *
 * */
add_filter('acf/settings/save_json', function ($path) {
    $path = get_theme_file_path('/fields');

    return $path;
});

/**
 * --------------------------------------------------------------------------
 * ACF | Load fields JSON
 * --------------------------------------------------------------------------
 *
 * */
add_filter('acf/settings/load_json', function ($paths) {
    unset($paths[0]);

    $paths[] = get_theme_file_path('/fields');

    return $paths;
});

/**
 * --------------------------------------------------------------------------
 * Options page
 * --------------------------------------------------------------------------
 *
 * */
if( function_exists('acf_add_options_page') ) {
    $pm_contact = acf_add_options_page([
        'page_title'      => 'Información',
        'menu_title'      => 'Información',
        'menu_slug'       => 'options-contact',
        'redirect'        => false, // true
        'icon_url'        => 'dashicons-phone',
        'position'        => 3,
        'update_button'   => __('Actualizar', 'acf'),
        'updated_message' => __('Cambios Guardados exitosamente', 'acf')
    ]);

    $pm_inscriptions = acf_add_options_page([
        'page_title'      => 'Inscripciones',
        'menu_title'      => 'Inscripciones',
        'menu_slug'       => 'options-inscriptions',
        'redirect'        => false, // true
        'icon_url'        => 'dashicons-id-alt',
        'position'        => 2,
        'update_button'   => __('Actualizar', 'acf'),
        'updated_message' => __('Cambios Guardados exitosamente', 'acf')
    ]);

    $pm_sells = acf_add_options_page([
        'page_title'      => 'Ventas',
        'menu_title'      => 'Ventas',
        'menu_slug'       => 'options-sells',
        'redirect'        => false, // true
        'icon_url'        => 'dashicons-cart',
        'position'        => 4,
        'update_button'   => __('Actualizar', 'acf'),
        'updated_message' => __('Cambios Guardados exitosamente', 'acf')
    ]);

    acf_add_options_page([
        'page_title'      => 'Cuestionario y Encuesta',
        'menu_title'      => 'Cuestionario y Encuesta',
        'menu_slug'       => 'options-questions',
        'redirect'        => true,
        'icon_url'        => 'dashicons-editor-ol',
        'position'        => 3
    ]);

	$pm_sb_questions = acf_add_options_sub_page(array(
        'parent_slug'	    => 'options-questions',
        'page_title' 	    => 'Cuestionario de seguimiento',
		'menu_title'	    => 'Cuestionario de seguimiento',
        'update_button'     => __('Actualizar', 'acf'),
        'updated_message'   => __('Cambios Guardados exitosamente', 'acf')        
	));    

	$pm_sb_encuentas = acf_add_options_sub_page(array(
        'parent_slug'	    => 'options-questions',
        'page_title' 	    => 'Encuesta de satisfacción',
		'menu_title'	    => 'Encuesta de satisfacción',
        'update_button'     => __('Actualizar', 'acf'),
        'updated_message'   => __('Cambios Guardados exitosamente', 'acf')        
	));    
}
