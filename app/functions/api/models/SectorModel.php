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

        return [$sectors->items[1], $sectors->items[2]];
    }
}
