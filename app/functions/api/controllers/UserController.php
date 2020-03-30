<?php

require(__DIR__ . '/../models/UserModel.php');

class UserController{

    public function __construct(){
        
    }

    /**
     * Methods
     */
    public function auth($request){
        if( isset($request['user']) and isset($request['password']) ){
            $user = UserModel::auth($request);
            
            if( is_wp_error($user) ){
                return new WP_Error( 'no_user', __("User doesn't exist"), array( 'status' => 404 ) );
            }else{
                return new WP_REST_Response($user, 200);
            }       
        }else{
            return new WP_Error( 'no_user_credentials', __('No user credentials'), array( 'status' => 404 ) );
        }
    }    
}
