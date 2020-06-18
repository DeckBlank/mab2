<?php
/**
* Template Name: Template - Sesion virtual
*/

use Timber\Timber;

$context = Timber::get_context();

$context['post'] = Timber::get_post();
$context['courses'] = get_field('courses', $post->ID);

Timber::render( 'page-sesion-virtual.twig', $context );