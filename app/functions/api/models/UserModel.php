<?php

class UserModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function auth($request){
        $user = wp_authenticate( $request['user'], $request['password'] );

        if( is_wp_error($user) ){
            throw new Exception("Wrong credentials");
        }else{
            try {
                self::saveLoginLog($request);

                return (object)[
                    "user_login" => $user->data->user_login,
                    "user_pass" => $user->data->user_pass,
                    "user_email" => $user->data->user_email,
                    "user_mobile" => get_field('mobile', 'user_' . $user->data->ID),
                    "user_sector" => get_field('school_type', 'user_' . $user->data->ID)
                ];                
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
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
                        update_field('phone', $request['phone'], 'user_' . $user_id);
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
                        update_field('age', $request['age'], 'user_' . $user_id);
                        update_field('phone', $request['phone'], 'user_' . $user_id);
                        update_field('mobile', $request['mobile'], 'user_' . $user_id);
                        update_field('school_type', $request['school_type'], 'user_' . $user_id);
                        update_field('ugel', $request['ugel'], 'user_' . $user_id);
                        update_field('school', $request['school'], 'user_' . $user_id);
                        update_field('grade', $request['grade'], 'user_' . $user_id);
                        update_field('location', $request['location'], 'user_' . $user_id);               
                    break;
                case 'teacher' :
                        update_field('age', $request['age'], 'user_' . $user_id);
                        update_field('phone', $request['phone'], 'user_' . $user_id);
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

    public static function saveLoginLog($request){
        $query = DBConnection::getConnection()->query("
            SELECT
                *
            FROM
                wp_login_logs
            WHERE
                user_email = '". $request['user'] ."'
            
        ");

        if ($query->num_rows > 0) {
            $login_counts = ($query->fetch_assoc()['login_count']) + 1;
            $response = DBConnection::getConnection()->query("
                UPDATE 
                    wp_login_logs
                SET
                    login_count = '". $login_counts ."',
                    last_date = '". date("Y-m-d G:i:s") ."'
                WHERE
                    user_email = '". $request['user'] ."'
            ");

            if ($response) {
                return true;
            } else {
                throw new Exception("Log couldn't updated");
            }
        } else {
            $response = DBConnection::getConnection()->query("
                INSERT INTO 
                    wp_login_logs(user_email, login_count, last_date)
                VALUES(
                    '". $request['user'] ."',
                    '". $login_counts ."',
                    '". date("Y-m-d G:i:s") ."'
                )
            ");

            if ($response) {
                return true;
            } else {
                throw new Exception("Log couldn't init");
            }
        }
    }
}
