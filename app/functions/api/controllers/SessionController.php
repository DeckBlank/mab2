<?php

require(__DIR__ . '/../models/SessionModel.php');

class SessionController{

    public function __construct(){

    }

    /**
     * Methods
     */
    public function getSession($request) {
        $session = SessionModel::getSession($request);

        if(empty($session)){
            return new WP_Error( 'no_session', __("No session found"), array( 'status' => 404 ) );
        }else{
            return new WP_REST_Response($session, 200);
        }
    }

    public function createSession($request) {
        $session_id = SessionModel::createSession($request);

        if ($session_id) {
            return new WP_REST_Response((object)[
                "id" => $session_id
            ], 200);
        } else {
            return new WP_Error( 'no_session_created', __("No session created"), array( 'status' => 404 ) );
        }
        
    }
}
