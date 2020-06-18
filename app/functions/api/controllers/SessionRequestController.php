<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require(__DIR__ . '/../models/SessionRequestModel.php');

class SessionRequestController{

    public function __construct(){

    }

    /**
     * Methods
     */
    public function addSessionRequest($request) {
        $user_folder = str_replace( ' ', '_', strtolower($request['fullname']) );
        $session_dir_user = __DIR__ . "/../../../../../../uploads/sessions/" . $user_folder;
        $files = [];
        
        if(!file_exists($session_dir_user)){
            mkdir($session_dir_user);
        }

        if( isset($_FILES['resources']) ){
            for($index = 0; $index < count($_FILES['resources']['name']); $index++){
                $session_file_ext = pathinfo($_FILES['resources']['name'][$index], PATHINFO_EXTENSION);
    
                if($session_file_ext == 'jpg' || $session_file_ext == 'jpeg' || $session_file_ext == 'pdf'){
                    $time = date("Y_m_d_H_i");
                    $session_file = $session_dir_user . '/' . $time . $_FILES['resources']['name'][$index];            
    
                    if( is_uploaded_file($_FILES['resources']['tmp_name'][$index]) ) {			
                        if( !move_uploaded_file($_FILES['resources']['tmp_name'][$index], $session_file) ) {
                            return new WP_Error( 'no_seesion_file_saved', __('Session file (' . $_FILES['resources']['name'][$index] . ') not saved'), array( 'status' => 404 ) );
                        }else {
                            array_push($files, (object)[
                                "link" => sprintf(
                                    '%s/wp-content/uploads/sessions/%s/%s%s',
                                    get_site_url(),
                                    $user_folder,
                                    $time,
                                    $_FILES['resources']['name'][$index]
                                ),
                                "name" => $_FILES['resources']['name'][$index],
                            ]);
                        }
                    }else{
                        return new WP_Error( 'no_seesion_file_uploaded', __('Session file (' . $_FILES['resources']['name'][$index] . ') not uploaded'), array( 'status' => 404 ) );
                    }
                }else{
                    return new WP_Error( 'no_right_session_file', __('Session file (' . $_FILES['resources']['name'][$index] . ') with wrong format'), array( 'status' => 404 ) );
                }
            }
        }
            
        if( SessionRequestModel::addSessionRequest($request) ){
            if ($this::sendNotification($request, $files)) {
                return new WP_REST_Response('Session request saved', 200);
            } else {
                return new WP_Error( 'no_notification_sent', __('Notification not sent'), array( 'status' => 404 ) );
            }            
        }else{
            return new WP_Error( 'no_seesion_request_saved', __('Session request not saved'), array( 'status' => 404 ) );
        }
    }

    private function sendNotification($request, $files) {
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
            $mail->addAddress(get_field('email', 'options'));

            //Content Files
            $body_files = '';

            foreach($files as $file){
                $body_files .= '
                    <div style="margin-bottom: 1rem">
                        - <a href="'. $file->link .'">'. $file->name .'</a>
                    </div>                   
                ';
            }
    
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
                                        <h1 style="font-size: 25px; color: #222;">MAB - Solicitar sesión virtual</h1>

                                        <table style="width: 100%; padding-left: 1.5rem">          
                                            <tbody style="width: 100%">
                                                <tr>
                                                    <td style="padding: 10px 0; width: 30%; font-weight: bold; color: #222; font-weight:bold">Nombres y apellidos: </td>
                                                    <td style="padding: 10px 0; color: #575757;">'. $request["fullname"] .'</td>     
                                                </tr>
                                                <tr>
                                                    <td style="padding: 10px 0; width: 30%; font-weight: bold; color: #222; font-weight:bold">Correo: </td>
                                                    <td style="padding: 10px 0; color: #575757;">'. $request["email"] .'</td>     
                                                </tr>
                                                <tr>
                                                    <td style="padding: 10px 0; width: 30%; font-weight: bold; color: #222; font-weight:bold">Fecha 1: </td>
                                                    <td style="padding: 10px 0; color: #575757;">
                                                        '. $request["date1"] .' - '. $request["time1"] .'
                                                    </td>     
                                                </tr> 
                                                <tr>
                                                    <td style="padding: 10px 0; width: 30%; font-weight: bold; color: #222; font-weight:bold">Fecha 2: </td>
                                                    <td style="padding: 10px 0; color: #575757;">
                                                        '. $request["date2"] .' - '. $request["time2"] .'
                                                    </td>     
                                                </tr>
                                                <tr>
                                                    <td style="padding: 10px 0; width: 30%; font-weight: bold; color: #222; font-weight:bold">Curso de interés: </td>
                                                    <td style="padding: 10px 0; color: #575757;">'. $request["course"] .'</td>     
                                                </tr> 
                                                <tr>
                                                    <td style="padding: 10px 0; width: 30%; font-weight: bold; color: #222; font-weight:bold">Archivos: </td>
                                                    <td style="padding: 10px 0; color: #575757;">
                                                        '. $body_files .'
                                                    </td>     
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
            $mail->Subject = "Solicitud de sesión virtual";
            $mail->MsgHTML($body);
    
            $mail->send();
    
            return true;
        } catch (Exception $e) {
            return false;
        }        
    }
}
