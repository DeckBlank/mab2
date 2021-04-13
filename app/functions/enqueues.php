<?php

include_once __DIR__ . '/api/libs/enviroment.php';

<<<<<<< HEAD
$assets_version = '1618336668916';
=======
$assets_version = '1618326788930';
>>>>>>> 78ae8ed40cb4439334feede9de0003f4f993b33f
$config         = require get_theme_file_path('config/base.php');

add_action( 'wp_enqueue_scripts', function () use ($config, $assets_version) {
    $enviroment = [];

    $fa = [
        'handle'    => 'pandawp/fontawesome/base',
        'src'       =>  $config['resources']['fontawesome']['cdn']['base'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ];

    register_assets('script', [
        'handle'    => 'pandawp/js/script/main',
        'src'       => $config['resources']['script_main'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    /**
     * --------------------------------------------------------------------------
     * Register Scripts
     * --------------------------------------------------------------------------
     *
     */

    register_assets('package', [
        'handle'    => 'pandawp/package/vue',
        'src'       => $config['resources']['package_vue'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    register_assets('package', [
        'handle'    => 'pandawp/package/setimmediate',
        'src'       => $config['resources']['package_setimmediate'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);    

    register_assets('package', [
        'handle'    => 'pandawp/package/process',
        'src'       => $config['resources']['package_process'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    register_assets('package', [
        'handle'    => 'pandawp/package/vuex',
        'src'       => $config['resources']['package_vuex'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    register_assets('package', [
        'handle'    => 'pandawp/package/timers_bf',
        'src'       => $config['resources']['package_timers_bf'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    register_assets('package', [
        'handle'    => 'pandawp/package/babel',
        'src'       => $config['resources']['package_babel'],
        'deps'      => [ ],
        'ver'       => $assets_version,
        'in_footer' => true
    ]);

    register_assets('package', [
        'handle'    => 'pandawp/package/webpack',
        'src'       => $config['resources']['package_webpack'],
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
     * Register Scripts with conditionals
     * --------------------------------------------------------------------------
     *
     */
    if ( is_front_page() ) {   
        register_assets('package', [
            'handle'    => 'pandawp/package/swiper',
            'src'       => $config['resources']['package_swiper'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/dom7',
            'src'       => $config['resources']['package_dom7'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/ssrwindow',
            'src'       => $config['resources']['package_ssr_window'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandawp/js/page/home',
            'src'       => $config['resources']['page_home'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
    }elseif ( is_page('emotional') ) {
        register_assets('package', [
            'handle'    => 'pandawp/package/swiper',
            'src'       => $config['resources']['package_swiper'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
    
        register_assets('package', [
            'handle'    => 'pandawp/package/dom7',
            'src'       => $config['resources']['package_dom7'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
    
        register_assets('package', [
            'handle'    => 'pandawp/package/ssrwindow',
            'src'       => $config['resources']['package_ssr_window'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandawp/js/page/emotional',
            'src'       => $config['resources']['page_emotional'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
    }elseif (is_page_template('templates/template-sesion-virtual.php')){
        register_assets('package', [
            'handle'    => 'pandawp/package/corejs',
            'src'       => $config['resources']['package_corejs'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/element_ui',
            'src'       => $config['resources']['package_element_ui'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/async_val',
            'src'       => $config['resources']['package_async_val'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/normalize_w',
            'src'       => $config['resources']['package_normalize_w'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/resize_obv',
            'src'       => $config['resources']['package_resize_obv'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandawp/js/page/virtual_sesion',
            'src'       => $config['resources']['page_virtual_sesion'],
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

    }elseif (is_page_template('templates/template-solicitar-cursos.php')){
        register_assets('script', [
            'handle'    => 'pandawp/js/page/solicitar-cursos',
            'src'       => $config['resources']['page_solicitar_cursos'],
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

    }elseif (is_page_template('templates/template-test.php')){
        register_assets('package', [
            'handle'    => 'pandawp/package/swiper',
            'src'       => $config['resources']['package_swiper'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
    
        register_assets('package', [
            'handle'    => 'pandawp/package/dom7',
            'src'       => $config['resources']['package_dom7'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);                
    
        register_assets('package', [
            'handle'    => 'pandawp/package/popperjs',
            'src'       => $config['resources']['package_popperjs'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/tippyjs',
            'src'       => $config['resources']['package_tippyjs'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
    
        register_assets('package', [
            'handle'    => 'pandawp/package/ssrwindow',
            'src'       => $config['resources']['package_ssr_window'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]); 
                
        register_assets('script', [
            'handle'    => 'pandawp/js/page/test',
            'src'       => $config['resources']['page_test'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

    }elseif (is_page_template('templates/template-test-behaviour.php')){
        register_assets('package', [
            'handle'    => 'pandawp/package/swiper',
            'src'       => $config['resources']['package_swiper'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/dom7',
            'src'       => $config['resources']['package_dom7'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);                
    
        register_assets('package', [
            'handle'    => 'pandawp/package/popperjs',
            'src'       => $config['resources']['package_popperjs'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/tippyjs',
            'src'       => $config['resources']['package_tippyjs'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
    
        register_assets('package', [
            'handle'    => 'pandawp/package/ssrwindow',
            'src'       => $config['resources']['package_ssr_window'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]); 
                
        register_assets('script', [
            'handle'    => 'pandawp/js/page/test-behaviour',
            'src'       => $config['resources']['page_test_behaviour'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

    }elseif (is_page('progreso')){
        register_assets('package', [
            'handle'    => 'pandawp/package/apexcharts',
            'src'       => $config['resources']['package_apexcharts'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
                
        register_assets('script', [
            'handle'    => 'pandawp/js/page/progress',
            'src'       => $config['resources']['page_progress'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);        

    }elseif (is_page('carrito')){               
        register_assets('script', [
            'handle'    => 'pandawp/js/page/shop_cart',
            'src'       => $config['resources']['page_shop_cart'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);        

    }elseif (is_page('compra-exitosa')){               
        register_assets('script', [
            'handle'    => 'pandawp/js/page/successfull_buy',
            'src'       => $config['resources']['page_successfull_buy'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

    }elseif (is_page('recuperar-contrasena') || is_page('actualizar-contrasena')){               
        register_assets('script', [
            'handle'    => 'pandawp/js/page/recovery_password',
            'src'       => $config['resources']['page_recovery_password'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

    }elseif (is_page('progreso')){               
        register_assets('script', [
            'handle'    => 'pandawp/js/page/progress',
            'src'       => $config['resources']['page_progress'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]); 

    }elseif (is_page('preguntas-frecuentes')){               
        register_assets('script', [
            'handle'    => 'pandawp/js/page/faq',
            'src'       => $config['resources']['page_faq'],
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
        register_assets('package', [
            'handle'    => 'pandawp/package/swiper',
            'src'       => $config['resources']['package_swiper'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
    
        register_assets('package', [
            'handle'    => 'pandawp/package/dom7',
            'src'       => $config['resources']['package_dom7'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]); 
    
        register_assets('package', [
            'handle'    => 'pandawp/package/vaswiper',
            'src'       => $config['resources']['package_vaswiper'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);         
    
        register_assets('package', [
            'handle'    => 'pandawp/package/ssrwindow',
            'src'       => $config['resources']['package_ssr_window'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]); 
        
        register_assets('script', [
            'handle'    => 'pandawp/js/page/topic',
            'src'       => $config['resources']['page_topic'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);        

    }elseif (is_singular('session')){
        register_assets('package', [
            'handle'    => 'pandawp/package/swiper',
            'src'       => $config['resources']['package_swiper'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
    
        register_assets('package', [
            'handle'    => 'pandawp/package/dom7',
            'src'       => $config['resources']['package_dom7'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]); 
                
        register_assets('package', [
            'handle'    => 'pandawp/package/vaswiper',
            'src'       => $config['resources']['package_vaswiper'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]); 

        register_assets('package', [
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
        register_assets('package', [
            'handle'    => 'pandawp/package/swiper',
            'src'       => $config['resources']['package_swiper'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
    
        register_assets('package', [
            'handle'    => 'pandawp/package/dom7',
            'src'       => $config['resources']['package_dom7'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]); 
    
        register_assets('package', [
            'handle'    => 'pandawp/package/vaswiper',
            'src'       => $config['resources']['package_vaswiper'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]); 

        register_assets('script', [
            'handle'    => 'pandawp/js/page/404',
            'src'       => $config['resources']['page_404'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
    }elseif (is_page('access')){
        $enviroment = array_merge(
            $enviroment,
            [
                'google_id'     => ENV['GOOGLE_ID'],
                'facebook_id'   => ENV['FACEBOOK_ID'],
            ]
        );

        register_assets('package', [
            'handle'    => 'pandawp/package/swiper',
            'src'       => $config['resources']['package_swiper'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/dom7',
            'src'       => $config['resources']['package_dom7'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/ssrwindow',
            'src'       => $config['resources']['package_ssr_window'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandawp/js/page/access',
            'src'       => $config['resources']['page_access'],
            'deps'      => [ ],
            'ver'       => $assets_version,
            'in_footer' => true
        ]);
    }


    /**
     * --------------------------------------------------------------------------
     * Enviroment variables
     * --------------------------------------------------------------------------
     *
     */
    setEnviromentVariables($enviroment);
}, 100);

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

add_action( 'admin_head', function() use ($config, $assets_version) {
    $current = get_current_screen();

    switch ($current->base) {
        case 'toplevel_page_options-inscriptions':
            register_assets('script', [
                'handle'    => 'pandawp/wp/registration',
                'src'       =>  $config['resources']['wp_registration'],
                'deps'      => [ ],
                'ver'       => $assets_version,
                'in_footer' => true
            ]); 
            break;

        case 'toplevel_page_acf-options-cuestionario-de-seguimiento':
            register_assets('script', [
                'handle'    => 'pandawp/wp/questionaries',
                'src'       =>  $config['resources']['wp_questionaries'],
                'deps'      => [ ],
                'ver'       => $assets_version,
                'in_footer' => true
            ]); 
            break;

        case 'edit':
            if($current->id == 'edit-course') {
                register_assets('package', [
                    'handle'    => 'pandawp/package/babel',
                    'src'       => $config['resources']['package_babel'],
                    'deps'      => [ ],
                    'ver'       => $assets_version,
                    'in_footer' => true
                ]);

                register_assets('script', [
                    'handle'    => 'pandawp/wp/courses',
                    'src'       =>  $config['resources']['wp_courses'],
                    'deps'      => [ ],
                    'ver'       => $assets_version,
                    'in_footer' => true
                ]);

                wp_localize_script( 'pandawp/wp/courses', 'mab', [
                    "site" => get_site_url()
                ]);
            }
            break;
    }
});
