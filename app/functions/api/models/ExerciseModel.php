<?php

use Timber\Timber;

class ExerciseModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function getAll($request){
        $exercises = get_posts([
            "post_type" => "exercise",
            "posts_per_page" => -1
        ]);
        $exercises_array = []; 

        foreach($exercises as $exercise){
            array_push($exercises_array, (object)[
                "title" => $exercise->post_title,
                "file" => get_field('file', $exercise->ID)
            ]);
        }

        return $exercises_array;
    }

    public static function saveDownloadLog($request){
        $response = DBConnection::getConnection()->query("
            INSERT INTO 
                wp_exercise_download_logs(user_email, exercises_download, last_exercise, last_date)
            VALUES(
                '". $request['user'] ."',
                1,
                '". $request['exercise_id'] ."',
                '". date("Y-m-d G:i:s") ."'
            )
            ON DUPLICATE KEY UPDATE
                exercises_download = exercises_download + 1,
                last_exercise = '". $request['exercise_id'] ."',
                last_date = '". date("Y-m-d G:i:s") ."'                
        ");

        if ($response) {
            return true;
        } else {
            throw new Exception("Log couldn't saved");
        }        
    }    
}
