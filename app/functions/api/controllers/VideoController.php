<?php

require(__DIR__ . '/../models/VideoModel.php');

class VideoController{

    public function __construct(){

    }

    /**
     * Methods
     */
    //1. Videos ---------------------------------------//
    public function getAll($request){
        $videos = VideoModel::getAll($request);
    
        if(empty($videos)){
            return new WP_Error( 'no_videos', __('No videos found'), array( 'status' => 404 ) );
        }else{
            return new WP_REST_Response($videos, 200);
        }    
    }

    //2. Likes ---------------------------------------//
    public function getLikes($request){
        $likes_average = VideoModel::getLikes($request);

        if( $likes_average ){
            return new WP_REST_Response($likes_average, 200);
        }else{
            return new WP_Error( 'no_likes', __('No defined likes'), array( 'status' => 404 ) );
        }
    }
    
    public function updateLikes($request){
        try{
            $new_likes_average = VideoModel::updateLikes($request);
            return new WP_REST_Response($new_likes_average, 200);
        }catch(Exception $e){
            return new WP_Error( 'no_likes', __($e->getMessage()), array( 'status' => 404 ) );
        }
    }
    
    public function checkoutUserLike($request){
        $likes_score = VideoModel::checkoutUserLike($request);

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

    //4. Extras ---------------------------------------//
    public static function saveViewLog($request){
        try {
            return new WP_REST_Response(VideoModel::saveViewLog($request), 200);
        } catch (Exception $e) {
            return new WP_Error( 'video_log_failed', __($e->getMessage()), array( 'status' => 404 ) );
        }
    }    
}
