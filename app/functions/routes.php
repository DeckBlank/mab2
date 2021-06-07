<?php

use Timber\Timber;

$vertion    = '1622704552472';
$config     = require get_theme_file_path('config/base.php');

Routes::map('user/:user_nicename', function($routeParams) use ($vertion, $config) {
    $params = [
        'route' => $routeParams,
        'view'  => 'perfil',
    ];

    add_action( 'wp_enqueue_scripts', function () use ($config, $vertion) {
        register_assets('package', [
            'handle'    => 'pandawp/package/swiper',
            'src'       => $config['resources']['package_swiper'],
            'deps'      => [ ],
            'ver'       => $vertion,
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/dom7',
            'src'       => $config['resources']['package_dom7'],
            'deps'      => [ ],
            'ver'       => $vertion,
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/ssrwindow',
            'src'       => $config['resources']['package_ssr_window'],
            'deps'      => [ ],
            'ver'       => $vertion,
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandawp/js/page/perfil',
            'src'       => $config['resources']['page_perfil'],
            'deps'      => [ ],
            'ver'       => $vertion,
            'in_footer' => true
        ]);    
    });

    Routes::load('app.php', $params, "", 200);
});

Routes::map('lider/:user_nicename', function($routeParams) use ($vertion, $config) {
    $params = [
        'route' => $routeParams,
        'view'  => 'leader',
    ];

    add_action( 'wp_enqueue_scripts', function () use ($config, $vertion) {
        register_assets('script', [
            'handle'    => 'pandawp/js/page/lideres-single',
            'src'       => $config['resources']['page_lideres-single'],
            'deps'      => [ ],
            'ver'       => $vertion,
            'in_footer' => true
        ]); 
    });

    Routes::load('app.php', $params, "", 200);
});

Routes::map('donaciones', function($routeParams) use ($vertion, $config) {
    $params = [
        'route' => $routeParams,
        'view'  => 'donations',
    ];

    add_action( 'wp_enqueue_scripts', function () use ($config, $vertion) {
        register_assets('script', [
            'handle'    => 'pandawp/js/page/donaciones',
            'src'       => $config['resources']['page_donaciones'],
            'deps'      => [ ],
            'ver'       => $vertion,
            'in_footer' => true
        ]); 
    });

    Routes::load('app.php', $params, "", 200);
});
