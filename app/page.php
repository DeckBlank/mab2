<?php

use Timber\Timber;

if (!isset($paged) || !$paged){
    $paged = 1;
}

$context = Timber::get_context();
$context['post'] = Timber::get_post();

$templates = [
    'page-' . $post->post_name . '.twig',
    'page.twig'
];

if(is_page('login')){
    $context['title'] = get_field('title', $post->ID);
    $context['phrase'] = get_field('phrase', $post->ID);

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

}else if(is_page('carrito')){
    $env = require(__DIR__ . '/../env.php');

    $context['pasarell'] = (object)[
        "enviroment" => $env['ENV'],
        "action" => $env['PU_ACTION'],
        "merchan_id" => $env['PU_MERCHAND_ID'],
        "account_id" => $env['PU_ACCOUNT_ID']
    ];
}else if(is_page('recuperar-contrasena')){
    if (isset($_GET['stage']) && $_GET['stage'] == 2) {
        $templates = 'auth/password/page-actualizar-contrasena.twig' ;
    } else {
        $templates = 'auth/password/page-recuperar-contrasena.twig' ;
    }
    
}else if(is_page('formularios')){
    if (!isset($_GET['type']) || !in_array($_GET['type'], ['profesor', 'estudiante', 'tutor'])) {
        header('Location:' . $context['site']->url);
    }
}

Timber::render( $templates, $context );
