<?php session_start() ?>
<style>
    html {
        height: 100%;
    }
    body {
        position: relative;
        margin: 0;
        padding-bottom: 6rem;
        min-height: 100%;
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
<div class="px-5">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
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
                    echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#signInModal">Login</button>';
                }
            ?>
        </div>
    </header>
</div>

<?php $_SESSION["backPage"] = basename($_SERVER["PHP_SELF"])?>

<!-- Modal -->
<div class="modal fade" id="signInModal" tabindex="-1" aria-labelledby="signInModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Authenticate</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <ul class="container px-4 nav nav-pills mt-4" id="pills-tab" role="tablist">
            <li class="nav-item px-2" role="presentation">
                <button class="nav-link active" id="pills-signin-tab" data-bs-toggle="pill" data-bs-target="#pills-signin" type="button" role="tab" aria-controls="pills-signin" aria-selected="true">Sign In</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-signup-tab" data-bs-toggle="pill" data-bs-target="#pills-signup" type="button" role="tab" aria-controls="pills-signup" aria-selected="false">Sign Up</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
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
            <div class="tab-pane fade show active" id="pills-signin" role="tabpanel" aria-labelledby="pills-signin-tab" tabindex="0">
                <div class="container px-5 my-4">
                    <h3>Sign In</h3>
                    <p>Please enter the email and password to continue.</p>
                    <form id="loginForm" action="doSignIn.php" method="post">
                        <div class="form-floating mb-3">
                            <input class="form-control" name="signInEmail" type="email" placeholder="Email Address" required/>
                            <label for="emailAddress">Email Address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="signInPassword" type="password" placeholder="Password" required/>
                            <label for="password">Password</label>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg" id="signInButton" type="submit">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-signup" role="tabpanel" aria-labelledby="pills-signup-tab" tabindex="0">
                <div class="container px-5 my-4">
                    <h3>Sign Up (Attendees Only)</h3>
                    <p>This form is for attendees only. Club Representatives and Officers can contact the administrator for help. Please fill in this form to continue.</p>
                    <form id="signupForm" action="doSignUp.php" method="post">
                        <div class="form-floating mb-3">
                            <input class="form-control" name="email" type="email" placeholder="Email Address" required/>
                            <label for="emailAddress">Email Address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="password" type="password" placeholder="Password" required/>
                            <label for="password">Password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="confirmPassword" type="password" placeholder="Confirm Password" required/>
                            <label for="password">Confirm Password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="name" type="text" placeholder="Name" required/>
                            <label for="name">Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control number" name="telephone" type="text" placeholder="Telephone" onkeydown='{(evt) => ["e", "E", "-"].includes(evt.key) && evt.preventDefault()}' required/>
                            <label for="telephone">Telephone</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="courseCode" type="text" placeholder="Course Code" required/>
                            <label for="courseCode">Course Code</label>
                        </div>
                        <!--The code below is left as is to enable the usage of doSignUp.php as a some sort of an API to allow other
                        forms to reuse the same code. (cant leave the role POST as null)-->
                        <div class="form-floating mb-3" hidden>
                            <select class="form-select" name="role" aria-label="Role">
                                <option value="3">Attendee</option>
                                <option value="0">Student</option>
                                <option value="1">Admin</option>
                                <option value="2">Officer</option>
                            </select>
                            <label for="role">Role</label>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg" id="signUpButton" type="submit">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <style>
            /* Chrome, Safari, Edge, Opera */
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
            }

            /* Firefox */
            input[type=number] {
            -moz-appearance: textfield;
            }
        </style>
        <!-- JavaScript Bundle with Popper -->
        <script type="application/javascript">
            <?php 
                //check if $_GET isset
                if(isset($_GET["error"])){
                    //error exists
                    echo "$('#signInModal).show();'";
                }
                if(isset($_GET["signup"])){
                    echo "$('#signInModal).show();'";
                }
            ?>
            document.querySelector(".number").addEventListener("keypress", function (evt) {
                if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57)
                {
                    evt.preventDefault();
                }
            });
        </script>
      </div>
      <!--div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div-->
    </div>
  </div>
</div>