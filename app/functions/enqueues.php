<?php

<<<<<<< HEAD
$assets_version = '1586136254515';
=======
$assets_version = '1586130010291';
>>>>>>> 3bf82f24beba36f00e29d0aeb26d509a7e676df5
$config = require get_theme_file_path('config/base.php');

add_action( 'wp_enqueue_scripts', function () use ($config, $assets_version) {

    $fa = [
        'handle'    => 'pandawp/fontawesome/base',
        'src'       =>  $config['resources']['fontawesome']['cdn']['base'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ];

    /**
     * --------------------------------------------------------------------------
     * Register Scripts
     * --------------------------------------------------------------------------
     *
     */
    register_assets('script', [
        'handle'    => 'pandawp/package/jquery',
        'src'       =>  $config['resources']['package_jquery']['cdn'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    // register_assets('script', [
    //     'handle'    => 'pandawp/package/foundation',
    //     'src'       => $config['resources']['package_foundation'],
    //     'deps'      => [ ],
    //     'ver'       => $assets_version,
    //     'in_footer' => true
    // ]);

    register_assets('script', [
        'handle'    => 'pandawp/package/vue',
        'src'       => $config['resources']['package_vue'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);    

    register_assets('script', [
        'handle'    => 'pandawp/package/setimmediate',
        'src'       => $config['resources']['package_setimmediate'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);    

    register_assets('script', [
        'handle'    => 'pandawp/package/process',
        'src'       => $config['resources']['package_process'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    register_assets('script', [
        'handle'    => 'pandawp/package/vuex',
        'src'       => $config['resources']['package_vuex'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    register_assets('script', [
        'handle'    => 'pandawp/package/timers_bf',
        'src'       => $config['resources']['package_timers_bf'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    register_assets('script', [
        'handle'    => 'pandawp/package/ssr_window',
        'src'       => $config['resources']['package_ssr_window'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    register_assets('script', [
        'handle'    => 'pandawp/js/main',
        'src'       => $config['resources']['script_main'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    register_assets('script', [
        'handle'    => 'pandawp/package/swiper',
        'src'       => $config['resources']['package_swiper'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    register_assets('script', [
        'handle'    => 'pandawp/package/dom7',
        'src'       => $config['resources']['package_dom7'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]); 

    register_assets('script', [
        'handle'    => 'pandawp/package/vaswiper',
        'src'       => $config['resources']['package_vaswiper'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);    

    /**
     * --------------------------------------------------------------------------
     * Register Styles
     * --------------------------------------------------------------------------
     *
     */
    register_assets('style', [
        'handle' => 'pandawp/google/font',
        'src'    => $config['resources']['google_fonts'],
        'deps'   => [ ],
        'ver'    => $assets_version,
        'media'  => 'all'
    ]);

    register_assets('style', [
        'handle' => 'pandawp/style/main',
        'src'    => $config['resources']['style_main'],
        'deps'   => [ ],
        'ver'    => $assets_version,
        'media'  => 'all'
    ]);

    /**
     * --------------------------------------------------------------------------
     * Register Fontawesome
     * --------------------------------------------------------------------------
     *
     */
    wp_register_script($fa['handle'], $fa['src'], $fa['deps'], $fa['ver'], $fa['in_footer']);

    register_assets('script', [
        'handle'    => 'pandawp/fontawesome/style',
        'src'       => $config['resources']['fontawesome']['cdn']['style'],
        'deps'      => [ $fa['handle'] ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    register_assets('script', [
        'handle'    => 'pandawp/fontawesome/brands',
        'src'       => $config['resources']['fontawesome']['cdn']['brands'],
        'deps'      => [ $fa['handle'] ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    /**
     * --------------------------------------------------------------------------
     * Register google maps script
     * --------------------------------------------------------------------------
     *
     */
    /* GMaps here */

    /**
     * --------------------------------------------------------------------------
     * Register Scripts with conditionals
     * --------------------------------------------------------------------------
     *
     */
    if ( is_page('emotional') ) {
        register_assets('script', [
            'handle'    => 'pandawp/js/page/emotional',
            'src'       => $config['resources']['page_emotional'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
        
    }elseif (is_page('mab-click')){
        register_assets('script', [
            'handle'    => 'pandawp/package/corejs',
            'src'       => $config['resources']['package_corejs'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandawp/package/element_ui',
            'src'       => $config['resources']['package_element_ui'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandawp/package/async_val',
            'src'       => $config['resources']['package_async_val'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandawp/package/normalize_w',
            'src'       => $config['resources']['package_normalize_w'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandawp/package/resize_obv',
            'src'       => $config['resources']['package_resize_obv'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandawp/js/page/mab_click',
            'src'       => $config['resources']['page_mab_click'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);   

    }elseif (is_page('login')){
        register_assets('script', [
            'handle'    => 'pandawp/js/page/login',
            'src'       => $config['resources']['page_login'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

    }elseif (is_page('solicitar-id')){
        register_assets('script', [
            'handle'    => 'pandawp/js/page/solicitar-id',
            'src'       => $config['resources']['page_solicitar_id'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

    }elseif (is_page('formularios')){
        register_assets('script', [
            'handle'    => 'pandawp/js/page/forms',
            'src'       => $config['resources']['page_forms'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

    }elseif (is_singular('video')){
        register_assets('script', [
            'handle'    => 'pandawp/js/page/video',
            'src'       => $config['resources']['page_video'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);        

    }elseif (is_singular('course')){
        register_assets('script', [
            'handle'    => 'pandawp/js/page/course',
            'src'       => $config['resources']['page_course'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);        

    }elseif (is_singular('topic')){      
        register_assets('script', [
            'handle'    => 'pandawp/js/page/topic',
            'src'       => $config['resources']['page_topic'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);        

    }elseif (is_singular('session')){
        register_assets('script', [
            'handle'    => 'pandawp/package/vaswiper',
            'src'       => $config['resources']['package_vaswiper'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]); 

        register_assets('script', [
            'handle'    => 'pandawp/package/opentok',
            'src'       => $config['resources']['package_opentok'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]); 

        register_assets('script', [
            'handle'    => 'pandawp/js/page/session',
            'src'       => $config['resources']['page_session'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);        

    }elseif (is_404()){
        register_assets('script', [
            'handle'    => 'pandawp/js/page/404',
            'src'       => $config['resources']['page_404'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
    }
});

add_action('admin_enqueue_scripts', function ($hook) use ($config, $assets_version) {
    if ('post.php' !== $hook) {
        return;
    }
    register_assets('script', [
        'handle'    => 'pandawp/wp/session',
        'src'       =>  $config['resources']['wp_session'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);
});
