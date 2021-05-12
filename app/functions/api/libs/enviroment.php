<?php

function setEnviromentVariables($context = []) {
    $context = array_merge($context, [
        'nonce' => wp_create_nonce( 'wp_rest' ),
        'api'   => get_rest_url() . "custom/v1"
    ]);

    if(is_user_logged_in()){
        $current_user = wp_get_current_user();

        $context = array_merge(
            $context,
            [
                "user_id"           => $current_user->ID,
                "user_auth"         => $current_user->data->display_name,
                "user_email"        => $current_user->data->user_email,
                "user_firstname"    => get_user_meta( $current_user->ID, 'first_name', true ),
                "user_lastname"     => get_user_meta( $current_user->ID, 'last_name', true ),
                "user_mobile"       => get_field('mobile', 'user_' . $current_user->ID),
                "user_rol"          => $current_user->roles[0],
                "user_grade"        => get_field('grade', 'user_' . $current_user->ID),
                "user_sector"       => get_field('school_type', 'user_' . $current_user->ID),
                "user_metas"        => (object)[
                    "questionary"   => BehaviourModel::questionaryCheckout($current_user->data->user_email),
                    "poll"          => BehaviourModel::pollCheckout($current_user->data->user_email)
                ]
            ]
        );
    }

    wp_localize_script( 'pandawp/js/script/main', 'mab', $context);
}
