<?php

$database::statement('CREATE TABLE IF NOT EXISTS wp_user_certificates(
    id INT NOT NULL AUTO_INCREMENT,
    signature VARCHAR(200) NOT NULL,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    notification INT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
)');
