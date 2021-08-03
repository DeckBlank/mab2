<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Timber\Timber;

class EventController {
    public function __construct() {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'custom/v1', '/events/(?P<event_id>\d+)/participants', array(
                'methods' => 'POST',
                'callback' => array($this, 'storeParticipants'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));
        });
    }

    public function storeParticipants($request) {
        if (
            !empty($request['fullname']) &&
            !empty($request['email'])
        ) {
            $eventId    = $request['event_id'];
            $fullname   = $request['fullname'];
            $email      = $request['email'];

            $this::__initMetas($eventId);

            $participants = intval( get_post_meta($eventId, 'participants', true) );

            update_post_meta($eventId, 'participants', ++$participants);

            if ( $this::__sendNotify($request) ) {

                return new WP_REST_Response((object)[
                    'status' => true
                ], 200);
            } else {
                return new WP_REST_Response((object)[
                    'message'   => 'Mail could not sent',
                    'status'    => false
                ], 200);
            }
        } else {
            return new WP_REST_Response((object)[
                'message'   => 'Parametros invalidos!!',
                'status'    => false
            ], 200);
        }
    }

    private function __sendNotify($request) {
        $mail   = new PHPMailer(true);
        $admins = array_map(function($admin){return $admin->data->user_email;}, get_users(['role' => 'administrator']));

        $event = Timber::get_post([
            'post_type' => 'event',
            'p'         => $request['event_id']
        ]);

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

            foreach($admins as $admin){
                $mail->addAddress($admin);
            }

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
                                    <img src="' . get_template_directory_uri() . '/static/images/logo.png" style="width: 100px;">
                                </header>
    
                                <div style="padding: 1rem;">
                                    <h1 style="font-size: 25px; color: white;">¡Nuevo participante!</h1>

                                    <table style="width: 100%; padding-left: 1.5rem">          
                                        <tbody style="width: 100%">
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Charla: </td>
                                                <td style="padding: 10px 0; color: white;">'. $event->title . '</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Nombres completos: </td>
                                                <td style="padding: 10px 0; color: white;">'. $request['fullname'] . '</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 0; width: 30%; font-weight: bold; color: white; font-weight:bold">Email: </td>
                                                <td style="padding: 10px 0; color: #0166D0; background: white; padding: 5px 10px;">'. $request['email'] . '</td>
                                            </tr>
                                        </tbody>
                                    </table>
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
            $mail->Subject = "¡Nuevo participante 📅!";
            $mail->MsgHTML($body);
    
            $mail->send();
    
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function __initMetas($eventId) {
        if ( !get_post_meta($eventId, 'participants') ) {
            add_post_meta($eventId, 'participants', 0);
        }
    }
}
