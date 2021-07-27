<?php

$GOOGLE_API_KEY = '*********************************';
$FONTS_FAMILIES = 'Lato:wght@400;700';

return [
    'GOOGLE_API_KEY' => $GOOGLE_API_KEY,

    'resources' => [

        /**
         * Google
         * */
        'google_fonts' => 'https://fonts.googleapis.com/css?family=' . $FONTS_FAMILIES . '&display=swap',

        'google_maps' => 'https://maps.googleapis.com/maps/api/js?key=' . $GOOGLE_API_KEY . '&callback=initMap',

        /**
         * Fontawesome
         * */
        'fontawesome' => [
            'cdn' => [
                'base'          => get_theme_file_uri('/static/fonts/fontawesome/main.min.js'),
                'style'         => get_theme_file_uri('/static/fonts/fontawesome/regular.min.js'),
                'style_solid'   => get_theme_file_uri('/static/fonts/fontawesome/solid.min.js'),
                'brands'        => get_theme_file_uri('/static/fonts/fontawesome/brands.min.js')
            ]
        ],

        /**
         * Packages
         * */

        'package_fancyapps'         => get_theme_file_uri( __getResourceURL('js', 'package.fancyapps.bundle.js') ),
        'package_dom7'              => get_theme_file_uri( __getResourceURL('js', 'package.dom7.bundle.js') ),
        'package_swiper'            => get_theme_file_uri( __getResourceURL('js', 'package.swiper.bundle.js') ),
        'package_vaswiper'          => get_theme_file_uri( __getResourceURL('js', 'package.vue-awesome-swiper.bundle.js') ),
        'package_vue'               => get_theme_file_uri( __getResourceURL('js', 'package.vue.bundle.js') ),
        'package_setimmediate'      => get_theme_file_uri( __getResourceURL('js', 'package.setimmediate.bundle.js') ),
        'package_process'           => get_theme_file_uri( __getResourceURL('js', 'package.process.bundle.js') ),
        'package_vuex'              => get_theme_file_uri( __getResourceURL('js', 'package.vuex.bundle.js') ),
        'package_timers_bf'         => get_theme_file_uri( __getResourceURL('js', 'package.timers-browserify.bundle.js') ),
        'package_corejs'            => get_theme_file_uri( __getResourceURL('js', 'package.core-js.bundle.js') ),
        'package_element_ui'        => get_theme_file_uri( __getResourceURL('js', 'package.element-ui.bundle.js') ),
        'package_async_val'         => get_theme_file_uri( __getResourceURL('js', 'package.async-validator.bundle.js') ),
        'package_normalize_w'       => get_theme_file_uri( __getResourceURL('js', 'package.normalize-wheel.bundle.js') ),
        'package_resize_obv'        => get_theme_file_uri( __getResourceURL('js', 'package.resize-observer-polyfill.bundle.js') ),
        'package_opentok'           => get_theme_file_uri( __getResourceURL('js', 'package.opentok.bundle.js') ),
        'package_apexcharts'        => get_theme_file_uri( __getResourceURL('js', 'package.apexcharts.bundle.js') ),
        'package_babel'             => get_theme_file_uri( __getResourceURL('js', 'package.babel.bundle.js') ),
        'package_webpack'           => get_theme_file_uri( __getResourceURL('js', 'package.webpack.bundle.js') ),
        'package_popperjs'          => get_theme_file_uri( __getResourceURL('js', 'package.popperjs.bundle.js') ),
        'package_tippyjs'           => get_theme_file_uri( __getResourceURL('js', 'package.tippy.js.bundle.js') ),
        'package_ssr_window'        => get_theme_file_uri( __getResourceURL('js', 'package.ssr-window.bundle.js') ),
        'package_dm_file_uploader'  => get_theme_file_uri( __getResourceURL('js', 'package.dm-file-uploader.bundle.js') ),
        'package_jquery'            => get_theme_file_uri( __getResourceURL('js', 'package.jquery.bundle.js') ),
        'package_pure_md5'          => get_theme_file_uri( __getResourceURL('js', 'package.pure-md5.bundle.js') ),
        'package_vimeo'             => get_theme_file_uri( __getResourceURL('js', 'package.vimeo.bundle.js') ),

        /**
         * Styles
         * */
        'style_main' => get_theme_file_uri( __getResourceURL('css', 'main.css') ),

        /**
         * Scripts
         * */
        'script_main' => get_theme_file_uri( __getResourceURL('js', 'main.bundle.js') ),

        //Page
        'page_home'                 => get_theme_file_uri( __getResourceURL('js', 'page-home.bundle.js') ),
        'page_emotional'            => get_theme_file_uri( __getResourceURL('js', 'page-emotional.bundle.js') ),
        'page_virtual_sesion'       => get_theme_file_uri( __getResourceURL('js', 'page-virtual-sesion.bundle.js') ),
        'page_solicitar_cursos'     => get_theme_file_uri( __getResourceURL('js', 'page-solicitar-cursos.bundle.js') ),
        'page_forms'                => get_theme_file_uri( __getResourceURL('js', 'page-forms.bundle.js') ),
        'page_test'                 => get_theme_file_uri( __getResourceURL('js', 'page-test.bundle.js') ),
        'page_test_behaviour'       => get_theme_file_uri( __getResourceURL('js', 'page-test-behaviour.bundle.js') ),
        'page_progress'             => get_theme_file_uri( __getResourceURL('js', 'page-progress.bundle.js') ),
        'page_shop_cart'            => get_theme_file_uri( __getResourceURL('js', 'page-shop-cart.bundle.js') ),
        'page_shop_success'         => get_theme_file_uri( __getResourceURL('js', 'page-shop-success.bundle.js') ),
        'page_recovery_password'    => get_theme_file_uri( __getResourceURL('js', 'page-recovery-password.bundle.js') ),
        'page_faq'                  => get_theme_file_uri( __getResourceURL('js', 'page-faq.bundle.js') ),
        'page_404'                  => get_theme_file_uri( __getResourceURL('js', 'page-404.bundle.js') ),
        'page_video'                => get_theme_file_uri( __getResourceURL('js', 'page-video.bundle.js') ),
        'page_course'               => get_theme_file_uri( __getResourceURL('js', 'page-course.bundle.js') ),
        'page_topic'                => get_theme_file_uri( __getResourceURL('js', 'page-topic.bundle.js') ),
        'page_session'              => get_theme_file_uri( __getResourceURL('js', 'page-session.bundle.js') ),
        'page_access'               => get_theme_file_uri( __getResourceURL('js', 'page-access.bundle.js') ),
        'page_my_courses'           => get_theme_file_uri( __getResourceURL('js', 'page-my-courses.bundle.js') ),
        'page_courses'              => get_theme_file_uri( __getResourceURL('js', 'page-courses.bundle.js') ),
        'page_perfil'               => get_theme_file_uri( __getResourceURL('js', 'page-perfil.bundle.js') ),
        'page_donaciones'           => get_theme_file_uri( __getResourceURL('js', 'page-donaciones.bundle.js') ),
        'page_lideres'              => get_theme_file_uri( __getResourceURL('js', 'page-lideres.bundle.js') ),
        'page_lideres-single'       => get_theme_file_uri( __getResourceURL('js', 'page-lideres-single.bundle.js') ),
        'page_charlas'              => get_theme_file_uri( __getResourceURL('js', 'page-charlas.bundle.js') ),
        'page_blog'                 => get_theme_file_uri( __getResourceURL('js', 'page-blog.bundle.js') ),
        'page_articulo'             => get_theme_file_uri( __getResourceURL('js', 'page-articulo.bundle.js') ),
        'page_nosotros'             => get_theme_file_uri( __getResourceURL('js', 'page-nosotros.bundle.js') ),
        'page_psicologos'           => get_theme_file_uri( __getResourceURL('js', 'page-psicologos.bundle.js') ),
        'page_educacion'            => get_theme_file_uri( __getResourceURL('js', 'page-educacion.bundle.js') ),
        'page_empresas'             => get_theme_file_uri( __getResourceURL('js', 'page-empresas.bundle.js') ),
        'page_proyectos'             => get_theme_file_uri( __getResourceURL('js', 'page-proyectos.bundle.js') ),
        'page_llegamos'             => get_theme_file_uri( __getResourceURL('js', 'page-llegamos.bundle.js') ),
        'page_servicios'             => get_theme_file_uri( __getResourceURL('js', 'page-servicios.bundle.js') ),
        'page_certificado'          => get_theme_file_uri( __getResourceURL('js', 'page-certificado.bundle.js') ),



        //WP
        'wp_session'            => get_theme_file_uri( __getResourceURL('js', 'wp-session.bundle.js') ),
        'wp_registration'       => get_theme_file_uri( __getResourceURL('js', 'wp-registration.bundle.js') ),
        'wp_questionaries'      => get_theme_file_uri( __getResourceURL('js', 'wp-questionaries.bundle.js') ),
        'wp_courses'            => get_theme_file_uri( __getResourceURL('js', 'wp-courses.bundle.js') ),
        'wp_topics'             => get_theme_file_uri( __getResourceURL('js', 'wp-topics.bundle.js') ),
        'wp_categories_import'  => get_theme_file_uri( __getResourceURL('js', 'wp-categories-import.bundle.js') ),
    ],
    'vertion' => '1627366728691'
];
