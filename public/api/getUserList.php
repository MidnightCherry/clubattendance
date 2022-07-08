<?php
    require_once "../inc/connect.php";

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        class User {
            //init all tracking stuff
            public $conn;
            public $mode;
            function __construct($dbconnection, $mode) {
                $this->conn = $dbconnection;
                $this->mode = $mode;
            }
    
            function getListJson() {
                if($this->mode == 0){ 
                    //student
                    $itemListSql = "SELECT u.user_id, u.user_email, s.student_name, s.student_telno, c.club_name FROM users AS u JOIN students AS s ON u.user_id = s.user_id JOIN clubs AS c ON c.club_id = s.club_id";
                } else if($this->mode == 1){
                    //admin
                    $itemListSql = "SELECT u.user_id, u.user_email, a.admin_name, a.admin_telno FROM users AS u JOIN admins AS a ON u.user_id = a.user_id";
                } else if($this->mode == 2){
                    //officer
                    $itemListSql = "SELECT u.user_id, u.user_email, o.officer_name, o.officer_telno FROM users AS u JOIN officers AS o ON u.user_id = o.user_id";
                } else if($this->mode == 3){
                    //attendees
                    $itemListSql = "SELECT u.user_id, u.user_email, a.attendee_name, a.attendee_telno, a.attendee_course FROM users AS u JOIN attendees AS a ON u.user_id = a.user_id";
                } else {
                    $itemListSql = "SELECT u.user_id, u.user_email FROM users AS u";
                }

                $res = mysqli_query($this->conn, $itemListSql);
                if(!is_bool($res)){
                    $rowArray = array();
                    $resArr = mysqli_fetch_all($res);
                    $resArr = array_values($resArr);
                    if($this->mode == 0){
                        foreach($resArr as $currRowColumn){
                            $columnArray = array();
                            array_push($columnArray, $currRowColumn[0]);
                            array_push($columnArray, $currRowColumn[1]);
                            array_push($columnArray, $currRowColumn[2]);
                            array_push($columnArray, $currRowColumn[3]);
                            array_push($columnArray, $currRowColumn[4]);
                            array_push($columnArray, '<button class="d-grid mx-auto btn btn-primary" style="display: block;" id="editButton">Edit Student</button>
                                                        <br><button class="d-grid mx-auto btn btn-danger" style="display: block;" id="delButton">Delete Student</button>');
                            array_push($rowArray, $columnArray);
                        }
                    } else if($this->mode == 3){
                        foreach($resArr as $currRowColumn){
                            $columnArray = array();
                            array_push($columnArray, $currRowColumn[0]);
                            array_push($columnArray, $currRowColumn[1]);
                            array_push($columnArray, $currRowColumn[2]);
                            array_push($columnArray, $currRowColumn[3]);
                            array_push($columnArray, $currRowColumn[4]);
                            array_push($columnArray, '<button class="d-grid mx-auto btn btn-primary" style="display: block;" id="editButton">Edit Attendee</button>
                                                        <br><button class="d-grid mx-auto btn btn-danger" style="display: block;" id="delButton">Delete Attendee</button>');
                            array_push($rowArray, $columnArray);
                        }
                    } else if($this->mode <= 2){
                        foreach($resArr as $currRowColumn){
                            $columnArray = array();
                            array_push($columnArray, $currRowColumn[0]);
                            array_push($columnArray, $currRowColumn[1]);
                            array_push($columnArray, $currRowColumn[2]);
                            array_push($columnArray, $currRowColumn[3]);
                            array_push($columnArray, '<button class="d-grid mx-auto btn btn-primary" style="display: block;" id="editButton">Edit User</button>
                                                        <br><button class="d-grid mx-auto btn btn-danger" style="display: block;" id="delButton">Delete User</button>');
                            array_push($rowArray, $columnArray);
                        }
                    } else {
                        foreach($resArr as $currRowColumn){
                            $columnArray = array();
                            array_push($columnArray, $currRowColumn[0]);
                            array_push($columnArray, $currRowColumn[1]);
                            array_push($rowArray, $columnArray);
                        }
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
        if(isset($_GET["type"])){
            $type = $_GET["type"];
        } else {
            $type = null;
        }
        $appJson = new User($conn, $type);
        if(!$appJson->getListJson()){
            header('X-PHP-Response-Code: 500', true, 500);
            die();
        }
        header("Content-Type: application/json");
        echo $appJson->getListJson();
        die();
    } else {
        header('X-PHP-Response-Code: 500', true, 500);
        die();
    }
?>