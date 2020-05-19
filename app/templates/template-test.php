<?php
/**
* Template Name: Template - Test
*/

use Timber\Timber;

$context = Timber::get_context();

$context['post'] = Timber::get_post();
$context['info'] = get_field('info', $post->ID);

Timber::render( 'page-test.twig', $context );
