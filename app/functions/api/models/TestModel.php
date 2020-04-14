<?php

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
