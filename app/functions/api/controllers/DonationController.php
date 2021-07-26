<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class DonationController {
    public function __construct() {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'custom/v1', '/donations/signature', array(
                'methods' => 'POST',
                'callback' => array($this, 'generateSignature'),
                'permission_callback' => function ($request) {
                    return ($request['_wpnonce']) ? true : false;
                }
            ));
        });
    }

    public function generateSignature($request) {
        if (
            !empty($request['referenceCode']) &&
            !empty($request['amount'])
        ) {
            $env = require(__DIR__ . '/../../../../env.php');

            $referenceCode  = $request['referenceCode'];
            $amount         = $request['amount'];

            $pasarell = (object)[
                "api_key"       => $env['PU_API_KEY'],
                "merchan_id"    => $env['PU_MERCHAND_ID'],
            ];

            return new WP_REST_Response((object)[
                'message'   => 'Signature generated succesfully!!',
                'data'      => md5($pasarell->api_key . '~' . $pasarell->merchan_id . '~' . $referenceCode . '~' . $amount . '~PEN'),
                'status'    => true
            ], 200);
        } else {
            return new WP_REST_Response((object)[
                'message'   => 'Parametros invalidos!!',
                'status'    => false
            ], 200);
        }
    }
}
