<?php

class SessionRequestModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function addSessionRequest($request) {
        return DBConnection::getConnection()->query("
            INSERT INTO wp_session_requests(date_at,fullname,email,date1,time1,date2,time2,course) VALUES(
                '". date("Y-m-d") ."',
                '". $request['fullname'] ."',
                '". $request['email'] ."',
                '". $request['date1'] ."',
                '". $request['time1'] ."',
                '". $request['date2'] ."',
                '". $request['time2'] ."',
                '". $request['course'] ."'
            )
        ");      
    }
}
