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

    public function createUserBehaviourTestTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_user_behaviour_tests(
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
            last_date DATETIME NULL,
            PRIMARY KEY (user_email, course_id)
        )");
    }    

    public function createUserCourseBuyTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_user_course_buy(
            id INT NOT NULL AUTO_INCREMENT,
            date_at DATETIME NOT NULL,
            reference_code VARCHAR(100) NOT NULL,
            user_email VARCHAR(100) NOT NULL,
            course_id INT NOT NULL,
            state VARCHAR(50) NOT NULL,
            PRIMARY KEY (id, reference_code)
        )");
    } 

    public function createUserCourseEnrrollmentTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_user_course_enrollment(
            user_email VARCHAR(100) NOT NULL,
            course_id INT NOT NULL,
            date_at DATETIME NOT NULL,
            date_end DATETIME NULL,
            last_date DATETIME NULL,
            state INT NOT NULL,
            PRIMARY KEY (user_email, course_id)
        )");
    }

    public function createUserTopicTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_user_topic(
            user_email VARCHAR(50) NOT NULL,
            topic_id INT NOT NULL,
            video_viewed INT NOT NULL DEFAULT 0,
            summary_downloaded INT NOT NULL DEFAULT 0,
            map_downloaded INT NOT NULL DEFAULT 0,
            worksheet_downloaded INT NOT NULL DEFAULT 0,
            solutions_downloaded INT NOT NULL DEFAULT 0,
            PRIMARY KEY (user_email, topic_id)
        )");
    }

    public function createQuestionaryTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_questionaries(
            id INT NOT NULL AUTO_INCREMENT,
            date_at DATE NOT NULL, 
            rol VARCHAR(10) NOT NULL,
            user_email VARCHAR(50) NOT NULL,
            result TEXT NOT NULL,
            PRIMARY KEY (id, user_email)
        )");
    }    

    public function createPollTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_polls(
            id INT NOT NULL AUTO_INCREMENT,
            date_at DATE NOT NULL, 
            rol VARCHAR(10) NOT NULL,
            user_email VARCHAR(50) NOT NULL,
            result TEXT NOT NULL,
            PRIMARY KEY (id, user_email)
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

    public function createTopicVideoLogTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_topic_video_logs(
            user_email VARCHAR(50) NOT NULL,
            views INT NOT NULL,
            last_topic INT NOT NULL,
            last_date DATETIME NOT NULL,
            PRIMARY KEY (user_email)            
        )");
    }

    public function createTopicMaterialLogTable(){
        $this->connection->query("CREATE TABLE IF NOT EXISTS wp_topic_material_logs(
            user_email VARCHAR(50) NOT NULL,
            downloads INT NOT NULL,
            last_topic INT NOT NULL,
            last_date DATETIME NOT NULL,
            PRIMARY KEY (user_email)            
        )");
    }

    public function createTopicTestLogTable(){
        $response = $this->connection->query("CREATE TABLE IF NOT EXISTS wp_topic_test_logs(
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

        $this::seederTopicTestLogTable($this->connection);
    }


    /**
     * ----------------------------------------------------/
     * Seeders
     * ----------------------------------------------------/
     */

    private function seederTopicTestLogTable($db_connection){
        $query = $db_connection->query("SELECT * FROM wp_topic_test_logs");

        if($query->num_rows == 0){
            $users = get_users();

            if (!empty($users )){
                foreach($users as $user){
                    $right_answers = 0;
                    $wrong_answers = 0;
                    $test_count = 0;
                    $course_id = 0;

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
                        $course_id = $topic_test_score['course_id'];

                        $test_count += 1;
                    }

                    if($right_answers > 0 || $wrong_answers > 0){
                        $response = $db_connection->query("
                            INSERT INTO 
                                wp_topic_test_logs(
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
                        
                        if($response) {
                            $db_connection->query("
                                INSERT INTO 
                                    wp_user_course(user_email, course_id, test_count, right_answers, wrong_answers)
                                VALUES(
                                    '". $user->user_email ."',
                                    '". $course_id ."',
                                    '". $test_count ."',
                                    '". $right_answers ."',
                                    '". $wrong_answers ."'
                                )
                                ON DUPLICATE KEY UPDATE
                                    test_count = test_count + 1,
                                    right_answers = right_answers + '". $right_answers ."',
                                    wrong_answers = wrong_answers + '". $wrong_answers ."'
                            ");
                        }
                    }
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
$connection->createUserBehaviourTestTable();
$connection->createRecoverySessionsTable();
$connection->createUserCourseTable();
$connection->createUserCourseBuyTable();
$connection->createUserCourseEnrrollmentTable();
$connection->createUserTopicTable();
$connection->createQuestionaryTable();
$connection->createPollTable();

//2. Logs
$connection->createAccessLogTable();
$connection->createTopicVideoLogTable();
$connection->createTopicMaterialLogTable();
$connection->createTopicTestLogTable();
