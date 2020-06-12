<?php

use Timber\Timber;

class TestModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function getTest($request){
        $tests = DBConnection::getConnection()->query("
            SELECT * 
            FROM
                wp_user_tests
            WHERE
                user = '". $request['user'] ."'
        ");
        $test_results = [];

        if ($tests && $tests->num_rows > 0) {
            while($row = $tests->fetch_assoc()) {
                if($row)
                    array_push($test_results, $row);
            }
            return $test_results[0];

        }else {
            return false;
        }
    }

    public static function getTests($request){
        if (isset($request['user'])) {
            $test_logs_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_user_tests
                WHERE
                    user = '". $request['user'] ."'
            ");
        } else {
            $test_logs_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_user_tests
                ORDER BY date_at DESC
                LIMIT ". __getLimit() ."
                OFFSET ". __getOffset($request['page']) ."
            ");
        }
        $test_logs = [];

        if($test_logs_query && $test_logs_query->num_rows > 0){
            while($log = $test_logs_query->fetch_assoc()){
                array_push($test_logs, (object)[
                    "user" => get_user_by('email', $log['user']),
                    "user_email" => $log['user'],
                    "result" => $log['result'],
                    "date_at" => $log['date_at']
                ]);
            }
        }

        return $test_logs;        
    }

    public static function getQuestions($request){
        $test_page = Timber::get_post([
            "post_type" => "page",
            "name" => "test"
        ]);
        $questions = get_field('questions', $test_page->ID);

        return (object)[
            "list" => $questions,
            "count" => count($questions)
        ];
    }

    public static function saveTest($request){
         return DBConnection::getConnection()->query("
             INSERT INTO wp_user_tests(date_at,user,result) VALUES(
                 '". date("Y-m-d") ."',
                 '". $request['user'] ."',
                 '". $request['result'] ."'
             )
         ");
    }
}
