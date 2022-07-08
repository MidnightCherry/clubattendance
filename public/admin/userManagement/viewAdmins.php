<?php
    session_start();
    if (!isset($_SESSION["admin_id"])){
        $_SESSION["userErrCode"] = "ADMIN_ID_NOT_SET";
        $_SESSION["userErrMsg"] = "The session has expired or is invalid. Please login again. Do contact the administrator if you believe that this should not happen.";
        header("refresh:0;url=/login.php?error=true");
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>UiTM Club Activities Approval System - Admin List</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.2.4/css/fixedHeader.bootstrap.css">
        <link rel="icon" type="image/x-icon" href="https://saringc19.uitm.edu.my/statics/icons/favicon.ico">
    </head>
    <body>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/fixedheader/3.2.4/js/fixedHeader.bootstrap.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.3.0/js/responsive.bootstrap.min.js"></script>
        <script type="text/javascript">
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
            var dataFirstColumn;
            $(document).ready( function () {
                const delModal = new bootstrap.Modal('#delModal', {
                    backdrop: 'static'
                })
                var mainTable = $('#appTable').DataTable({
                                    ajax: {
                                        url: '/api/getUserList.php?type=1',
                                        responsive: true,
                                        dataSrc: 'data',
                                    },
                                    fixedHeader: true
                                });
                $("#appTable tbody").on('click', 'button', function() {
                    var data = mainTable.row($(this).parents('tr')).data();

                    var button = this.id;
                    dataFirstColumn = data[0]
                    if(button == "editButton"){
                        window.location.href = "editUser.php?type=1&user_id="+data[0];
                    }
                    if(button == "delButton"){
                        delModal.show();
                    }
                })
            } );
        </script>
        <!-- Modal -->
        <div class="modal fade" id="delModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Do you really want to delete this user?</p>
                <b>THIS ACTION IS NOT REVERSIBLE!</b>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="window.location.href = 'deleteUser.php?user_id='+dataFirstColumn;">Proceed</button>
            </div>
            </div>
        </div>
        </div>
        <?php
            include("../../../header/header.php");
        ?>
        <nav class="px-5 py-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <?php
                    $currDir = $_SERVER['PHP_SELF'];
                    $currUrl = $_SERVER['PHP_HOST'];
                    $pageTitle = "Application List";
                    include('../../../header/breadcrumb.php');
                ?>
            </ol>
        </nav>
        <div class="px-5">
            <h4>View Admins</h4>
        </div>
        <br>
        <?php
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
        ?>
        <div class="px-5">
            <table id="appTable" class="table table-bordered table-hover dt-responsive">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>User Email</th>
                        <th>Admin Name</th>
                        <th>Admin Telephone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>User ID</th>
                        <th>User Email</th>
                        <th>Admin Name</th>
                        <th>Admin Telephone</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php
            include("../../../header/footer.php");
        ?>
    </body>
</html>