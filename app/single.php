<?php

use Timber\Timber;

/**
 * Lib
 */
function __getTopicsSanitize($topics){
    $topics_sanitize = [];

    if($topics){
        foreach($topics as $topic){
            array_push($topics_sanitize, (object)[
                "title" => $topic['topic']->post_title,
                "link" => get_the_permalink($topic['topic']->ID),
                "summary" => ( get_field('summary', $topic['topic']->ID) ) ? get_field('summary', $topic['topic']->ID)['url'] : false,
                "map" => ( get_field('map', $topic['topic']->ID) ) ? get_field('map', $topic['topic']->ID)['url'] : false,
                "worksheet" => ( get_field('worksheet', $topic['topic']->ID) ) ? get_field('worksheet', $topic['topic']->ID)['url'] : false,
                "solutions" => ( get_field('solutions', $topic['topic']->ID) ) ? get_field('solutions', $topic['topic']->ID)['url'] : false
            ]);
        }
    }

    return $topics_sanitize;
}

$context         = Timber::get_context();
$context['post'] = Timber::get_post();

if($post->post_type == "video"){
    $context['author'] = (object)[
        "first_name" =>  $context['post']->author->first_name,
        "last_name" =>  $context['post']->author->last_name,
        "avatar" => get_field('picture', 'user_'. $context['post']->author->ID )
    ];

}else if($post->post_type == "course"){
    $context['unities'] = [];

    if(get_field('unities', $post->ID)){
        foreach( get_field('unities', $post->ID) as $unity){
            array_push($context['unities'],(object)[
                "title" => $unity['title'],
                "topics" => __getTopicsSanitize( $unity['topics'] )
            ]);
        }
    }

    $context['price'] = 100;
    $context['discount'] = 0;
    $context['g_discount'] = 50;

}else if($post->post_type == "topic"){
    $context['author'] = (object)[
        "first_name" =>  $context['post']->author->first_name,
        "last_name" =>  $context['post']->author->last_name,
        "avatar" => get_field('picture', 'user_'. $context['post']->author->ID )
    ];

    $context['source'] = get_field('source', $post->ID);
    $context['summary'] = ( get_field('summary', $post->ID) ) ? get_field('summary', $post->ID)['url'] : false;
    $context['map'] = ( get_field('map', $post->ID) ) ? get_field('map', $post->ID)['url'] : false;
    $context['worksheet'] = ( get_field('worksheet', $post->ID) ) ? get_field('worksheet', $post->ID)['url'] : false;
    $context['solutions'] = ( get_field('solutions', $post->ID) ) ? get_field('solutions', $post->ID)['url'] : false;
}

$templates = [
    'single-' . $post->post_type . '.twig',
    'single.twig'
];

Timber::render( $templates, $context );
