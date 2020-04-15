<?php

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
            return DBConnection::getConnection()->query(" 
                UPDATE 
                    wp_topic_test_scores 
                SET 
                    date_at = '". date("Y-m-d") ."',
                    score = '". $request['result'] ."' 
                WHERE 
                    user = 'fika' AND topic_id = 27
            ");
        }else{
            return DBConnection::getConnection()->query("
                INSERT INTO wp_topic_test_scores(date_at,user,course_id,topic_id,score) VALUES(
                    '". date("Y-m-d") ."',
                    '". $request['user'] ."',
                    '". $request['course_id'] ."',
                    '". $request['topic_id'] ."',
                    '". $request['result'] ."'
                )   
            ");
        }
    }    
}
