<?php

use Timber\Timber;

$config = require get_theme_file_path('config/base.php');

Routes::map('user/:user_nicename', function($routeParams) use ($config) {
    $params = [
        'route' => $routeParams,
        'view'  => 'perfil',
    ];

    add_action( 'wp_enqueue_scripts', function () use ($config) {
        register_assets('package', [
            'handle'    => 'pandawp/package/swiper',
            'src'       => $config['resources']['package_swiper'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/dom7',
            'src'       => $config['resources']['package_dom7'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/ssrwindow',
            'src'       => $config['resources']['package_ssr_window'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandawp/js/page/perfil',
            'src'       => $config['resources']['page_perfil'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);    
    });

    Routes::load('app.php', $params, "", 200);
});

Routes::map('lider/:user_nicename', function($routeParams) use ($config) {
    $params = [
        'route' => $routeParams,
        'view'  => 'leader',
    ];

    add_action( 'wp_enqueue_scripts', function () use ($config) {
        register_assets('script', [
            'handle'    => 'pandawp/js/page/lideres-single',
            'src'       => $config['resources']['page_lideres-single'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]); 
    });

    Routes::load('app.php', $params, "", 200);
});
