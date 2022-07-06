<?php
    require_once "../public/inc/connect.php";
    $password = password_hash("digittend@1912", PASSWORD_DEFAULT);
    $email = "admin@isp.alz.moe";
    $name = "Admin";
    $tel = "0123456789";
    $role = 0;

    $signUpSQL = "INSERT INTO users (user_email, user_pass, user_type) VALUES (?, ?, ?)";
    if ($stmt=mysqli_prepare($conn, $signUpSQL)){
        mysqli_stmt_bind_param($stmt, "ssi", $db_email, $db_password, $db_type);

        $db_email = $email;
        $db_password = $password;
        $db_type = $role;

        if(mysqli_stmt_execute($stmt)){
            //echo "SUCCESS ADD TO USERS TABLE!<br>";
        } else {
            $_SESSION["userErrCode"] = "MYSQL_ERROR";
            $_SESSION["userErrMsg"] = "MySQL error encountered: ".mysqli_error($conn)." Please contact the administrator if you believe that this should not happen.";
            header("refresh:0;url=$backPage?error=true");
            die();
        }

        mysqli_stmt_close($stmt);
    }

    $getUserIDSQL = "SELECT user_id FROM users WHERE user_email = (?)";
    if ($stmt=mysqli_prepare($conn, $getUserIDSQL)){
        mysqli_stmt_bind_param($stmt, "s", $user_email);

        $user_email = $email;

        if(mysqli_stmt_execute($stmt)){
            $usersArray = mysqli_fetch_array(mysqli_stmt_get_result($stmt));
            $userId = $usersArray["user_id"];
            //echo "SUCCESS QUERY USERS TABLE!<br>";
        } else {
            $_SESSION["userErrCode"] = "MYSQL_ERROR";
            $_SESSION["userErrMsg"] = "MySQL error encountered: ".mysqli_error($conn)." Please contact the administrator if you believe that this should not happen.";
            header("refresh:0;url=$backPage?error=true");
            die();
        }

        mysqli_stmt_close($stmt);
    }

    $adminSignUpSQL = "INSERT INTO admins (admin_name, admin_telno, user_id) VALUES (?, ?, ?)";
    if ($stmt=mysqli_prepare($conn, $adminSignUpSQL)){
        mysqli_stmt_bind_param($stmt, "ssi", $adm_name, $adm_telno, $u_id);

        $adm_name = $name;
        $adm_telno = $tel;
        $u_id = $userId;

        if(mysqli_stmt_execute($stmt)){
            //echo "SUCCESS ADD TO ADMINS TABLE!<br>";
        } else {
            $_SESSION["userErrCode"] = "MYSQL_ERROR";
            $_SESSION["userErrMsg"] = "MySQL error encountered: ".mysqli_error($conn)." Please contact the administrator if you believe that this should not happen.";
            header("refresh:0;url=$backPage?error=true");
            die();
        }

        mysqli_stmt_close($stmt);
    }

?>