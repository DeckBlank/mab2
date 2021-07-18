<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Timber\Timber;

require(__DIR__ . '/../models/TopicModel.php');
require(__DIR__ . '/../models/schema/Attachment.php');

class TopicController{

    public function __construct() {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'custom/v1', '/topic/(?P<topic_id>\d+)/questions', array(
                'methods' => 'GET',
                'callback' => array($this,'getQuestions'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/likes', array(
                'methods' => 'GET',
                'callback' => array($this,'getLikes'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));    

            register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/likes', array(
                'methods' => 'PUT',
                'callback' => array($this,'updateLikes'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/likes/checkout', array(
                'methods' => 'GET',
                'callback' => array($this,'checkoutUserLike'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));    

            register_rest_route( 'custom/v1', '/topic/(?P<topic_id>\d+)/test_score', array(
                'methods' => 'GET',
                'callback' => array($this,'getTestScore'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topic/(?P<topic_id>\d+)/test_score', array(
                'methods' => 'PUT',
                'callback' => array($this,'updateTestScore'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/comments', array(
                'methods' => 'GET',
                'callback' => array($this,'getComments'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/comment', array(
                'methods' => 'POST',
                'callback' => array($this,'addComment'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));     

            register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/comment/(?P<comment_id>\d+)/answer', array(
                'methods' => 'POST',
                'callback' => array($this,'addAnswer'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topics/(?P<post_id>\d+)/comments/(?P<comment_id>\d+)/sticky', array(
                'methods' => 'POST',
                'callback' => array($this,'stickyComments'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topics/(?P<post_id>\d+)/comments/(?P<comment_id>\d+)/likes', array(
                'methods' => 'POST',
                'callback' => array($this,'likeComments'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topics/comments/attachments', array(
                'methods' => 'POST',
                'callback' => array($this,'storeAttachments'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topics/comments/attachments/(?P<attachment_id>\d+)', array(
                'methods' => 'DELETE',
                'callback' => array($this,'deleteAttachments'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/Video/log', array(
                'methods' => 'PUT',
                'callback' => array($this,'saveVideoLog'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));    

            register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/material/log', array(
                'methods' => 'PUT',
                'callback' => array($this,'saveMaterialLog'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topics/video/logs', array(
                'methods' => 'GET',
                'callback' => array($this,'getVideoLogs'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topics/video/logs/download', array(
                'methods' => 'GET',
                'callback' => array($this,'downloadVideoLogs'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topics/material/logs', array(
                'methods' => 'GET',
                'callback' => array($this,'getMaterialLogs'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topics/material/logs/download', array(
                'methods' => 'GET',
                'callback' => array($this,'downloadMaterialLogs'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topics/test/logs', array(
                'methods' => 'GET',
                'callback' => array($this,'getTestLogs'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topics/test/logs/download', array(
                'methods' => 'GET',
                'callback' => array($this,'downloadTestLogs'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/topics/comments', array(
                'methods' => 'GET',
                'callback' => array($this,'getAllComments'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));    

            register_rest_route( 'custom/v1', '/topics/comments/download', array(
                'methods' => 'GET',
                'callback' => array($this,'downloadAllComments'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));    
        });
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
            } else {
                return new WP_Error( 'no_comment_added', __('No sent notification'), array( 'status' => 404 ) );
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
            } else {
                return new WP_Error( 'no_comment_added', __('No sent notification'), array( 'status' => 404 ) );
            }
        } catch (Exception $e){
            return new WP_Error( 'no_answer_added', __($e->getMessage()), array( 'status' => 404 ) );
        }
    }

    public function stickyComments($request) {
        if (
            !empty($request['user_id']) &&
            !empty($request['course_id']) &&
            !empty($request['mode'])
        ) {
            $userId     = $request['user_id'];
            $commentId  = $request['comment_id'];
            $topicId    = $request['post_id'];
            $courseId   = $request['course_id'];
            $mode       = $request['mode'];

            $commentData = get_comment($commentId);

            if (!$commentData) {
                return new WP_Error( 'not_comment_found', __('Comment not found'), array( 'status' => 404 ) );
            }

            if ( __isUserOwnerOnCourse($userId, $courseId) ) {
                if ($mode == 1) {
                    if ( get_post_meta($topicId, 'comment_sticky') ) {
                        update_post_meta($topicId, 'comment_sticky', $commentId, false);
                    } else {
                        add_post_meta($topicId, 'comment_sticky', $commentId, false);
                    }
                } else {
                    delete_post_meta($topicId, 'comment_sticky', $commentId, false);
                }

                return new WP_REST_Response('Comment as sticky', 200);
            } else {
                return new WP_Error( 'invalid_user', __('Invalid user'), array( 'status' => 404 ) );
            }
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 404 ) );
        }
    }

    public function likeComments($request) {
        if (
            !empty($request['user_id'])
        ) {
            $userId     = $request['user_id'];
            $commentId  = $request['comment_id'];

            $commentData    = get_comment($commentId);

            if ($commentData) {
                $userLikes      = get_comment_meta($commentId, 'user_likes');
                $commentLike    = intval($commentData->comment_karma);

                wp_update_comment([
                    'comment_ID'    => $commentId,
                    'comment_karma' => ++$commentLike
                ]);

                if ($userLikes) {
                    array_push($userLikes, $userId);

                    update_comment_meta($commentId, 'user_likes', $userLikes, true);
                } else {
                    add_comment_meta($commentId, 'user_likes', $userId, true);
                }

                return new WP_REST_Response('Comment liked', 200);
            } else {
                return new WP_Error('not_comment_found', __('Comment not found'), array( 'status' => 404 ));
            }
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 404 ) );
        }
    }

    public function storeAttachments($request) {
        $invoiceFile = (object)[
            "ext" => pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION),
            "name" => '' ,
            "dir" => __DIR__ . "/../../../../../../uploads/attachments/"
        ];

        if($invoiceFile->ext == 'jpg' || $invoiceFile->ext == 'jpeg' || $invoiceFile->ext == 'png' || $invoiceFile->ext == 'PNG') {
            $now = date("Y_m_d_H_i");
            $invoiceFile->name = $invoiceFile->dir . $now . $_FILES['image']['name'];      

            if( is_uploaded_file($_FILES['image']['tmp_name']) ) {			
                if( !move_uploaded_file($_FILES['image']['tmp_name'], $invoiceFile->name) ) {
                    return (object)[
                        "code"      => "no_invoice_saved",
                        "message"   => "Invoice file (" . $_FILES['image']['name'] . ") not saved",
                        "status"    => false
                    ];
                } else {
                    $attachment = new Attachment();

                    $attachment->path       = sprintf("%s/wp-content/uploads/attachments/%s", get_site_url()  , $now . $_FILES['image']['name']);
                    $attachment->filename   = $now . $_FILES['image']['name'];

                    $attachment->save();

                    if ($attachment) {
                        return (object)[
                            "data" => [
                                "path"  => $now . $_FILES['image']['name'],
                                "id"    => $attachment->id,
                            ],
                            "status"    => true
                        ];
                    } else {
                        return new WP_Error( 'no_invoice_saved', __('Couldnt sore on DB'), array( 'status' => 404 ) );
                    }

                }
            } else {
                return (object)[
                    "code"      => "no_invoice_saved",
                    "message"   => "Invoice file (" . $_FILES['image']['name'] . ") not uploaded",
                    "status"    => false
                ];
            }
        } else {
            return (object)[
                "code"      => "no_invoice_saved",
                "message"   => "Invoice file (" . $_FILES['image']['name'] . ") with wrong format",
                "status"    => false
            ];    
        }
    }

    public function deleteAttachments($request) {
        $attachment = Attachment::find($request['attachment_id']);

        if ($attachment) {
            unlink(__DIR__ . "/../../../../../../uploads/attachments/" . $attachment->filename);

            $attachment->delete();

            return new WP_REST_Response('Attachment deleted', 200);
        } else {
            return new WP_Error( 'attachment_not_found', __('Attachment not found'), array( 'status' => 404 ) );
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
        if( TopicModel::updateTestScore($request) ) {
            $userEmail  = $request['user'];
            $courseId   = $request['course_id'];

            $user   = get_user_by('email', $userEmail);
            $userId = $user->ID;

            $courseProgress = __getUserCourseProgress($user->ID, $userEmail, $courseId);

            if ( floatval($courseProgress['percentage']) == 100 ) {
                $userCertificate = UserCertificate::where(['user_id' => $userId, 'course_id' => $courseId])->first();

                if (!$userCertificate) {
                    $userCertificate = new UserCertificate();

                    $userCertificate->signature     = uniqid();
                    $userCertificate->notification  = 0;
                    $userCertificate->user_id       = $userId;
                    $userCertificate->course_id     = $courseId;

                    $userCertificate->save();

                    if ($userCertificate) {
                        return new WP_REST_Response((object)[
                            'message'   => 'Certificate saved!!',
                            'data'      => [ 'course_completed' => true ],
                            'status'    => true
                        ], 200);
                    } else {
                        return new WP_REST_Response((object)[
                            'message'   => 'Certificate not saved!!',
                            'status'    => false
                        ], 200);
                    }
                } else {
                    return new WP_REST_Response((object)[
                        'message'   => 'Test saved!!',
                        'data'      => [ 'course_completed' => true, 'notification' => true ],
                        'status'    => true
                    ], 200);
                }
            }
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

    private function __sendCommentNotification($request, $topic){
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
    
    private function __sendAnswerNotification($request, $topic){
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
