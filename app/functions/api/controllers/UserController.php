<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require(__DIR__ . '/../models/UserModel.php');

class UserController{

    public function __construct(){
        
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
}
