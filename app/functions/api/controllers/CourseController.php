<?php

require(__DIR__ . '/../models/CourseModel.php');

class CourseController{

    public function __construct(){

    }

    /**
     * Methods
     */
    public function getAll(){
        if( isset($_GET['query']) ){
            $course_args = [
                "post_type" => "course",
                "posts_per_page" => -1,
                'orderby' => 'post_date',
                "order" => "ASC",
                's' => $_GET['query']
            ];
    
            $courses = get_posts($course_args);
            
            if(empty($courses)){
                return new WP_Error( 'no_courses', __('No courses found'), array( 'status' => 404 ) );
            }else{
                return new WP_REST_Response($courses, 200);
            }            
        }else {
            return new WP_Error( 'no_courses', __('No courses found'), array( 'status' => 404 ) );
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

    public function registrationCheckout($request){
        if( CourseModel::registrationCheckout($request) ){
            return new WP_REST_Response('ok', 200);
        }else{
            return new WP_Error( 'no_access_course', __("No access course"), array( 'status' => 404 ) );
        }
    }
}
