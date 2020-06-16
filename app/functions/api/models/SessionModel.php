<?php

use Timber\Timber;
use OpenTok\OpenTok;

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
            $env = require(__DIR__ . '/../../../../env.php');
            $opentok = new OpenTok($env['API_KEY'], $env['SECRET']);

            try {
                $room = get_field('room', $session_post->ID);
                $token = $opentok->generateToken($room);
    
                return (object)[
                    "id" => $session_post->ID,
                    "name" => $session_post->post_title,
                    "slug" => $session_post->post_name,
                    "key" => $request['key'],
                    "room" => $room,
                    "credentials" => (object)[
                        "api_key" => "46544742",
                        "token" => $token           
                    ],
                    "teacher" => (object)[
                        "first_name" =>  $session_post->author->first_name,
                        "last_name" =>  $session_post->author->last_name,
                        "avatar" => get_field('picture', 'user_'. $session_post->author->ID )
                    ]
                ];
            } catch (Exception $e) {
                return [];
            }
        }
    }

    public static function createSession($request){
        $env = require(__DIR__ . '/../../../../env.php');
        
        $opentok = new OpenTok($env['API_KEY'], $env['SECRET']);
        $session = $opentok->createSession();

        if ($session->getSessionId()) {
            return $session->getSessionId();
        } else {
            return false;
        }
    }
}
