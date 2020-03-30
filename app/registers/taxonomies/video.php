<?php

function taxonomy_video() {
  $settings = [
    'name_taxonomy_plural'   => 'Categoría de videos',
    'name_taxonomy_Singular' => 'Categoría de video',
    'name_register_taxonomy' => 'tax-video',
    'rewrite_slug'           => 'videos',
    'post_type'              => [ 'video' ],
    'text_domain'            => 'pandawp'
  ];

  $labels = [
    'name'          => _x( $settings['name_taxonomy_plural'], 'Taxonomy General Name', $settings['text_domain'] ),
    'singular_name' => _x( $settings['name_taxonomy_Singular'], 'Taxonomy Singular Name', $settings['text_domain'] ),
    'menu_name'     => __( $settings['name_taxonomy_plural'], $settings['text_domain'] )
  ];

  $rewrite = [
    'slug'         => $settings['rewrite_slug'],
    'with_front'   => true,
    'hierarchical' => false,
  ];

  $args = [
    'labels'            => $labels,
    'hierarchical'      => true,
    'public'            => false,
    'show_ui'           => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud'     => true,
    'show_in_rest'      => true,
    'rewrite'           => $rewrite,
  ];
  register_taxonomy( $settings['name_register_taxonomy'], $settings['post_type'], $args );
}
add_action( 'init', 'taxonomy_video', 0 );
