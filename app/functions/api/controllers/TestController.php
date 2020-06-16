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

    public function getTests($request){
        $tests = TestModel::getTests($request);

        if ($tests) {
            return new WP_REST_Response($tests, 200);
        } else {
            return new WP_Error( 'no_tests', __('No tests found'), array( 'status' => 404 ) );
        }
    }

    public function downloadTests($request){
        $tests = TestModel::getTests($request, 'all');

        if ($tests) {
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/xls; charset=UTF-8");
            header("Content-Disposition: attachment; filename=resportes-estilos-aprendizaje-mabclick-".date('Y-m-d').".xls"); 
            echo "\xEF\xBB\xBF";

            //Header
            include_once __DIR__."/../exports/reports/learning.php";
        } else {
            return new WP_Error( 'no_tests', __('No tests found'), array( 'status' => 404 ) );
        }  
    }    

    public function getQuestions($request){
        $questions = TestModel::getQuestions($request);

        if ( empty($questions) ) {
            return new WP_Error( 'no_questions', __('No questions found'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($questions, 200);
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
