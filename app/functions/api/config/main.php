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

    /**
     * ----------------------------------------------------/
     * Logs
     * ----------------------------------------------------/
     */
    public function createLoginLogTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_login_logs(
            user_email VARCHAR(50) NOT NULL,
            login_count INT NOT NULL,
            last_date DATETIME NOT NULL,
            PRIMARY KEY (user_email)
        )");
    }

    public function createVideoViewLogTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_video_views_logs(
            user_email VARCHAR(50) NOT NULL,
            video_views INT NOT NULL,
            last_video INT NOT NULL,
            last_date DATETIME NOT NULL,
            PRIMARY KEY (user_email)            
        )");
    }

    public function createExerciseDownloadLogTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_exercise_download_logs(
            user_email VARCHAR(50) NOT NULL,
            exercises_download INT NOT NULL,
            last_exercise INT NOT NULL,
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

//2. Logs
$connection->createLoginLogTable();
$connection->createVideoViewLogTable();
$connection->createExerciseDownloadLogTable();
$connection->createTopicTestAnswerLogTable();
