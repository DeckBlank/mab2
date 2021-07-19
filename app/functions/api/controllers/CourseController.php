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

            register_rest_route('custom/v1', '/courses/(?P<course_id>\d+)/unities/(?P<unity>\d+)', array(
                'methods' => 'GET',
                'callback' => array($this,'showUnity'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));

            register_rest_route('custom/v1', '/courses', array(
                'methods' => 'GET',
                'callback' => array($this,'getAll'),
                'permission_callback' => function ($request) {
                    return true;
                }
            ));

            register_rest_route('custom/v1', '/courses/all', array(
                'methods' => 'GET',
                'callback' => array($this,'index'),
                'permission_callback' => function ($request) {
                    // return ($request['_wpnonce']) ? true : false;
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

            register_rest_route('custom/v1', '/courses/(?P<course_id>\d+)/progress', array(
                'methods' => 'GET',
                'callback' => array($this,'showCourseProgress'),
                'permission_callback' => function ($request) {
                    // return ($request['_wpnonce']) ? true : false;
                    return true;
                }
            ));

            register_rest_route('custom/v1', '/courses/(?P<course_id>\d+)/progress/notification', array(
                'methods' => 'POST',
                'callback' => array($this,'storeCourseProgressNotification'),
                'permission_callback' => function ($request) {
                    // return ($request['_wpnonce']) ? true : false;
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
    public function index($request) {
        $category   = $request['category'];
        $level      = $request['level'];
        $grade      = $request['grade'];
        $search     = $request['search'];
        $paged      = ( !empty($request['paged']) ) ? $request['paged'] : 1;
        $userEmail  = ( !empty($request['user_email']) ) ? $request['user_email'] : 0;

        $coursesFinal   = [];
        $hasPagination  = false;

        if ( !empty($level) || !empty($grade) || !empty($search) ) {
            if ( !empty($level) || !empty($grade) ) {
                $courses        = $this::__getCourseFromPrimaryMenu($level, $grade);
                $coursesArray   = [];

                if ( !empty($search) ) {
                    if ($category) {
                        foreach ($courses as $course) {
                            $__course = Timber::get_post([
                                'post_type' => 'course',
                                'p'         => $course->object_id
                            ]);
                            $categories = array_map(function($__course) { return $__course->term_id; }, $__course->terms);

                            if ( in_array($category, $categories) ) array_push($coursesArray, $course);
                        }
                    } else {
                        $coursesArray = $courses;
                    }

                    foreach ($coursesArray as $course) {
                        if ( stripos($course->name, $search) !== false ) {
                            array_push( $coursesFinal, __sanitizeCourse($course->object_id, $userEmail, '-1', 'general') );
                        }
                    }
                } else {

                    if ($category) {
                        foreach ($courses as $course) {
                            $__course = Timber::get_post([
                                'post_type' => 'course',
                                'p'         => $course->object_id
                            ]);
                            $categories = array_map(function($__course) { return $__course->term_id; }, $__course->terms);

                            if ( in_array($category, $categories) ) array_push($coursesArray, $course);
                        }
                    } else {
                        $coursesArray = $courses;
                    }

                    foreach($coursesArray as $course) {
                        array_push($coursesFinal, __sanitizeCourse($course->object_id, $userEmail, '-1', 'general'));
                    }
                }
            } else if ( !empty($search) ) {
                $courses = Timber::get_posts(array_merge(
                    [
                        'post_type'         => 'course',
                        'posts_per_page'    => 12,
                        'paged'             => $paged,
                        's'                 => $search
                    ],
                    ($category)
                        ? [
                            'tax_query' => array(
                                array(
                                    'taxonomy'  => 'tax-mab-course',
                                    'field'     => 'id',
                                    'terms'     => [$category],
                                )
                            )
                        ]
                        : []
                ));

                foreach($courses as $course) {
                    array_push($coursesFinal, __sanitizeCourse($course->ID, $userEmail, '-1', 'general'));
                }

                $hasPagination = ( count($coursesFinal) ) ? true : false;
            }
        } else {
            $courses = Timber::get_posts(array_merge(
                [
                    'post_type'         => 'course',
                    'posts_per_page'    => 12,
                    'paged'             => $paged
                ]
                ,
                ($category)
                    ? [
                        'tax_query' => array(
                            array(
                                'taxonomy'  => 'tax-mab-course',
                                'field'     => 'id',
                                'terms'     => [$category],
                            )
                        )
                    ]
                    : []
            ));

            foreach($courses as $course) {
                array_push($coursesFinal, __sanitizeCourse($course->ID, $userEmail, '-1', 'general'));
            }

            $hasPagination = ( count($coursesFinal) ) ? true : false;
        }

        if ( count($coursesFinal) ) {
            return new WP_REST_Response((object)[
                'message'       => 'Courses heres!!',
                'data'          => $coursesFinal,
                'pagination'    => $hasPagination,
                'status'        => true
            ], 200);
        } else {
            return new WP_REST_Response((object)[
                'status'    => false
            ], 200);
        }
    }

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
                    [ 'name' => $category->name, 'id' => $category->term_id, 'subcategories' => $this::__getSubcategories($category->term_id) ],
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
            return new WP_REST_Response([
                'data'      => $unities,
                'lastClass' => __getLastTopic($request['course_id'], $request['user'], $request['user_id'], null)
            ], 200);
        }else{
            return new WP_Error( 'no_unities', __('No unities found'), array( 'status' => 404 ) );
        }  
    }

    public function showUnity($request){
        $courseId   = $request['course_id'];
        $unity      = $request['unity'];
        $data       = [
            'course' => '',
            'unity'  => '',
        ];

        $course = Timber::get_post([
            'post_type' => 'course',
            'p'         => $courseId
        ]);

        if ($course)
            $data['course'] = [
                'title' => $course->title,
                'slug'  => $course->slug,
            ];

        $data['unity'] = get_field('unities', $course->ID)[$unity - 1];
        $data['unity'] = ($data['unity']) ? $data['unity']['title'] : '';


        if ( $data['course'] && $data['unity'] ) {
            return new WP_REST_Response((object)[
                'message'       => 'Courses heres!!',
                'data'          => $data,
                'status'        => true
            ], 200);
        } else {
            return new WP_REST_Response((object)[
                'status'    => false
            ], 200);
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

    public function showCourseProgress($request) {
        if (
            !empty($request['user_email'])
        ) {
            $userEmail  = $request['user_email'];
            $courseId   = $request['course_id'];

            $user = get_user_by('email', $userEmail);

            $courseProgress = __getUserCourseProgress($user->ID, $userEmail, $courseId);

            if ($courseProgress) {
                return new WP_REST_Response((object)[
                    'message'       => 'Course progress here!!',
                    'data'          => $courseProgress,
                    'status'    => true
                ], 200);
            } else {
                return new WP_REST_Response((object)[
                    'message'   => 'No course progress!!',
                    'status'    => false
                ], 200);
            }
        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 403 ) );
        }
    }

    public function storeCourseProgressNotification($request) {
        if (
            !empty($request['user_email'])
        ) {
            $userEmail  = $request['user_email'];
            $courseId   = $request['course_id'];

            $user = get_user_by('email', $userEmail);

            $userCertificate = UserCertificate::where([
                'user_id'   => $user->ID,
                'course_id' => $courseId
            ])->first();

            if ($userCertificate) {
                UserCertificate::where([
                        'user_id'   => $user->ID,
                        'course_id' => $courseId
                    ])
                    ->update([
                        'notification' => 1,
                    ]);

                if ($userCertificate) {
                    return new WP_REST_Response((object)[
                        'message'   => 'Notification saved!!',
                        'status'    => true
                    ], 200);
                } else {
                    return new WP_REST_Response((object)[
                        'message'   => 'No notification saved!!',
                        'status'    => false
                    ], 200);
                }
            } else {
                return new WP_REST_Response((object)[
                    'message'   => 'No user course found!!',
                    'status'    => false
                ], 200);
            }

        } else {
            return new WP_Error( 'invalid_params', __('Invalid params'), array( 'status' => 403 ) );
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

    private function __getCourseFromPrimaryMenu($level, $grade) {
        $sector = (new \Timber\Menu( 'primary-menu' ))->items[1]; /* Sector privado */

        if ($level && $grade) {
            $levelIndex = array_search($level, array_column($sector->children, 'name'));
            $level      = $sector->children[$levelIndex];

            $gradeIndex = array_search($grade, array_column($level->children, 'name'));
            $grade      = $level->children[$gradeIndex];
            $courses    = [];

            foreach($grade->children as $course) {
                array_push($courses, $course);
            }

            return $courses;
        } else if ($level) {
            $levelIndex = array_search($level, array_column($sector->children, 'name'));
            $level      = $sector->children[$levelIndex];
            $courses    = [];

            foreach($level->children as $grade) {
                $courses = array_merge($courses, $grade->children);
            }

            return $courses;
        }
    }

    private function __getSubcategories($categoryId) {
        $subcategories = Timber::get_terms([
            'parent'    => $categoryId,
            'taxonomy'  => 'tax-mab-course'
        ]);

        $subcategories = array_map( function($subcategory) {
            return array_merge(
                [ 'name' => $subcategory->name, 'id' => $subcategory->term_id ]
            );
        }, $subcategories);

        return $subcategories;
    }
}
