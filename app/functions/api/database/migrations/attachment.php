<?php

$database::statement('CREATE TABLE IF NOT EXISTS wp_attachments(
    id INT NOT NULL AUTO_INCREMENT,
    path VARCHAR(300) NOT NULL,
    filename VARCHAR(200) NOT NULL,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
)');
