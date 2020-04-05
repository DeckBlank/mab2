<?php

use Timber\Timber;

class SessionModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function getSession($request){
        $session_post = Timber::get_post([
            "post_type" => "session",
            "meta_query" => array(
                array(
                    'key' => 'key',
                    'value' => $request['key'],
                    'compare' => '=',
                )
            )
        ]);

        if(empty($session_post)){
            return $session_post;
        }else{
            return (object)[
                "id" => $session_post->ID,
                "name" => $session_post->post_title,
                "slug" => $session_post->post_name,
                "key" => $request['key'],
                "teacher" => (object)[
                    "first_name" =>  $session_post->author->first_name,
                    "last_name" =>  $session_post->author->last_name,
                    "avatar" => get_field('picture', 'user_'. $session_post->author->ID )
                ]
            ];
        }
    }
}
