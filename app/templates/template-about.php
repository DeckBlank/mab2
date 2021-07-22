<?php
/**
* Template Name: Template - About
*/

use Timber\Timber;

$context = Timber::get_context();

$context['time'] = get_field('time', $post->ID);

Timber::render( 'page-nosotros.twig', $context );
