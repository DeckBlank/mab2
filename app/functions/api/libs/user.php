<?php
use Timber\Timber;

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

function __getCertificate($certificate) {
    $userCertificate = UserCertificate::find($certificate);

    if ($userCertificate) {
        $userFullname = get_user_meta( $userCertificate->user_id, 'first_name', true ) . ' ' . get_user_meta( $userCertificate->user_id, 'last_name', true );
        $userFullname = count( explode('-panda-', $userFullname) ) ? str_replace(['-panda-'], ' ', $userFullname) : $userFullname;

        $course = Timber::get_post(['post_type' => 'course', 'p' => $userCertificate->course_id]);

        $date = sprintf('%s de %s de %s',
            date("d", strtotime($userCertificate->created_at)),
            array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre")[date("m", strtotime($userCertificate->created_at)) - 1],
            date("Y", strtotime($userCertificate->created_at))
        );

        $certificate = [
            'user'          => $userFullname,
            'course'        => $course->title,
            'date'          => $date,
            'duration'      => __getMetaCourse($userCertificate->course_id, -1, 'duration'),
            'course_link'   => $course->link,
            'data'          => $userCertificate,
        ];

        return $certificate;
    } else {
        return false;
    }
}
