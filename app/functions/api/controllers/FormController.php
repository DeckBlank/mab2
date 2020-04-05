<?php

require(__DIR__ . '/../models/FormModel.php');

class FormController{

    public function __construct(){

    }

    /**
     * Methods
     */
    public function addTutorForm($request){
        if(FormModel::addTutorForm($request)){
            return new WP_REST_Response('Tutor form saved', 200);            
        }else{
            return new WP_Error( 'no_saved_tutor_form', __("No saved tutor form"), array( 'status' => 404 ) );
        }
    }

    public function addStudentForm($request){
        if(FormModel::addStudentForm($request)){
            return new WP_REST_Response('Student form saved', 200);            
        }else{
            return new WP_Error( 'no_saved_student_form', __("No saved student form"), array( 'status' => 404 ) );
        }
    }

    public function addTeacherForm($request){
        if(FormModel::addTeacherForm($request)){
            return new WP_REST_Response('Teacher form saved', 200);            
        }else{
            return new WP_Error( 'no_saved_teacher_form', __("No saved teacher form"), array( 'status' => 404 ) );
        }
    }
}
