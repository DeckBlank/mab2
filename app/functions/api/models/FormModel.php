<?php

class FormModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function addTutorForm($request){
        return DBConnection::getConnection()->query("
            INSERT INTO 
                wp_tutor_forms(
                    date_at,
                    name,
                    last_father_name,
                    last_mother_name,
                    email,
                    phone,
                    mobile,
                    school_type,
                    ugel,
                    children_school,
                    children_quantity,
                    children,
                    location)
            VALUES(
                '". date("Y-m-d") ."',
                '". $request['name'] ."',
                '". $request['last_father_name'] ."',
                '". $request['last_mother_name'] ."',
                '". $request['email'] ."',
                '". $request['phone'] ."',
                '". $request['mobile'] ."',
                '". $request['school_type'] ."',
                '". $request['ugel'] ."',
                '". $request['children_school'] ."',
                '". $request['children_quantity'] ."',
                '". $request['children'] ."',
                '". $request['location'] ."'
            )
        ");  
    }

    public static function addStudentForm($request){
        return DBConnection::getConnection()->query("
            INSERT INTO 
                wp_student_forms(
                    date_at,
                    name,
                    last_father_name,
                    last_mother_name,
                    email,
                    phone,
                    mobile,
                    school_type,
                    ugel,
                    school,
                    grade,
                    age,
                    location) 
            VALUES(
                '". date("Y-m-d") ."',
                '". $request['name'] ."',
                '". $request['last_father_name'] ."',
                '". $request['last_mother_name'] ."',
                '". $request['email'] ."',
                '". $request['phone'] ."',
                '". $request['mobile'] ."',
                '". $request['school_type'] ."',
                '". $request['ugel'] ."',
                '". $request['school'] ."',
                '". $request['grade'] ."',
                '". $request['age'] ."',
                '". $request['location'] ."'
            )
        ");  
    }

    public static function addTeacherForm($request){
        return DBConnection::getConnection()->query("
            INSERT INTO 
                wp_teacher_forms(
                    date_at,
                    name,
                    last_father_name,
                    last_mother_name,
                    email,
                    phone,
                    mobile,
                    school_type,
                    ugel,
                    school,
                    grade,
                    age,
                    location) 
            VALUES(
                '". date("Y-m-d") ."',
                '". $request['name'] ."',
                '". $request['last_father_name'] ."',
                '". $request['last_mother_name'] ."',
                '". $request['email'] ."',
                '". $request['phone'] ."',
                '". $request['mobile'] ."',
                '". $request['school_type'] ."',
                '". $request['ugel'] ."',
                '". $request['school'] ."',
                '". $request['grade'] ."',
                '". $request['age'] ."',
                '". $request['location'] ."'
            )
        ");  
    }
}
