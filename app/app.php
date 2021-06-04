<?php

use Timber\Timber;

if (!isset($paged) || !$paged){
    $paged = 1;
}

$context            = Timber::get_context();
$context['params']  = $params;

switch ($params['view']) {
    case 'perfil':
        Timber::render('page-perfil.twig', $context);
        break;
}
