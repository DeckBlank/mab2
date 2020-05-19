<?php
/**
* Template Name: Template - Solicitar Cursos
*/

use Timber\Timber;

$context = Timber::get_context();
$context['post'] = Timber::get_post();

Timber::render( 'page-solicitar-cursos.twig', $context );
