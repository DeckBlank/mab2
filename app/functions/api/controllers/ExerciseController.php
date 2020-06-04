<?php

require(__DIR__ . '/../models/ExerciseModel.php');

class ExerciseController{

    public function __construct(){

    }

    /**
     * Methods
     */
    public function getAll($request){
        $exercises = ExerciseModel::getAll($request);
    
        if(empty($exercises)){
            return new WP_Error( 'no_exercises', __('No exercises found'), array( 'status' => 404 ) );
        }else{
            return new WP_REST_Response($exercises, 200);
        }    
    }

    public static function saveDownloadLog($request){
        try {
            return new WP_REST_Response(ExerciseModel::saveDownloadLog($request), 200);
        } catch (Exception $e) {
            return new WP_Error( 'excercise_log_failed', __($e->getMessage()), array( 'status' => 404 ) );
        }
    }    
}
