<?php

require(__DIR__ . '/../models/RegistrationModel.php');

class RegistrationController{

    public function __construct(){

    }

    /**
     * Methods
     */
    public function getRegistration($request){
        if( RegistrationModel::getRegistration($request) ){
            return new WP_REST_Response((object)[
                "course" => $request['course'],
                "user" => $request['user'],
                "status" => "activado"
            ], 200);
        }else{
            return new WP_Error( 'no_registration', __('No registration found'), array( 'status' => 404 ) );
        }
    }
}
