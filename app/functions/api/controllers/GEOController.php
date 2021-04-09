<?php

class GEOController {
    public function __construct() {
        register_rest_route( 'custom/v1', '/geo', array(
            'methods' => 'GET',
            'callback' => array($this, 'show'),
            'permission_callback' => function ($request) {
                return ($request['_wpnonce']) ? true : false;
            }
        ));
    }

    public function show($request) {
        $data = [];

        if( isset( $request['dep'] ) ){
            $data = json_decode( file_get_contents(__DIR__ . '/../assets/geo/provincias.json') )->{ $request['dep'] };
        }else if( isset( $request['prov'] ) ){
            $data = json_decode( file_get_contents(__DIR__ . '/../assets/geo/distritos.json') )->{ $request['prov'] };
        }else {
            $data = json_decode( file_get_contents(__DIR__ . '/../assets/geo/departamentos.json') );
        }

        if ( count($data) ) {
            return new WP_REST_Response((object)[
                'message'   => 'GEO Data here',
                'data'      => $data,
                'status'    => true
            ], 200);
        } else {
            return new WP_REST_Response((object)[
                'message'   => 'No GEO Data found',
                'status'    => false
            ], 200);
        }
    }
}
