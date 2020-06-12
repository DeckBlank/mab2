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

    public static function getCategories($request){
        $courses = Timber::get_posts([
            "post_type" => "course",
            'orderby'   => 'title',
            'order' => 'ASC'
        ]);

        return array_map(function($course){
            return (object)[
                "course" => $course->title,
                "course_id" => $course->ID,
                "categories" => $course->terms
            ];
        }, $courses);
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
        $first_unity =  get_field('unities',$request['course_id'])[0];

        if( $first_unity['topics'][0]['topic']->ID == $request['topic'] ){
            return true;
        }else{
            if (!empty($courses)){
                foreach($courses as $course){
                    if($course['course']['course']->ID == $request['course_id']){
                        foreach($course['course']['registrations'] as $registration){
                            if(
                                ($registration['registration']['user']['user_email'] == $request['user'] and
                                $registration['registration']['date_finish'] >= date("Y-m-d") and
                                $registration['registration']['state'] == true) ||
                                get_field('price', $course['course']['course']->ID) == 0 ){
                
                                return true;
                            }
                        }
                    }
                }
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

    public static function saveLog($request, $type){
        $response = false;

        switch ($type) {
            case 'topic':
                $response = DBConnection::getConnection()->query("
                    INSERT INTO 
                        wp_user_course(user_email, course_id, topic_views, last_date)
                    VALUES(
                        '". $request['user'] ."',
                        '". $request['course_id'] ."',
                        1,
                        '". date("Y-m-d G:i:s") ."'
                    )
                    ON DUPLICATE KEY UPDATE
                        topic_views = topic_views + 1,
                        last_date = '". date("Y-m-d G:i:s") ."'
                ");
                break;
            
            case 'material':
                $response = DBConnection::getConnection()->query("
                    INSERT INTO
                        wp_user_course(user_email, course_id, material_downloads, last_date)
                    VALUES(
                        '". $request['user'] ."',
                        '". $request['course_id'] ."',
                        1,
                        '". date("Y-m-d G:i:s") ."'
                    )
                    ON DUPLICATE KEY UPDATE
                        material_downloads = material_downloads + 1,
                        last_date = '". date("Y-m-d G:i:s") ."'
                ");
                break;
            
            case 'test':
                $right_answers = json_decode($request['result'])->rights;
                $wrong_answers = json_decode($request['result'])->wrongs;

                $response = DBConnection::getConnection()->query("
                    INSERT INTO 
                        wp_user_course(user_email, course_id, test_count, right_answers, wrong_answers, last_date)
                    VALUES(
                        '". $request['user'] ."',
                        '". $request['course_id'] ."',
                        1,
                        '". $right_answers ."',
                        '". $wrong_answers ."',
                        '". date("Y-m-d G:i:s") ."'
                    )
                    ON DUPLICATE KEY UPDATE
                        test_count = test_count + 1,
                        right_answers = right_answers + '". $right_answers ."',
                        wrong_answers = wrong_answers + '". $wrong_answers ."',
                        last_date = '". date("Y-m-d G:i:s") ."'
                ");
                break;
        }

        if ($response) {
            return true;
        } else {
            throw new Exception("Course log couldn't saved");
        }        
    }

    public static function getUserCourseLogs($request){
        if (isset($request['user'])) {
            $user_course_logs_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_user_course
                WHERE
                    user_email = '". $request['user'] ."'
            ");
        } else {
            $user_course_logs_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_user_course
                ORDER BY last_date DESC
                LIMIT ". __getLimit() ."
                OFFSET ". __getOffset($request['page']) ."
            ");
        }
        $user_course_logs = [];

        if($user_course_logs_query && $user_course_logs_query->num_rows > 0){
            while($log = $user_course_logs_query->fetch_assoc()){
                array_push($user_course_logs, (object)[
                    "user" => get_user_by('email', $log['user_email']),
                    "user_email" => $log['user_email'],
                    "course" => CourseModel::__getCourseName($log['course_id']),
                    "topic_views" => $log['topic_views'],
                    "material_downloads" => $log['material_downloads'],
                    "test_count" => $log['test_count'],
                    "right_answers" => $log['right_answers'],
                    "wrong_answers" => $log['wrong_answers'],
                    "last_date" => $log['last_date'],
                ]);
            }
        }

        return $user_course_logs;        
    }

    public static function __getCourseName($id){
        if ($id) {
            return Timber::get_post([
                "post_type" => "course",
                "p" => $id
            ])->title;
        } else {
            return '';
        }
    }
}
