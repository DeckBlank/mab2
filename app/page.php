<?php

use Timber\Timber;

if (!isset($paged) || !$paged){
    $paged = 1;
}

$context         = Timber::get_context();
$context['post'] = Timber::get_post();

$templates = [
    'page-' . $post->post_name . '.twig',
    'page.twig'
];

if(is_page('login')){
    $context['title'] = get_field('title', $post->ID);
    $context['phrase'] = get_field('phrase', $post->ID);

}else if(is_page('sesion-virtual')){
    $context['courses'] = get_field('courses', $post->ID);

}else if(is_page('emotional')){
    $context['video_categories'] = Timber::get_terms([
        "taxonomy" => "tax-video",
        'meta_key'  => 'order',
        'orderby'   => 'meta_value_num',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'order',
                'value' => 0,
                'compare' => '>',
            )
        )
    ]);
    $context['banner'] = get_field('banner', $post->ID);

}else if(is_page('test')){
    $context['info'] = get_field('info', $post->ID);

}else if(is_page('carrito')){
    $context['pasarell'] = get_field('sell', 'options')['pasarell'];
}else if(is_page('recuperar-contrasena')){
    if ($_GET['stage'] == 2) {
        $templates = 'auth/password/page-actualizar-contrasena.twig' ;
    } else {
        $templates = 'auth/password/page-recuperar-contrasena.twig' ;
    }
    
}

Timber::render( $templates, $context );
