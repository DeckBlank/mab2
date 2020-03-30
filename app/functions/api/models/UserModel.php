<?php

class UserModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function auth($request){
        return wp_authenticate( $request['user'], $request['password'] );
    }
}
