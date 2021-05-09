<?php

use Timber\Timber;

if (!isset($paged) || !$paged){
    $paged = 1;
}

$context = Timber::get_context();
$context['post'] = Timber::get_post();

$templates = [
    'page-' . $post->post_name . '.twig',
    'page.twig'
];

if(is_page('access')){
    if (is_user_logged_in()) {
        header('Location:' . $context['site']->url . '/mis-cursos');        
    }

}else if(is_page('mis-cursos')){
    if (!is_user_logged_in()) {
        header('Location:' . $context['site']->url . '/access');        
    }

}else if(is_page('carrito')){
    if (is_user_logged_in()) {
        $env = require(__DIR__ . '/../env.php');
    
        $context['pasarell'] = (object)[
            "enviroment" => $env['ENV'],
            "action" => $env['PU_ACTION'],
            "merchan_id" => $env['PU_MERCHAND_ID'],
            "account_id" => $env['PU_ACCOUNT_ID']
        ];
    } else {
        header('Location:' . $context['site']->url . '/emotional');
    }

}else if(is_page('recuperar-contrasena')){
    if (isset($_GET['stage']) && $_GET['stage'] == 2) {
        $templates = 'auth/password/page-actualizar-contrasena.twig' ;
    } else {
        $templates = 'auth/password/page-recuperar-contrasena.twig' ;
    }
    
}else if(is_page('formularios')){
    if (!isset($_GET['type']) || !in_array($_GET['type'], ['profesor', 'estudiante', 'tutor', 'foreign'])) {
        header('Location:' . $context['site']->url);
    }
}

Timber::render( $templates, $context );
