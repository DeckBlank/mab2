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

            Timber::render('user/profile.twig', $context);
        } else {
            Timber::render('404.twig', $context);
        }
        break;

    case 'leader':
        $userNicename   = $params['route']['user_nicename'];
        $user           = User::where(['user_nicename' => $userNicename])->first();

        if ($user) {
            $cover = get_field('cover', 'user_' . $user->ID);

            $context['user'] = [
                'fullname' => sprintf(
                    '%s %s',
                    get_user_meta( $user->ID, 'first_name', true ),
                    get_user_meta( $user->ID, 'last_name', true )
                ),
                'slogan'        => get_field('slogan', 'user_' . $user->ID),
                'description'   => get_field('about', 'user_' . $user->ID),
                'cover'         => ($cover) ? $cover['url'] : '',
            ];

            Timber::render('user/leader.twig', $context);
        } else {
            Timber::render('404.twig', $context);
        }
        break;
    case 'donations':
        $env = require(__DIR__ . '/../env.php');

        $context['pasarell'] = (object)[
            "enviroment"    => $env['ENV'],
            "action"        => $env['PU_ACTION'],
            "merchan_id"    => $env['PU_MERCHAND_ID'],
            "account_id"    => $env['PU_ACCOUNT_ID']
        ];

        Timber::render('page-donaciones.twig', $context);
        break;
    case 'shopcart':
        $env = require(__DIR__ . '/../env.php');

        $context['pasarell'] = (object)[
            "enviroment"    => $env['ENV'],
            "action"        => $env['PU_ACTION'],
            "merchan_id"    => $env['PU_MERCHAND_ID'],
            "account_id"    => $env['PU_ACCOUNT_ID']
        ];

        Timber::render('shop/cart.twig', $context);
        break;
    case 'shopcart-success':
        $env = require(__DIR__ . '/../env.php');

        $context['pasarell'] = (object)[
            "enviroment"    => $env['ENV'],
            "action"        => $env['PU_ACTION'],
            "merchan_id"    => $env['PU_MERCHAND_ID'],
            "account_id"    => $env['PU_ACCOUNT_ID']
        ];

        Timber::render('shop/success.twig', $context);
        break;
    case 'educacion':

        Timber::render('comunity/educacion.twig', $context);
        break;
    case 'empresas':

        Timber::render('comunity/empresas.twig', $context);
        break;
    case 'proyectos':

        Timber::render('comunity/proyectos.twig', $context);
        break;
    case 'llegamos':

        Timber::render('comunity/llegamos.twig', $context);
        break;
    case 'servicios':

        Timber::render('comunity/servicios.twig', $context);
        break;
}
