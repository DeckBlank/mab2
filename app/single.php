<?php

use Timber\Timber;

/**
 * Libs
 */

function __getTopicOnNavigation($course_id, $unity_id, $topic_id, $direction){
    $course = Timber::get_post([
        "post_type" => "course",
        "p" => $course_id
    ]);

    $unities = get_field('unities', $course_id);
    $topics = $unities[$unity_id - 1]['topics'];

    for ($i=0; $i < count($topics); $i++) { 
        $_topic = $topics[$i]['topic'];

        if($_topic->ID == $topic_id){
            if ($direction == 'next') {
                if( isset($topics[$i + 1]['topic']) ){
                    return sprintf(
                        '%s?course_id=%s&course_name=%s&course_slug=%s&unity=%s&sector=%s',
                        get_the_permalink($topics[$i + 1]['topic']->ID),
                        $course_id,
                        $course->title,
                        $course->slug,
                        $unity_id,
                        $_GET['sector']
                    );                    

                }else if( isset($unities[$unity_id]['topics'][0]['topic']) ){
                    return sprintf(
                        '%s?course_id=%s&course_name=%s&course_slug=%s&unity=%s&sector=%s',
                        get_the_permalink($unities[$unity_id]['topics'][0]['topic']->ID),
                        $course_id,
                        $course->title,
                        $course->slug,
                        $unity_id + 1,
                        $_GET['sector']
                    );

                }else {
                    return null;
                }
            } else {
                if( isset($topics[$i - 1]['topic']) ){
                    return sprintf(
                        '%s?course_id=%s&course_name=%s&course_slug=%s&unity=%s&sector=%s',
                        get_the_permalink($topics[$i - 1]['topic']->ID),
                        $course_id,
                        $course->title,
                        $course->slug,
                        $unity_id,
                        $_GET['sector']
                    );                    
                    
                }else if( isset($unities[$unity_id - 2]['topics'][0]['topic']) ){
                    return sprintf(
                        '%s?course_id=%s&course_name=%s&course_slug=%s&unity=%s&sector=%s',
                        get_the_permalink($unities[$unity_id - 2]['topics'][0]['topic']->ID),
                        $course_id,
                        $course->title,
                        $course->slug,
                        $unity_id - 1,
                        $_GET['sector']
                    );

                }else {
                    return null;
                }
            }
        }
    }
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
    $context['price'] = 100;
    $context['discount'] = 0;
    $context['g_discount'] = 50;
    $context['area'] = get_field('area', $post->ID);

}else if($post->post_type == "topic"){
    $context['navigation'] = (object)[
        "previous" => __getTopicOnNavigation($_GET['course_id'], $_GET['unity'], $post->ID, 'previous'),
        "next" => __getTopicOnNavigation($_GET['course_id'], $_GET['unity'], $post->ID, 'next')
    ];

    $context['area'] = get_field('area', $_GET['course_id']);

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
