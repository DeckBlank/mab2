<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Timber\Timber;

require(__DIR__ . '/../models/UserModel.php');
require(__DIR__ . '/../models/schema/User.php');
require(__DIR__ . '/../models/schema/UserCourse.php');
require(__DIR__ . '/../models/schema/UserCertificate.php');
require(__DIR__ . '/../models/schema/UserCourseEnrollment.php');
require(__DIR__ . '/../models/schema/UserTopic.php');
require(__DIR__ . '/../models/schema/TopicTestScore.php');

class UserController{
    public function __construct(){
        add_action( 'rest_api_init', function () {
            register_rest_route( 'custom/v1', '/users', array(
                'methods' => 'GET',
                'callback' => array($this, 'getAll'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/users/download', array(
                'methods' => 'GET',
                'callback' => array($this, 'downloadUsers'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/user/auth', array(
                'methods' => 'GET',
                'callback' => array($this, 'auth'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/user/logout', array(
                'methods' => 'GET',
                'callback' => array($this, 'logout'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/user/recovery_session', array(
                'methods' => 'GET',
                'callback' => array($this, 'getRecoverySession'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/user/recovery_session', array(
                'methods' => 'POST',
                'callback' => array($this, 'createRecoverySession'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/user', array(
                'methods' => 'POST',
                'callback' => array($this, 'createUser'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/user', array(
                'methods' => 'PUT',
                'callback' => array($this, 'updateUser'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/user/teacher/network', array(
                'methods' => 'POST',
                'callback' => array($this, 'sendTeacherData'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/user/password', array(
                'methods' => 'PUT',
                'callback' => array($this, 'resetPassword'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/user/enrollments', array(
                'methods' => 'GET',
                'callback' => array($this, 'getEnrollments'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/user/enrollments', array(
                'methods' => 'POST',
                'callback' => array($this, 'saveEnrollments'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/user/enrollments', array(
                'methods' => 'DELETE',
                'callback' => array($this, 'deleteEnrollments'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/user/access/log', array(
                'methods' => 'PUT',
                'callback' => array($this, 'saveAccessLog'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/users/access/logs', array(
                'methods' => 'GET',
                'callback' => array($this, 'getAccessLogs'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/users/(?P<user_id>\d+)/courses', array(
                'methods' => 'GET',
                'callback' => array($this, 'getEnrolledCourses'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));

            register_rest_route( 'custom/v1', '/users/(?P<user_id>\d+)/courses/recommended', array(
                'methods' => 'GET',
                'callback' => array($this, 'getRecommendedCourses'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));

            register_rest_route( 'custom/v1', '/user/enrollments', array(
                'methods' => 'POST',
                'callback' => array($this, 'saveEnrollments'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route( 'custom/v1', '/users/(?P<user_id>\d+)/profile', array(
                'methods' => 'GET',
                'callback' => array($this, 'showProfile'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));

            register_rest_route( 'custom/v1', '/users/(?P<user_id>\d+)/profile', array(
                'methods' => 'PUT',
                'callback' => array($this, 'updateProfile'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));

            register_rest_route( 'custom/v1', '/users/(?P<user_id>\d+)/profile/avatar', array(
                'methods' => 'POST',
                'callback' => array($this, 'updateProfileAvatar'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));

            register_rest_route( 'custom/v1', '/users/(?P<user_id>\d+)/profile/habilities', array(
                'methods' => 'PUT',
                'callback' => array($this, 'updateProfileHabilities'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));

            register_rest_route( 'custom/v1', '/users/(?P<user_id>\d+)/certificate', array(
                'methods' => 'GET',
                // 'callback' => array($this, 'renderCertificate'),
                'callback' => array($this, 'renderCertificateTemp'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
        });
    }

    /**
     * Methods
     */
    public function getAll($request){
        $users = UserModel::getAll($request);

        if (empty($users)) {
            return new WP_Error( 'users_not_found', __('Users not found'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($users, 200);
        }
        
    }

    public function downloadUsers($request){
        $users = UserModel::getAll($request);

        if( empty($users) ){
            return new WP_Error( 'users_not_found', __('Users not found'), array( 'status' => 404 ) );         
        }else{
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/xls; charset=UTF-8");    
            header("Content-Disposition: attachment; filename=resportes-usuarios-mabclick-".date('Y-m-d').".xls"); 
            echo "\xEF\xBB\xBF";

            //Header
            include_once __DIR__."/../exports/reports/users/". $request['role'] ."s.php";
        }        
    }

    public function auth($request){
        if( isset($request['user']) and isset($request['password']) ){
            try {
                return new WP_REST_Response(UserModel::auth($request), 200);
            } catch (Exception $e) {
                return new WP_Error( 'user_failed', __($e->getMessage()), array( 'status' => 404 ) );
            }
        }else{
            return new WP_Error( 'no_user_credentials', __('No user credentials'), array( 'status' => 404 ) );
        }
    }

    public function logout($request){
        if( UserModel::logout($request) ){
            return new WP_REST_Response('Logout successfull!', 200);
        }else{
            return new WP_Error( 'no_user_logout', __('No user logout'), array( 'status' => 404 ) );
        }
    }

    public function createRecoverySession($request){
        if( isset($request['user']) ){
            $user_id = UserModel::checkout($request);
            
            if( !$user_id ){
                return new WP_Error( 'no_user', __("User doesn't exist"), array( 'status' => 404 ) );
            }else{
                $recovery_session = UserModel::createRecoverySession($user_id);
                
                if ($recovery_session) {
                    return $this::sendInstructions($request, $recovery_session, $user_id);
                } else {
                    return new WP_Error( 'no_recovery_session_created', __("No recovery session created"), array( 'status' => 404 ) );
                }                
            }       
        }else{
            return new WP_Error( 'no_user_credentials', __('No user credentials'), array( 'status' => 404 ) );
        }
    }

    public function getRecoverySession($request){
        $recovery_session = UserModel::getRecoverySession($request);

        if ($recovery_session && $recovery_session->num_rows > 0) {
            $user_id = ($recovery_session->fetch_assoc())['user'];

            return new WP_REST_Response(get_user_by('id', $user_id)->data, 200);
        } else {
            return new WP_Error( 'recovery_session_not_exists', __('No exists recovery session'), array( 'status' => 404 ) );
        }        
    }

    public function createUser($request){
        if(UserModel::createUser($request)){
            if ($this::sendNotification($request)) {
                return new WP_REST_Response('User created', 200);
            } else {
                return new WP_Error( 'no_sent_notification', __('No sent notification'), array( 'status' => 404 ) );
            }
        }else{
            return new WP_Error( 'no_user_created', __('No user created'), array( 'status' => 404 ) );
        }
    }

    public function updateUser($request){
        if(UserModel::updateUser($request)){
            return new WP_REST_Response('User updated', 200);
        }else{
            return new WP_Error( 'no_user_updated', __('No user updated'), array( 'status' => 404 ) );
        }
    }

    public function sendTeacherData($request){
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
                            <div style="background: white; width: 100%; max-width: 640px;">
                                <header style="padding: 1rem;">
                                    <img src="https://mabclick.com/wp-content/themes/mab-theme/app/static/images/logo.png" style="width: 100px;">
                                </header>
    
                                <div style="padding: 1rem;">
                                    <h1 style="font-size: 25px;">¡Nuevo docente!</h1>

                                    <table style="width: 100%; padding-left: 1.5rem">          
                                        <tbody style="width: 100%">
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; font-weight:bold">Email: </td>
                                                <td style="padding: 10px 0;">'. $request["email"] .'</td>     
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; font-weight:bold">Teléfono: </td>
                                                <td style="padding: 10px 0;">'. $request["phone"] .'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
    
                                <footer style="text-align: center; font-size: 12px; font-family: Verdana, serif; padding: 1rem; color: white; background: #0166D0;">
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
            $mail->Subject = "Nuevo docente";
            $mail->MsgHTML($body);
    
            $mail->send();
    
            return new WP_REST_Response('User created', 200);
        } catch (Exception $e) {
            return new WP_Error( 'no_sent_teacher', __($mail->ErrorInfo), array( 'status' => 404 ) );
        }
    }

    public function resetPassword($request){
        if (isset($request['new_pass'])) {
            $recovery_session = UserModel::getRecoverySession($request);

            if ($recovery_session && $recovery_session->num_rows > 0) {
                UserModel::resetPassword($request, $recovery_session->fetch_assoc());
                return new WP_REST_Response('Password reset', 200);
            } else {
                return new WP_Error( 'recovery_session_not_exists', __('No exists recovery session'), array( 'status' => 404 ) );
            }        
        } else {
            return new WP_Error( 'no_password_setted', __('No password setted'), array( 'status' => 404 ) );
        }
        
    }

    public function getEnrollments($request){
        $enrollments = UserModel::getEnrollments($request);

        if ($enrollments) {
            return new WP_REST_Response($enrollments, 200);
        } else {
            return new WP_Error( 'no_enrollments_found', __("No enrollments found"), array( 'status' => 404 ) );
        }
        
    }    

    public function saveEnrollments($request){
        if (UserModel::saveEnrollments($request)) {
            return new WP_REST_Response('Enrollments saved', 200);
        } else {
            return new WP_Error( 'no_enrollments_saved', __("No enrollments saved"), array( 'status' => 404 ) );
        }
    }

    public function deleteEnrollments($request){
        if (UserModel::deleteEnrollments($request)) {
            return new WP_REST_Response('Enrollments deleted', 200);
        } else {
            return new WP_Error( 'no_enrollments_deleted', __("No enrollments deleted"), array( 'status' => 404 ) );
        }
    }

    public function saveAccessLog($request){
        try {
            UserModel::saveAccessLog($request);

            return new WP_REST_Response('Log created', 200);
        } catch (Exception $e) {
            return new WP_Error( 'user_failed', __($e->getMessage()), array( 'status' => 404 ) );
        }
    }

    public function getAccessLogs($request){
        $access_logs = UserModel::getAccessLogs($request);

        if ( empty($access_logs) ) {
            return new WP_Error( 'no_access_logs', __('No access logs'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($access_logs, 200);
        }
    }

    public function getEnrolledCourses($request) {
        if ( !empty($request['user_email']) ) {
            $userEmail      = $request['user_email'];
            $userID         = $request['user_id'];
            $coursesArray   = [];

            /*
            $courses = UserCourse::where(['user_email' => $userEmail])
                ->orderBy('last_date', 'DESC')
                ->get();
            */

            $enrolledCourses = UserCourseEnrollment::where(['user_email' => $userEmail])
                ->orderBy('last_date', 'DESC')
                ->get();

            /*
            foreach ($courses as $course) {
                $sanitizedCourse = __sanitizeCourse($course->course_id, $userEmail, $userID);

                if ( $sanitizedCourse && !in_array($sanitizedCourse, $coursesArray) ) {
                    if ( __checkEnrollOnCourse($course->course_id, $userEmail) ) array_push($coursesArray, $sanitizedCourse);
                }
            }*/

            foreach ($enrolledCourses as $course) {
                $sanitizedCourse = __sanitizeCourse($course->course_id, $userEmail, $userID);

                if ( $sanitizedCourse && !in_array($sanitizedCourse, $coursesArray) ) {
                    if ( __checkEnrollOnCourse($course->course_id, $userEmail) ) array_push($coursesArray, $sanitizedCourse);
                }
            }

            if ( count($coursesArray) ) {
                return new WP_REST_Response((object)[
                    'message'   => 'Courses here!!',
                    'data'      => $coursesArray,
                    'status'    => true
                ], 200);
            } else {
                return new WP_REST_Response((object)[
                    'message'   => 'No courses found',
                    'status'    => false
                ], 200);
            }
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 403 ) );
        }
    }

    public function getRecommendedCourses($request) {
        if ( !empty($request['user_email']) ) {
            $userEmail          = $request['user_email'];
            $userId             = $request['user_id'];
            $userPreferences    = get_field('mab_sub_category', 'user_' . $userId);
            $userPreferences    = explode(',', $userPreferences);

            $coursesArray = [];

            if ( $userPreferences &&  $userPreferences[0]) {
                $courses = Timber::get_posts([
                    'post_type'         => 'course',
                    'posts_per_page'    => -1,
                    'tax_query' => array( 
                        array(
                            'taxonomy' => 'tax-mab-course',
                            'field'    => 'term_id',
                            'terms'    => $userPreferences
                        )
                    )
                ]);
            } else {
                $coursesIds         = [];
                $courseCategories   = [];
                $userCourses        = UserCourse::where([
                        'user_email' => $userEmail
                    ])
                    ->where('topic_views', '>', 0)
                    ->orderBy('last_date', 'DESC')
                    ->get();

                foreach($userCourses as $uCourse) {
                    array_push($coursesIds, $uCourse->course_id);
                }

                $userObjectCourses = Timber::get_posts([
                    'post_type'     => 'course',
                    'post__not_in'  => $coursesIds,
                ]);

                foreach($userObjectCourses as $course) {
                    $courseCategories = array_merge($courseCategories, $course->terms);
                }

                $courseCategories = array_map(function($cCategory){ return $cCategory->term_id; }, $courseCategories);

                $courses = Timber::get_posts([
                    'post_type'         => 'course',
                    'posts_per_page'    => 16,
                    'tax_query' => array( 
                        array(
                            'taxonomy' => 'tax-course',
                            'field'    => 'term_id',
                            'terms'    => $courseCategories
                        )
                    )
                ]);
            }

            foreach ($courses as $course) {
                $sanitizedCourse = __sanitizeCourse($course->ID, $userEmail, $userId, 'recommend');

                if ( $sanitizedCourse && !in_array($sanitizedCourse, $coursesArray) )
                    array_push($coursesArray, $sanitizedCourse);
            }

            if ( count($coursesArray) ) {
                return new WP_REST_Response((object)[
                    'message'   => 'Courses here!!',
                    'data'      => $coursesArray,
                    'status'    => true
                ], 200);
            } else {
                return new WP_REST_Response((object)[
                    'message'   => 'No courses found',
                    'status'    => false
                ], 200);
            }
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 403 ) );
        }
    }

    public function downloadAccesLogs($request){
        $access_logs = UserModel::getAccessLogs($request, 'all');

        if( empty($access_logs) ){                
            return new WP_Error( 'no_access_logs', __('No access logs'), array( 'status' => 404 ) );         
        }else{
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/xls; charset=UTF-8");    
            header("Content-Disposition: attachment; filename=resportes-accesos-mabclick-".date('Y-m-d').".xls"); 
            echo "\xEF\xBB\xBF";

            //Header
            include_once __DIR__."/../exports/reports/accesses.php";
        }        
    }

    public function showProfile($request) {
        $userId = $request['user_id'];

        $habilitesSoft = get_field('habilities_soft', 'user_' . $userId);
        $habilitesHard = get_field('habilities_hard', 'user_' . $userId);

        $data = [
            'user_firstname'    => get_user_meta($userId, 'first_name', true),
            'user_lastname'     => get_user_meta($userId, 'last_name', true),
            'phrase'            => get_field('phrase', 'user_' . $userId),
            'avatar'            => get_field('avatar', 'user_' . $userId),
            'habilites'         => [
                'soft' => ($habilitesSoft) ? explode(',', $habilitesSoft) : [],
                'hard' => ($habilitesHard) ? explode(',', $habilitesHard) : [],
            ],
        ];

        return new WP_REST_Response((object)[
            'message'   => 'Profile data found!!',
            'data'      => $data,
            'status'    => true
        ], 200);
    }

    public function updateProfile($request) {
        if (
            !empty($request['firstname']) &&
            !empty($request['father_name']) &&
            !empty($request['mother_name']) &&
            !empty($request['phrase'])
        ) {
            $userId = $request['user_id'];

            wp_update_user([
                'ID'            => $userId,
                'first_name'    => $request['firstname'],
                'last_name'     => sprintf('%s-panda-%s', $request['father_name'], $request['mother_name']),
            ]);

            update_field('phrase', $request['phrase'], 'user_' . $userId);

            return new WP_REST_Response((object)[
                'message'   => 'Profile updated!!',
                'status'    => true
            ], 200);
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 403 ) );
        }
    }

    public function updateProfileAvatar($request) {
        if (
            isset($_FILES['avatar'])
        ) {
            $userAvatar = $this::__saveAvatarFile();
            $user       = update_field('avatar', $userAvatar->id, 'user_' . $request['user_id']);

            if ($user) {
                return new WP_REST_Response((object)[
                    'message'   => 'Profile updated!!',
                    "avatar"    => $userAvatar->url,
                    'status'    => true
                ], 200);
            } else {
                return new WP_REST_Response((object)[
                    'message'   => 'Profile avatar not updated!!',
                    'status'    => false
                ], 200);
            }
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 403 ) );
        }
    }

    public function updateProfileHabilities($request) {
        if (
            !empty($request['soft']) &&
            !empty($request['hard'])
        ) {
            $userId = $request['user_id'];

            update_field('habilities_soft', $request['soft'], 'user_' . $userId);
            update_field('habilities_hard', $request['hard'], 'user_' . $userId);

            return new WP_REST_Response((object)[
                'message'   => 'Profile habilities updated!!',
                'status'    => true
            ], 200);
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 403 ) );
        }
    }

    public function renderCertificate($request) {
        $defaultConfig  = (new Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs       = $defaultConfig['fontDir'];

        $defaultFontConfig  = (new Mpdf\Config\FontVariables())->getDefaults();
        $fontData           = $defaultFontConfig['fontdata'];

        $quotationPDF   = new \Mpdf\Mpdf([
            'mode'          => 'utf-8',
            'margin_top'    => 0,
            'margin_bottom' => 0,
            'margin_left'   => 0,
            'margin_right'  => 0,
            'format'        => [190, 236],
            'orientation'   => 'L',

            'fontDir' => array_merge($fontDirs, [
                __DIR__ . '/../assets/fonts',
            ]),
            'fontdata' => $fontData + [
                'dinround' => [
                    'R' => 'DINRoundPro-Medi.ttf',
                    'B' => 'DINRoundPro-Black.ttf'
                ]
            ],
            'default_font' => 'dinround'
        ]);
        
        $logoMab = get_template_directory_uri() . '/static/images/certificates/logo-mab.png';
        $heartVector = get_template_directory_uri() . '/static/images/certificates/vectors-2.png';
        $firma1 = get_template_directory_uri() . '/static/images/certificates/firma-1.png';
        $firma2 = get_template_directory_uri() . '/static/images/certificates/firma-2.png';


        $document = '
            <html lang="en">
                <head>
                    <style>
                        body {
                            font-family: dinround, sans-serif;
                        }

                        table {
                            font-family: dinround, sans-serif;
                            border-collapse: collapse;
                            width: 100%;
                            height: 100%;
                            background: #fff;
                        }
                
                        td, th {
                            border: 0
                            text-align: left;
                            padding: 8px;
                        }
                    </style>
                </head>
                <body>
                    <table>
                        <tr>
                            <td style="padding:4rem 3rem 10px" colspan="4" align="center">
                                <table style="width:100%"> 
                                    <tr>
                                        <td style="width:60%; text-align:left">
                                            <br>
                                            <div style="margin-bottom:1rem">Certificado del curso</div> 
                                            <br>
                                            <h1 style="font-size:28px; text-tranform: uppercase">CIENCIAS CREATIVAS PARA LA VIDA</h1>
                                            <br>
                                            <div style=""> El presente diploma va dirigido a: </div>
                                        </td>
                                        <td style="width:40%; text-align: center;">
                                            <div style="width: 40%;"> 
                                                <img style="margin-bottom:0.5rem" src="'. $heartVector .'">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="background:#0166d0; width:30%; padding:0 2.5rem 0" rowspan="10" align="center">
                                <img style="margin-bottom:1rem; width: 15%" src="'. $logoMab .'">
                                <br>
                                <div style="color:#fff; font-size:18px">
                                    <strong> Felicitaciones por atreverte a aprender distinto y complementar tu aprendizaje con educación para CRECER</strong>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td  style="color: #0166d0; margin-top: 4rem; padding: 0 3rem 0.5rem; width:70%" align="center">
                                <br>
                                <br>
                                <h1>ANGELA RAMOS DURAND</h1>
                            </td>
                        </tr>
                        <tr>
                            <td style=" padding: 0rem 3rem 10px; text-align:center">
                                <div>Certificado de aprobación online </div>
                            </td>
                        </tr>
                        <tr>
                            <td style=" padding: 0 3rem 10px; text-align:center">
                                <div><strong>Aprobado el 25 de Mayo de 2020</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <td style=" padding: 0 3rem 10px; text-align:center">
                                <div>40 horas de teoría y práctica</div>
                            </td>
                        </tr>
                        <tr >
                            <td colspan="4" align="center" style="padding:3rem 4rem 5rem">
                                <table style="width:100%">
                                    <tr>
                                        <td style="width:50%; text-align: center">
                                            <img style="height: 60px; border-bottom:2px solid #000;margin-bottom:0.5rem" src="'. $firma2 .'">
                                            <div style="padding:10px 0 0; text-align: center;">
                                                Macarena Arribas
                                            </div>
                                            <div style="text-align: center;">
                                                Ceo & Fundadora
                                            </div>
                                        </td>
                                        <td style="width:50%; text-align: center;">
                                            <img style="height: 60px; border-bottom:2px solid #000;margin-bottom:0.5rem" src="'. $firma1 .'">
                                            <div style="padding:10px 0 0; text-align: center;">
                                                Maria Fernanda Cabrera
                                            </div>
                                            <div style="text-align: center;">
                                                Gerente General
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="padding:10rem 0rem 3rem;">
                                        <td style="text-align: left; font-size:12px">
                                            <br>
                                            <br>
                                            <a href="http://aprendemab.com " target="_blank" style="color:#000; text-decoration: none">http://aprendemab.com </a>
                                        </td>
                                        <td style="text-align: right; font-size:12px">
                                            <br>
                                            <br>
                                            <span>Código 1d8d8746547-4D-d644565455</span>
                                        </td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table>
                </body>
            </html>
        ';

        $quotationPDF->SetTitle("Certificado - Cleiver Valera Flores");
        $quotationPDF->WriteHTML($document);
        $quotationPDF->Output();
    }

    public function renderCertificateTemp($request) {
        if (
            !empty($request['course'])
        ) {
            $quotationPDF   = new \Mpdf\Mpdf([
                'mode'          => 'utf-8',
                'margin_top'    => 0,
                'margin_bottom' => 0,
                'margin_left'   => 0,
                'margin_right'  => 0,
                'format'        => [190, 236],
                'orientation'   => 'L'
            ]);

            $courseId   = $request['course'];
            $userId     = $request['user_id'];

            $userFullname = get_user_meta( $userId, 'first_name', true ) . ' ' . get_user_meta( $userId, 'last_name', true );
            $userFullname = count( explode('-panda-', $userFullname) ) ? str_replace(['-panda-'], ' ', $userFullname) : $userFullname;

            $course     = Timber::get_post(['post_type' => 'course', 'p' => $courseId]);
            $userCertificate = UserCertificate::where([
                'user_id'   => $userId,
                'course_id' => $courseId
            ])->first();

            if ($userCertificate) {
                $logoMab        = get_template_directory_uri() . '/static/images/certificates/logo-mab.png';
                $heartVector    = get_template_directory_uri() . '/static/images/certificates/vectors.png';
                $firma1         = get_template_directory_uri() . '/static/images/certificates/firma-1.png';
                $firma2         = get_template_directory_uri() . '/static/images/certificates/firma-2.png';

                $date = sprintf('%s de %s de %s',
                    date("d", strtotime($userCertificate->created_at)),
                    array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre")[date("m", strtotime($userCertificate->created_at)) - 1],
                    date("Y", strtotime($userCertificate->created_at)),
                );

                $document = '
                    <html lang="en">
                        <head>
                            <style>
                                body {
                                    font-family: sans-serif;
                                }

                                table {
                                    font-family: arial, sans-serif;
                                    border-collapse: collapse;
                                    width: 100%;
                                    height: 100%;
                                    background: #fff;
                                }

                                td, th {
                                    border: 0
                                    text-align: left;
                                    padding: 8px;
                                }
                            </style>
                        </head>
                        <body>
                        <table>
                            <tr>
                                <td rowspan="12" style="background:#0166d0; width:25%; text-align:center">
                                    <img src="'. $logoMab .'">
                                </td>
                            </tr>
                            <tr>
                                <td style=" padding: 6rem 4rem 10px; font-size: 18px">
                                    <div>Certificado de</div>
                                </td>
                            </tr>
                            <tr>
                                <td style=" padding: 0 4rem 10px;">
                                    <h2>'. strtoupper($course->title) .'</h2>
                                </td>
                            </tr>
                            <tr>
                                <td style=" padding: 0 4rem 10px;">
                                    <hr style="border-color: #000">
                                </td>
                            </tr>
                            <tr>
                                <td style=" padding: 0 4rem 20px; font-size: 18px">
                                    <div>El presente diploma se otorga a </div>
                                </td>
                            </tr>
                            
                            <tr>
                                <td style=" padding: 0 4rem 2rem;">
                                    <h1>'. strtoupper($userFullname) .'</h1>
                                </td>
                            </tr>
                            <tr>
                                <td style=" padding: 0rem 4rem 10px; text-align:center">
                                    <div>Certificado de aprobación online </div>
                                </td>
                            </tr>
                            <tr>
                                <td style=" padding: 0 4rem 10px; text-align:center">
                                    <div style="font-size:21px"><strong>Aprobado el '. $date .'</strong></div>
                                </td>
                            </tr>
                            <tr>
                                <td style=" padding: 0 4rem 10px; text-align:center">
                                    <div><strong>'. __getMetaCourse($courseId, -1, 'duration') .' horas de teoría y práctica</strong></div>
                                </td>
                            </tr>
                            <tr>
                                <td style=" padding: 0 4rem 10px; text-align:center">
                                    <div> <a href="" style="text-decoration: none" target="_blank"> <strong style="color:#000">https://mabclick.com/@Angela/</a></strong></div>
                                </td>
                            </tr>
                            <tr>
                                <td style=" padding: 0 4rem 2rem; text-align:center">
                                    <div>Código '. $userCertificate->signature .'</div>
                                </td>
                            </tr>
                            
                            <tr>
                                <td style="padding: 0rem 4rem 5rem">
                                    <table style="width:100%">
                                        <tr>
                                            <td style="text-align: center">
                                                <div style="width: 400px; border-bottom:2px solid #000;"> 
                                                    <img style="margin-bottom:0.5rem" src="'. $firma1 .'">
                                                </div>
                                                <div style="padding:10px 0 0; text-align: center;">
                                                    Macarena R. 
                                                </div>
                                            </td>
                                            <td style="text-align: center">
                                                <img src="'. $heartVector .'">
                                            </td>
                                            <td style="text-align: center">
                                                <div style="width: 400px; border-bottom:2px solid #000;"> 
                                                    <img style="margin-bottom:0.5rem" src="'. $firma2 .'">
                                                </div>
                                                <div style="padding:10px 0 0; text-align: center;">
                                                    Macarena R. 
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        </body>
                    </html>
                ';

                $quotationPDF->SetTitle("Certificado - " . $userFullname);
                $quotationPDF->WriteHTML($document);
                $quotationPDF->Output("Certificado - " . $userFullname . '.pdf', 'D');
            } else {
                # code...
            }
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 403 ) );
        }
    }

    private function sendInstructions($request, $recovery_session, $user_id){
        $username = get_user_by('id', $user_id)->data->user_login;
        $mail = new PHPMailer(true);
    
        try {
            //Server settings
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
            $mail->addAddress($request['user']);
    
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
                                    <img src="https://mabclick.com/wp-content/themes/mab-theme/app/static/images/logo.png" style="width: 80px;">
                                </header>
    
                                <div style="padding: 1rem;">
                                    <h1 style="margin-bottom: 3rem; color: white; font-size: 18px; font-weight: 700; font-family: Verdana, serif;">
                                        Hola, '. $username .'
                                    </h1>
                                    <div style="text-align: center; margin-bottom: 2rem;">
                                        <p style="font-family: Verdana, serif; margin-bottom: 2rem; color: white">
                                            Recibimos una solicitud para cambiar la contraseña de su cuenta de MABCLICK.<br>
                                            Si no realizó esta solicitud, simplemente ignore este correo electrónico. De lo contrario, haga click en el botón a continuación para cambiar su contraseña:                                        
                                        </p>
                                        <p style="font-family: Verdana, serif; margin-bottom: 3rem">
                                            <a href="'. get_site_url() .'/recuperar-contrasena?stage=2&session_id='. $recovery_session .'" style="text-decoration: none; padding: 1rem; color: white; background: #DE0D46">CAMBIAR CONTRASEÑA</a>
                                        </p>
                                        <p style="font-family: Verdana, serif; margin-bottom: 2rem">
                                            También puede cambiar y pegar esta URL en su navegador web: <a href="'. get_site_url() .'/recuperar-contrasena?stage=2&session_id='. $recovery_session .'" style="color: white; font-weight: 700">'. get_site_url() .'/actualizar-contrasena?recuperar-contrasena?stage=2&session_id='. $recovery_session .'</a>                                       
                                        </p>                                        
                                    </div>
                                    <footer style="text-align: center; font-size: 12px; font-family: Verdana, serif; padding: 1rem; color: white;">
                                        All rights reserved - MABLICK
                                    </footer>         
                                </div>
                            </div>
                            </td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                </table>           
            ';
    
            $mail->isHTML(true); 
            $mail->Subject = "Solicitud de cambio de contrasena - MABCLICK";
            $mail->MsgHTML($body);
    
            $mail->send();
    
            return new WP_REST_Response('Message has been send', 200);
        } catch (Exception $e) {
            return new WP_Error( 'Message could not be sent', __($mail->ErrorInfo), array( 'status' => 404 ) );
        }
    }

    private function sendNotification($request){
        $mail = new PHPMailer(true);
    
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
            $mail->addAddress($request['email']);
    
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
                                    <h1 style="margin-bottom: 3rem; color: white; font-size: 18px; font-weight: 700; font-family: Verdana, serif;">
                                        Hola, '. $request["first_name"] .' '. $request["last_name"] .'
                                    </h1>
                                    <div style="text-align: center; margin-bottom: 2rem;">
                                        <p style="font-family: Verdana, serif; margin-bottom: 3rem; color: white">
                                            Te damos la bienvenida a MABCLICK 🤩. Desde hoy eres parte de nuestra comunidad de estudiantes, profesores y padres/tutores.<br><br>
                                            Para iniciar sesión lo puedes hacer desde aquí:                                        
                                        </p>
                                        <p style="font-family: Verdana, serif; margin-bottom: 3rem">
                                            <a href="'. get_site_url() .'/login" style="text-decoration: none; padding: 1rem; color: white; background: #DE0D46">INICIAR SESIÓN</a>
                                        </p>                                  
                                    </div>
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
            $mail->Subject = "Cuenta creada exitosamente ❤️";
            $mail->MsgHTML($body);
    
            $mail->send();
    
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function __saveAvatarFile() {
        $uploadDir = wp_upload_dir(); $i = 1;

        $avatar         = $_FILES['avatar'];
        $avatarFilepath = $uploadDir['path'] . '/' . $avatar['name'];
        $avatarFilemime = mime_content_type( $avatar['tmp_name'] );

        if( $avatar['size'] > wp_max_upload_size() )
            die( 'It is too large than expected.' );

        if( !in_array( $avatarFilemime, get_allowed_mime_types() ) )
            die( 'WordPress doesn\'t allow this type of uploads.' );

        while( file_exists( $avatarFilepath ) ) {
            $i++;
            $avatarFilepath = $uploadDir['path'] . '/' . $i . '_' . $avatar['name'];
        }

        if( move_uploaded_file( $avatar['tmp_name'], $avatarFilepath ) ) {
            $attachmentId = wp_insert_attachment( array(
                'guid'           => $avatarFilepath, 
                'post_mime_type' => $avatarFilemime,
                'post_title'     => preg_replace( '/\.[^.]+$/', '', $avatar['name'] ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            ), $avatarFilepath );

            require_once( ABSPATH . 'wp-admin/includes/image.php' );

            wp_update_attachment_metadata( $attachmentId, wp_generate_attachment_metadata( $attachmentId, $avatarFilepath ) );

            return (object)[
                'id'    => $attachmentId,
                'url'   => wp_get_attachment_url($attachmentId)
            ];
        } else {
            return new WP_Error( 'no_avatar_file_saved', __('No avatar file saved'), array( 'status' => 500 ) );
        }
    }
}
