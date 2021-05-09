<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Timber\Timber;

require(__DIR__ . '/../models/UserModel.php');
require(__DIR__ . '/../models/schema/UserCourse.php');
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
                    // return ($request['_wpnonce']) ? true : false;
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

            $courses = UserCourse::where(['user_email' => $userEmail])
                ->orderBy('last_date', 'DESC')
                ->get();

            $enrolledCourses = UserCourseEnrollment::where(['user_email' => $userEmail])
                ->orderBy('last_date', 'DESC')
                ->get();

            foreach ($courses as $course) {
                $sanitizedCourse = $this::__sanitizeCourse($course->course_id, $userEmail, $userID);

                if ( $sanitizedCourse && !in_array($sanitizedCourse, $coursesArray) )
                    array_push($coursesArray, $sanitizedCourse);
            }

            foreach ($enrolledCourses as $course) {
                $sanitizedCourse = $this::__sanitizeCourse($course->course_id, $userEmail, $userID);

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
                $sanitizedCourse = $this::__sanitizeCourse($course->ID, $userEmail, $userId, 'recommend');

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

    private function __sanitizeCourse($courseId, $userEmail, $userID, $type = 'enrolled') {
        $course = Timber::get_post([
            'post_type' => 'course',
            'p'         => $courseId
        ]);

        if ($course) {
            $mabCategory = array_filter($course->terms, function($cat){ return $cat->taxonomy == 'tax-mab-course' && !$cat->parent; });
            $mabCategory = count($mabCategory) ? array_values($mabCategory)[0] : false;

            $teacher = get_field('teacher', $course->ID);

            $courseObject = [
                'id'            => $course->ID,
                'name'          => $course->title,
                'thumbnail'     => ($course->thumbnail) ? $course->thumbnail->src : false,
                'teacher'       => ($teacher) ? sprintf('%s %s', $teacher['user_firstname'], $teacher['user_lastname']) : '',
                'description'   => get_the_excerpt($course->ID),
                'likes'         => $this::__getMetaCourse($courseId, $userEmail, 'likes'),
                'color'         => ($mabCategory) ? get_field('color', 'category_' . $mabCategory->term_id) : 'primary',
                'link'          => get_the_permalink($course->ID)
            ];

            if ($type == 'recommend') {
                $sell = get_field('sell', 'options');
                $priceSettings = get_field('price_settings', $courseId);

                $courseObject = array_merge($courseObject, [
                    'price' => ($priceSettings == 'global') ? floatval( $sell['course_price'] ) : floatval( get_field('price', $courseId) )
                ]);
            } else {
                $courseObject = array_merge($courseObject, [
                    'last_class'    => $this::__getLastTopic($courseId, $userEmail, $userID),
                    'progress'      => $this::__getMetaCourse($courseId, $userEmail, 'progress'),
                ]);
            }

            return $courseObject;
        } else {
            return false;
        }
    }

    private function __getMetaCourse($courseId, $userEmail, $meta) {
        switch ($meta) {
            case 'likes':
                $likes      = 0;
                $unities    = get_field('unities', $courseId);

                if ( $unities && count($unities) ) {
                    foreach($unities as $unity){
                        foreach($unity['topics'] as $topic) {
                            if ($topic['topic']) {
                                $likes += floatval( get_post_meta($topic['topic']->ID, 'post_likes_count') );
                            }
                        }
                    }
                }

                return $likes;

                break;

            case 'progress':
                $tests = TopicTestScore::where(['user' => $userEmail, 'course_id' => $courseId])
                    ->get();

                $topics     = 0;
                $unities    = get_field('unities', $courseId);

                if ( $unities && count($unities) ) {
                    foreach($unities as $unity) {
                        $topics += count($unity['topics']);
                    }
                }

                return ($topics) ? bcdiv( (count($tests) * 100), $topics, 2 ) : 0;

                break;
        }
    }

    private function __getLastTopic($courseId, $userEmail, $userID) {
        $unities    = get_field('unities', $courseId);
        $topics     = [];
        $unityIndex = 1;
        $topicIndex = 0;
        $lastClass  = '';
        $firstClass = '';

        if ( $unities && count($unities) ) {
            foreach($unities as $unity){
                foreach($unity['topics'] as $topic) {
                    array_push(
                        $topics,
                        [
                            'unity' => $unityIndex,
                            'title' => ($topic['topic']) ? $topic['topic']->post_title : '',
                            'id'    => ($topic['topic']) ? $topic['topic']->ID : 0,
                            'link'  => ($topic['topic']) ? get_the_permalink($topic['topic']->ID) : '',
                        ]
                    );
                }
            }

            $unityIndex++;
        }

        if ( count($topics) ) {
            $firstClass = $topics[0];
            $topics     = array_reverse($topics);

            foreach($topics as $topic) {
                $userTopic = UserTopic::where(['topic_id' => $topic['id']])
                    ->first();
    
                if ($userTopic && $userTopic->video_viewed) {
                    $lastClass = ($topics[$topicIndex++]) ? $topics[$topicIndex++] : $topic;
                }
    
                if ($lastClass) break;
    
                $topicIndex++;
            }

            $lastClass = ($lastClass) ? $lastClass : $firstClass;

            if ($lastClass) {
                $topic = Timber::get_post([
                    'post_type' => 'topic',
                    'p'         => $lastClass['id']
                ]);

                $userSector = get_field('school_type', 'user_' . $userID);

                $lastClass['link'] = sprintf(
                    '%s?course_id=%s&course_name=%s&course_slug=%s&unity=%s&sector=%s',
                    $lastClass['link'],
                    $courseId,
                    $topic->title,
                    $topic->slug,
                    $lastClass['unity'],
                    ($userSector) ? $userSector : 'publico'
                );

                return $lastClass;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}
