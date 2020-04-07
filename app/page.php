<?php

use Timber\Timber;

if (!isset($paged) || !$paged){
    $paged = 1;
}

$context         = Timber::get_context();
$context['post'] = Timber::get_post();

if(is_page('login')){
    $context['title'] = get_field('title', $post->ID);
    $context['phrase'] = get_field('phrase', $post->ID);

}else if(is_page('sesion-virtual')){
    $context['courses'] = Timber::get_posts([
        "post_type" => "course",
        "posts_per_page" => -1
    ]);

}else if(is_page('emotional')){
    $context['video_categories'] = Timber::get_terms([
        "taxonomy" => "tax-video"
    ]);

}

$templates = [
    'page-' . $post->post_name . '.twig',
    'page.twig'
];

Timber::render( $templates, $context );
