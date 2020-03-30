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
}

$templates = [
    'page-' . $post->post_name . '.twig',
    'page.twig'
];

Timber::render( $templates, $context );
