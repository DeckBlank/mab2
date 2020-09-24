<?php

use Timber\Timber;

class SectorModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function getAll($request){
        $sectors = new \Timber\Menu( 'primary-menu' );

        return ($request['type'] == 'public') ? $sectors->items[0] : $sectors->items[1];
    }
}
