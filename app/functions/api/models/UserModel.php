<?php

use Timber\Timber;

class UserModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function getAll($request){
        $users = get_users([
            "role__in" => [$request['role']]
        ]);
        $users_array = [];

        foreach($users as $user){
            switch ($request['role']) {
                case 'student':
                case 'teacher':
                    array_push($users_array,
                        (object)[
                            "role" => ($request['role'] == 'student') ? 'Estudiante' : 'Profesor',
                            "firstname" => get_user_meta($user->ID)['first_name'][0],
                            "lastname" => get_user_meta($user->ID)['last_name'][0],
                            "email" => $user->data->user_email,
                            "age" => get_field('age', 'user_' . $user->ID),
                            "gender" => get_field('gender', 'user_' . $user->ID),
                            "phone" => get_field('phone', 'user_' . $user->ID),
                            "mobile" => get_field('mobile', 'user_' . $user->ID),
                            "calling_code" => get_field('calling_code', 'user_' . $user->ID),
                            "school_type" => get_field('school_type', 'user_' . $user->ID),
                            "school" => get_field('school', 'user_' . $user->ID),
                            "grade" => get_field('grade', 'user_' . $user->ID),
                            "location" => get_field('location', 'user_' . $user->ID),
                        ]                    
                    );
                    break;

                case 'tutor':
                    array_push($users_array,
                        (object)[
                            "role" => 'Tutor/Padre',
                            "firstname" => get_user_meta($user->ID)['first_name'][0],
                            "lastname" => get_user_meta($user->ID)['last_name'][0],
                            "email" => $user->data->user_email,
                            "age" => get_field('age', 'user_' . $user->ID),
                            "gender" => get_field('gender', 'user_' . $user->ID),
                            "phone" => get_field('phone', 'user_' . $user->ID),
                            "mobile" => get_field('mobile', 'user_' . $user->ID),
                            "calling_code" => get_field('calling_code', 'user_' . $user->ID),
                            "school_type" => get_field('school_type', 'user_' . $user->ID),
                            "children_school" => get_field('children_school', 'user_' . $user->ID),
                            "children_quantity" => get_field('children_quantity', 'user_' . $user->ID),
                            "children" => get_field('children', 'user_' . $user->ID),
                            "location" => get_field('location', 'user_' . $user->ID),
                        ]                    
                    );
                    break;

                default:
                    break;
            }
        }

