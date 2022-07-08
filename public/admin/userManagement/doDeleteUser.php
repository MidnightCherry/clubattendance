<?php
    session_start();
    if (!isset($_SESSION["admin_id"])){
        $_SESSION["userErrCode"] = "ADMIN_ID_NOT_SET";
        $_SESSION["userErrMsg"] = "The session has expired or is invalid. Please login again. Do contact the administrator if you believe that this should not happen.";
        header("refresh:0;url=/login.php?error=true");
        die();
    }
    $backPage = $_SESSION["backPage"];
    if(!isset($_SESSION["backPage"])){
        $backPage = "index.php";
    }
    if (!isset($_GET["user_id"])){
        $_SESSION["userErrCode"] = "USER_ID_NOT_SET";
        $_SESSION["userErrMsg"] = "The required parameter, USER_ID, is not set. Do contact the administrator if you believe that this should not happen.";
        header("refresh:0;url=$backPage?error=true");
        die();
    }
    $userId = $_GET["user_id"];
    if (!isset($_GET["role"])){
        $_SESSION["userErrCode"] = "ROLE_NOT_SET";
        $_SESSION["userErrMsg"] = "The required parameter, ROLE, is not set. Do contact the administrator if you believe that this should not happen.";
        header("refresh:0;url=$backPage?error=true");
        die();
    }
    $role = $_GET["role"];
    if($_SERVER("REQUEST_METHOD") == "GET"){
        //what to do?
        //delete from role tables
        switch($role){
            case "0":
                //student
                $delRoleSql = "DELETE FROM students WHERE user_id = $userId";
                break;
            case "1":
                //admin
                $delRoleSql = "DELETE FROM admins WHERE user_id = $userId";
                break;
            case "2":
                //officer
                $delRoleSql = "DELETE FROM officers WHERE user_id = $userId";
                break;
            case "3":
                //attendee
                $delRoleSql = "DELETE FROM attendees WHERE user_id = $userId";
                break;
            default:
                break;
        }
        $delRes = mysqli_query($conn, $delRoleSql);
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
        //delete from users table
        $delUserSql = "DELETE FROM users WHERE user_id = $userId";
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
        $_SESSION["userErrCode"] = "DELETE_USER_SUCCESS";
        $_SESSION["userErrMsg"] = "User deleted successfully. Changes will be reflected on the system.";
        header("refresh:0;url=$backPage&signup=success");
    }
?>