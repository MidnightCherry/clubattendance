<?php
    session_start();
    if (!isset($_SESSION["attendee_id"])){
        $_SESSION["userErrCode"] = "ATTENDEE_ID_NOT_SET";
        $_SESSION["userErrMsg"] = "The session has expired or is invalid. Please login again. Do contact the administrator if you believe that this should not happen.";
        header("refresh:0;url=/login.php?error=true");
        die();
    }
    if (!isset($_GET["app_id"])){
        $_SESSION["userErrCode"] = "APP_ID_NOT_SET";
        $_SESSION["userErrMsg"] = "Required parameter APP_ID is not available. Please contact the administrator if you believe that this should not happen.";
        header("refresh:0;url=/admin/applicationList.php?error=true");
        die();
    }

    $backPage = $_SESSION["backPage"];

    if(!isset($_SESSION["backPage"])){
        //backPage is not set, defaulting to applicationDetails.php
        $backPage = "applicationDetails.php";
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // Include config file
    require_once "../inc/connect.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $attendeeId = $_SESSION["attendee_id"];
        $appId = $_GET["app_id"];
        //get date and time
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $dateNow = date('Y-m-d');
        $timeNow = date('H:i:s');

        $addTrackingSQL = "INSERT INTO attendances (attendance_date, attendance_time, attendee_id, application_id) VALUES (?, ?, ?, ?)";
        if ($stmt=mysqli_prepare($conn, $addTrackingSQL)){
            mysqli_stmt_bind_param($stmt, "sssi", $at_date, $at_time, $att_id, $app_id);

            $at_date = $dateNow;
            $at_time = $timeNow;
            $att_id = $attendeeId;
            $app_id = $appId;

            if(mysqli_stmt_execute($stmt)){
                //echo "SUCCESS ADD TO tracking TABLE!<br>";
            } else {
                $_SESSION["userErrCode"] = "MYSQL_ERROR";
                $_SESSION["userErrMsg"] = "MySQL error encountered: ".mysqli_error($conn)." Please contact the administrator if you believe that this should not happen.";
                header("refresh:0;url=$backPage?error=true");
                die();
            }

            mysqli_stmt_close($stmt);
        }
        $_SESSION["userErrCode"] = "UPDATE_APPLICATION_SUCCESS";
        $_SESSION["userErrMsg"] = "Application updated. Please wait for the officer to approve or reject the application.";
        header("refresh:0;url=$backPage?signup=success");
    }
?>