<?php
/**
* Template Name: Template - Test Behaviour
*/

use Timber\Timber;

$context = Timber::get_context();

$context['post'] = Timber::get_post();

Timber::render( 'page-test-behaviour.twig', $context );
