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
            return false;
        }else{
            return (object)[
                "user_login" => $user->data->user_login,
                "user_pass" => $user->data->user_pass,
                "user_email" => $user->data->user_email,
                "user_phone" => get_field('phone', 'user_' . $user->data->ID),
            ];
        }
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
}
