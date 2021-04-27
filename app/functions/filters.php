<?php

use Timber\Timber;

add_filter('script_loader_tag', function($tag, $handle) {
    if ( 'api-google-maps' !== $handle )
        return $tag;
    return str_replace( ' src', ' async defer src', $tag );
}, 10, 2);

add_filter('timber/context', function($context) {
    $context['primary_menu'] = new \Timber\Menu( 'primary-menu' );
    $context['footer_menu']  = new \Timber\Menu( 'footer-menu' );
    $context['social_menu']  = new \Timber\Menu( 'social-menu' );

    $context['information']  = (object)[
        "phone" => get_field('phone', 'options'),
        "wsp"   => get_field('wsp', 'options'),
        "email" => get_field('email', 'options')
    ];

    $context['public_sector'] = Timber::get_terms([
        "taxonomy" => "tax-course"
    ]);
    
    return $context;
});
