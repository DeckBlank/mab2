<?php

use Timber\Timber;
use Timber\Term;

class SectorModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function getAll($request){
        $levels = [];

        $sectors    = new \Timber\Menu( 'primary-menu' );
        $sector     = ($request['type'] == 'public') ? $sectors->items[0] : $sectors->items[1];

        foreach($sector->children as $level) {
            array_push($levels, [
                "name" => $level->name,
                "id" => $level->ID,
                "grades" => self::__getGradesByLevel($level)
            ]);
        }

        return (object)[
            "name" => $request['type'],
            "id" => $sector->ID,
            "levels" => $levels
        ];
    }

    public static function __getGradesByLevel($level) {
        $grades = [];

        foreach($level->children as $grade) {
            array_push($grades, [
                "name" => $grade->name,
                "id" => intval($grade->object_id),
                "courses" => self::__getCoursesByGrade($grade, strtolower($level->name))
            ]);
        }

        return $grades;
    }

    public static function __getCoursesByGrade($grade, $level) {
        $courses = [];

        foreach($grade->children as $course) {
            array_push($courses, [
                "name" => $course->name,
                "slug" => $course->slug,
                "id" => intval($course->object_id),
                "area" => (get_field('area', intval($course->object_id))) ? get_field('area', intval($course->object_id)) : ''
            ]);
        }

        $areas = array_map(function($course){return $course['area'];}, $courses);

        if (strtolower($level) != 'adultos' && strtolower($level) != 'escuela para docentes') {
            if (
                count( array_filter($areas, function($area){return in_array($area, ['creative', 'emotional', 'academic']);}) ) == count($areas) &&
                strtolower($grade->name) != 'ib'
            ) {
                $data = (object)[
                    'emotional' => [
                        'name' => 'Emocional/Bienestar',
                        'courses' => array_filter($courses, function($course){return $course['area'] == 'emotional';})
                    ],
                    'creative' => [
                        'name' => 'Creativo',
                        'courses' => array_filter($courses, function($course){return $course['area'] == 'creative';})
                    ],
                    'academic' => [
                        'name' => 'Académico',
                        'courses' => array_filter($courses, function($course){return $course['area'] == 'academic';})
                    ]
                ];
    
                return (object)[
                    'isAreas' => true,
                    'areas' => $data
                ];
    
            } else {
                return (object)[
                    'isAreas' => false,
                    'courses' => $courses
                ];        
            }
        } else {
            return (object)[
                'isAreas' => false,
                'courses' => $courses
            ]; 
        }
    }
}
