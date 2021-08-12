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
    $topics = (($unity_id >= 1) && $unities[$unity_id - 1]) ? $unities[$unity_id - 1]['topics'] : [];

    for ($i=0; $i < count($topics); $i++) { 
        $_topic = $topics[$i]['topic'];

        if($_topic->ID == $topic_id){
            if ($direction == 'next') {
                if( isset($topics[$i + 1]['topic']) ){
                    return sprintf(
                        '%s?course_id=%s&topic_number=%s&unity=%s',
                        get_the_permalink($topics[$i + 1]['topic']->ID),
                        $course_id,
                        ($i + 2),
                        $unity_id
                    );                    

                }else if( isset($unities[$unity_id]['topics'][0]['topic']) ){
                    return sprintf(
                        '%s?course_id=%s&topic_number=%s&unity=%s',
                        get_the_permalink($unities[$unity_id]['topics'][0]['topic']->ID),
                        $course_id,
                        ($i + 2),
                        $unity_id + 1
                    );

                }else {
                    return null;
                }
            } else {
                if( isset($topics[$i - 1]['topic']) ){
                    return sprintf(
                        '%s?course_id=%s&topic_number=%s&unity=%s',
                        get_the_permalink($topics[$i - 1]['topic']->ID),
                        $course_id,
                        ($i),
                        $unity_id
                    );                    
                    
                }else if( isset($unities[$unity_id - 2]['topics'][0]['topic']) ){
                    return sprintf(
                        '%s?course_id=%s&topic_number=%s&unity=%s',
                        get_the_permalink($unities[$unity_id - 2]['topics'][0]['topic']->ID),
                        $course_id,
                        ($i),
                        $unity_id - 1
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

$templates = [
    'single-' . $post->post_type . '.twig',
    'single.twig'
];

if ($post->post_type == "video") {
    $context['author'] = (object)[
        "first_name" =>  $context['post']->author->first_name,
        "last_name" =>  $context['post']->author->last_name,
        "avatar" => get_field('picture', 'user_'. $context['post']->author->ID )
    ];

} else if($post->post_type == "course") {
    $teacher = get_field('teacher', $post->ID);

    $context['description'] = get_the_excerpt($post->ID);
    $context['likes']       = __getMetaCourse($post->ID, '-1', 'likes');
    $context['vector']      = get_field('vector', $post->ID);
    $context['trailer']     = get_field('trailer', $post->ID);
    $context['duration']    = __getMetaCourse($post->ID, '-1', 'duration');

    if ($teacher) {
        $teacherUser = get_userdata($teacher['ID']);

        $context['teacher']     = [
            'fullname'  => $teacher['user_firstname'] . ' ' . $teacher['user_lastname'],
            'job'       => get_field('job', 'user_' . $teacher['ID']),
            'cover'     => get_field('cover', 'user_' . $teacher['ID']),
            'link'      => ( in_array( 'speaker', (array) $teacherUser->roles ) ) ? sprintf('/speaker/%s', $teacher['user_nicename']) : '',
        ];
    }

    $templates = ['courses/single.twig'];
} else if($post->post_type == "topic") {
    $context['navigation'] = (object)[
        "previous" => __getTopicOnNavigation($_GET['course_id'], $_GET['unity'], $post->ID, 'previous'),
        "next" => __getTopicOnNavigation($_GET['course_id'], $_GET['unity'], $post->ID, 'next')
    ];

    $context['source']          = get_field('source', $post->ID);
    $context['mp4']    = get_field('mp4', $post->ID);
    $context['source_type']     = get_field('source_type', $post->ID);
    $context['source_banner']   = ($context['source_type'] == 'ebook') ? get_field('source_banner', $post->ID) : '';
    $context['summary']         = ( get_field('summary', $post->ID) ) ? get_field('summary', $post->ID)['url'] : false;
    $context['map']             = ( get_field('map', $post->ID) ) ? get_field('map', $post->ID)['url'] : false;
    $context['worksheet']       = ( get_field('worksheet', $post->ID) ) ? get_field('worksheet', $post->ID)['url'] : false;
    $context['solutions']       = ( get_field('solutions', $post->ID) ) ? get_field('solutions', $post->ID)['url'] : false;
} else {
    $author = get_field('author', $post->ID);

    $context['author']  = ($author) ? $author['user_firstname'] . ' ' . $author['user_lastname'] : 'MAB';
    $context['share']   = [
        'facebook'  => 'https://www.facebook.com/sharer/sharer.php?u=' . $context['post']->link,
        'linkedin'  => 'http://www.linkedin.com/shareArticle?mini=true&url=' . $context['post']->link,
        'twitter'   => sprintf(
            'https://twitter.com/share?text=%s&url=%s',
            'Hola, te comparto esta articulo de mab',
            $context['post']->link
        ),
    ];

    $articles = Timber::get_posts([
        'post_type'         => 'post',
        'posts_per_page'    => 3,
        'post__not_in'      => [$post->ID],
        'orderby'           => 'rand'
    ]);

    $context['articles'] = array_map(function($article) use ($context){
        $author = get_field('author', $article->ID);
        $avatar = ($author) ? get_field('avatar', 'user_' . $author['ID']) : false;

        return [
            'title'     => $article->title,
            'author'    => ($avatar) ? $avatar['url'] : $context['theme']->link . '/static/images/og_image.png',
            'date'      => $article->date,
            'link'      => $article->link,
        ];
    }, $articles);

    $templates = ['blog/article.twig'];
}

Timber::render( $templates, $context );
