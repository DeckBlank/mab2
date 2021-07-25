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

            register_rest_route('custom/v1', '/discussions/(?P<discussion_id>\d+)/comments/(?P<comment_id>\d+)/answers', array(
                'methods' => 'POST',
                'callback' => array($this,'storeAnswer'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route('custom/v1', '/discussions/(?P<discussion_id>\d+)/comments/(?P<comment_id>\d+)/sticky', array(
                'methods' => 'POST',
                'callback' => array($this,'stickyComment'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route('custom/v1', '/discussions/(?P<discussion_id>\d+)/comments/(?P<comment_id>\d+)/like', array(
                'methods' => 'POST',
                'callback' => array($this,'likeComment'),
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

            register_rest_route('custom/v1', '/discussions/(?P<discussion_id>\d+)/sticky', array(
                'methods' => 'POST',
                'callback' => array($this,'sticky'),
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
                    'data'      => $this::__sanitizeDiscussion($discussion),
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
            $discussions        = [];
            $discussionsArray   = [];

            $userId     = $request['user_id'];
            $courseId   = $request['course_id'];

            $discussionSticky = get_post_meta($request['course_id'], 'discussion_sticky');
            $discussionSticky = ($discussionSticky) ? Discussion::find($discussionSticky[0]) : false;

            if (isset($request['paged']) && $request['paged'] > 1) {
                $skip = ($request['paged'] - 1) * 25;

                $discussions = Discussion::where(['course_id' => $courseId])
                    ->orderBy('updated_at', 'desc')
                    ->skip($skip)
                    ->take(25)
                    ->get();

            } else {
                $discussions = Discussion::where(['course_id' => $courseId])
                    ->orderBy('updated_at', 'desc')
                    ->limit(25)
                    ->get();
            }

            if ($discussions)
                foreach ($discussions as $disc) {
                    if ($discussionSticky) {
                        if ( $disc->id != $discussionSticky->id )
                            array_push($discussionsArray, $this::__sanitizeDiscussion($disc));
                    } else {
                        array_push($discussionsArray, $this::__sanitizeDiscussion($disc));
                    }
                }

            if ( count($discussionsArray) ) {
                return new WP_REST_Response([
                    'sticky'    => $discussionSticky ? $this::__sanitizeDiscussion($discussionSticky) : false,
                    'data'      => $discussionsArray,

                    'is_user_owner' => __isUserOwnerOnCourse($userId, $courseId),

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
                "comment_author"        => $request['user'],
                "comment_author_email"  => $request['user_email'],
                "comment_content"       => $request['content'],
                "comment_meta"          => [
                    "discussion" => $request['discussion_id'],
                    'attachment' => $request['attachment']
                ]
            ]);

            if ( $comment && $this::__sendCommentNotification($request)) {
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

    public function storeAnswer($request) {
        if (
            !empty($request['user_id']) &&
            !empty($request['user_email'])
        ) {
            $answer = wp_insert_comment([
                "comment_author"        => $request['user'],
                "comment_author_email"  => $request['user_email'],
                "comment_content"       => $request['content'],
                "comment_parent"        => $request['comment_id'],
                "comment_meta"          => [
                    'attachment' => $request['attachment']
                ]
            ]);

            if ( $answer && $this::__sendAnswerNotification($request)) {
                $discussion = Discussion::find( $request['discussion_id'] )
                    ->increment('total_comments', 1);

                return new WP_REST_Response([
                    'data'      => $answer,
                    'status'    => true
                ], 200);
            } else {
                throw new Exception("No answer added");
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
                $commentsQuery  = new WP_Comment_Query;
                $comments       = [];
                $paged          = ( empty($request['paged']) ) ? 1 : $request['paged'];

                if ($discussion->topic_id) {
                    $comments = $commentsQuery->query([
                        "parent"    => 0,
                        "number"    => 5,
                        "paged"     => $paged,
                        "post_id"   => $discussion->topic_id,
                    ]);
                } else {
                    $comments = $commentsQuery->query([
                        "parent"        => 0,
                        "number"        => 5,
                        "paged"         => $paged,
                        'meta_query'    => [
                            [
                                'key'       => 'discussion',
                                'value'     =>  $discussion->id,
                                'compare'   => '='
                            ]
                        ]
                    ]);
                }

                if ($comments) {
                    $commentSticky  = null;
                    $commentsArray  = [];
                    $userId         = $request['user_id'];
                    $courseId       = $request['course_id'];

                    $discussionCommentSticky = false;

                    if ($discussion->topic_id) {
                        $discussionCommentSticky = get_post_meta($discussion->topic_id, 'comment_sticky');
                    } else {
                        $discussionCommentSticky = $discussion->sticky_comment ? [$discussion->sticky_comment] : false;
                    }

                    $commentSticky = ($discussionCommentSticky) ? __sanitizeComment( get_comment($discussionCommentSticky[0]), $userId, $request) : false;

                    foreach($comments as $comment){
                        $isSticky = ($discussionCommentSticky) ? in_array($comment->comment_ID, $discussionCommentSticky) : false;

                        $commentObject = __sanitizeComment($comment, $userId, $request);

                        if (!$isSticky) array_push($commentsArray, $commentObject);
                    }

                    $discussionCommentSticky = get_post_meta($courseId, 'comment_sticky');

                    return (object)[
                        "sticky"        => $commentSticky,
                        "list"          => $commentsArray,

                        'is_user_owner' => __isUserOwnerOnCourse($userId, $courseId)
                    ];
                } else {
                    return new WP_Error( 'comments_not_found', __('Comments not found'), array( 'status' => 404 ) );
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

    public function sticky($request) {
        if (
            !empty($request['user_id']) &&
            !empty($request['course_id'])
        ) {
            $userId         = $request['user_id'];
            $courseId       = $request['course_id'];
            $discussionId   = $request['discussion_id'];
            $mode           = $request['mode'];

            if ( __isUserOwnerOnCourse($userId, $courseId) ) {
                if ($mode == 1) {
                    if ( get_post_meta($courseId, 'discussion_sticky') ) {
                        update_post_meta($courseId, 'discussion_sticky', $discussionId, false);
                    } else {
                        add_post_meta($courseId, 'discussion_sticky', $discussionId, false);
                    }
                } else {
                    delete_post_meta($courseId, 'discussion_sticky', $discussionId, false);
                }

                return new WP_REST_Response('Discussion as sticky', 200);
            } else {
                return new WP_Error( 'invalid_user', __('Invalid user'), array( 'status' => 404 ) );
            }
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 404 ) );
        }
    }

    public function stickyComment($request) {
        if (
            !empty($request['user_id']) &&
            !empty($request['course_id']) &&
            !empty($request['mode'])
        ) {
            $userId         = $request['user_id'];
            $commentId      = $request['comment_id'];
            $discussionId   = $request['discussion_id'];
            $courseId       = $request['course_id'];
            $mode           = $request['mode'];

            $commentData = get_comment($commentId);

            if (!$commentData) {
                return new WP_Error( 'not_comment_found', __('Comment not found'), array( 'status' => 404 ) );
            }

            if ( __isUserOwnerOnCourse($userId, $courseId) ) {
                Discussion::where(['id' => $discussionId])
                    ->update(['sticky_comment' => ($mode == 1) ? $commentId : '0']);

                return new WP_REST_Response('Comment as sticky', 200);
            } else {
                return new WP_Error( 'invalid_user', __('Invalid user'), array( 'status' => 404 ) );
            }
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 404 ) );
        }
    }

    public function likeComment($request) {
        if (
            !empty($request['user_id'])
        ) {
            $userId     = $request['user_id'];
            $commentId  = $request['comment_id'];

            $commentData = get_comment($commentId);

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

    private function __sanitizeDiscussion($discussion) {
        $userFirstName  = get_user_meta($discussion->user_id, 'first_name', true);
        $userLastName   = get_user_meta($discussion->user_id, 'last_name', true);

        $userLastNameSplit  = explode('-panda-', $userLastName);
        $userLastName       = ( count($userLastNameSplit) > 1 ) ? $userLastNameSplit[0] . ' ' . $userLastNameSplit[1] : $userLastName;

        $discussion['author'] = $userFirstName . ' ' . $userLastName;

        if ($discussion->topic_id) {
            $topic = Timber::get_post([
                'post_type' => 'topic',
                'p'         => $discussion->topic_id
            ]);

            $discussion['subject'] = 'Clase: ' . $topic->title;
        }

        return $discussion;
    }

    private function __sendCommentNotification($request){
        $mail = new PHPMailer(true);
        $admins = array_map(function($admin){return $admin->data->user_email;}, get_users(['role' => 'administrator']));

        $discussion = Discussion::find( $request['discussion_id'] );

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
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Discusión: </td>
                                                <td style="padding: 10px 0; color: white;">'. $discussion->subject .'</td>
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

    private function __sendAnswerNotification($request){
        $mail = new PHPMailer(true);
        $admins = array_map(function($admin){return $admin->data->user_email;}, get_users(['role' => 'administrator']));
        $comment = get_comments([
            "comment__in" => [$request['comment_id']]
        ]);

        $discussion = Discussion::find( $request['discussion_id'] );

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
                                    <h1 style="font-size: 25px; color: white;">Nueva respuesta en : '. $discussion->subject .'</h1>

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
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Discusión: </td>
                                                <td style="padding: 10px 0; color: white;">'. $discussion->subject .'</td>
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
