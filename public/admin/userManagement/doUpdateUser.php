<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once "../../inc/connect.php";

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
    if (!isset($_SESSION["editing_user_id"])){
        $_SESSION["userErrCode"] = "USER_ID_NOT_SET";
        $_SESSION["userErrMsg"] = "The required parameter related to USER_ID is not set. Do contact the administrator if you believe that this should not happen.";
        header("refresh:0;url=$backPage&error=true");
        die();
    }
    if (!isset($_SESSION["editing_user_type"])){
        $_SESSION["userErrCode"] = "USER_TYPE_NOT_SET";
        $_SESSION["userErrMsg"] = "The required parameter related to USER_TYPE is not set. Do contact the administrator if you believe that this should not happen.";
        header("refresh:0;url=$backPage&error=true");
        die();
    }
    $userType = $_SESSION["editing_user_type"];

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //what to do?
        //if password exists,
        //check if password is good
        if(isset($_POST["password"]) && strlen($_POST["password"] > 1)){
            if(empty(trim($_POST["password"]))){
                $password_err = "Please enter a password.";
                die($password_err);
            } elseif(strlen(trim($_POST["password"])) < 8){
                $_SESSION["userErrCode"] = "INVALID_PASSWORD";
                $_SESSION["userErrMsg"] = "Password is invalid. Password must have at least 8 characters.";
                header("refresh:0;url=$backPage&error=true");
                die();
            } else{
                $password = trim($_POST["password"]);
            }
            $password = password_hash($password, PASSWORD_DEFAULT);
        }
        //check if email would cause duplication
        $userEmail = $_POST["email"];
        $userId = $_SESSION["editing_user_id"];
        $emailsql = "SELECT count(user_email) FROM users WHERE user_email = (?) AND NOT user_id = (?)" ;
        if ($stmt=mysqli_prepare($conn, $emailsql)){
            mysqli_stmt_bind_param($stmt, "si", $user_email, $user_id);

            $user_email = $userEmail;
            $user_id = $userId;

            if(mysqli_stmt_execute($stmt)){
                $emailArray = mysqli_fetch_array(mysqli_stmt_get_result($stmt));
                $userEmail = $emailArray["count(user_email)"];
                if($userEmail > 0 || $userEmail != NULL){
                    $_SESSION["userErrCode"] = "EMAIL_EXISTS";
                    $_SESSION["userErrMsg"] = "The account for this email already exists.";
                    header("refresh:0;url=$backPage&error=true");
                    die();
                }//end if
                //echo "SUCCESS QUERY USERS TABLE FOR EMAIL!<br>";
            } else {
                $_SESSION["userErrCode"] = "MYSQL_ERROR";
                $_SESSION["userErrMsg"] = "MySQL error encountered: ".mysqli_error($conn)." Please contact the administrator if you believe that this should not happen.";
                header("refresh:0;url=$backPage&error=true");
                die();
            }

            mysqli_stmt_close($stmt);
        }
        //update users table
        if(!isset($_POST["password"])){
            $usersSql = "UPDATE users SET user_email = $userEmail WHERE user_id = $userId";
        } else {
            $usersSql = "UPDATE users SET user_email = $userEmail, user_pass = $password WHERE user_id = $userId";
        }
        $appRes = mysqli_query($conn, $usersSql);
        if(is_bool($appRes)){
            if($appRes){
                //success update
            } else {
                $_SESSION["userErrCode"] = "MYSQL_ERROR";
                $_SESSION["userErrMsg"] = "MySQL error encountered: ".mysqli_error($conn).". Please contact the administrator if you believe that this should not happen.";
                header("refresh:0;url=$backPage&error=true");
                die();
            }
        } else {
            $_SESSION["userErrCode"] = "MYSQL_ERROR";
            $_SESSION["userErrMsg"] = "MySQL error encountered: ".mysqli_error($conn)." Please contact the administrator if you believe that this should not happen.";
            header("refresh:0;url=$backPage&error=true");
            die();
        }
        //update respective roles table
        $name = $_POST["name"];
        $telno = $_POST["telephone"];
        $clubId = $_POST["clubid"];
        $course = $_POST["courseCode"];
        switch ($userType){
            case "0":
                $roleSql = "UPDATE students SET student_name = $name, student_telno = $telno, club_id = $clubId WHERE user_id = $userId";
                break;
            case "1":
                $roleSql = "UPDATE admins SET admin_name = $name, admin_telno = $telno WHERE user_id = $userId";
                break;
            case "2":
                $roleSql = "UPDATE officers SET officer_name = $name, officer_telno = $telno WHERE user_id = $userId";
                break;
            case "3":
                $roleSql = "UPDATE attendees SET attendee_name = $name, attendee_telno = $telno, attendee_course = $course WHERE user_id = $userId";
                break;
            default:
                break;
        }
        $appRes = mysqli_query($conn, $roleSql);
        if(is_bool($appRes)){
            if($appRes){
                //success update
            } else {
                $_SESSION["userErrCode"] = "MYSQL_ERROR";
                $_SESSION["userErrMsg"] = "MySQL error encountered: ".mysqli_error($conn).". Please contact the administrator if you believe that this should not happen.";
                header("refresh:0;url=$backPage&error=true");
                die();
            }
        } else {
            $_SESSION["userErrCode"] = "MYSQL_ERROR";
            $_SESSION["userErrMsg"] = "MySQL error encountered: ".mysqli_error($conn)." Please contact the administrator if you believe that this should not happen.";
            header("refresh:0;url=$backPage&error=true");
            die();
        }
        $_SESSION["userErrCode"] = "UPDATE_USER_SUCCESS";
        $_SESSION["userErrMsg"] = "User updated successfully. Changes will be reflected on the system.";
        header("refresh:0;url=$backPage&signup=success");
    }
?>