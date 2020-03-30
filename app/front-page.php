<?php

use Timber\Timber;

$context          = Timber::get_context();
$context['post'] = Timber::get_post();

$context['video_categories'] = Timber::get_terms([
    "taxonomy" => "tax-video"
]);

Timber::render( 'home.twig', $context );
