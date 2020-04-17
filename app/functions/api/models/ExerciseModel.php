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
            "post_type" => "exercise"
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
}
