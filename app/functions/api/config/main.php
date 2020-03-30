<?php 

class DBConnection{        
    public $connection;

    public function __construct(){
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }

    public static function getConnection(){
        return new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }

    public function createVideoScoreTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_video_scores(
            id INT NOT NULL AUTO_INCREMENT,
            date_at DATE NOT NULL, 
            user VARCHAR(100) NOT NULL,
            video_id INT NOT NULL,
            score DOUBLE NOT NULL,
            PRIMARY KEY (id)
        )");
    }

    public function createTopicScoreTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_topic_scores(
            id INT NOT NULL AUTO_INCREMENT,
            date_at DATE NOT NULL, 
            user VARCHAR(100) NOT NULL,
            topic_id INT NOT NULL,
            score DOUBLE NOT NULL,
            PRIMARY KEY (id)
        )");
    }

    public function createTopicTestScoreTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_topic_test_scores(
            id INT NOT NULL AUTO_INCREMENT,
            date_at DATE NOT NULL, 
            user VARCHAR(100) NOT NULL,
            topic_id INT NOT NULL,
            score VARCHAR(100) NOT NULL,
            PRIMARY KEY (id)
        )");
    }
}

//Init
$connection = new DBConnection();

//Create Tables
$connection->createVideoScoreTable();
$connection->createTopicScoreTable();
$connection->createTopicTestScoreTable();
