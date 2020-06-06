<?php

require(__DIR__ . '/../models/TopicModel.php');

class TopicController{

    public function __construct(){

    }

    /**
     * Methods
     */
    //1. Questions ---------------------------------------//
    public function getQuestions($request){
        $questions = TopicModel::getQuestions($request);

        if(empty($questions)){
            return new WP_Error( 'no_questions', __('No questions found'), array( 'status' => 404 ) );
        }else{
            return new WP_REST_Response($questions, 200);
        } 
    }

    //2. Likes ---------------------------------------//
    public function getLikes($request){
        $likes_average = TopicModel::getLikes($request);

        if( $likes_average ){
            return new WP_REST_Response($likes_average, 200);
        }else{
            return new WP_Error( 'no_likes', __('No defined likes'), array( 'status' => 404 ) );
        }
    }
    
    public function updateLikes($request){
        try{
            $new_likes_average = TopicModel::updateLikes($request);
            return new WP_REST_Response($new_likes_average, 200);
        }catch(Exception $e){
            return new WP_Error( 'no_likes', __($e->getMessage()), array( 'status' => 404 ) );
        }
    }
    
    public function checkoutUserLike($request){
        $likes_score = TopicModel::checkoutUserLike($request);

        if ( $likes_score && $likes_score->num_rows > 0) {
            return new WP_REST_Response((object)[
                "user" => $request['user'],
                "status" => "liked"
            ], 200);
        } else {
            return new WP_Error( 'no_score', __("Score doesn't exist"), array( 'status' => 404 ) );
        }         
    }

    //3. Comments ---------------------------------------//
    public function getComments($request){
        if(isset($request['paged'])){
            try {
                return new WP_REST_Response(TopicModel::getComments($request), 200);
            } catch (Exception $e) {
                return new WP_Error( 'no_comments', __($e->getMessage()), array( 'status' => 404 ) );
            }
        }else{
            return new WP_Error( 'no_comments_paged', __("No paged specified"), array( 'status' => 404 ) );
        }
    }

    public function addComment($request){        
        try {
            return new WP_REST_Response(TopicModel::addComment($request), 200);
        } catch (Exception $e){
            return new WP_Error( 'no_comment_added', __($e->getMessage()), array( 'status' => 404 ) );
        }
    }
    
    public function addAnswer($request){
        try {
            return new WP_REST_Response(TopicModel::addAnswer($request), 200);
        } catch (Exception $e){
            return new WP_Error( 'no_answer_added', __($e->getMessage()), array( 'status' => 404 ) );
        }
    }

    //4. Test Score ---------------------------------------//
    public function getTestScore($request){
        $test_scores = TopicModel::getTestScore($request);

        if( empty($test_scores) ){
            return new WP_Error( 'no_score', __("Score doesn't exist"), array( 'status' => 404 ) );
        }else{
            return new WP_REST_Response($test_scores[0], 200);
        }
    }

    public function updateTestScore($request){
        if( TopicModel::updateTestScore($request) ){
            return new WP_REST_Response($request['result'], 200);
        }else{
            return new WP_Error( 'no_test_saved', __('No test saved'), array( 'status' => 404 ) );
        }
    }

    //5. Log ---------------------------------------//
    public static function saveViewLog($request){
        try {
            return new WP_REST_Response(TopicModel::saveViewLog($request), 200);
        } catch (Exception $e) {
            return new WP_Error( 'topic_log_failed', __($e->getMessage()), array( 'status' => 404 ) );
        }
    }
 
    public static function saveMaterialDownloadLog($request){
        try {
            return new WP_REST_Response(TopicModel::saveMaterialDownloadLog($request), 200);
        } catch (Exception $e) {
            return new WP_Error( 'topic_log_failed', __($e->getMessage()), array( 'status' => 404 ) );
        }
    } 
}
