<?php

require(__DIR__ . '/../models/SectorModel.php');

class SectorController{

    public function __construct(){

    }

    /**
     * Methods
     */
    public function getAll(){
        $sectors = SectorModel::getAll();

        if( empty($sectors) ){
            return new WP_Error( 'no_sectors', __("No sectors found"), array( 'status' => 404 ) );
        }else{
            return new WP_REST_Response($sectors, 200);
        }
    }
}
