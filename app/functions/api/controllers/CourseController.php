<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Timber\Timber;

require(__DIR__ . '/../models/CourseModel.php');

class CourseController{
    public function __construct(){
        add_action( 'rest_api_init', function () {
            register_rest_route('custom/v1', '/course/categories', array(
                'methods' => 'GET',
                'callback' => array($this,'getCategories'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/courses/mab_categories', array(
                'methods' => 'GET',
                'callback' => array($this,'getMabCategories'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));
    
            register_rest_route('custom/v1', '/courses/mab_subcategories', array(
                'methods' => 'GET',
                'callback' => array($this,'getMabCategories'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));
    
            register_rest_route('custom/v1', '/course/(?P<course_id>\d+)/unities', array(
                'methods' => 'GET',
                'callback' => array($this,'getUnities'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/courses', array(
                'methods' => 'GET',
                'callback' => array($this,'getAll'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/courses/progress', array(
                'methods' => 'GET',
                'callback' => array($this,'getProgress'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/course/(?P<course_id>\d+)/registration/checkout', array(
                'methods' => 'GET',
                'callback' => array($this,'registrationCheckout'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/courses/expired_registrations', array(
                'methods' => 'GET',
                'callback' => array($this,'getExpiredRegistrations'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/courses/expired_registrations/download', array(
                'methods' => 'GET',
                'callback' => array($this,'downloadExpiredRegistrations'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/courses/user/logs', array(
                'methods' => 'GET',
                'callback' => array($this,'getUserCourseLogs'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/courses/user/logs/download', array(
                'methods' => 'GET',
                'callback' => array($this,'downloadUserCourseLogs'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/course/request', array(
                'methods' => 'POST',
                'callback' => array($this,'sendCourseRequest'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/courses/buy/log', array(
                'methods' => 'POST',
                'callback' => array($this,'saveBuyRequest'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/courses/buy/checkout', array(
                'methods' => 'GET',
                'callback' => array($this,'checkoutBuyRequests'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/courses/enrollments', array(
                'methods' => 'GET',
                'callback' => array($this,'getEnrollments'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/courses/enrollments/expired/download', array(
                'methods' => 'GET',
                'callback' => array($this,'downloadExpiredEnrollments'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
    
            register_rest_route('custom/v1', '/courses/export', array(
                'methods' => 'GET',
                'callback' => array($this,'export'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));
        });
    }

    /**
     * Methods
     */
    public function getCategories($request){
        $categories = CourseModel::getCategories($request['course_id']);

        if(empty($categories)){
            return new WP_Error( 'no_course_categories', __('No course categories found'), array( 'status' => 404 ) );
        }else{
            return new WP_REST_Response($categories, 200);
        }  
    }

    public function getMabCategories($request) {
        $parentCategories = (!empty($request['categories'])) ? $request['categories'] : 0;
        $parentCategories = ($parentCategories) ? explode(',', $parentCategories) : 0;

        $categories = [];

        if ($parentCategories)
            foreach($parentCategories as $pCategory) {
                $categories = array_merge(
                    $categories,
                    Timber::get_terms([
                        'parent'    => $pCategory,
                        'taxonomy'  => 'tax-mab-course'
                    ])
                );
            }
        else
            $categories = Timber::get_terms([
                'parent'    => 0,
                'taxonomy'  => 'tax-mab-course'
            ]);

        if ( count($categories) ) {
            $categories = array_map( function($category) use ($parentCategories) {
                return array_merge(
                    [ 'name' => $category->name, 'id' => $category->term_id ],
                    ( !$parentCategories ) ? [ 'thumbnail' => get_field('image', 'category_' . $category->term_id) ] : [],
                    ( !$parentCategories ) ? [ 'color'     => get_field('color', 'category_' . $category->term_id) ] : []
                );
            }, $categories);

            return new WP_REST_Response((object)[
                'message'   => 'Categories heres!!',
                'data'      => $categories,
                'status'    => true
            ], 200);
        } else {
            return new WP_REST_Response((object)[
                'message'   => 'No categories found!!',
                'status'    => false
            ], 200);
        }
    }

    public function getAll($request){
        $courses = CourseModel::getAll($request);
        
        if(empty($courses)){
            return new WP_Error( 'no_courses', __('No courses found'), array( 'status' => 404 ) );
        }else{
            return new WP_REST_Response($courses, 200);
        }  
    }

    public function getUnities($request){
        $unities = CourseModel::getUnities($request);
        
        if($unities){
            return new WP_REST_Response($unities, 200);
        }else{
            return new WP_Error( 'no_unities', __('No unities found'), array( 'status' => 404 ) );
        }  
    }

    public function getProgress($request){
        $progess = CourseModel::getProgress($request);

        if( empty($progess) ){
            return new WP_Error( 'no_progess', __("No progess found"), array( 'status' => 404 ) );
        }else{
            return new WP_REST_Response($progess, 200);
        }
    }

    public function sendCourseRequest($request){
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
            $mail->addAddress(get_field('email', 'options'));
    
            // Content
            $body = '
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background: #DE0D46; padding: 3rem 0">
                    <tr>
                        <td width="100%" align="center">
                        <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                            <td width="600" align="center">
                                <div style="background: white; width: 70%; min-width: 310px;">
                                    <header style="background: #FF3334; padding: 1rem; color: #fff;">
                                        <h2 style="text-align: center;">MAB</h2>
                                    </header>

                                    <div style="padding: 1rem;">
                                        <h1 style="font-size: 25px; color: #222;">MAB - Solicitar Cursos</h1>

                                        <table style="width: 100%; padding-left: 1.5rem">          
                                            <tbody style="width: 100%">
                                                <tr>
                                                    <td style="padding: 10px 0; width: 30%; font-weight: bold; color: #222; font-weight:bold">Nombres y apellidos: </td>
                                                    <td style="padding: 10px 0; color: #575757;">'. $request["fullname"] .'</td>     
                                                </tr>
                                                <tr>
                                                    <td style="padding: 10px 0; width: 30%; font-weight: bold; color: #222; font-weight:bold">Ocupación: </td>
                                                    <td style="padding: 10px 0; color: #575757;">'. $request["ocupation"] .'</td>     
                                                </tr>
                                                <tr>
                                                    <td style="padding: 10px 0; width: 30%; font-weight: bold; color: #222; font-weight:bold">Correo: </td>
                                                    <td style="padding: 10px 0; color: #575757;">'. $request["email"] .'</td>     
                                                </tr> 
                                                <tr>
                                                    <td style="padding: 10px 0; width: 30%; font-weight: bold; color: #222; font-weight:bold">Celular: </td>
                                                    <td style="padding: 10px 0; color: #575757;">'. $request["mobile"] .'</td>     
                                                </tr> 
                                                <tr>
                                                    <td style="padding: 10px 0; width: 30%; font-weight: bold; color: #222; font-weight:bold">Curso de interés: </td>
                                                    <td style="padding: 10px 0; color: #575757;">'. $request["course"] .'</td>     
                                                </tr> 
                                            </tbody>
                                        </table>
                                    </div>

                                    <footer style="text-align: center; background: #FF3334; padding: 1rem; color: white;">
                                        All rights reserved | MAB
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
            $mail->Subject = "Solicitud de curso";
            $mail->MsgHTML($body);
    
            $mail->send();
    
            return new WP_REST_Response('Message has been send', 200);
        } catch (Exception $e) {
            return new WP_Error( 'Message could not be sent', __($mail->ErrorInfo), array( 'status' => 404 ) );
        }
    }

    public function registrationCheckout($request){
        if( CourseModel::registrationCheckout($request) ){
            return new WP_REST_Response('ok', 200);
        }else{
            return new WP_Error( 'no_access_course', __("No access course"), array( 'status' => 404 ) );
        }
    }

    public function getExpiredRegistrations($request){
        $expired_registrations = CourseModel::getExpiredRegistrations($request);

        if( empty($expired_registrations) ){
            return new WP_Error( 'no_access_course', __("No access course"), array( 'status' => 404 ) );
        }else{
            return new WP_REST_Response($expired_registrations, 200);
        }        
    }

    public function downloadExpiredRegistrations($request){
        $expired_registrations = CourseModel::getExpiredRegistrations($request);

        if( empty($expired_registrations) ){                
            return new WP_Error( 'no_expired_registrations', __('No expired registrations'), array( 'status' => 404 ) );         
        }else{
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/xls; charset=UTF-8");    
            header("Content-Disposition: attachment; filename=inscripciones-expiradas-mabclick-".date('Y-m-d').".xls"); 
            echo "\xEF\xBB\xBF";               

            //Header
            include_once __DIR__."/../exports/registration.php";            
        }
    }

    public function saveBuyRequest($request){
        if (CourseModel::saveBuyRequest($request)) {
            return new WP_REST_Response('Courses buy request saved', 200);
        } else {
            return new WP_Error( 'no_saved_buy_courses_request', __("No courses buy request saved"), array( 'status' => 404 ) );
        }
        
    }

    public function checkoutBuyRequests($request){
        $buy_requests = CourseModel::getBuyRequests($request, 'APPROVED');

        if ($buy_requests) {
            return new WP_REST_Response('Courses granted', 200);
        } else {
            return new WP_Error( 'no_saved_courses_granted', __("No courses granted saved"), array( 'status' => 404 ) );
        }
    }

    public function getEnrollments($request){
        $enrollments = CourseModel::getEnrollments($request);

        if ($enrollments) {
            return new WP_REST_Response((object)[
                "data" => $enrollments,
                "expired" => count(CourseModel::getEnrollments($request, 'expired'))
            ], 200);
        } else {
            return new WP_Error( 'no_enrollments_found', __("No enrollments found"), array( 'status' => 404 ) );
        }
        
    }

    public function downloadExpiredEnrollments($request){
        $expired_enrollments = CourseModel::getEnrollments($request, 'expired');

        if( empty($expired_enrollments) ){                
            return new WP_Error( 'no_expired_enrollments', __('No expired enrollments'), array( 'status' => 404 ) );         
        }else{
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/xls; charset=UTF-8");    
            header("Content-Disposition: attachment; filename=resportes-matriculas-expiradas-mabclick-".date('Y-m-d').".xls"); 
            echo "\xEF\xBB\xBF";

            //Header
            include_once __DIR__."/../exports/reports/enrollments.php";
        }        
    }

    public function export($request) {
        $courses = CourseModel::getAllSanitize($request);
        
        if(empty($courses)){
            return new WP_Error( 'no_courses', __('No courses found'), array( 'status' => 404 ) );
        }else{
            return new WP_REST_Response($courses, 200);
        }
    }

    //Logs-----------------------------------------------/
    public function getUserCourseLogs($request){
        $user_course_logs = CourseModel::getUserCourseLogs($request);

        if ( empty($user_course_logs) ) {
            return new WP_Error( 'no_topic_user_course_logs', __('No topic user_course logs'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($user_course_logs, 200);
        }        
    }

    public function downloadUserCourseLogs($request){
        $user_course_logs = CourseModel::getUserCourseLogs($request, 'all');

        if( empty($user_course_logs) ){
            return new WP_Error( 'no_user_course_logs', __('No user couser logs'), array( 'status' => 404 ) );         
        }else{
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/xls; charset=UTF-8");    
            header("Content-Disposition: attachment; filename=resportes-curso-usuario-mabclick-" . date('Y-m-d') . ".xls"); 
            echo "\xEF\xBB\xBF";

            //Header
            include_once __DIR__."/../exports/reports/user-courses.php";
        }        
    }
}
