<?php
/**
 * ----------------------------------------------/
 * Seetings
 * ----------------------------------------------/
 */
$env = require(__DIR__ . '/../../env.php');
$connection = new mysqli(
    $env['DB_HOST'], 
    $env['DB_USER'], 
    $env['DB_PASSWORD'], 
    $env['DB_NAME']);

if($connection->connect_error){
    die("Connection failed: " . $conn->connect_error);
}else{
    __savePayment($connection);
}

/**
 * ----------------------------------------------/
 * Handlers
 * ----------------------------------------------/
 */
function __savePayment($connection){
    $buy_requests = __getBuyRequests($_REQUEST['email_buyer'], $_REQUEST['reference_sale']);
    // $buy_requests = __getBuyRequests($connection, 'carmen@g.com', 'ComprasMAB-carmen@g.com-1593393016837');

    if($buy_requests){
        if (__saveEnrollment($connection, $buy_requests, 'carmen@g.com')) {
            echo "Enrolled";
        }       
    }
}

function __getBuyRequests($connection, $user, $reference_code){
    $buy_requests = $connection->query("
        SELECT
            *
        FROM
            wp_user_course_buy
        WHERE
            user_email = '". $user ."' AND
            reference_code = '". $reference_code ."' AND
            state = 'PENDING'
    ");
    $buy_requests_array = [];
    
    if($buy_requests && $buy_requests->num_rows > 0){
        while($buy_request = $buy_requests->fetch_assoc()){
            array_push($buy_requests_array, $buy_request);
        }

        return $buy_requests_array;
    }else {
        return false;
    }    
}

function __saveEnrollment($connection, $buy_requests, $user_email){
    $response = false;
    $date_end = date('Y-m-d G:i:s', strtotime('+1 year'));
    $courses = array_map(function($buy_request){
        return $buy_request['course_id'];
    }, $buy_requests);

    foreach($courses as $course){
        $response = $connection->query("
            INSERT INTO 
                wp_user_course_enrollment(user_email, course_id, date_at, date_end, last_date, state)
            VALUES(
                '". $user_email ."',
                '". $course ."',
                '". date("Y-m-d G:i:s") ."',
                '". $date_end ."',
                '". date("Y-m-d G:i:s") ."',
                1
            )
            ON DUPLICATE KEY UPDATE
                date_end = '". $date_end ."',
                last_date = '". date("Y-m-d G:i:s") ."'
        ");

        if(!$response){
            break;
        }
    }

    return $response;    
}