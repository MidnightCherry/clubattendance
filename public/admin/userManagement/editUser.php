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
    if(!isset($_GET["type"])){
        $_SESSION["userErrCode"] = "USER_TYPE_NOT_SET";
        $_SESSION["userErrMsg"] = "The user type is not set. Do contact the administrator if you believe that this should not happen.";
        header("refresh:0;url=$backPage?error=true");
        die();
    }
    $userType = $_GET["type"];
    $_SESSION["backPage"] = "editUser.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>UiTM Club Activities Attendance System - Edit User</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
        <link rel="icon" type="image/x-icon" href="https://saringc19.uitm.edu.my/statics/icons/favicon.ico">
    </head>
    <body>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
        <?php
            include("../../../header/header.php");
        ?>
        <nav class="px-5 py-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <?php
                    if($userType == 0){
                        $urlStr = "/admin/userManagement/viewStudents.php/editUser.php";
                    } else if($userType == 1){
                        $urlStr = "/admin/userManagement/viewAdmins.php/editUser.php";
                    } else if($userType == 2){
                        $urlStr = "/admin/userManagement/viewOfficers.php/editUser.php";
                    } else if($userType == 3){
                        $urlStr = "/admin/userManagement/viewAttendees.php/editUser.php";
                    } else {
                        $urlStr = $_SERVER["PHP_SELF"];
                    }
                    $currDir = $urlStr;
                    $currUrl = $_SERVER['PHP_HOST'];
                    $pageTitle = "Edit User";
                    include('../../../header/breadcrumb.php');
                ?>
            </ol>
        </nav>
        <div class="container px-5">
            <h1 class="pb-4">Edit Application</h1>
            <?php
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
                require_once "../../inc/connect.php";

                $thisApp = array();

                //check if $_GET isset
                if(isset($_GET["error"])){
                    //error exists
                    echo "<div class=\"alert alert-danger my-4\" style=\"margin-left: 13%; margin-right: 13%;\">";
                    if(isset($_SESSION["userErrMsg"])){
                        //get err msg
                        $errMsg = $_SESSION["userErrMsg"];
                        $errCode = $_SESSION["userErrCode"];
                        echo "<h5 style=\"text-align: justify; text-justify: inter-word;\">$errMsg</h5>";
                        echo "<br><p>Error code: $errCode</p>";
                    }
                    echo "</div>";
                }
                if(isset($_GET["signup"])){
                    echo "<div class=\"alert alert-success my-4\" style=\"margin-left: 13%; margin-right: 13%;\">";
                    if(isset($_SESSION["userErrMsg"])){
                        //get err msg
                        $errMsg = $_SESSION["userErrMsg"];
                        $errCode = $_SESSION["userErrCode"];
                        echo "<h5 style=\"text-align: justify; text-justify: inter-word;\">$errMsg</h5>";
                    }
                    echo "</div>";
                }

                //get userinfo
                if($userType == 0){
                    //students
                    $getUserSQL = "SELECT s.student_name, u.user_email, s.student_telno, c.club_id FROM users AS u JOIN students AS s ON u.user_id = s.user_id JOIN clubs AS c ON s.club_id = c.club_id";
                } else if($userType == 1){
                    //admins
                    $getUserSQL = "SELECT a.admin_name, u.user_email, a.admin_telno FROM users AS u JOIN admins AS a ON u.user_id = a.user_id";
                } else if($userType == 2){
                    //officers
                    $getUserSQL = "SELECT o.officer_name, u.user_email, o.officer_telno FROM users AS u JOIN officers AS o ON u.user_id = o.user_id";
                } else if($userType == 3){
                    //attendee
                    $getUserSQL = "SELECT a.attendee_name, u.user_email, a.attendee_telno, a.attendee_course FROM users AS u JOIN attendees AS a ON u.user_id = a.user_id";
                } else {
                    //invalid userType
                }
                $appRes = mysqli_query($conn, $getUserSQL);
                if(!is_bool($appRes)){
                    $appArr = mysqli_fetch_all($appRes);
                    //$appArr = array_values($appArr);
                    foreach($appArr as $currApp){
                        for($i = 0; $i < sizeof($currApp); $i++){
                            array_push($thisApp, $currApp[$i]);
                        }
                    }
                } else {
                    echo "what";
                    header('X-PHP-Response-Code: 500', true, 500);
                    die();
                }
            ?>
            <form id="updateForm" action="./doUpdateUser.php" method="post">
                <div class="form-floating mb-3">
                    <input class="form-control" name="name" id="name" type="text" value="<?php echo $thisApp[0] ?>" placeholder="Application Name" required/>
                    <label for="name">Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="email" id="email" type="email" value="<?php echo $thisApp[1] ?>"  placeholder="Email Address" required/>
                    <label for="email">Email Address</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="telephone" id="telephone" type="telephone" value="<?php echo $thisApp[2] ?>"  placeholder="telephone" required/>
                    <label for="telephone">telephone</label>
                </div>
                <div class="form-floating mb-3" id="clubField" style="display: none;">
                    <select class="form-select" name="clubid" id="clublist" aria-label="Club" required>
                        <option value=""></option>
                        <!--Code here-->
                    </select>
                    <label for="clubid">Club</label>
                </div>
                <div class="form-floating mb-3" id="courseId" style="display: none;">
                    <input class="form-control" name="courseCode" id="courseCode" type="text" value="<?php echo $thisApp[3]?>" placeholder="Course Code" required/>
                    <label for="courseCode">Course Code</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="password" id="password" type="password" placeholder="Password" autocomplete="new-password"/>
                    <label for="password">Password (Type to Change)</label>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary btn-lg" id="submitButton" type="submit" disabled>Submit</button>
                </div>
            </form>
        </div>
        <?php
            include("../../../header/footer.php");
        ?>
        <script type="text/javascript">
            var xmlhttp = new XMLHttpRequest();
            var url = "/clubs/getClubId.php";
            var currClub = <?php echo $thisApp[3] ?>;
            var role = <?php echo $userType ?>;

            if(role == 0) {
                    document.getElementById('courseId').style.display = "none";
                    document.getElementById('courseCode').required = false;
                    document.getElementById('courseCode').innerText = null;
                    document.getElementById('clubField').style.display = "block";
                    document.getElementById('clublist').required = true;
                } else if(role == 3) {
                    document.getElementById('clubField').style.display = "none";
                    document.getElementById('clublist').required = false;
                    document.getElementById('courseId').style.display = "block";
                    document.getElementById('courseCode').required = true;
                } else {
                    document.getElementById('clubField').style.display = "none";
                    document.getElementById('clublist').required = false;
                    document.getElementById('courseId').style.display = "none";
                    document.getElementById('courseCode').required = false;
                    document.getElementById('courseCode').innerText = null;
                }

            xmlhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    var htmlData = "<option value=\"\"></option>";
                    for(let i = 0; i < data.clubId.length; i++){
                        if(data.clubId[i] == currClub && role == 0){
                            htmlData = htmlData.concat("\n", "<option selected value=\""+data.clubId[i]+"\">"+data.clubName[i]+"</option>\n");
                        } else {
                            htmlData = htmlData.concat("\n", "<option value=\""+data.clubId[i]+"\">"+data.clubName[i]+"</option>\n");
                        }
                    }
                    document.getElementById("clublist").innerHTML = htmlData;
                }
            }
            xmlhttp.open("GET", url, true);
            xmlhttp.send();
            $(document).ready(function() {
                $('#updateForm').on('keyup input change', function() {
                    if(($('#name').val() != "<?php echo $thisApp[0] ?>") || ($('#email').val() != "<?php echo $thisApp[1] ?>") || ($('#telephone').val() != "<?php echo $thisApp[2] ?>") || ($('#clubid').val() != "<?php echo $thisApp[3] ?>") || ($('#clubid').val() != "<?php echo $thisApp[3] ?>") || ($('#password').val().length >= 1)){
                        $('#submitButton').attr('disabled', false);
                    } else {
                        $('#submitButton').attr('disabled', true);
                    }
                });
            })
        </script>
    </body>
</html>