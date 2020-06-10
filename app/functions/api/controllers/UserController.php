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
            return new WP_REST_Response('User created', 200);
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

    public function saveAccessLog($request){
        try {
            UserModel::saveAccessLog($request);

            return new WP_REST_Response('Log created', 200);
        } catch (Exception $e) {
            return new WP_Error( 'user_failed', __($e->getMessage()), array( 'status' => 404 ) );
        }
    }

    public function getAccessLog($request){
        $access_logs = UserModel::getAccessLog($request);

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
            $mail->Host       = 'innovdevelopers.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@innovdevelopers.com';
            $mail->Password   = 'n#r-(]N6yH=5';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;
    
            //Recipients
            $mail->setFrom('no-reply@innovdevelopers.com', "MABCLICK");
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
}
