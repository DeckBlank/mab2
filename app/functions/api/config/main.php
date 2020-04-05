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

    public function createSessionRequestTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_session_requests(
            id INT NOT NULL AUTO_INCREMENT,
            date_at DATE NOT NULL, 
            fullname VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            date1 VARCHAR(100) NOT NULL,
            time1 VARCHAR(100) NOT NULL,
            date2 VARCHAR(100) NOT NULL,
            time2 VARCHAR(100) NOT NULL,
            course VARCHAR(100) NOT NULL,
            PRIMARY KEY (id)
        )");
    }

    public function createTutorFormTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_tutor_forms(
            id INT NOT NULL AUTO_INCREMENT,
            date_at DATE NOT NULL,
            name VARCHAR(100) NOT NULL,
            last_father_name VARCHAR(100) NOT NULL,
            last_mother_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone INT NOT NULL,
            mobile INT NOT NULL,
            school_type VARCHAR(100) NOT NULL,
            ugel VARCHAR(100) NOT NULL,
            children_school VARCHAR(100) NOT NULL,
            children_quantity INT NOT NULL,
            department VARCHAR(100) NOT NULL,
            province VARCHAR(100) NOT NULL,
            district VARCHAR(100) NOT NULL,
            PRIMARY KEY (id)
        )");
    }

    public function createStudentFormTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_student_forms(
            id INT NOT NULL AUTO_INCREMENT,
            date_at DATE NOT NULL,
            name VARCHAR(100) NOT NULL,
            last_father_name VARCHAR(100) NOT NULL,
            last_mother_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone INT NOT NULL,
            mobile INT NOT NULL,
            school_type VARCHAR(100) NOT NULL,
            ugel VARCHAR(100) NOT NULL,
            school VARCHAR(100) NOT NULL,
            grade INT NOT NULL,
            age INT NOT NULL,
            department VARCHAR(100) NOT NULL,
            province VARCHAR(100) NOT NULL,
            district VARCHAR(100) NOT NULL,
            PRIMARY KEY (id)
        )");
    }

    public function createTeacherFormTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_teacher_forms(
            id INT NOT NULL AUTO_INCREMENT,
            date_at DATE NOT NULL,
            name VARCHAR(100) NOT NULL,
            last_father_name VARCHAR(100) NOT NULL,
            last_mother_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone INT NOT NULL,
            mobile INT NOT NULL,
            school_type VARCHAR(100) NOT NULL,
            ugel VARCHAR(100) NOT NULL,
            school VARCHAR(100) NOT NULL,
            grade INT NOT NULL,
            age INT NOT NULL,
            department VARCHAR(100) NOT NULL,
            province VARCHAR(100) NOT NULL,
            district VARCHAR(100) NOT NULL,
            PRIMARY KEY (id)
        )");
    }
}

/**
 * Init
 */
$connection = new DBConnection();

/**
 * Creation tables
 */
$connection->createVideoScoreTable();
$connection->createTopicScoreTable();
$connection->createTopicTestScoreTable();
$connection->createSessionRequestTable();

//Form
$connection->createTutorFormTable();
$connection->createStudentFormTable();
$connection->createTeacherFormTable();
