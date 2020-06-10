<?php

use Timber\Timber;

class BehaviourModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function getQuestionary($request){
        if ($request['rol'] == 'student') {
            $questionary_student = get_field('questionary_student', 'options'); 
            
            return (object)[
                "base" => (object)[
                    "list" => $questionary_student['base'],
                    "count" => count($questionary_student['base'])
                ],
                "decisions" => (object)[
                    "list" => $questionary_student['decisions'],
                    "count" => count($questionary_student['decisions'])
                ]
            ];
        } else if($request['rol'] == 'tutor') {
            $questionary_tutor = get_field('questionary_tutor', 'options');

            return (object)[
                "base" => (object)[
                    "list" => $questionary_tutor,
                    "count" => count($questionary_tutor)
                ]
            ];
        }
    }

    public static function checkoutQuestionaryEnable($request){
        $questionary_enable = get_field('questionary_enable', 'options'); 
        
        return $questionary_enable;
    }

    public static function saveQuestionary($request){
        return DBConnection::getConnection()->query("
            INSERT INTO wp_questionaries(date_at,user_email,rol,result) VALUES(
                '". date("Y-m-d") ."',
                '". $request['user_email'] ."',
                '". $request['rol'] ."',
                '". $request['result'] ."'
            )
        ");
    }

    public static function questionaryCheckout($user){
        $questionary_done_query = DBConnection::getConnection()->query("
            SELECT
                *
            FROM
                wp_questionaries
            WHERE
                user_email = '". $user ."'
        ");

        if($questionary_done_query && $questionary_done_query->num_rows){
            return true;
        }else{
            return false;
        }
    }

    public static function getPoll($request){
        $encuesta = get_field('encuesta_questions', 'options'); 
        
        return (object)[
            "list" => $encuesta,
            "count" => count($encuesta)
        ];
    }

    public static function checkoutPollEnable($request){
        $poll_enable = get_field('encuesta_enable', 'options'); 

        return $poll_enable;
    }

    public static function savePoll($request){
        return DBConnection::getConnection()->query("
            INSERT INTO wp_polls(date_at,user_email,rol,result) VALUES(
                '". date("Y-m-d") ."',
                '". $request['user_email'] ."',
                '". $request['rol'] ."',
                '". $request['result'] ."'
            )
        ");
    }

    public static function pollCheckout($user){
        $poll_done_query = DBConnection::getConnection()->query("
            SELECT
                *
            FROM
                wp_polls
            WHERE
                user_email = '". $user ."'
        ");

        if($poll_done_query && $poll_done_query->num_rows){
            return true;
        }else{
            return false;
        }
    }    
}
