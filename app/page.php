<?php

use Timber\Timber;

if (!isset($paged) || !$paged){
    $paged = 1;
}

function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $j = 0;
    $key_array = array();

    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$j] = $val[$key];
            $temp_array[$j] = $val;

            $j++;
        }
        $i++;
    }
    return $temp_array;
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

    $context['terms'] = get_field('terms', 'options');

}else if(is_page('mis-cursos')){
    if (!is_user_logged_in()) {
        header('Location:' . $context['site']->url . '/access');        
    }

    $templates = 'courses/page-mis-cursos.twig';

}else if(is_page('cursos')){
    $templates = 'courses/page-cursos.twig';

}else if(is_page('carrito')){
    if (is_user_logged_in()) {
        $env = require(__DIR__ . '/../env.php');
    
        $context['pasarell'] = (object)[
            "enviroment"    => $env['ENV'],
            "action"        => $env['PU_ACTION'],
            "merchan_id"    => $env['PU_MERCHAND_ID'],
            "account_id"    => $env['PU_ACCOUNT_ID']
        ];
    } else {
        header('Location:' . $context['site']->url);
    }

}else if(is_page('recuperar-contrasena')){
    if (isset($_GET['stage']) && $_GET['stage'] == 2) {
        $templates = 'auth/password/page-actualizar-contrasena.twig';
    } else {
        $templates = 'auth/password/page-recuperar-contrasena.twig';
    }
    
}else if(is_page('charlas')){
    $groupEvents = [];
    $ddd = [];

    $events = Timber::get_posts([
        'post_type'         => 'event',
        'posts_per_page'    => -1,
        'orderby'           => 'meta_value_num',
        'meta_key'          => 'date_event',
        'order'             => 'ASC'
    ]);

    $events = array_map(function($event){
        $speaker    = get_field('speaker', $event->ID);
        $limit      = get_field('participants_limit', $event->ID);
        $date       = get_field('date_event', $event->ID);

        $participants = get_post_meta($event->ID, 'participants', true);
        $participants = ($participants) ? intval($participants) : 0;

        return (object)[
            'id'            => $event->ID,
            'title'         => $event->title,
            'thumbnail'     => $event->thumbnail,
            'description'   => get_the_excerpt($event->ID),
            'speaker'       => ($speaker) ? sprintf('%s %s', $speaker['user_firstname'], $speaker['user_lastname']) : '',
            'participants'  => $participants,
            'limit'         => $limit,
            'percentage'    => ($limit) ? ($participants * 100) / intval($limit) : 0,
            'day'           => ($date) ? explode('-', $date)[2] : 0,
            'date'          => $date,
            'complete'      => ($limit == $participants) ? true : false
        ];
    }, $events);

    foreach($events as $event) {
        $date = explode('-', $event->date);

        array_push($groupEvents, [
            'date'      => $date[0] . '-' . $date[1],
            'events'    => []
        ]);
    }

    $groupEvents = unique_multidim_array($groupEvents, 'date');

    for ($i=0; $i < count($groupEvents) ; $i++) { 
        $eventsFinal = [];

        foreach($events as $event) {
            $date = explode('-', $event->date);
            $date = $date[0] . '-' . $date[1];

            if ($date == $groupEvents[$i]['date']) {
                array_push($eventsFinal, $event);
            }
        }

        $dateFinal = explode('-', $groupEvents[$i]['date']);

        $groupEvents[$i]['date'] = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre")[$dateFinal[1] - 1];
        $groupEvents[$i]['date'] = $groupEvents[$i]['date'] . ', ' . $dateFinal[0];

        $groupEvents[$i]['events']  = $eventsFinal;        
    }

    $context['groupEvents'] = $groupEvents;
}else if(is_page('blog')) {
    $articles = Timber::get_posts([
        'post_type'         => 'post',
        'posts_per_page'    => -1
    ]);

    $context['articles'] = array_map(function($article) use ($context){
        $author = get_field('author', $article->ID);
        $avatar = ($author) ? get_field('avatar', 'user_' . $author['ID']) : false;

        return [
            'title'     => $article->title,
            'author'    => ($avatar) ? $avatar['url'] : $context['theme']->link . 'static/images/og_image.png',
            'date'      => $article->date,
            'link'      => $article->link,
        ];
    }, $articles);

    $templates = ['blog/main.twig'];
}

Timber::render( $templates, $context );
