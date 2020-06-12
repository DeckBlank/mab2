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

    public static function getQuestionaries($request){
        if (isset($request['user'])) {
            $questionaries_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_questionaries
                WHERE
                    user_email = '". $request['user'] ."'
            ");
        } else {
            $questionaries_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_questionaries
                ORDER BY date_at DESC
                LIMIT ". __getLimit() ."
                OFFSET ". __getOffset($request['page']) ."
            ");
        }
        $questionaries = [];

        if($questionaries_query && $questionaries_query->num_rows > 0){
            while($questionary = $questionaries_query->fetch_assoc()){
                array_push($questionaries, (object)[
                    "id" => $questionary['id'],
                    "user" => get_user_by('email', $questionary['user_email']),
                    "user_email" => $questionary['user_email'],
                    "rol" => $questionary['rol'],
                    "result" => self::__getFulledResult('questionary', json_decode($questionary['result']), $questionary['rol']),
                    "date_at" => $questionary['date_at']
                ]);
            }
        }

        return $questionaries;        
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

    public static function getPolls($request){
        if (isset($request['user'])) {
            $polls_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_polls
                WHERE
                    user_email = '". $request['user'] ."'
            ");
        } else {
            $polls_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_polls
                ORDER BY date_at DESC
                LIMIT ". __getLimit() ."
                OFFSET ". __getOffset($request['page']) ."
            ");
        }
        $polls = [];

        if($polls_query && $polls_query->num_rows > 0){
            while($poll = $polls_query->fetch_assoc()){
                array_push($polls, (object)[
                    "id" => $poll['id'],
                    "user" => get_user_by('email', $poll['user_email']),
                    "user_email" => $poll['user_email'],
                    "rol" => $poll['rol'],
                    "result" => self::__getFulledResult('poll', json_decode($poll['result']), $poll['rol']),
                    "date_at" => $poll['date_at']
                ]);
            }
        }

        return $polls;        
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
    
    public static function __getQuestionTitle($type, $key, $rol){
        switch($type){
            case 'questionary':
                if ($rol == 'student') {
                    $questionary_student = get_field('questionary_student', 'options');
                    
                    if (count(explode('Decisiones', $key)) == 2) {
                        $questions = array_map(
                            function($question){
                                return $question['question'];
                            },
                            $questionary_student['decisions']
                        );
        
                        foreach($questions as $question){
                            if($question['key'] == $key){
                                return $question['value'];
                            }
                        }
                    } else {
                        $questions = array_map(
                            function($question){
                                return $question['question'];
                            },
                            $questionary_student['base']
                        );
        
                        foreach($questions as $question){
                            if($question['key'] == $key){
                                return $question['value'];
                            }
                        }
                    }
                } else if($rol == 'tutor') {
                    $questionary_tutor = get_field('questionary_tutor', 'options');
        
                    $questions = array_map(
                        function($question){
                            return $question['question'];
                        },
                        $questionary_tutor
                    );
        
                    foreach($questions as $question){
                        if($question['key'] == $key){
                            return $question['value'];
                        }
                    }
                }                
                break;
            case 'poll':
                $poll_questions = get_field('encuesta_questions', 'options');
                    
                $questions = array_map(
                    function($question){
                        return $question['question'];
                    },
                    $poll_questions
                );

                foreach($questions as $question){
                    if($question['key'] == $key){
                        return $question['value'];
                    }
                }        
                break;
        }
    }
    
    public static function __getFulledResult($type, $result, $rol){
        $fulled_result = [];

        foreach($result as $question){
            array_push($fulled_result, (object)[
                "key" => $question->key,
                "title" => self::__getQuestionTitle($type, $question->key, $rol),
                "value" => $question->value,
            ]);
        }

        return $fulled_result;
    }
}
