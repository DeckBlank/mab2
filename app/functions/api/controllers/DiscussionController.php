<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Timber\Timber;

require(__DIR__ . '/../models/schema/Discussion.php');

class DiscussionController{
    public function __construct(){
        add_action( 'rest_api_init', function () {
            register_rest_route('custom/v1', '/discussions', array(
                'methods' => 'POST',
                'callback' => array($this,'store'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route('custom/v1', '/discussions', array(
                'methods' => 'GET',
                'callback' => array($this,'index'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route('custom/v1', '/discussions/(?P<discussion_id>\d+)/comments', array(
                'methods' => 'POST',
                'callback' => array($this,'storeComment'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route('custom/v1', '/discussions/(?P<discussion_id>\d+)', array(
                'methods' => 'GET',
                'callback' => array($this,'show'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
        });
    }

    public function store($request) {
        if (
            !empty($request['course_id']) &&
            !empty($request['user_id']) &&
            !empty($request['user_email']) &&
            !empty($request['subject'])
        ) {
            $discussion = new Discussion();

            $discussion->course_id  = $request['course_id'];
            $discussion->subject    = $request['subject'];
            $discussion->user_id    = $request['user_id'];

            if ( !empty($request['topic_id']) ) $discussion->topic_id = $request['topic_id'];

            $discussion->save();

            if ($discussion && $this::__sendDiscussionNotification($request)) {
                return new WP_REST_Response([
                    'data'      => $discussion,
                    'status'    => true
                ], 200);
            } else {
                return new WP_Error( 'discussion_not_saved', __('Discussion not saved'), array( 'status' => 404 ) );
            }
            
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 404 ) );
        }
    }

    public function index($request) {
        if (
            !empty($request['course_id'])
        ) {
            $discussions = [];

            if (isset($request['paged']) && $request['paged'] > 1) {
                $skip = ($request['paged'] - 1) * 25;

                $discussions = Discussion::orderBy('updated_at', 'desc')
                    ->skip($skip)
                    ->take(25)
                    ->get();

            } else {
                $discussions = Discussion::orderBy('updated_at', 'desc')
                    ->limit(25)
                    ->get();
            }

            if ( count($discussions) ) {
                return new WP_REST_Response([
                    'data'      => $discussions,
                    'status'    => true
                ], 200);
            } else {
                return new WP_Error( 'discussions_not_found', __('Discussions not found'), array( 'status' => 404 ) );
            }
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 404 ) );
        }
    }

    public function storeComment($request) {
        if (
            !empty($request['user_id']) &&
            !empty($request['user_email'])
        ) {
            $comment = wp_insert_comment([
                "comment_author"        => $request['user_id'],
                "comment_author_email"  => $request['user_email'],
                "comment_content"       => $request['content'],
                "comment_meta"          => [
                    "discussion" => $request['discussion_id'],
                    'attachment' => $request['attachment']
                ]
            ]);

            if ( $comment ) {
                $discussion = Discussion::find( $request['discussion_id'] )
                    ->increment('total_comments', 1);

                return new WP_REST_Response([
                    'data'      => $comment,
                    'status'    => true
                ], 200);
            } else {
                return new WP_Error( 'comment_not_saved', __('Comment not saved'), array( 'status' => 404 ) );
            } 
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 404 ) );
        }
    }

    public function show($request) {
        if (
            !empty($request['user_id']) &&
            !empty($request['course_id'])
        ) {
            $discussion = Discussion::find( $request['discussion_id'] );

            if ($discussion) {
                $commentsQuery = new WP_Comment_Query;
                $paged = ( empty($request['paged']) ) ? 1 : $request['paged'];

                $comments = $commentsQuery->query([
                    "parent"        => 0,
                    "number"        => 5,
                    "paged"         => $paged,
                    'meta_query'    => [
                        [
                            'key'       => 'discussion',
                            'value'     => 1,
                            'compare'   => '='
                        ]
                    ]
                ]);

                if ($comments) {
                    $commentSticky  = null;
                    $commentsArray  = [];
                    $userId         = $request['user_id'];
                    $courseId       = $request['course_id'];

                    $topicCommentSticky = get_post_meta($courseId, 'comment_sticky');
                    $commentSticky      = ($topicCommentSticky) ? __sanitizeComment( get_comment($topicCommentSticky[0]), $userId, $request) : false;

                    foreach($comments as $comment){
                        $isSticky = ($topicCommentSticky) ? in_array($comment->comment_ID, $topicCommentSticky) : false;

                        $commentObject = __sanitizeComment($comment, $userId, $request);

                        if (!$isSticky) array_push($commentsArray, $commentObject);
                    }

                    $topicCommentSticky = get_post_meta($courseId, 'comment_sticky');

                    return (object)[
                        "sticky"        => $commentSticky,
                        "list"          => $commentsArray,

                        'is_user_owner' => __isUserOwnerOnCourse($userId, $courseId)
                    ];
                } else {
                    throw new Exception("No comments");
                }   

                return new WP_REST_Response([
                    'data'      => $commentsArray,
                    'status'    => true
                ], 200);
            } else {
                return new WP_Error( 'comment_not_found', __('Comment not found'), array( 'status' => 404 ) );
            }
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 404 ) );
        }
    }

    private function __sendDiscussionNotification($request) {
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

            /* TODO: Descomment */
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
                                    <h1 style="font-size: 25px; color: white;">¡Nueva discussión abierta!</h1>

                                    <table style="width: 100%; padding-left: 1.5rem">          
                                        <tbody style="width: 100%">
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Asunto: </td>
                                                <td style="padding: 10px 0; color: white;">'. $request["subject"] .'</td>     
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Creador: </td>
                                                <td style="padding: 10px; color: #0166D0; background: white;">'. $request["user_email"] .'</td>
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
            $mail->Subject = "Nuevo discussión abierta";
            $mail->MsgHTML($body);
    
            $mail->send();
    
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