        return $users_array;
    }

    public static function auth($request){
        $user = wp_signon([
            'user_login'    => $request['user'],
            'user_password' => $request['password'],
            'remember'      => true
        ]);

        if( is_wp_error($user) ){
            throw new Exception("Wrong credentials");
        }else{
            try {
                self::saveAccessLog($request);

                return (object)[
                    "user_login" => sprintf(
                        '%s %s', 
                        get_user_meta( $user->data->ID, 'first_name', true ),
                        get_user_meta( $user->data->ID, 'last_name', true )
                    ),
                    "user_firstname" => get_user_meta( $user->data->ID, 'first_name', true ),
                    "user_lastname" => get_user_meta( $user->data->ID, 'last_name', true ),
                    "user_pass" => $user->data->user_pass,
                    "user_email" => $user->data->user_email,
                    "user_rol" => $user->roles[0],
                    "user_mobile" => get_field('mobile', 'user_' . $user->data->ID),
                    "user_sector" => get_field('school_type', 'user_' . $user->data->ID),
                    "user_metas" => (object)[
                        "questionary" => BehaviourModel::questionaryCheckout($user->data->user_email),
                        "poll" => BehaviourModel::pollCheckout($user->data->user_email)
                    ]
                ];                
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }

    public static function logout($request){
        wp_logout(); return true;
    }

    public static function checkout($request){
        return email_exists($request['user']);
    }

    public static function createUser($request){
        if( !email_exists($request['email']) ){
            $username = $request['user_name'];

            if( username_exists($request['user_name']) ){
                $username = $username . ' ' . ( count(get_users([
                    "role" => $request['type']
                ])) + 1 );
            }

            $user_id = wp_insert_user([
                'user_login' => $username, 
                'user_nicename' => $username, 
                'user_pass' => $request['password'], 
                'user_email' => $request['email'],
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'role' => $request['type']
            ]);

            switch($request['type']){
                case 'tutor' :
                        update_field('gender', $request['gender'], 'user_' . $user_id);
                        update_field('phone', $request['phone'], 'user_' . $user_id);
                        update_field('calling_code', $request['calling_code'], 'user_' . $user_id);
                        update_field('mobile', $request['mobile'], 'user_' . $user_id);
                        update_field('school_type', $request['school_type'], 'user_' . $user_id);
                        update_field('ugel', $request['ugel'], 'user_' . $user_id);
                        update_field('children_school', $request['children_school'], 'user_' . $user_id);
                        update_field('children_quantity', $request['children_quantity'], 'user_' . $user_id);
                        update_field('location', $request['location'], 'user_' . $user_id);

                        $children = [];

                        foreach(json_decode($request['children']) as $child){
                            array_push($children, [
                                "child" => [
                                    "age" => $child->{'age'},
                                    "grade" => $child->{'grade'}
                                ]
                            ]);
                        }                

                        update_field('children', $children, 'user_' . $user_id);                    
                    break;
                case 'student' :
                        update_field('gender', $request['gender'], 'user_' . $user_id);
                        update_field('age', $request['age'], 'user_' . $user_id);
                        update_field('phone', $request['phone'], 'user_' . $user_id);
                        update_field('calling_code', $request['calling_code'], 'user_' . $user_id);
                        update_field('mobile', $request['mobile'], 'user_' . $user_id);
                        update_field('school_type', $request['school_type'], 'user_' . $user_id);
                        update_field('ugel', $request['ugel'], 'user_' . $user_id);
                        update_field('school', $request['school'], 'user_' . $user_id);
                        update_field('grade', $request['grade'], 'user_' . $user_id);
                        update_field('location', $request['location'], 'user_' . $user_id);               
                    break;
                case 'teacher' :
                        update_field('gender', $request['gender'], 'user_' . $user_id);
                        update_field('age', $request['age'], 'user_' . $user_id);
                        update_field('phone', $request['phone'], 'user_' . $user_id);
                        update_field('calling_code', $request['calling_code'], 'user_' . $user_id);
                        update_field('mobile', $request['mobile'], 'user_' . $user_id);
                        update_field('school_type', $request['school_type'], 'user_' . $user_id);
                        update_field('ugel', $request['ugel'], 'user_' . $user_id);
                        update_field('school', $request['school'], 'user_' . $user_id);
                        update_field('grade', $request['grade'], 'user_' . $user_id);
                        update_field('location', $request['location'], 'user_' . $user_id);               
                    break;
            }
    
            return true;
        }else{
            return false;
        }
    }

    public static function createRecoverySession($user_id){
        $recovery_session_id = uniqid();

        $response = DBConnection::getConnection()->query("
            INSERT INTO 
                wp_recovery_sessions(id,date_at,user) 
            VALUES(
                '". $recovery_session_id ."',
                '". date("Y-m-d") ."',
                '". $user_id ."'
            )
        ");

        if ($response) {
            return $recovery_session_id;
        } else {
            return false;
        }
    }

    public static function resetPassword($request, $recovery_session){
        wp_set_password($request['new_pass'], $recovery_session['user']);
    }

    public static function getRecoverySession($request){
        return DBConnection::getConnection()->query("
            SELECT
                *
            FROM
                wp_recovery_sessions
            WHERE
                id = '". $request['session_id'] ."'
        ");
    }

    public static function getEnrollments($request){
        if (isset($request['user'])) {
            $courses = Timber::get_posts([
                "post_type" => "course",
                "posts_per_page" => 25,
                "paged" => $request['page'],
            ]);
        }

        $enrollments_array = [];
        
        if(!empty($courses) && email_exists($request['user'])){
            foreach($courses as $course){
                array_push($enrollments_array, (object)[
                    "course" => $course->title,
                    "course_id" => $course->ID,
                    "categories" => $course->terms,
                    "state" => self::__checkCourseOnEnrollments($request, $course->ID, 'state'),
                    "date_at" => self::__checkCourseOnEnrollments($request, $course->ID, 'date_at'),
                    "date_end" => self::__checkCourseOnEnrollments($request, $course->ID, 'date_end')
                ]);                
            }
        }

        return $enrollments_array;
    }

    public static function saveEnrollments($request){
        $response = false;
        $date_end = date('Y-m-d G:i:s', strtotime('+1 year'));

        foreach(json_decode($request['courses']) as $course){
            $response = DBConnection::getConnection()->query("
                INSERT INTO 
                    wp_user_course_enrollment(user_email, course_id, date_at, date_end, last_date, state)
                VALUES(
                    '". $request['user'] ."',
                    '". $course ."',
                    '". date("Y-m-d G:i:s") ."',
                    '". $date_end ."',
                    '". date("Y-m-d G:i:s") ."',
                    1
                )
                ON DUPLICATE KEY UPDATE
                    date_end = '". $date_end ."',
                    last_date = '". date("Y-m-d G:i:s") ."'
            ");

            if(!$response){
                break;
            }
        }

        return $response;
    }    

    public static function deleteEnrollments($request){
        $response = false;

        foreach($request['courses'] as $course){
            $response = DBConnection::getConnection()->query("
                DELETE FROM 
                    wp_user_course_enrollment
                WHERE
                    user_email = '". $request['user'] ."' and
                    course_id = '". $course ."'
            ");

            if(!$response){
                break;
            }
        }

        // return $request['courses'];
        return $response;
    }    

    public static function saveAccessLog($request){
        $user = ($request['user'] == 'anonimo') ? 'anonimo-' . $_SERVER['REMOTE_ADDR'] : $request['user'];

        $response = DBConnection::getConnection()->query("
            INSERT INTO 
                wp_access_logs(user_email, access_count, last_date)
            VALUES(
                '". $request['user'] ."',
                1,
                '". date("Y-m-d G:i:s") ."'
            )
            ON DUPLICATE KEY UPDATE
                access_count = access_count + 1,
                last_date = '". date("Y-m-d G:i:s") ."'                    
        ");

        if ($response) {
            return true;
        } else {
            throw new Exception("Log couldn't saved");
        }
    }

    public static function getAccessLogs($request, $limit = false){
        if (isset($request['user'])) {
            $access_logs_query = DBConnection::getConnection()->query("
                SELECT 
                    *
                FROM 
                    wp_access_logs
                WHERE
                    user_email = '". $request['user'] ."'
            ");
        } else {
            if ($limit == 'all') {
                $access_logs_query = DBConnection::getConnection()->query("
                    SELECT 
                        *
                    FROM 
                        wp_access_logs
                    ORDER BY last_date DESC
                ");
            } else {
                $access_logs_query = DBConnection::getConnection()->query("
                    SELECT 
                        *
                    FROM 
                        wp_access_logs
                    ORDER BY last_date DESC
                    LIMIT ". __getLimit() ."
                    OFFSET ". __getOffset($request['page']) ."
                ");
            }
            
        }
        $access_logs = [];

        if($access_logs_query && $access_logs_query->num_rows > 0){
            while($log = $access_logs_query->fetch_assoc()){
                array_push($access_logs, (object)[
                    "user" => get_user_by('email', $log['user_email']),
                    "user_email" => $log['user_email'],
                    "access_count" => $log['access_count'],
                    "last_date" => $log['last_date'],
                ]);
            }
        }

        return $access_logs;        
    }

    public static function __checkCourseOnEnrollments($request, $course_id, $field){
        $enrollments = DBConnection::getConnection()->query("
            SELECT 
                *
            FROM 
                wp_user_course_enrollment
            WHERE
                user_email = '". $request['user'] ."' and 
                course_id = '". $course_id ."'
            ORDER BY last_date DESC
        ");

        if($enrollments->num_rows > 0){
            switch ($field) {
                case 'state':
                        return ($enrollments->fetch_assoc()['state'] == 1) ? 'Habilitado' : 'Bloqueado'; 
                    break;
                case 'date_at':
                        return $enrollments->fetch_assoc()['date_at']; 
                    break;
                case 'date_end':
                        return $enrollments->fetch_assoc()['date_end']; 
                    break;
            }
        }else {
            return null;
        }
        // while($enrollment = $enrollments->fetch_assoc()){
        //     array_push($enrollments_array, (object)[
        //         "course" => CourseModel::__getCourseName($enrollment['course_id']),
        //         "state" => ($enrollment['state'] == 1) ? 'Habilitado' : 'Bloqueado',
        //         "date_at" => $enrollment['date_at'],
        //         "date_end" => $enrollment['date_end']
        //     ]);            
        // }
    }
}
