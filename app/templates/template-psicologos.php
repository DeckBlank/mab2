<?php
/**
* Template Name: Template - Psicologos
*/

use Timber\Timber;

$context = Timber::get_context();

$context['main_wsp']    = get_field('main_whatsapp', $post->ID);
$context['team']        = get_field('team', $post->ID);

Timber::render( 'page-psicologos.twig', $context );
