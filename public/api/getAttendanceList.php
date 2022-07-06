<?php
    require_once "../inc/connect.php";

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        class AttendanceList {
            public $conn;
            public $appId;
            function __construct($dbconnection, $applicationId) {
                $this->conn = $dbconnection;
                $this->appId = $applicationId;
            }
    
            function getAttendanceListJson() {
                if($this->appId != null){
                    $attendeeSql = "SELECT t.attendance_id, t.attendance_date, t.attendance_time, a.attendee_name, p.app_name FROM attendances AS t JOIN attendees AS a ON a.attendee_id = t.attendee_id JOIN applications AS p ON t.application_id = p.application_id WHERE t.application_id = ".$this->appId;
                } else {
                    $attendeeSql = "SELECT t.attendance_id, t.attendance_date, t.attendance_time, a.attendee_name, p.app_name FROM attendances AS t JOIN attendees AS a ON a.attendee_id = t.attendee_id JOIN applications AS p ON t.application_id = p.application_id";
                }

                $res = mysqli_query($this->conn, $attendeeSql);
                if(!is_bool($res)){
                    $tableArray = array();
                    $rowArray = array();
                    $resArr = mysqli_fetch_all($res);
                    $resArr = array_values($resArr);
                    foreach($resArr as $currRowColumn){
                        $columnArray = array();
                        array_push($columnArray, $currRowColumn[0]);
                        array_push($columnArray, $currRowColumn[1]);
                        array_push($columnArray, $currRowColumn[2]);
                        array_push($columnArray, $currRowColumn[3]);
                        array_push($columnArray, $currRowColumn[4]);
                        array_push($rowArray, $columnArray);
                    }
                    $outputArray = array(
                        "data" => $rowArray
                    );
                } else {
                    return false;
                }

                return json_encode($outputArray, JSON_PRETTY_PRINT);
            }
        }
        if(isset($_GET["app_id"])){
            $appId = $_GET["app_id"];
        } else {
            $appId = null;
        }
        $appJson = new AttendanceList($conn, $appId);
        if(!$appJson->getAttendanceListJson()){
            header('X-PHP-Response-Code: 500', true, 500);
            die();
        }
        header("Content-Type: application/json");
        echo $appJson->getAttendanceListJson();
        die();
    } else {
        header('X-PHP-Response-Code: 500', true, 500);
        die();
    }
?>