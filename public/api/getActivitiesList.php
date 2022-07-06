<?php
    require_once "../inc/connect.php";

    class Activities {
        public $conn;
        public $outputArray;
        function __construct($dbConnection){
            date_default_timezone_set("Asia/Kuala_Lumpur");
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');
            $this->conn = $dbConnection;

            $trackingSql = "SELECT app_name, app_startDate, app_endDate, app_time FROM applications WHERE approved = 1";
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
                    if((strtotime($currRowColumn[2]) == strtotime($dateNow) && strtotime($currRowColumn[3]) > strtotime($timeNow)) || (strtotime($currRowColumn[2]) > strtotime($dateNow))){
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
        header("Content-Type: application/json");
        $atvt = new Activities($conn);
        echo $atvt->getActivitiesJson();
    }
?>