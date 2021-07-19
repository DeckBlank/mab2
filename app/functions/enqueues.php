<?php

include_once __DIR__ . '/api/libs/enviroment.php';

$config = require get_theme_file_path('config/base.php');

add_action( 'wp_enqueue_scripts', function () use ($config) {
    $enviroment = [];

    $fa = [
        'handle'    => 'pandawp/fontawesome/base',
        'src'       =>  $config['resources']['fontawesome']['cdn']['base'],
        'deps'      => [ ],
        'ver'       => $config['vertion'],
        'in_footer' => true
    ];

    register_assets('script', [
        'handle'    => 'pandawp/js/script/main',
        'src'       => $config['resources']['script_main'],
        'deps'      => [ ],
        'ver'       => $config['vertion'],
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
        'ver'       => $config['vertion'],
        'in_footer' => true
    ]);

    register_assets('package', [
        'handle'    => 'pandawp/package/setimmediate',
        'src'       => $config['resources']['package_setimmediate'],
        'deps'      => [ ],
        'ver'       => $config['vertion'],
        'in_footer' => true
    ]);    

    register_assets('package', [
        'handle'    => 'pandawp/package/process',
        'src'       => $config['resources']['package_process'],
        'deps'      => [ ],
        'ver'       => $config['vertion'],
        'in_footer' => true
    ]);

    register_assets('package', [
        'handle'    => 'pandawp/package/vuex',
        'src'       => $config['resources']['package_vuex'],
        'deps'      => [ ],
        'ver'       => $config['vertion'],
        'in_footer' => true
    ]);

    register_assets('package', [
        'handle'    => 'pandawp/package/timers_bf',
        'src'       => $config['resources']['package_timers_bf'],
        'deps'      => [ ],
        'ver'       => $config['vertion'],
        'in_footer' => true
    ]);

    register_assets('package', [
        'handle'    => 'pandawp/package/babel',
        'src'       => $config['resources']['package_babel'],
        'deps'      => [ ],
        'ver'       => $config['vertion'],
        'in_footer' => true
    ]);

    register_assets('package', [
        'handle'    => 'pandawp/package/webpack',
        'src'       => $config['resources']['package_webpack'],
        'deps'      => [ ],
        'ver'       => $config['vertion'],
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
        'ver'    => $config['vertion'],
        'media'  => 'all'
    ]);

    register_assets('style', [
        'handle' => 'pandawp/style/main',
        'src'    => $config['resources']['style_main'],
        'deps'   => [ ],
        'ver'    => $config['vertion'],
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
        'ver'       => $config['vertion'],
        'in_footer' => true
    ]);

    register_assets('script', [
        'handle'    => 'pandawp/fontawesome/style_solid',
        'src'       => $config['resources']['fontawesome']['cdn']['style_solid'],
        'deps'      => [ $fa['handle'] ],
        'ver'       => $config['vertion'],
        'in_footer' => true
    ]);

    register_assets('script', [
        'handle'    => 'pandawp/fontawesome/brands',
        'src'       => $config['resources']['fontawesome']['cdn']['brands'],
        'deps'      => [ $fa['handle'] ],
        'ver'       => $config['vertion'],
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
            'handle'    => 'pandawp/js/page/home',
            'src'       => $config['resources']['page_home'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

        $enviroment = array_merge($enviroment, [
            'questions'     => get_field('faq', 'options'),
            'testimonies'   => get_field('testimonies', 'options')
        ]);
    }elseif ( is_page('emotional') ) {
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
            'handle'    => 'pandawp/js/page/emotional',
            'src'       => $config['resources']['page_emotional'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);
    }elseif (is_page_template('templates/template-sesion-virtual.php')){
        register_assets('package', [
            'handle'    => 'pandawp/package/corejs',
            'src'       => $config['resources']['package_corejs'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/element_ui',
            'src'       => $config['resources']['package_element_ui'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/async_val',
            'src'       => $config['resources']['package_async_val'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/normalize_w',
            'src'       => $config['resources']['package_normalize_w'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/resize_obv',
            'src'       => $config['resources']['package_resize_obv'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandawp/js/page/virtual_sesion',
            'src'       => $config['resources']['page_virtual_sesion'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

    }elseif (is_page_template('templates/template-solicitar-cursos.php')){
        register_assets('script', [
            'handle'    => 'pandawp/js/page/solicitar-cursos',
            'src'       => $config['resources']['page_solicitar_cursos'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

    }elseif (is_page('formularios')){
        register_assets('script', [
            'handle'    => 'pandawp/js/page/forms',
            'src'       => $config['resources']['page_forms'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

    }elseif (is_page_template('templates/template-test.php')){
        register_assets('package', [
            'handle'    => 'pandawp/package/apexcharts',
            'src'       => $config['resources']['package_apexcharts'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);
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
            'handle'    => 'pandawp/package/popperjs',
            'src'       => $config['resources']['package_popperjs'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/tippyjs',
            'src'       => $config['resources']['package_tippyjs'],
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
            'handle'    => 'pandawp/js/page/test',
            'src'       => $config['resources']['page_test'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

    }elseif (is_page_template('templates/template-test-behaviour.php')){
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
            'handle'    => 'pandawp/package/popperjs',
            'src'       => $config['resources']['package_popperjs'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/tippyjs',
            'src'       => $config['resources']['package_tippyjs'],
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
            'handle'    => 'pandawp/js/page/test-behaviour',
            'src'       => $config['resources']['page_test_behaviour'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

    }elseif (is_page('progreso')){
        register_assets('package', [
            'handle'    => 'pandawp/package/apexcharts',
            'src'       => $config['resources']['package_apexcharts'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);
                
        register_assets('script', [
            'handle'    => 'pandawp/js/page/progress',
            'src'       => $config['resources']['page_progress'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);
    }elseif (is_page('compra-exitosa')){               
        register_assets('script', [
            'handle'    => 'pandawp/js/page/successfull_buy',
            'src'       => $config['resources']['page_successfull_buy'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

    }elseif (is_page('recuperar-contrasena') || is_page('actualizar-contrasena')){               
        register_assets('script', [
            'handle'    => 'pandawp/js/page/recovery_password',
            'src'       => $config['resources']['page_recovery_password'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

    }elseif (is_page('progreso')){               
        register_assets('script', [
            'handle'    => 'pandawp/js/page/progress',
            'src'       => $config['resources']['page_progress'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]); 

    }elseif (is_page('preguntas-frecuentes')){               
        register_assets('script', [
            'handle'    => 'pandawp/js/page/faq',
            'src'       => $config['resources']['page_faq'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]); 

    }elseif (is_singular('video')){
        register_assets('script', [
            'handle'    => 'pandawp/js/page/video',
            'src'       => $config['resources']['page_video'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);        

    }elseif (is_singular('course')){
        $enviroment = array_merge(
            $enviroment, [
                'course_id' => get_the_ID(),
            ]
        );

        register_assets('script', [
            'handle'    => 'pandawp/js/page/course',
            'src'       => $config['resources']['page_course'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);        

    }elseif (is_singular('topic')){
        $enviroment = array_merge(
            $enviroment, [
                'topic_id' => get_the_ID(),
            ]
        );

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
            'handle'    => 'pandawp/package/vaswiper',
            'src'       => $config['resources']['package_vaswiper'],
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

        register_assets('package', [
            'handle'    => 'pandawp/package/jquery',
            'src'       => $config['resources']['package_jquery'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]); 

        register_assets('package', [
            'handle'    => 'pandawp/package/dm_file_uploader',
            'src'       => $config['resources']['package_dm_file_uploader'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]); 

        register_assets('script', [
            'handle'    => 'pandawp/js/page/topic',
            'src'       => $config['resources']['page_topic'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);        

    }elseif (is_singular('session')){
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
            'handle'    => 'pandawp/package/vaswiper',
            'src'       => $config['resources']['package_vaswiper'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]); 

        register_assets('package', [
            'handle'    => 'pandawp/package/opentok',
            'src'       => $config['resources']['package_opentok'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]); 

        register_assets('script', [
            'handle'    => 'pandawp/js/page/session',
            'src'       => $config['resources']['page_session'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);        

    }elseif (is_404()){
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
            'handle'    => 'pandawp/package/vaswiper',
            'src'       => $config['resources']['package_vaswiper'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]); 

        register_assets('script', [
            'handle'    => 'pandawp/js/page/404',
            'src'       => $config['resources']['page_404'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);
    }elseif (is_page('mis-cursos')){ 
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
            'handle'    => 'pandawp/js/page/my-courses',
            'src'       => $config['resources']['page_my_courses'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);        

    }elseif (is_page('access')){ 
        $enviroment = array_merge(
            $enviroment, [
                'facebook_id'   => ENV['FACEBOOK_ID'],
                'google_id'     => ENV['GOOGLE_ID']
            ]
        );

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
            'handle'    => 'pandawp/js/page/access',
            'src'       => $config['resources']['page_access'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);
    }elseif (is_page('cursos')){ 
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
            'handle'    => 'pandawp/js/page/courses',
            'src'       => $config['resources']['page_courses'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);
    }elseif (is_page('cursito')){               
        register_assets('script', [
            'handle'    => 'pandawp/js/page/curso',
            'src'       => $config['resources']['page_curso'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);
    }elseif (is_page('donaciones')){               
        register_assets('script', [
            'handle'    => 'pandawp/js/page/donaciones',
            'src'       => $config['resources']['page_donaciones'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);
    }elseif (is_page('lideres')){ 
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
            'handle'    => 'pandawp/js/page/lideres',
            'src'       => $config['resources']['page_lideres'],     
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]); 
    }elseif (is_page('charlas')){
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
            'handle'    => 'pandawp/js/page/charlas',
            'src'       => $config['resources']['page_charlas'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);
    }elseif (is_page('blog')){               
        register_assets('script', [
            'handle'    => 'pandawp/js/page/blog',
            'src'       => $config['resources']['page_blog'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]); 
    }elseif (is_singular('post')){
        register_assets('script', [
            'handle'    => 'pandawp/js/page/articulo',
            'src'       => $config['resources']['page_articulo'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]); 
    }elseif (is_page('nosotros')){   
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
            'handle'    => 'pandawp/package/popperjs',
            'src'       => $config['resources']['package_popperjs'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]);

        register_assets('package', [
            'handle'    => 'pandawp/package/tippyjs',
            'src'       => $config['resources']['package_tippyjs'],
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
            'handle'    => 'pandawp/js/page/nosotros',
            'src'       => $config['resources']['page_nosotros'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
            'in_footer' => true
        ]); 
    }elseif (is_page_template('templates/template-psicologos.php')){               
        register_assets('script', [
            'handle'    => 'pandawp/js/page/psicologos',
            'src'       => $config['resources']['page_psicologos'],
            'deps'      => [ ],
            'ver'       => $config['vertion'],
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

add_action('admin_enqueue_scripts', function ($hook) use ($config) {
    if ('post.php' !== $hook) {
        return;
    }
    register_assets('script', [
        'handle'    => 'pandawp/wp/session',
        'src'       =>  $config['resources']['wp_session'],
        'deps'      => [ ],
        'ver'       => $config['vertion'],
        'in_footer' => true
    ]);
});

add_action( 'admin_head', function() use ($config) {
    $current = get_current_screen();

    switch ($current->base) {
        case 'toplevel_page_options-inscriptions':
            register_assets('script', [
                'handle'    => 'pandawp/wp/registration',
                'src'       =>  $config['resources']['wp_registration'],
                'deps'      => [ ],
                'ver'       => $config['vertion'],
                'in_footer' => true
            ]); 
            break;

        case 'toplevel_page_acf-options-cuestionario-de-seguimiento':
            register_assets('script', [
                'handle'    => 'pandawp/wp/questionaries',
                'src'       =>  $config['resources']['wp_questionaries'],
                'deps'      => [ ],
                'ver'       => $config['vertion'],
                'in_footer' => true
            ]); 
            break;

        case 'edit':
            if($current->id == 'edit-course') {
                register_assets('package', [
                    'handle'    => 'pandawp/package/babel',
                    'src'       => $config['resources']['package_babel'],
                    'deps'      => [ ],
                    'ver'       => $config['vertion'],
                    'in_footer' => true
                ]);

                register_assets('script', [
                    'handle'    => 'pandawp/wp/courses',
                    'src'       =>  $config['resources']['wp_courses'],
                    'deps'      => [ ],
                    'ver'       => $config['vertion'],
                    'in_footer' => true
                ]);

                wp_localize_script( 'pandawp/wp/courses', 'mab', [
                    "site" => get_site_url()
                ]);
            }
            break;
    }
});
