<?php

require(__DIR__ . '/../models/SessionRequestModel.php');

class SessionRequestController{

    public function __construct(){

    }

    /**
     * Methods
     */
    public function addSessionRequest($request) {
        $session_dir_user = __DIR__ . 
            "/../../../../../../uploads/sessions/" . 
            str_replace( ' ', '_', strtolower($request['fullname']) );
        
        if(!file_exists($session_dir_user)){
            mkdir($session_dir_user);
        }

        if( isset($_FILES['resources']) ){
            for($index = 0; $index < count($_FILES['resources']['name']); $index++){
                $session_file_ext = pathinfo($_FILES['resources']['name'][$index], PATHINFO_EXTENSION);
    
                if($session_file_ext == 'jpg' || $session_file_ext == 'jpeg' || $session_file_ext == 'pdf'){
                    $session_file = $session_dir_user . '/' . date("Y_m_d_H_i") . $_FILES['resources']['name'][$index];            
    
                    if( is_uploaded_file($_FILES['resources']['tmp_name'][$index]) ) {			
                        if( !move_uploaded_file($_FILES['resources']['tmp_name'][$index], $session_file) ) {
                            return new WP_Error( 'no_seesion_file_saved', __('Session file (' . $_FILES['resources']['name'][$index] . ') not saved'), array( 'status' => 404 ) );
                        }
                    }else{
                        return new WP_Error( 'no_seesion_file_uploaded', __('Session file (' . $_FILES['resources']['name'][$index] . ') not uploaded'), array( 'status' => 404 ) );
                    }
                }else{
                    return new WP_Error( 'no_right_session_file', __('Session file (' . $_FILES['resources']['name'][$index] . ') with wrong format'), array( 'status' => 404 ) );
                }
            }
        }
            
        if( SessionRequestModel::addSessionRequest($request) ){
            return new WP_REST_Response('Session request saved', 200);
        }else{
            return new WP_Error( 'no_seesion_request_saved', __('Session request not saved'), array( 'status' => 404 ) );
        }
    }
}
