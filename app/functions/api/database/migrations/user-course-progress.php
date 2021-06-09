<?php

try {
    $departments = $database::statement('SELECT progress  FROM wp_user_course');
} catch (Exception $e) {
    $database::statement('ALTER TABLE wp_user_course
        ADD COLUMN progress DECIMAL NOT NULL AFTER wrong_answers,
        ADD COLUMN updated_at TIMESTAMP NULL AFTER last_date
    ');
}
