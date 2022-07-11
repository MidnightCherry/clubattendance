<?php session_start() ?>
<style>
    html {
        height: 100%;
    }
    body {
        background-color: #7700dd;
        color: white;
        position: relative;
        margin: 0;
        padding-bottom: 6rem;
        min-height: 100%;
    }
    .btn-white{
        background-color: #ffffff;
    }
    .btn-primary{
        background-color: #7700ff;
        color: #ffffff;
        border-color: #7700dd;
    }
    .btn-primary:hover{
        background-color: #ffffff;
        color: #7700dd;
        border-color: #7700dd;
    }
    .btn-primary:active{
        background-color: #7700cc;
        color: #ffffff;
        border-color: #7700dd;
    }
    .btn-primary:focus{
        background-color: #7700dd;
        color: #ffffff;
        border-color: #7700dd;
    }
    .btn-primary:disabled{
        background-color: #7700ff;
        color: #ffffff;
    }
    .bg-uitm{
        background-color: #7700dd;
        color: white;
    }
    label, .form-control {
        color: #000000; 
    }
    .nav-link{
        color: #ffffff;
    }
    .nav-link:hover{
        color: #ffffff;
    }
    .nav-pills .nav-link.active,
    .nav-pills .show > .nav-link{
        background-color: #ffffff;
        color: #7700dd;
    }
    .footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        padding: 1rem;
        background-color: #efefef;
        text-align: center;
    }
</style>
<div class="px-5 shadow bg-white <?php if($_SERVER["PHP_SELF"] != "/index.php" || $_SERVER["PHP_SELF"] == "/login.php"){echo "mb-5";} ?>">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3">
        <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
            <!--svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg-->
            <img src="https://saringc19.uitm.edu.my/statics/LogoUiTM.png" class="" height="50px" alt="UiTM Logo">
            <p class="h6 ps-3">Club Activities Attendance System</p>
        </a>

        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="/" class="nav-link px-2 link-secondary">Home</a></li>
            <li><a href="/clubs/index.php" class="nav-link px-2 link-dark">Clubs</a></li>
            <?php
            /*
                if(isset($_SESSION["uid"])){
                    echo '<li><a href="/doSignOut.php" class="nav-link px-2 link-dark">Logout</a></li>';
                } else {
                    echo '<li><a href="/login.php" class="nav-link px-2 link-dark">Login</a></li>';
                }
            */
            ?>
            <li><a href="/contact.php" class="nav-link px-2 link-dark">Contact</a></li>
            <li><a href="/faq.php" class="nav-link px-2 link-dark">FAQs</a></li>
            <li><a href="/about.php" class="nav-link px-2 link-dark">About</a></li>
        </ul>

        <div class="col-md-3 text-end">
            <?php
                if(isset($_SESSION["uid"])){
                    $url = $_SESSION["utype"];
                    $shortName = strtok($_SESSION["name"], " ");
                    echo "<label class=\"px-2\">Welcome, <a class=\"text-decoration-none\" href=/".$url."/>".$shortName."</a>!</label>";
                    echo '<button type="button" class="btn btn-danger" onclick="location.href=\'/doSignOut.php\';">Logout</button>';
                } else {
                    echo '<button type="button" class="btn btn-primary mx-1" data-bs-toggle="modal" data-bs-target="#loginModal">Sign In</button>';
                    echo '<button type="button" class="btn btn-primary mx-1" onclick="location.href=\'/login.php\'">Sign Up</button>';
                }
            ?>
        </div>
    </header>
</div>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-uitm">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Sign In</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php 
                    $_SESSION["backPage"] = $_SERVER["PHP_SELF"];
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
                <div class="container px-5">
                    <h3>Sign In</h3>
                    <p>Please enter the email and password to continue.</p>
                    <form id="loginForm" action="/doSignIn.php" method="post">
                        <div class="form-floating mb-3">
                            <input class="form-control" name="signInEmail" type="email" placeholder="Email Address" required/>
                            <label for="emailAddress">Email Address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="signInPassword" type="password" placeholder="Password" required/>
                            <label for="password">Password</label>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: white; color: #7700ff;">Close</button>
                <button class="btn btn-primary" form="loginForm" id="signInButton" type="submit">Sign In</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    //onload window jquery
    $(window).on('load', function(){
        <?php
            if(isset($_GET["error"])){
                //error exists
                echo "$('#loginModal').modal('show');";
            }
        ?>
    })
    $('#loginModal').on('hidden.bs.modal', function(){
        let url = new URL(window.location.href);
        url.searchParams.delete('error');
        url.searchParams.delete('signup');
        window.history.pushState({}, document.title, url);
    })
</script>