<?php

require(__DIR__ . '/../models/SchoolModel.php');

class SchoolController{

    public function __construct(){

    }

    /**
     * Methods
     */
    public function getSchoolsBySector($request){
        $schools = SchoolModel::getSchoolsBySector($request);

        if ( empty($schools) ) {
            return new WP_Error( 'no_schools', __('No schools found'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($schools, 200);
        }        
    }

    public function getSchoolsByUGEL($request){
        $schools = SchoolModel::getSchoolsByUGEL($request);

        if ( empty($schools) ) {
            return new WP_Error( 'no_schools', __('No schools found'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($schools, 200);
        }        
    }

    public function getUGELS($request){
        $ugels = SchoolModel::getUGELS($request);

        if ( empty($ugels) ) {
            return new WP_Error( 'no_ugels', __('No ugels found'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($ugels, 200);
        }        
    }
}
