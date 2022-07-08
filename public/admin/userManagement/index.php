<?php
    session_start();
    if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == ""){
        $_SESSION["userErrCode"] = "ADMIN_ID_NOT_SET";
        $_SESSION["userErrMsg"] = "The session has expired or is invalid. Please login again. Do contact the administrator if you believe that this should not happen.";
        header("refresh:0;url=/login.php?error=true");
        die();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>UiTM Club Activities Attendance System - User Management</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <link rel="icon" type="image/x-icon" href="https://saringc19.uitm.edu.my/statics/icons/favicon.ico">
    </head>
    <body>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
        <?php
            include("../../../header/header.php");
        ?>
        <nav class="px-5 py-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <?php
                    $currDir = "/admin/userManagement";
                    $currUrl = $_SERVER['PHP_HOST'];
                    $pageTitle = "User Management";
                    include('../../../header/breadcrumb.php');
                ?>
            </ol>
        </nav>
        <div class="px-5">
            <div class="text-center">
                <h1>List Users</h1>
            </div>
            <div>
                <div class="row">
                    <h4 class="pb-4">Available actions:</h4>
                    <div class="col p-2">
                        <b class="pb-2">View, Edit, and Delete Students: </b>
                        <button type="button" class="btn btn-primary" onclick="location.href='viewStudents.php';">View Students</button>
                    </div>
                    <div class="col p-2">
                        <b class="pb-2">View, Edit, and Delete Officers: </b>
                        <button type="button" class="btn btn-primary" onclick="location.href='viewOfficers.php';">View Officers</button>
                    </div>
                    <div class="col p-2">
                        <b class="pb-2">View, Edit, and Delete Admins: </b>
                        <button type="button" class="btn btn-primary" onclick="location.href='viewAdmins.php';">View Admins</button>
                    </div>
                    <div class="col p-2">
                        <b class="pb-2">View, Edit, and Delete Attendees: </b>
                        <button type="button" class="btn btn-primary" onclick="location.href='viewAttendees.php';">View Attendees</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
            include("../../../header/footer.php");
        ?>
    </body>
</html>