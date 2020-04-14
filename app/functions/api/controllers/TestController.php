<?php

require(__DIR__ . '/../models/TestModel.php');

class TestController{

    public function __construct(){

    }

    /**
     * Methods
     */
    public function getTest($request){
        $test = TestModel::getTest($request);

        if ($test) {
            return new WP_REST_Response($test['result'], 200);
        } else {
            return new WP_Error( 'no_test', __('No test found'), array( 'status' => 404 ) );
        }
    } 
   
    public function saveTest($request){
        if (TestModel::saveTest($request)) {
            return new WP_REST_Response('Test saved', 200);
        } else {
            return new WP_Error( 'no_test_saved', __('No test saved'), array( 'status' => 404 ) );
        }        
    }    
}
