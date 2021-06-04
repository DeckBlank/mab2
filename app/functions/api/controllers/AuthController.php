<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController {
    public function __construct() {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'custom/v1', '/auth/login', array(
                'methods' => 'GET',
                'callback' => array($this, 'login'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));

            register_rest_route( 'custom/v1', '/auth/check', array(
                'methods' => 'GET',
                'callback' => array($this, 'checkUser'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));

            register_rest_route( 'custom/v1', '/auth/register', array(
                'methods' => 'POST',
                'callback' => array($this, 'register'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));

            register_rest_route( 'custom/v1', '/auth/social', array(
                'methods' => 'POST',
                'callback' => array($this, 'social'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));
        });
    }

    public function login($request) {
        if( isset($request['email']) and isset($request['password']) ){
            return $this::__handleLogin($request);
        }else{
            return new WP_Error( 'no_user_credentials', __('No user credentials'), array( 'status' => 404 ) );
        }
    }

    public function checkUser($request) {
        if( isset($request['email']) ){
            $user = ( get_user_by('email', $request['email']) );

            if ($user) {
                return new WP_REST_Response((object)[
                    'message'   => 'El usuario ya existe',
                    'status'    => true
                ], 200);
            } else {
                return new WP_REST_Response((object)[
                    'message'   => 'User not exist',
                    'status'    => false
                ], 200);
            }
            
        }else{
            return new WP_Error( 'no_user_credentials', __('No user credentials'), array( 'status' => 404 ) );
        }
    }

    public function register($request) {
        if (
            !empty($request['email']) &&
            !empty($request['password']) &&
            !empty($request['sector']) &&
            !empty($request['profile']) &&
            !empty($request['role']) &&
            !empty($request['category']) &&
            !empty($request['subcategory'])
        ) {
            $email          = $request['email'];

            $password       = ($request['social'] && $request['social'] == 'google' || $request['social'] == 'facebook')
                ? wp_generate_password(5)
                : $request['password'];

            $sector         = $request['sector'];
            $profile        = json_decode($request['profile']);
            $role           = json_decode($request['role']);
            $category       = $request['category'];
            $subcategory    = $request['subcategory'];

            if( !email_exists($email) ){
                $username = sprintf('%s %s %s', $profile->name, $profile->father_name, $profile->mother_name);

                if( username_exists($username) ){
                    $username = $username . ' ' . ( count(get_users([
                        "role" => $role->type
                    ])) + 1 );
                }

                $userID = wp_insert_user([
                    'user_login'    => $username, 
                    'user_nicename' => $username, 
                    'user_pass'     => $password,
                    'user_email'    => $email,
                    'first_name'    => $profile->name,
                    'last_name'     => $profile->father_name . '-panda-' . $profile->mother_name,
                    'role'          => $role->type
                ]);

                update_field('date_birth', $profile->date_birth, 'user_' . $userID);
                update_field('mobile', $profile->phone->number, 'user_' . $userID);
                update_field('calling_code', $profile->phone->code, 'user_' . $userID);
                update_field('school_type', $sector, 'user_' . $userID);
                update_field('country', $profile->country, 'user_' . $userID);
                update_field('city', $profile->city, 'user_' . $userID);
                update_field('mab_category', $category, 'user_' . $userID);
                update_field('mab_sub_category', $subcategory, 'user_' . $userID);

                $extension = $role->extension;

                switch($role->type){
                    case 'tambero' :
                        update_field('comunity', $extension->comunity, 'user_' . $userID);
                        update_field('comunity_department', $extension->comunityDepartment, 'user_' . $userID);
                        break;
                    case 'teacher'  :
                    case 'tutor'    :
                        update_field('students', $extension->students, 'user_' . $userID);
                        break;
                }

                if ( $this::__sendNotification($email, $profile->name, $profile->father_name . $profile->mother_name) ) {
                    wp_set_auth_cookie($userID, true);
                    wp_set_current_user($userID, $email);
                    do_action('wp_login', $email);

                    return new WP_REST_Response((object)[
                        'message'   => 'User registered',
                        'data'      => $userID,
                        'status'    => true
                    ], 200);
                } else {
                    return new WP_REST_Response((object)[
                        'message'   => 'Notification couln\'t sent',
                        'status'    => false
                    ], 200);
                }
            }else{
                return new WP_REST_Response((object)[
                    'message'   => 'User already exist',
                    'status'    => false
                ], 200);
            }
        } else {
            return new WP_REST_Response((object)[
                'message'   => 'Invalid params',
                'status'    => false
            ], 200);
        }
    }

    public function social($request) {
        $user = get_user_by('email', $request['email']);

        if ($user) {
            $data = $this::__handleLogin($request, 'master');

            return new WP_REST_Response((object)[
                'message'   => 'User authorized',
                'status'    => true,
                'data'      => $data
            ], 200);
        } else {
            return new WP_REST_Response((object)[
                'message'   => 'User not registered',
                'status'    => false
            ], 200);
        }
    }

    private function __handleLogin($request, $mode = 'auth') {
        try {
            return new WP_REST_Response(UserModel::auth($request, $mode), 200);
        } catch (Exception $e) {
            return new WP_Error( 'user_failed', __($e->getMessage()), array( 'status' => 404 ) );
        }
    }

    private function __sendNotification($email, $firstName, $lastName){
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
            $mail->addAddress($email);

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
                                        Hola, '. $firstName .' '. $lastName .'
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
}
