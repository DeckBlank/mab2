<?php

use Timber\Timber;

class CourseModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function getAll($request){
        $ids = explode(',', $request['ids']);
        $sell = get_field('sell', 'options');

        if (isset($request['query'])) {
            $course_args = [
                "post_type" => "course",
                "posts_per_page" => -1,
                'orderby' => 'post_date',
                "order" => "ASC",
                's' => $_GET['query']
            ];

            return get_posts($course_args);
        } else if (isset($request['ids'])){
            $course_args = [
                "post_type" => "course",
                "posts_per_page" => -1,
                "post__in" => $ids,
                "orderby" => "post__in"
            ];
            $courses = get_posts($course_args);
            $courses_array = [];

            for ($i=0; $i < count($courses) ; $i++) {
                array_push($courses_array, (object)[
                    "title" => $courses[$i]->post_title,
                    "unities" => get_field('unities', $courses[$i]->ID),
                    "price" =>  floatval( $sell['course_price'] ),
                    "discount" => ($courses[$i]->ID == $ids[0]) ? 0 : floatval( $sell['individual_discount'] )
                ]);
            }

            return (object)[
                "discount" => (object)[
                    "global" => floatval( $sell['global_discount'] ),
                    "individual" => floatval( $sell['individual_discount'] )
                ],
                "pasarell" => $sell['pasarell'],
                "list" => $courses_array
            ];
        }        
    }

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
        $all_tests = DBConnection::getConnection()->query("
            SELECT 
                *
            FROM
                wp_topic_test_scores
            WHERE
                user = '". $request['user'] ."'
        ");
        $all_test_scores = [];
        
        if ($all_tests && $all_tests->num_rows > 0) {
            while($test_score = $all_tests->fetch_assoc()) {
                if($test_score)  
                    array_push($all_test_scores, $test_score);
            }
        }

        $courses_id = array_map(function($score){ return $score['course_id']; }, $all_test_scores);
        $courses_id = array_unique( $courses_id );

        foreach($courses_id as $course_id){
            $tests = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM
                    wp_topic_test_scores
                WHERE
                    user = '". $request['user'] ."' and course_id = '". $course_id ."'
            ");
            $test_scores = [];
            
            if ($tests && $tests->num_rows > 0) {
                while($test_score = $tests->fetch_assoc()) {
                    if($test_score)  
                        array_push($test_scores, $test_score);
                }
            }            

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

        if (!empty($courses)){
            foreach($courses as $course){
                if($course['course']['course']->ID == $request['course_id']){
                    $first_unity =  get_field('unities',$course['course']['course']->ID)[0];

                    if( $first_unity['topics'][0]['topic']->ID == $request['topic'] ){
                        return true;
                    }else{
                        foreach($course['course']['registrations'] as $registration){
                            if(
                                $registration['registration']['user']['user_email'] == $request['user'] and
                                $registration['registration']['date_finish'] >= date("Y-m-d") and
                                $registration['registration']['state'] == true ){
                
                                return true;
                            }
                        }
                    }
                }
            }
        }
        else{
            $first_unity =  get_field('unities',$request['course_id'])[0];

            if( $first_unity['topics'][0]['topic']->ID == $request['topic'] ){
                return true;
            }
        }

        return false;
    }

    public static function getExpiredRegistrations($request){
        $courses = get_field('courses', 'options');
        $expired_registrations = [];

        foreach($courses as $course){
            foreach($course['course']['registrations'] as $registration){
                if( $registration['registration']['date_finish'] < date("Y-m-d") ){
                    array_push($expired_registrations, (object)[
                        "fullname" => $registration['registration']['user']['user_firstname'] . ' ' . $registration['registration']['user']['user_lastname'],
                        "type" => get_userdata( $registration['registration']['user']['ID'] )->roles[0],
                        "course" => $course['course']['course']->post_title,
                        "date_start" => $registration['registration']['date'],
                        "date_end" => $registration['registration']['date_finish']
                    ]);
                }
            }
        }

        return $expired_registrations;        
    }
}
