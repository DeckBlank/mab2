<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Timber\Timber;

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
    public function getAllComments($request){
        $comments = TopicModel::getAllComments($request);

        if (empty($comments)) {
            return new WP_Error( 'no_comments', __('Not comments found'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($comments, 200);
        }
        
    }

    public function downloadAllComments($request){
        $comments = TopicModel::getAllComments($request);

        if ( empty($comments) ) {
            return new WP_Error( 'no_comments', __('Not comments found'), array( 'status' => 404 ) );
        } else {
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/xls; charset=UTF-8");
            header("Content-Disposition: attachment; filename=comentarios-mabclick-".date('Y-m-d').".xls"); 
            echo "\xEF\xBB\xBF";

            //Header
            include_once __DIR__."/../exports/reports/comments/topic.php";
        }       
    }    

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
            $comment_result = TopicModel::addComment($request);
            $topic =  Timber::get_post([
                "post_type" => "topic",
                "p" => $request['post_id']
            ]);

            if ($comment_result && $this::__sendCommentNotification($request, $topic)) {
                return new WP_REST_Response($comment_result, 200);
            }
        } catch (Exception $e){
            return new WP_Error( 'no_comment_added', __($e->getMessage()), array( 'status' => 404 ) );
        }
    }
    
    public function addAnswer($request){
        try {
            $answer_result = TopicModel::addAnswer($request);
            $topic =  Timber::get_post([
                "post_type" => "topic",
                "p" => $request['post_id']
            ]);

            if ($answer_result && $this::__sendAnswerNotification($request, $topic)) {
                return new WP_REST_Response($answer_result, 200);
            }
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
            header("Content-Disposition: attachment; filename=reportes-videos-vistos-mabclick-".date('Y-m-d').".xls"); 
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
            header("Content-Disposition: attachment; filename=reportes-pdfs-descargados-mabclick-".date('Y-m-d').".xls"); 
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
            header("Content-Disposition: attachment; filename=reportes-cuestionarios-resueltos-mabclick-".date('Y-m-d').".xls"); 
            echo "\xEF\xBB\xBF";

            //Header
            include_once __DIR__."/../exports/reports/tests.php";
        }     
    }

    public function export($request) {
        $topics = [];

        if (isset($request['ids'])) {
            $ids = explode(',', $request['ids']);

            $topics = Timber::get_posts([
                "post_type"         => "topic",
                "posts_per_page"    => -1,
                "post__in"          => $ids,
                "orderby"           => "post__in"
            ]);
        } else {
            $topics = Timber::get_posts([
                "post_type"         => "topic",
                "posts_per_page"    => 20,
                "paged"             => $request['page']
            ]);
        }

        $topics = CourseModel::__getTopicsSanitize($topics, -1, 'deep');

        if(empty($topics)){
            return new WP_Error( 'no_topics', __('No topics found'), array( 'status' => 404 ) );
        }else{
            return new WP_REST_Response($topics, 200);
        }
    }

    public function __sendCommentNotification($request, $topic){
        $mail = new PHPMailer(true);
        $admins = array_map(function($admin){return $admin->data->user_email;}, get_users(['role' => 'administrator']));

        try {
            //Server settings
            $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'mail.mabclick.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@mabclick.com';
            $mail->Password   = '-@6]W8u_5qA@';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            //Recipients
            $mail->setFrom('no-reply@mabclick.com', "MABCLICK");
            $mail->addAddress($request['user_email']);

            foreach($admins as $admin){
                $mail->addAddress($admin);
            }
    
            // Content
            $body = '
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background: #DE0D46; padding: 3rem 0;">
                    <tr>
                    <td width="100%" align="center" style="padding: 0 1rem">
                        <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="600" align="center">
                            <div style="background: #0166D0; color: white; width: 100%; max-width: 640px;">
                                <header style="background: white; padding: 1rem;">
                                    <img src="https://mabclick.com/wp-content/themes/mab-theme/app/static/images/logo.png" style="width: 100px;">
                                </header>
    
                                <div style="padding: 1rem;">
                                    <h1 style="font-size: 25px; color: white;">¡Nuevo comentario!</h1>

                                    <table style="width: 100%; padding-left: 1.5rem">          
                                        <tbody style="width: 100%">
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Autor: </td>
                                                <td style="padding: 10px 0; color: white;">'. $request["user"] .'</td>     
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Contenido: </td>
                                                <td style="padding: 10px 0; color: white;">'. $request["content"] .'</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Tema: </td>
                                                <td style="padding: 10px 0; color: white;">'. $topic->post_title .'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
    
                                <footer style="text-align: center; font-size: 12px; font-family: Verdana, serif; padding: 1rem; color: #0166D0; background: white;">
                                    All rights reserved - MABCLICK
                                </footer>         
                            </div>
                            </td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                </table>           
            ';
    
            $mail->isHTML(true); 
            $mail->Subject = "Nuevo comentario";
            $mail->MsgHTML($body);
    
            $mail->send();
    
            return true;
        } catch (Exception $e) {
            return false;
        }
    }    

    public function __sendAnswerNotification($request, $topic){
        $mail = new PHPMailer(true);
        $admins = array_map(function($admin){return $admin->data->user_email;}, get_users(['role' => 'administrator']));
        $comment = get_comments([
            "comment__in" => [$request['comment_id']]
        ]);

        try {
            //Server settings
            $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'mail.mabclick.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@mabclick.com';
            $mail->Password   = '-@6]W8u_5qA@';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            //Recipients
            $mail->setFrom('no-reply@mabclick.com', "MABCLICK");
            $mail->addAddress($request['user_email']);

            if($comment[0]->comment_author_email)
                $mail->addAddress($comment[0]->comment_author_email);

            foreach($admins as $admin){
                $mail->addAddress($admin);
            }
    
            // Content
            $body = '
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background: #DE0D46; padding: 3rem 0;">
                    <tr>
                    <td width="100%" align="center" style="padding: 0 1rem">
                        <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="600" align="center">
                            <div style="background: #0166D0; color: white; width: 100%; max-width: 640px;">
                                <header style="background: white; padding: 1rem;">
                                    <img src="https://mabclick.com/wp-content/themes/mab-theme/app/static/images/logo.png" style="width: 100px;">
                                </header>
    
                                <div style="padding: 1rem;">
                                    <h1 style="font-size: 25px; color: white;">Nueva respuesta en : '. $topic->post_title .'</h1>

                                    <table style="width: 100%; padding-left: 1.5rem">          
                                        <tbody style="width: 100%">
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Comentario: </td>
                                                <td style="padding: 10px 0; color: white;">
                                                    <p><b>'. $comment[0]->comment_author .'</b>:</p>                                                    
                                                    "'. $comment[0]->comment_content .'"
                                                </td>     
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Autor: </td>
                                                <td style="padding: 10px 0; color: white;">'. $request["user"] .'</td>     
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Respuesta: </td>
                                                <td style="padding: 10px 0; color: white;">'. $request["content"] .'</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Tema: </td>
                                                <td style="padding: 10px 0; color: white;">'. $topic->post_title .'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
    
                                <footer style="text-align: center; font-size: 12px; font-family: Verdana, serif; padding: 1rem; color: #0166D0; background: white;">
                                    All rights reserved - MABCLICK
                                </footer>         
                            </div>
                            </td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                </table>           
            ';
    
            $mail->isHTML(true); 
            $mail->Subject = "Nueva respuesta";
            $mail->MsgHTML($body);
    
            $mail->send();
    
            return true;
        } catch (Exception $e) {
            return false;
        }
    }    
}
