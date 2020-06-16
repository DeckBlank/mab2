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
    public static function saveVideoLog($request){
        try {
            return new WP_REST_Response(TopicModel::saveVideoLog($request), 200);
        } catch (Exception $e) {
            return new WP_Error( 'topic_log_failed', __($e->getMessage()), array( 'status' => 404 ) );
        }
    }
 
    public static function saveMaterialLog($request){
        try {
            return new WP_REST_Response(TopicModel::saveMaterialLog($request), 200);
        } catch (Exception $e) {
            return new WP_Error( 'topic_log_failed', __($e->getMessage()), array( 'status' => 404 ) );
        }
    } 
 
    public static function getVideoLogs($request){
        $view_logs = TopicModel::getVideoLogs($request);

        if ( empty($view_logs) ) {
            return new WP_Error( 'no_topic_view_logs', __('No topic view logs'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($view_logs, 200);
        }
    } 

    public function downloadVideoLogs($request){
        $view_logs = TopicModel::getVideoLogs($request, 'all');

        if ( empty($view_logs) ) {
            return new WP_Error( 'no_topic_view_logs', __('No topic view logs'), array( 'status' => 404 ) );
        } else {
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/xls; charset=UTF-8");
            header("Content-Disposition: attachment; filename=resportes-videos-vistos-mabclick-".date('Y-m-d').".xls"); 
            echo "\xEF\xBB\xBF";

            //Header
            include_once __DIR__."/../exports/reports/videos.php";
        }       
    }

    public static function getMaterialLogs($request){
        $material_logs = TopicModel::getMaterialLogs($request);

        if ( empty($material_logs) ) {
            return new WP_Error( 'no_topic_material_logs', __('No topic material logs'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($material_logs, 200);
        }
    }

    public function downloadMaterialLogs($request){
        $material_logs = TopicModel::getMaterialLogs($request, 'all');


        if ( empty($material_logs) ) {
            return new WP_Error( 'no_topic_material_logs', __('No topic material logs'), array( 'status' => 404 ) );
        } else {
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/xls; charset=UTF-8");
            header("Content-Disposition: attachment; filename=resportes-pdfs-descargados-mabclick-".date('Y-m-d').".xls"); 
            echo "\xEF\xBB\xBF";

            //Header
            include_once __DIR__."/../exports/reports/materials.php";
        }     
    }    

    public static function getTestLogs($request){
        $test_logs = TopicModel::getTestLogs($request);

        if ( empty($test_logs) ) {
            return new WP_Error( 'no_topic_test_logs', __('No topic test logs'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($test_logs, 200);
        }
    }

    public function downloadTestLogs($request){
        $material_logs = TopicModel::getTestLogs($request, 'all');


        if ( empty($material_logs) ) {
            return new WP_Error( 'no_topic_material_logs', __('No topic material logs'), array( 'status' => 404 ) );
        } else {
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/xls; charset=UTF-8");
            header("Content-Disposition: attachment; filename=resportes-cuestionarios-resueltos-mabclick-".date('Y-m-d').".xls"); 
            echo "\xEF\xBB\xBF";

            //Header
            include_once __DIR__."/../exports/reports/tests.php";
        }     
    }    
}
