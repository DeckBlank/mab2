<?php

use Timber\Timber;

class TopicModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    //1. Questions ---------------------------------------//
    public static function getQuestions($request){
        return get_field('questions', $request['topic_id']);
    }

    //2. Likes ---------------------------------------//
    public static function getLikes($request){
        return __getLikes($request);
    }
    
    public static function updateLikes($request){
        $likes_count = get_post_meta($request['post_id'], 'post_likes_count');
    
        if( $likes_count ){
            try {
                return __updateLikes($request, 'topic', $likes_count);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }else{
            throw new Exception('No defined likes');
        }        
    }

    public static function checkoutUserLike($request){
        return __checkoutUserLike($request, 'topic');
    }

    //3. Comments ---------------------------------------//
    public static function getAllComments($request){
        return array_map(function($comment){
            $post_type = get_post_type($comment->comment_post_ID);

            if ($comment->comment_parent == 0) {
                return (object)[
                    "id" => $comment->comment_ID,
                    "post" => Timber::get_post([
                        "post_type" => $post_type,
                        "p" => $comment->comment_post_ID
                    ]),
                    "parent" => $comment->comment_parent,
                    "body" => $comment->comment_content,
                    "author" => $comment->comment_author,
                    "author_email" => (isset($comment->comment_author_email)) ? $comment->comment_author_email : '',
                    "date" => $comment->comment_date,
                    "answers" => get_comments(["parent" => $comment->comment_ID]),
                ];
            }
        }, get_comments());
    }

    public static function getComments($request){
        return __getComments($request);
    }

    public static function addComment($request){        
        return __addComment($request);
    }
    
    public static function addAnswer($request){
        return __addAnswer($request);
    }

    //4. Test Score ---------------------------------------//
    public static function getTestScore($request){
        $test_result = DBConnection::getConnection()->query("
            SELECT * FROM wp_topic_test_scores WHERE user='". $request['user'] ."' and topic_id=". $request['topic_id'] ."
        "); 
        $test_scores = [];
        
        if ($test_result && $test_result->num_rows > 0) {
            while($row = $test_result->fetch_assoc()) {
                if($row)
                    array_push($test_scores, $row);
            }
        }
        
        return $test_scores;
    }

    public static function updateTestScore($request){
        $test_scores = DBConnection::getConnection()->query("
            SELECT 
                * 
            FROM 
                wp_topic_test_scores 
            WHERE 
                user='". $request['user'] ."' and topic_id='". $request['topic_id'] ."'
        ");

        if($test_scores && $test_scores->num_rows > 0){
            try {
                self::saveTestLog($request, 1);

                return DBConnection::getConnection()->query(" 
                    UPDATE 
                        wp_topic_test_scores 
                    SET 
                        date_at = '". date("Y-m-d") ."',
                        score = '". $request['result'] ."' 
                    WHERE 
                        user='". $request['user'] ."' and topic_id='". $request['topic_id'] ."'
                ");
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }else{
            try {
                if (self::saveTestLog($request, 0)) {
                    return DBConnection::getConnection()->query("
                        INSERT INTO 
                            wp_topic_test_scores(date_at,user,course_id,topic_id,score) VALUES(
                                '". date("Y-m-d") ."',
                                '". $request['user'] ."',
                                '". $request['course_id'] ."',
                                '". $request['topic_id'] ."',
                                '". $request['result'] ."'
                            )
                    ");
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }

    //5. Log ---------------------------------------//
    public static function saveVideoLog($request){
        $user = ($request['user'] == 'anonimo') ? 'anonimo-' . $_SERVER['REMOTE_ADDR'] : $request['user'];

        try {
            if(self::__saveUserTopicLog($request, $user, 'video')){
                $response = DBConnection::getConnection()->query("
                    INSERT INTO 
                        wp_topic_video_logs(user_email, views, last_topic, last_date)
                    VALUES(
                        '". $user  ."',
                        1,
                        '". $request['post_id'] ."',
                        '". date("Y-m-d G:i:s") ."'
                    )
                    ON DUPLICATE KEY UPDATE
                        views = views + 1,
                        last_topic = '". $request['post_id'] ."',
                        last_date = '". date("Y-m-d G:i:s") ."'                    
                ");

                if ($response) {
                    try {
                        return CourseModel::saveLog($request, 'topic');
                    } catch (Exception $e) {
                        throw new Exception($e->getMessage());
                    }
                } else {
                    throw new Exception("Log couldn't saved");
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function saveMaterialLog($request){
        $user = ($request['user'] == 'anonimo') ? 'anonimo-' . $_SERVER['REMOTE_ADDR'] : $request['user'];

        try {
            if(self::__saveUserTopicLog($request, $user, $request['media'])){
                $response = DBConnection::getConnection()->query("
                    INSERT INTO 
                        wp_topic_material_logs(user_email, downloads, last_topic, last_date)
                    VALUES(
                        '". $user ."',
                        1,
                        '". $request['post_id'] ."',
                        '". date("Y-m-d G:i:s") ."'
                    )
                    ON DUPLICATE KEY UPDATE
                        downloads = downloads + 1,
                        last_topic = '". $request['post_id'] ."',
                        last_date = '". date("Y-m-d G:i:s") ."'                    
                ");

                if ($response) {
                    try {
                        return CourseModel::saveLog($request, 'material');
                    } catch (Exception $e) {
                        throw new Exception($e->getMessage());
                    }
                } else {
                    throw new Exception("Log couldn't saved");
                }
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }             
    }

    public static function saveTestLog($request, $operation){
        try {
            if(CourseModel::saveLog($request, 'test')){
                $right_answers = json_decode($request['result'])->rights;
                $wrong_answers = json_decode($request['result'])->wrongs;
        
                $course = (object)[
                    "id" => $request['course_id'],
                    "security" => $request['course_security'],
                ];
        
                $response = DBConnection::getConnection()->query("
                    INSERT INTO 
                        wp_topic_test_logs(
                            user_email, 
                            right_answers, 
                            wrong_answers, 
                            test_count,
                            last_course,
                            last_unity, 
                            last_topic, 
                            last_date
                        )
                    VALUES(
                        '". $request['user'] ."',
                        1,
                        ". $right_answers .",
                        ". $wrong_answers .",
                        '". json_encode($course) ."',
                        '". $request['unity'] ."',
                        '". $request['topic_id'] ."',
                        '". date("Y-m-d G:i:s") ."'
                    )
                    ON DUPLICATE KEY UPDATE
                        test_count = test_count + 1,
                        right_answers = right_answers + ". $right_answers .",
                        wrong_answers = wrong_answers + ". $wrong_answers .",
                        last_course = '". json_encode($course) ."',
                        last_unity = '". $request['unity'] ."',
                        last_topic = '". $request['topic_id'] ."',               
                        last_date = '". date("Y-m-d G:i:s") ."'
                ");
        
                if ($response) {
                    return true;
                } else {
                    throw new Exception("Log couldn't saved");
                }                 
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }      
    }

    public static function getVideoLogs($request, $limit = false){
        if (isset($request['user'])) {
            $video_logs_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_topic_video_logs
                WHERE
                    user_email = '". $request['user'] ."'
            ");
        } else {
            if ($limit == 'all') {
                $video_logs_query = DBConnection::getConnection()->query("
                    SELECT 
                        *
                    FROM 
                        wp_topic_video_logs
                    ORDER BY last_date DESC
                ");
            } else {
                $video_logs_query = DBConnection::getConnection()->query("
                    SELECT 
                        *
                    FROM 
                        wp_topic_video_logs
                    ORDER BY last_date DESC
                    LIMIT ". __getLimit() ."
                    OFFSET ". __getOffset($request['page']) ."
                ");
            }
            
        }
        $video_logs = [];

        if($video_logs_query && $video_logs_query->num_rows > 0){
            while($log = $video_logs_query->fetch_assoc()){
                $user = get_user_by('email', $log['user_email']);
                
                array_push($video_logs, (object)[
                    "user" => $user,
                    "user_email" => $log['user_email'],
                    "user_metas" => __getUserDataById($user),
                    "views" => $log['views'],
                    "last_topic" => self::__getTopicName($log['last_topic']),
                    "last_date" => $log['last_date'],
                ]);
            }
        }

        return $video_logs;
    }

    public static function getMaterialLogs($request, $limit = false){
        if (isset($request['user'])) {
            $material_logs_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_topic_material_logs
                WHERE
                    user_email = '". $request['user'] ."'                
            ");
        } else {
            if ($limit == 'all') {
                $material_logs_query = DBConnection::getConnection()->query("
                    SELECT 
                        *
                    FROM 
                        wp_topic_material_logs
                    ORDER BY last_date DESC
                ");
            } else {
                $material_logs_query = DBConnection::getConnection()->query("
                    SELECT 
                        *
                    FROM 
                        wp_topic_material_logs
                    ORDER BY last_date DESC
                    LIMIT ". __getLimit() ."
                    OFFSET ". __getOffset($request['page']) ."
                ");
            }
        }
        $material_logs = [];

        if($material_logs_query && $material_logs_query->num_rows > 0){
            while($log = $material_logs_query->fetch_assoc()){
                $user = get_user_by('email', $log['user_email']);

                array_push($material_logs, (object)[
                    "user" => $user,
                    "user_email" => $log['user_email'],
                    "user_metas" => __getUserDataById($user),
                    "downloads" => $log['downloads'],
                    "last_topic" => self::__getTopicName($log['last_topic']),
                    "last_date" => $log['last_date'],
                ]);
            }
        }

        return $material_logs;
    }

    public static function getTestLogs($request, $limit = false){
        if (isset($request['user'])) {
            $test_logs_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_topic_test_logs
                WHERE
                    user_email = '". $request['user'] ."'
            ");
        } else {
            if ($limit == 'all') {
                $test_logs_query = DBConnection::getConnection()->query("
                    SELECT 
                        *
                    FROM 
                        wp_topic_test_logs
                    ORDER BY last_date DESC
                ");
            } else {
                $test_logs_query = DBConnection::getConnection()->query("
                    SELECT 
                        *
                    FROM 
                        wp_topic_test_logs
                    ORDER BY last_date DESC
                    LIMIT ". __getLimit() ."
                    OFFSET ". __getOffset($request['page']) ."
                ");
            }
        }
        $test_logs = [];

        if($test_logs_query && $test_logs_query->num_rows > 0){
            while($log = $test_logs_query->fetch_assoc()){
                $user = get_user_by('email', $log['user_email']);

                array_push($test_logs, (object)[
                    "user" => $user,
                    "user_email" => $log['user_email'],
                    "user_metas" => __getUserDataById($user),
                    "test_count" => $log['test_count'],
                    "right_answers" => $log['right_answers'],
                    "wrong_answers" => $log['wrong_answers'],
                    "last_course" => ($log['last_course']) ? CourseModel::__getCourseName(json_decode($log['last_course'])->id) : '',
                    "last_unity" => $log['last_unity'],
                    "last_topic" => self::__getTopicName($log['last_topic']),
                    "last_date" => $log['last_date'],
                ]);
            }
        }

        return $test_logs;
    }

    public static function __saveUserTopicLog($request, $user, $media){
        $response = false;
        
        switch ($media) {
            case 'video':
                if(!self::__isFilledUserTopicAt($user, $request['post_id'], 'video')){
                    $response = DBConnection::getConnection()->query("
                        INSERT INTO 
                            wp_user_topic(user_email, topic_id, video_viewed)
                        VALUES(
                            '". $user  ."',
                            '". $request['post_id'] ."',
                            1
                        )
                        ON DUPLICATE KEY UPDATE
                            video_viewed = 1
                    ");
                }
                break;

            case 'summary':
                if(!self::__isFilledUserTopicAt($user, $request['post_id'], 'summary')){
                    $response = DBConnection::getConnection()->query("
                        INSERT INTO 
                            wp_user_topic(user_email, topic_id, summary_downloaded)
                        VALUES(
                            '". $user  ."',
                            '". $request['post_id'] ."',
                            1
                        )
                        ON DUPLICATE KEY UPDATE
                            summary_downloaded = 1
                    ");
                }
                break;

            case 'map':
                if(!self::__isFilledUserTopicAt($user, $request['post_id'], 'map')){
                    $response = DBConnection::getConnection()->query("
                        INSERT INTO 
                            wp_user_topic(user_email, topic_id, map_downloaded)
                        VALUES(
                            '". $user  ."',
                            '". $request['post_id'] ."',
                            1
                        )
                        ON DUPLICATE KEY UPDATE
                            map_downloaded = 1
                    ");
                }
                break;

            case 'worksheet':
                if(!self::__isFilledUserTopicAt($user, $request['post_id'], 'worksheet')){
                    $response = DBConnection::getConnection()->query("
                        INSERT INTO 
                            wp_user_topic(user_email, topic_id, worksheet_downloaded)
                        VALUES(
                            '". $user  ."',
                            '". $request['post_id'] ."',
                            1
                        )
                        ON DUPLICATE KEY UPDATE
                            worksheet_downloaded = 1
                    ");
                }
                break;

            case 'solutions':
                if(!self::__isFilledUserTopicAt($user, $request['post_id'], 'solutions')){
                    $response = DBConnection::getConnection()->query("
                        INSERT INTO 
                            wp_user_topic(user_email, topic_id, solutions_downloaded)
                        VALUES(
                            '". $user  ."',
                            '". $request['post_id'] ."',
                            1
                        )
                        ON DUPLICATE KEY UPDATE
                            solutions_downloaded = 1
                    ");
                }
                break;
        }

        if ($response) {
            return true;
        } else {
            throw new Exception("User topic log couldn't saved");
        }        
    }

    public static function __isFilledUserTopicAt($user, $topic_id, $media){
        $response = false;
        
        switch ($media) {
            case 'video':
                $video_query = DBConnection::getConnection()->query("
                    SELECT
                        *
                    FROM
                        wp_user_topic
                    WHERE
                        user_email = '". $user ."' AND 
                        topic_id = '". $topic_id ."' AND 
                        video_viewed = 1
                    
                ");

                if($video_query && $video_query->num_rows > 0){
                    $response = true;
                }
                break;
            
            case 'summary':
                $summary_query = DBConnection::getConnection()->query("
                    SELECT
                        *
                    FROM
                        wp_user_topic
                    WHERE
                        user_email = '". $user ."' AND 
                        topic_id = '". $topic_id ."' AND 
                        summary_downloaded = 1
                    
                ");

                if($summary_query && $summary_query->num_rows > 0){
                    $response = true;
                }
                break;
            
            case 'map':
                $map_query = DBConnection::getConnection()->query("
                    SELECT
                        *
                    FROM
                        wp_user_topic
                    WHERE
                        user_email = '". $user ."' AND 
                        topic_id = '". $topic_id ."' AND 
                        map_downloaded = 1
                    
                ");

                if($map_query && $map_query->num_rows > 0){
                    $response = true;
                }
                break;
            
            case 'worksheet':
                $worksheet_query = DBConnection::getConnection()->query("
                    SELECT
                        *
                    FROM
                        wp_user_topic
                    WHERE
                        user_email = '". $user ."' AND 
                        topic_id = '". $topic_id ."' AND 
                        worksheet_downloaded = 1
                    
                ");

                if($worksheet_query && $worksheet_query->num_rows > 0){
                    $response = true;
                }
                break;
            
            case 'solutions':
                $solutions_query = DBConnection::getConnection()->query("
                    SELECT
                        *
                    FROM
                        wp_user_topic
                    WHERE
                        user_email = '". $user ."' AND 
                        topic_id = '". $topic_id ."' AND 
                        solutions_downloaded = 1
                    
                ");

                if($solutions_query && $solutions_query->num_rows > 0)
                    $response = true;
                break;
        }
        
        return $response;
    }

    public static function __getTopicName($id){
        return Timber::get_post([
            "post_type" => "topic",
            "p" => $id
        ])->title;
    }
}
