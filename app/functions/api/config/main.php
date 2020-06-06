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
            course_id INT NOT NULL,
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

    public function createUserTestTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_user_tests(
            id INT NOT NULL AUTO_INCREMENT,
            date_at DATE NOT NULL, 
            user VARCHAR(100) NOT NULL,
            result VARCHAR(100) NOT NULL,
            PRIMARY KEY (id)
        )");
    }

    public function createRecoverySessionsTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_recovery_sessions(
            id VARCHAR(100) NOT NULL,
            date_at DATE NOT NULL,
            user VARCHAR(100) NOT NULL,
            PRIMARY KEY (id)
        )");
    }

    public function createUserCourseTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_user_course(
            user_email VARCHAR(50) NOT NULL,
            course_id INT NOT NULL,
            topic_views INT NOT NULL DEFAULT 0,
            material_downloads INT NOT NULL DEFAULT 0,
            test_count INT NOT NULL DEFAULT 0,
            right_answers INT NOT NULL DEFAULT 0,
            wrong_answers INT NOT NULL DEFAULT 0,
            last_date DATETIME NOT NULL,
            PRIMARY KEY (user_email)
        )");
    }    

    /**
     * ----------------------------------------------------/
     * Logs
     * ----------------------------------------------------/
     */
    public function createAccessLogTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_access_logs(
            user_email VARCHAR(50) NOT NULL,
            access_count INT NOT NULL,
            last_date DATETIME NOT NULL,
            PRIMARY KEY (user_email)
        )");
    }

    public function createTopicViewLogTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_topic_views_logs(
            user_email VARCHAR(50) NOT NULL,
            views INT NOT NULL,
            last_topic INT NOT NULL,
            last_date DATETIME NOT NULL,
            PRIMARY KEY (user_email)            
        )");
    }

    public function createTopicMaterialDownloadLogTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_topic_material_download_logs(
            user_email VARCHAR(50) NOT NULL,
            downloads INT NOT NULL,
            last_topic INT NOT NULL,
            last_date DATETIME NOT NULL,
            PRIMARY KEY (user_email)            
        )");
    }

    public function createTopicTestAnswerLogTable(){
        $response = $this->connection->query("CREATE TABLE IF NOT EXISTS wp_topic_test_answers_logs(
            user_email VARCHAR(50) NOT NULL,
            test_count INT NOT NULL,
            right_answers INT NOT NULL,
            wrong_answers INT NOT NULL,
            last_course VARCHAR(50) NULL,
            last_unity INT NULL,
            last_topic INT NULL,
            last_date DATETIME NULL,
            PRIMARY KEY (user_email)            
        )");

        $this::seederTopicTestAnswerLogTable($this->connection);
    }


    /**
     * ----------------------------------------------------/
     * Seeders
     * ----------------------------------------------------/
     */

    private function seederTopicTestAnswerLogTable($db_connection){
        $query = $db_connection->query("SELECT * FROM wp_topic_test_answers_logs");

        if($query->num_rows == 0){
            $users = get_users();

            if (!empty($users )){
                foreach($users as $user){
                    $right_answers = 0;
                    $wrong_answers = 0;
                    $test_count = 0;

                    $topic_test_scores_query = $db_connection->query("
                        SELECT
                            *
                        FROM
                            wp_topic_test_scores
                        WHERE
                            user = '". $user->user_email ."'
                    ");

                    while($topic_test_score = $topic_test_scores_query->fetch_assoc()){
                        $right_answers += json_decode($topic_test_score['score'])->rights;
                        $wrong_answers += json_decode($topic_test_score['score'])->wrongs;

                         $test_count += 1;
                    }

                    $db_connection->query("
                        INSERT INTO 
                            wp_topic_test_answers_logs(
                                user_email, 
                                test_count,
                                right_answers,
                                wrong_answers
                            )
                        VALUES(
                            '". $user->user_email ."',
                            '". $test_count ."',
                            '". $right_answers ."',
                            '". $wrong_answers ."'
                        )
                    ");                    
                }
            }
        }
    }
}

/**
 * ------------------------------------------------------/
 * Init
 * ------------------------------------------------------/
 */
$connection = new DBConnection();

/**
 * ------------------------------------------------------/
 * Creation tables
 * ------------------------------------------------------/
 */

//1. Base
$connection->createVideoScoreTable();
$connection->createTopicScoreTable();
$connection->createTopicTestScoreTable();
$connection->createSessionRequestTable();
$connection->createUserTestTable();
$connection->createRecoverySessionsTable();
$connection->createUserCourseTable();

//2. Logs
$connection->createAccessLogTable();
$connection->createTopicViewLogTable();
$connection->createTopicMaterialDownloadLogTable();
$connection->createTopicTestAnswerLogTable();
