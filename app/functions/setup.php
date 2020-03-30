<?php

add_action( 'after_setup_theme', function() {
    register_nav_menus([
        'primary-menu' => __( 'Menú Principal', 'pandawp' ),
        'social-menu'  => __( 'Menú Redes Sociales', 'pandawp' ),
        'footer-menu'  => __( 'Menú Pie de página', 'pandawp' ),
    ]);

    add_theme_support( 'automatic-feed-links' );

    add_theme_support( 'title-tag' );

    add_theme_support( 'post-thumbnails' );

    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );

    add_theme_support( 'wp-block-styles' );

    add_theme_support( 'align-wide' );

    add_theme_support( 'editor-styles' );
});

add_action( 'pre_get_posts', function ( $query ) {
    if ( is_post_type_archive() ) {
        set_query_var('posts_per_page', 4);
    }
});

add_action( 'widgets_init', function () {
    if ( WP_DEBUG ) {
        register_sidebar( array(
            'name'          => esc_html__( 'Sidebar', 'demo' ),
            'id'            => 'sidebar-demo',
            'description'   => esc_html__( 'Add widgets here.', 'demo' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );
    }
});

add_action( 'wp', function() {
    if ( is_singular('video') ) {
        if ( !get_post_meta(get_the_ID(), 'post_likes_average') ) {
            add_post_meta(get_the_ID(), 'post_likes_average', 0);
        }

        if ( !get_post_meta(get_the_ID(), 'post_likes_count') ) {
            add_post_meta(get_the_ID(), 'post_likes_count', 0);
        }
    }

    if ( is_singular('topic') ) {
        if ( !get_post_meta(get_the_ID(), 'post_likes_average') ) {
            add_post_meta(get_the_ID(), 'post_likes_average', 0);
        }

        if ( !get_post_meta(get_the_ID(), 'post_likes_count') ) {
            add_post_meta(get_the_ID(), 'post_likes_count', 0);
        }
    }
});

add_action('template_redirect', function () {
   if ( is_author() ) {
       wp_redirect(site_url(), 301);
   }
});

add_filter('post_type_link', 'custom_sesion_permalink', 1, 3);
function custom_sesion_permalink($post_link, $id = 0, $leavename) {
    if ( strpos('%sesion_id%', $post_link) === 'FALSE' ) {
        return $post_link;
    }
    $post = get_post($id);
    if ( is_wp_error($post) || $post->post_type != 'sesion' ) {
        return $post_link;
    }
    return str_replace('%sesion_id%', $post->ID, $post_link);
}
