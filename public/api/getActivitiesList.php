<?php
    session_start();
    require_once "../inc/connect.php";

    class Activities {
        public $conn;
        public $outputArray;
        public $currDate;
        public $attendeeId;
        function __construct($dbConnection, $currentDate, $attendeeID){
            $this->conn = $dbConnection;
            $this->currDate = $currentDate;
            $this->attendeeId = $attendeeID;

            $activitiesSql = "SELECT a.application_id, a.app_name, c.club_name, a.app_startDate, a.app_endDate, a.app_time FROM applications AS a JOIN students AS s ON s.student_id = a.student_id JOIN clubs AS c ON c.club_id = s.club_id WHERE approved = 1";
            $res = mysqli_query($this->conn, $activitiesSql);
            if(!is_bool($res)){
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
                    array_push($columnArray, $currRowColumn[5]);

                    $dateThen = strtotime($currRowColumn[4]);
                    $dateNow = strtotime($this->currDate);

                    if($this->attendeeId != null){
                        $attended = $this->getIfAttendedActivity($this->attendeeId, $currRowColumn[0]);
                        if($attended){
                            array_push($columnArray, '<button class="d-grid mx-auto btn btn-success" style="display: block;" id="attButtonClosed" disabled>Attendance Recorded</button>');
                        } else if($dateNow < $dateThen){
                            array_push($columnArray, '<button class="d-grid mx-auto btn btn-danger" style="display: block;" id="attButtonClosed" disabled>Attendance Not Open</button>');
                        } else if($dateNow > $dateThen){
                            array_push($columnArray, '<button class="d-grid mx-auto btn btn-danger" style="display: block;" id="attButtonClosed" disabled>Attendance Closed</button>');
                        } else {
                            array_push($columnArray, '<button class="d-grid mx-auto btn btn-primary" style="display: block;" id="attButton">Fill Attendance</button>');
                        }
                    } else {
                        array_push($columnArray, $this->getIfAttendedActivity($this->attendeeId, $currRowColumn[0]));
                    }
                    array_push($rowArray, $columnArray);
                }
                $this->outputArray = array(
                    "data" => $rowArray
                );
            }
        }

        function getIfAttendedActivity($attendeeID, $applicationID){
            if($this->attendeeId == null){
                $attendanceSql = "SELECT COUNT(attendee_id) FROM attendances WHERE application_id = $applicationID";
            } else {
                $attendanceSql = "SELECT COUNT(attendee_id) FROM attendances WHERE application_id = $applicationID AND attendee_id = $attendeeID";
            }

            $res = mysqli_query($this->conn, $attendanceSql);
            if(!is_bool($res)){
                if($this->attendeeId != null){
                    $resArr = mysqli_fetch_all($res);
                    foreach($resArr as $currArr){
                        if($currArr[0] == 1){
                            return true;
                        } else {
                            return false;
                        }
                    }
                } else {
                    $resArr = mysqli_fetch_all($res);
                    foreach($resArr as $currArr){
                        return $currArr[0];
                    }
                }
            } else {
                return false;
            }
        }

        function getActivitiesJson(){
            return json_encode($this->outputArray, JSON_PRETTY_PRINT);
        }
    }
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $dateNow = date('Y-m-d');
        $timeNow = date('H:i:s');
        $attendeeId = $_SESSION["attendee_id"];
        if(!isset($_SESSION["attendee_id"])){
            $attendeeId = null;
        }
        header("Content-Type: application/json");
        $atvt = new Activities($conn, $dateNow, $attendeeId);
        echo $atvt->getActivitiesJson();
    }
?>