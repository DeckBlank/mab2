<?php

use Timber\Timber;

class CourseModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function getAll($request){
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
            $ids = explode(',', $request['ids']);
            $sell = get_field('sell', 'options');
            $env = require(__DIR__ . '/../../../../env.php');

            $course_args = [
                "post_type" => "course",
                "posts_per_page" => -1,
                "post__in" => $ids,
                "orderby" => "post__in"
            ];
            $courses = get_posts($course_args);
            $courses_array = [];

            for ($i=0; $i < count($courses) ; $i++) {
                $price_settings = get_field('price_settings', $courses[$i]->ID);

                array_push($courses_array, (object)[
                    "id" => $courses[$i]->ID,
                    "title" => $courses[$i]->post_title,
                    "unities" => get_field('unities', $courses[$i]->ID),
                    "price" =>  ($price_settings == 'global') ? floatval( $sell['course_price'] ) : floatval( get_field('price', $courses[$i]->ID) ),
                    "discount" => ($i == 0) ? 0 : floatval( $sell['individual_discount'] )
                ]);
            }

            return (object)[
                "discount" => (object)[
                    "global" => floatval( $sell['global_discount'] ),
                    "individual" => floatval( $sell['individual_discount'] )
                ],
                "pasarell" => (object)[
                    "api_key" => $env['PU_API_KEY'],
                    "merchan_id" => $env['PU_MERCHAND_ID'],
                    "account_id" => $env['PU_ACCOUNT_ID']
                ],
                "list" => $courses_array
            ];
        } else {
            $courses = get_posts([
                "post_type" => "course",
                "posts_per_page" => -1,
            ]);

            return $courses;
        } 
    }

    public static function getAllSanitize($request) {
        $courses = [];
        
        if (isset($request['ids'])) {
            $ids = explode(',', $request['ids']);

            $courses = Timber::get_posts([
                "post_type"         => "course",
                "posts_per_page"    => -1,
                "post__in"          => $ids,
                "orderby"           => "post__in"
            ]);
        } else {
            $courses = Timber::get_posts([
                "post_type"         => "course",
                "posts_per_page"    => 20,
                "paged"             => $request['page']
            ]);
        }

        $coursesArray = [];

        foreach($courses as $course) {
            array_push($coursesArray, [
                "id"            => $course->ID,
                "title"         => $course->title,
                "categories"    => $course->terms(),
                "unities"       => self::getUnities(['course_id' => $course->ID, 'user' => 'admin'], 'deep')
            ]);
        }

        return $coursesArray;
    }

    public static function getUnities($request, $mode = 'surface'){
        $unities = [];

        if(get_field('unities', $request['course_id'])){
            foreach( get_field('unities', $request['course_id']) as $unity){
                array_push($unities,(object)[
                    "title" => $unity['title'],
                    "topics" => self::__getTopicsSanitize($unity['topics'], $request['user'], $mode)
                ]);
            }
        }        

        return $unities;
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
        $first_unity =  get_field('unities', $request['course_id'])[0];

        if( $first_unity['topics'][0]['topic']->ID == $request['topic'] ){
            return true;
        }else{
            if ((get_field('price', $request['course_id']) == 0 and get_field('price_settings', $request['course_id']) == 'individual')) {
                return true;
            } else {
                if (!empty($courses)){
                    foreach($courses as $course){
                        if($course['course']['course']->ID == $request['course_id']){
                            foreach($course['course']['registrations'] as $registration){
                                if(
                                    ($registration['registration']['user']['user_email'] == $request['user'] and
                                    $registration['registration']['date_finish'] >= date("Y-m-d") and
                                    $registration['registration']['state'] == true)){
                    
                                    return true;
                                }
                            }
                        }
                    }
                }
    
                $courses_enrollment = DBConnection::getConnection()->query("
                    SELECT
                        *
                    FROM
                        wp_user_course_enrollment
                    WHERE
                        state = 1 AND
                        user_email = '". $request['user'] ."' AND
                        course_id = '". $request['course_id'] ."' AND
                        date_end >= '". date('Y-m-d G:i:s') ."'
                ");
    
                if($courses_enrollment->num_rows > 0){
                    return true;
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

    public static function getUserCourseLogs($request, $limit = false){
        if (isset($request['user']) && isset($request['course_id'])) {
            $user_course_logs_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_user_course
                WHERE
                    user_email = '". $request['user'] ."' AND
                    course_id = ". $request['course_id'] ."

            ");
        } elseif (isset($request['course_id'])) {
            $user_course_logs_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_user_course
                WHERE
                    course_id = ". $request['course_id'] ."
            ");
        } elseif (isset($request['user'])) {
            $user_course_logs_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_user_course
                WHERE
                    user_email = '". $request['user'] ."'
            ");
        } else {
            if ($limit == 'all') {
                $user_course_logs_query = DBConnection::getConnection()->query("
                    SELECT 
                        *
                    FROM 
                        wp_user_course
                    ORDER BY last_date DESC
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
        }

        $user_course_logs = [];

        if($user_course_logs_query && $user_course_logs_query->num_rows > 0){
            while($log = $user_course_logs_query->fetch_assoc()){
                $user = get_user_by('email', $log['user_email']);

                array_push($user_course_logs, (object)[
                    "user" => $user,
                    "user_email" => $log['user_email'],
                    "user_metas" => __getUserDataById($user),
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

    public static function saveBuyRequest($request){
        $courses = explode(',', $request['ids']);
        $response = false;

        foreach($courses as $course){
            $response = DBConnection::getConnection()->query("
                INSERT INTO 
                    wp_user_course_buy(date_at, reference_code, user_email, course_id, state)
                VALUES(
                    '". date("Y-m-d G:i:s") ."',
                    '". $request['reference_code'] ."',
                    '". $request['user'] ."',
                    '". $course ."',
                    'PENDING'
                )
            ");

            if(!$response){
                break;
            }
        }

        return $response;
    }

    public static function getBuyRequests($request, $state = 'PENDING'){
        $buy_requests = DBConnection::getConnection()->query("
            SELECT
                *
            FROM
                wp_user_course_buy
            WHERE
                user_email = '". $request['user'] ."' AND
                reference_code = '". $request['reference_code'] ."' AND
                state = '". $state ."'
        ");
        $buy_requests_array = [];
        
        if($buy_requests && $buy_requests->num_rows > 0){
            while($buy_request = $buy_requests->fetch_assoc()){
                array_push($buy_requests_array, $buy_request);
            }

            return $buy_requests_array;
        }else {
            return false;
        }
    }

    public static function getEnrollments($request, $type = false){
        if (isset($request['user'])) {
            $enrollments = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_user_course_enrollment
                WHERE
                    user_email = '". $request['user'] ."'
                ORDER BY last_date DESC                    
            ");
        } else {
            $enrollments = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_user_course_enrollment
                ORDER BY last_date DESC
                LIMIT ". __getLimit() ."
                OFFSET ". __getOffset($request['page']) ."
            ");
        }

        if ($type == 'expired') {
            $enrollments = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_user_course_enrollment
                WHERE
                    date_end < '". date('Y-m-d G:i:s') ."'
                ORDER BY last_date DESC 
            ");
        }

        $enrollments_array = [];
        
        if($enrollments && $enrollments->num_rows > 0){
            while($enrollment = $enrollments->fetch_assoc()){
                array_push($enrollments_array, (object)[
                    "user" => get_user_by('email', $enrollment['user_email']),
                    "user_email" => $enrollment['user_email'],
                    "course" => CourseModel::__getCourseName($enrollment['course_id']),
                    "state" => ($enrollment['state'] == 1) ? 'Habilitado' : 'Bloqueado',
                    "date_at" => $enrollment['date_at'],
                    "date_end" => $enrollment['date_end'],
                    "last_date" => $enrollment['last_date']
                ]);            
            }
        }

        return $enrollments_array;
    }
    
    public static function __saveEnrollment($buy_requests, $user_email){
        $response = false;
        $date_end = date('Y-m-d G:i:s', strtotime(date("Y-m-d", mktime()) . " + 365 day"));
        $courses = array_map(function($buy_request){
            return $buy_request['course_id'];
        }, $buy_requests);

        foreach($courses as $course){
            $response = DBConnection::getConnection()->query("
                INSERT INTO 
                    wp_user_course_enrollment(user_email, course_id, date_at, date_end, last_date, state)
                VALUES(
                    '". $user_email ."',
                    '". $course ."',
                    '". date("Y-m-d G:i:s") ."',
                    '". $date_end ."',
                    '". date("Y-m-d G:i:s") ."',
                    1
                )
                ON DUPLICATE KEY UPDATE
                    date_end = '". $date_end ."',
                    last_date = '". date("Y-m-d G:i:s") ."'
            ");

            if(!$response){
                break;
            }
        }

        return $response;
    }

    public static function __isViewedTopic($topic, $user){
        $response = DBConnection::getConnection()->query("
            SELECT
                *
            FROM
                wp_user_topic
            WHERE
                user_email = '". $user ."' and
                topic_id = ". $topic ."
        ");

        if ($response) {
            return isset($response->fetch_assoc()['video_viewed']) ? 1 : 0;
        }

        return 0;
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

    public static function __getTopicsSanitize($topics, $user, $mode){
        $topics_sanitize = [];
    
        if($topics){        
            foreach($topics as $topic){
                $tempTopic = [
                    "id"    => $topic['topic']->ID,
                    "title" => $topic['topic']->post_title,
                    "body"  => $topic['topic']->post_content,
                    "video" => (object)[
                        "state" => self::__isViewedTopic($topic['topic']->ID, $user),
                        "link"  => get_the_permalink($topic['topic']->ID)
                    ],
                    "summary"   => ( get_field('summary', $topic['topic']->ID) ) ? get_field('summary', $topic['topic']->ID)['url'] : false,
                    "map"       => ( get_field('map', $topic['topic']->ID) ) ? get_field('map', $topic['topic']->ID)['url'] : false,
                    "worksheet" => ( get_field('worksheet', $topic['topic']->ID) ) ? get_field('worksheet', $topic['topic']->ID)['url'] : false,
                    "solutions" => ( get_field('solutions', $topic['topic']->ID) ) ? get_field('solutions', $topic['topic']->ID)['url'] : false
                ];

                if ($mode == 'deep') {
                    $tempTopic = array_merge($tempTopic, [
                        'video_source'  => get_field('source', $topic['topic']->ID),
                        'questions'     => get_field('questions', $topic['topic']->ID)
                    ]);
                }

                array_push($topics_sanitize, $tempTopic);
            }
        }
    
        return $topics_sanitize;
    }
}
