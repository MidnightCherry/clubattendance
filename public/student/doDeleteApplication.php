<?php
    session_start();
    if (!isset($_SESSION["student_id"])){
        $_SESSION["userErrCode"] = "STUDENT_ID_NOT_SET";
        $_SESSION["userErrMsg"] = "The session has expired or is invalid. Please login again. Do contact the administrator if you believe that this should not happen.";
        header("refresh:0;url=/login.php?error=true");
        die();
    }
    $backPage = $_SESSION["backPage"];
    if(!isset($_SESSION["backPage"])){
        $backPage = "index.php";
    }
    if (!isset($_GET["app_id"])){
        $_SESSION["userErrCode"] = "APP_ID_NOT_SET";
        $_SESSION["userErrMsg"] = "The required parameter, APP_ID, is not set. Do contact the administrator if you believe that this should not happen.";
        header("refresh:0;url=$backPage?error=true");
        die();
    }
    $appId = $_GET["app_id"];
    if($_SERVER("REQUEST_METHOD") == "GET"){
        //what to do?
        //delete from users table
        $delUserSql = "DELETE FROM applications WHERE application_id = $appId";
        $delRes = mysqli_query($conn, $delUserSql);
        if(is_bool($delRes)){
            if($delRes){
                //success update
            } else {
                $_SESSION["userErrCode"] = "MYSQL_ERROR";
                $_SESSION["userErrMsg"] = "MySQL error encountered: ".mysqli_error($conn).". Please contact the administrator if you believe that this should not happen.";
                header("refresh:0;url=$backPage?error=true");
                die();
            }
        } else {
            $_SESSION["userErrCode"] = "MYSQL_ERROR";
            $_SESSION["userErrMsg"] = "MySQL error encountered: ".mysqli_error($conn)." Please contact the administrator if you believe that this should not happen.";
            header("refresh:0;url=$backPage?error=true");
            die();
        }
        $_SESSION["userErrCode"] = "DELETE_APPLICATION_SUCCESS";
        $_SESSION["userErrMsg"] = "Application deleted successfully. Changes will be reflected on the system.";
        header("refresh:0;url=$backPage&signup=success");
    }
?>