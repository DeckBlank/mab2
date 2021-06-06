<?php

use Timber\Timber;

if (!isset($paged) || !$paged){
    $paged = 1;
}

$context            = Timber::get_context();
$context['params']  = $params;

switch ($params['view']) {
    case 'perfil':
        $userNicename   = $params['route']['user_nicename'];
        $user           = User::where(['user_nicename' => $userNicename])->first();

        if ($user) {
            $context['user'] = $user;

            Timber::render('page-perfil.twig', $context);
        } else {
            Timber::render('404.twig', $context);
        }
        break;
}
