<?php
    require_once "../inc/connect.php";

    class Activities {
        public $conn;
        public $outputArray;
        public $currDate;
        function __construct($dbConnection, $currentDate){
            $this->conn = $dbConnection;
            $this->currDate = $currentDate;

            $trackingSql = "SELECT a.app_name, c.club_name, a.app_startDate, a.app_endDate, a.app_time FROM applications AS a JOIN students AS s ON s.student_id = a.student_id JOIN clubs AS c ON c.club_id = s.club_id WHERE approved = 1";
            $res = mysqli_query($this->conn, $trackingSql);
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
                    if($currRowColumn[3] != $this->currDate){
                        array_push($columnArray, '<button class="d-grid mx-auto btn btn-danger" style="display: block;" id="viewAppButton">Attendance Closed</button>');
                    } else {
                        array_push($columnArray, '<button class="d-grid mx-auto btn btn-primary" style="display: block;" id="viewAppButton">Fill Attendance</button>');
                    }
                    array_push($rowArray, $columnArray);
                }
                $this->outputArray = array(
                    "data" => $rowArray
                );
                return true;
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
        header("Content-Type: application/json");
        $atvt = new Activities($conn, $dateNow);
        echo $atvt->getActivitiesJson();
    }
?>