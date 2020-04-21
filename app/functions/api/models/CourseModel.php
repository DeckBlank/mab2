<?php

use Timber\Timber;

class CourseModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function getTopics($course_id){
        $topics = 0;

        foreach(get_field('unities', $course_id) as $unity){
            if($unity['topics']){
                $topics = $topics + count($unity['topics']);
            }
        }
        
        return $topics;
    }

    public static function getCourseByID($course_id){
        return Timber::get_post([
            "post_type" => "course",
            "p" => $course_id
        ]);
    }

    public static function getProgress($request){
        $progresses = [];
        $tests = DBConnection::getConnection()->query("
            SELECT 
                *
            FROM
                wp_topic_test_scores
            WHERE
                user = '". $request['user'] ."'
        ");
        $test_scores = [];  
        
        if ($tests && $tests->num_rows > 0) {
            while($test_score = $tests->fetch_assoc()) {
                if($test_score)  
                    array_push($test_scores, $test_score);
            }
        }

        $courses_id = array_map(function($score){ return $score['course_id']; }, $test_scores);
        $courses_id = array_unique( $courses_id );

        foreach($courses_id as $course_id){
            array_push($progresses, (object)[
                "course" => self::getCourseById($course_id)->post_title,
                "completed" => count($test_scores),
                "total" => self::getTopics($course_id),
                "percentage" => bcdiv( (count($test_scores)*100), self::getTopics($course_id), 2 )
            ]); 
        }

        return $progresses;
    }
    
    public static function registrationCheckout($request){
        $courses = get_field('courses', 'options');

        foreach($courses as $course){
            if($course['course']['course']->ID == $request['course_id']){
                $first_unity =  get_field('unities',$course['course']['course']->ID)[0];

                if( $first_unity['topics'][0]['topic']->ID == $request['topic'] ){
                    return true;
                }else{
                    foreach($course['course']['registrations'] as $registration){
                        if(
                            $registration['registration']['user']['user_email'] == $request['user'] and
                            $registration['registration']['state'] == true ){
            
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}
