<?php

function __getUserDataById($user) {
    switch ($user->roles[0]) {
        case 'teacher':
            return (object)[
                "role" => 'Profesor',
                "sector" => get_field('school_type', 'user_' . $user->ID),
                "age" => get_field('age', 'user_' . $user->ID),
                "phone" => get_field('phone', 'user_' . $user->ID),
                "mobile" => get_field('mobile', 'user_' . $user->ID),
                "calling_code" => get_field('calling_code', 'user_' . $user->ID),
                "location" => get_field('location', 'user_' . $user->ID),
                "school" => get_field('school', 'user_' . $user->ID)        
            ];
            break;

        case 'student':
            return (object)[
                "role" => 'Estudiante',
                "sector" => get_field('school_type', 'user_' . $user->ID),
                "age" => get_field('age', 'user_' . $user->ID),
                "phone" => get_field('phone', 'user_' . $user->ID),
                "mobile" => get_field('mobile', 'user_' . $user->ID),
                "calling_code" => get_field('calling_code', 'user_' . $user->ID),
                "location" => get_field('location', 'user_' . $user->ID),
                "school" => get_field('school', 'user_' . $user->ID)        
            ];
            break;

        case 'tutor':
            return (object)[
                "role" => 'Tutor/Padre',
                "sector" => get_field('school_type', 'user_' . $user->ID),
                "age" => get_field('age', 'user_' . $user->ID),
                "phone" => get_field('phone', 'user_' . $user->ID),
                "mobile" => get_field('mobile', 'user_' . $user->ID),
                "calling_code" => get_field('calling_code', 'user_' . $user->ID),
                "location" => get_field('location', 'user_' . $user->ID),
                "school" => get_field('children_school', 'user_' . $user->ID),
                "children" => get_field('children_quantity', 'user_' . $user->ID)
            ];            
            break;
    }
}
