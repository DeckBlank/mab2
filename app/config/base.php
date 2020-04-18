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
                'base' => 'https://pandora-space-america.nyc3.digitaloceanspaces.com/statics/icons/5.6.3/js/fontawesome.min.js',
                'style' => 'https://pandora-space-america.nyc3.digitaloceanspaces.com/statics/icons/5.6.3/js/regular.min.js',
                'brands' => 'https://pandora-space-america.nyc3.digitaloceanspaces.com/statics/icons/5.6.3/js/brands.min.js'
            ]
        ],

        /**
         * Packages
         * */

        'package_fancyapps' => get_theme_file_uri('/static/js/package.fancyapps.bundle.js'),

        'package_dom7' => get_theme_file_uri('/static/js/package.dom7.bundle.js'),

        'package_swiper' => get_theme_file_uri('/static/js/package.swiper.bundle.js'),

        'package_vaswiper' => get_theme_file_uri('/static/js/package.vue-awesome-swiper.bundle.js'),

        'package_vue' => get_theme_file_uri('/static/js/package.vue.bundle.js'),

        'package_setimmediate' => get_theme_file_uri('/static/js/package.setimmediate.bundle.js'),

        'package_process' => get_theme_file_uri('/static/js/package.process.bundle.js'),

        'package_vuex' => get_theme_file_uri('/static/js/package.vuex.bundle.js'),

        'package_timers_bf' => get_theme_file_uri('/static/js/package.timers-browserify.bundle.js'),

        'package_corejs' => get_theme_file_uri('/static/js/package.core-js.bundle.js'),

        'package_element_ui' => get_theme_file_uri('/static/js/package.element-ui.bundle.js'),

        'package_async_val' => get_theme_file_uri('/static/js/package.async-validator.bundle.js'),

        'package_normalize_w' => get_theme_file_uri('/static/js/package.normalize-wheel.bundle.js'),

        'package_resize_obv' => get_theme_file_uri('/static/js/package.resize-observer-polyfill.bundle.js'),

        'package_opentok' => get_theme_file_uri('/static/js/package.opentok.bundle.js'),   

        'package_apexcharts' => get_theme_file_uri('/static/js/package.apexcharts.bundle.js'),

        /**
         * Styles
         * */
        'style_main' => get_theme_file_uri('/static/css/main.css'),

        /**
         * Scripts
         * */

        //Pages
        'page_home' => get_theme_file_uri('/static/js/page-home.bundle.js'),

        'page_emotional' => get_theme_file_uri('/static/js/page-emotional.bundle.js'),

        'page_virtual_sesion' => get_theme_file_uri('/static/js/page-virtual-sesion.bundle.js'),

        'page_login' => get_theme_file_uri('/static/js/page-login.bundle.js'),

        'page_solicitar_cursos' => get_theme_file_uri('/static/js/page-solicitar-cursos.bundle.js'),

        'page_forms' => get_theme_file_uri('/static/js/page-forms.bundle.js'),

        'page_test' => get_theme_file_uri('/static/js/page-test.bundle.js'),

        'page_progress' => get_theme_file_uri('/static/js/page-progress.bundle.js'),

        'page_404' => get_theme_file_uri('/static/js/page-404.bundle.js'),

        'page_video' => get_theme_file_uri('/static/js/page-video.bundle.js'),

        'page_course' => get_theme_file_uri('/static/js/page-course.bundle.js'),

        'page_topic' => get_theme_file_uri('/static/js/page-topic.bundle.js'),

        'page_session' => get_theme_file_uri('/static/js/page-session.bundle.js'),

        //WP
        'wp_session' => get_theme_file_uri('/static/js/wp-session.bundle.js')
    ]
];
