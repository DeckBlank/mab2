<?php

$database::statement('CREATE TABLE IF NOT EXISTS wp_discussions(
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NULL,
    topic_id INT NULL,
    subject VARCHAR(200) NOT NULL,
    total_comments INT DEFAULT 0,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
)');
