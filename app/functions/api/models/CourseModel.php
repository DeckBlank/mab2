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

    public static function getProgress($request){
        $progresses = [];
        
        foreach(get_field('courses', 'options') as $course){
            foreach($course['course']['registrations'] as $registration){
                if(
                    $registration['registration']['user']['user_email'] == $request['user'] and
                    $registration['registration']['state'] == true ){

                    $course = $course['course']['course'];

                    $tests = DBConnection::getConnection()->query("
                        SELECT 
                            *
                        FROM
                            wp_topic_test_scores
                        WHERE
                            user = '". $request['user'] ."' and course_id='". $course->ID ."'
                    ");
                    $test_scores = [];
    
                    if ($tests && $tests->num_rows > 0) {
                        while($test_score = $tests->fetch_assoc()) {
                            if($test_score)                            
                                array_push($test_scores, $test_score);
                        }
                    }
    
                    array_push($progresses, (object)[
                        "course" => $course->post_title,
                        "completed" => count($test_scores),
                        "total" => self::getTopics($course->ID),
                        "percentage" => bcdiv( (count($test_scores)*100), self::getTopics($course->ID), 2 )
                    ]);    
                    
                }
            }
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
