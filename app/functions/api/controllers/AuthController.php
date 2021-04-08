<?php

class AuthController {
    public function __construct() {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'custom/v1', '/auth/login', array(
                'methods' => 'GET',
                'callback' => array($this, 'login'),
                'permission_callback' => function ($request) {
                    // return ($request['_wpnonce']) ? true : false;
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/auth/register', array(
                'methods' => 'POST',
                'callback' => array($this, 'register'),
                'permission_callback' => function ($request) {
                    // return ($request['_wpnonce']) ? true : false;
                    return true;
                }
            ));
        });
    }

    public function login($request) {
        if( isset($request['user']) and isset($request['password']) ){
            try {
                return new WP_REST_Response(UserModel::auth($request), 200);
            } catch (Exception $e) {
                return new WP_Error( 'user_failed', __($e->getMessage()), array( 'status' => 404 ) );
            }
        }else{
            return new WP_Error( 'no_user_credentials', __('No user credentials'), array( 'status' => 404 ) );
        }
    }

    public function register($request) {
        if (true) {

        } else {
            return new WP_REST_Response((object)[
                'message'   => 'Invalid params',
                'status'    => false
            ], 200);
        }
    }
}
