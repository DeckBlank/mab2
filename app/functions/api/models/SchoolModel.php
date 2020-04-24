<?php

class SchoolModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function getSchoolsByUGEL($request){
        $contents = file_get_contents(__DIR__ . '/../assets/schools.json');

        $schools = json_decode($contents); $ugel = $request['ugel'];

        return array_map( function($school){ return $school->C; }, array_filter($schools, function($school) use ($ugel){
            return $school->B == $ugel;
        }) );
    }

    public static function getUGELS($request){
        $contents = file_get_contents(__DIR__ . '/../assets/schools.json');

        $schools = json_decode($contents);;
        $ugels = array_unique( array_map(function($school){
            return $school->B;
        }, $schools) );

        return array_filter($ugels, function($ugel){
            return $ugel != 'UGEL' and $ugel != '' and $ugel != '53189';
        });
    }
}
