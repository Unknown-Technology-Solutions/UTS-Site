<?php //RE - WRITE TO BE BOTH EMPLOYEE AND CUSTOMER AUTHENTICATION!
include_once('./functions.php');
$GLOBALS['passed'] = false;
$authState = array('state' => false, 'ErrorCode' => null, 'type' => null);
$state = false;
if (isset($_POST['submit'])) {
    if (!$username || !$password) {
        $GLOBALS['passed'] = false;
    } else {
        $exploded = explode('@', $n_username);
        $domain = end($exploded);
        if ($domain == "unknownts.com") {
            $authState = authenticateAgainstEmployee($jwt_private_key, $connect->real_escape_string($_POST['username']), $connect->real_escape_string($_POST['password']), mail_db());
        } else {
            $authState = authenticateAgainstCustomer($jwt_private_key, $connect->real_escape_string($_POST['username']), $connect->real_escape_string($_POST['password']), mail_db());
        }
    }
} elseif (isset($_POST['logout'])) {
    logout("auth_token");
}
$state = checkSessionValid();
error_log((string) $state);
if ($state['type'] == "employee") {
    header("Location: /company/home.php");
} elseif ($state['type'] == "customer") {
    header("Location: /customer/home.php");
} else {
    false;
}
// TODO: re-write login system
//if (isset($_GET['f'])) {
//    print("<div class=\"heading center failed\">Incorrect username or password.</div>");
//} elseif (isset($_POST['login_passed'])) {
//Login Passed
//}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script defer src="/script-resources/menu.js"></script>
    <script defer src="/script-resources/clock.js"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" type="text/css" href="/css/modern.css" />
    <link rel="stylesheet" type="text/css" href="/css/blinking_cursor.css" />
    <link rel="stylesheet" type="text/css" href="/css/menu.css" />
    <link rel="stylesheet" type="text/css" href="/css/login.css" />
    <link href='https://fonts.googleapis.com/css?family=Anonymous%20Pro' rel='stylesheet'>
    <style>
        body {
            font-family: 'Anonymous Pro', monospace;
            font-size: 22px;
        }
    </style>
    <title>Company Login</title>
</head>

<body onload="startTime()">
    <noscript>Sorry, your browser does not support JavaScript!</noscript>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="/index.php">UTS Homepage</a>
        <br />
        <a href="#"><span id="clock"></span></a>
    </div>
    <div onclick="openNav()" class="open_menu" id="hams">
        <a href="javascript:void(0)">&times;</a>
    </div>
    <div id="main">
        <?php
        if (isset($_POST['submit'])) {
            if ($authState['state'] == true) {
                header("Location: /company/home.php");
            } elseif ($authState['state'] == false) {
                print("<script>alert('Invalid Login Attempt')</script>");
            }
        }
        ?>
        <div class="title">Unknown<br />Technology<br />Solutions<span class="blinking-cursor" style="font-size: 1em;">|</span></div>
        <div class="intro">
            <div class="login">
                <div class="bigger_heading center">UTS Portal</div> <br />
                <form action="./uts_login.php" method="POST" class="login-form center">
                    <input type="text" name="username" placeholder="Username" class="login-text" /> <br />
                    <input type="password" name="password" placeholder="Password" class="login-text" /> <br />
                    <!--<input type="checkbox" name="remember" class="login-text" />Remember me<br />-->
                    <button type="submit" name="submit" class="button-login"><span>Sign in</span></button> <br />
                </form>
            </div>
        </div>
    </div>
</body>

</html>