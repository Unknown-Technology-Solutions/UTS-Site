<?php
//session_start(); //Start Session, probably not needed
//Create a protect function to keep the database safe
function protect($string)
{
    $string = trim(strip_tags(addslashes($string)));
    return $string;
}

$web_settings = parse_ini_file("./web_settings.ini.php");

//Connection info for the database
$servername = $web_settings['ip'];
$username = $web_settings['username'];
$password = $web_settings['password'];
$database = $web_settings['database'];
$port = $web_settings['port'];
$connect = new mysqli($servername, $username, $password, $database, $port);

function mail_db() {
    global $web_settings;
    $rservername = $web_settings['r_ip'];
    $rusername = $web_settings['r_username'];
    $rpassword = $web_settings['r_password'];
    $rdatabase = $web_settings['r_database'];
    $rport = $web_settings['r_port'];
    $connect_r = new mysqli($rservername, $rusername, $rpassword, $rdatabase, $rport);
    return $connect_r;
}


function handlePassword($p, $q, $h_p=null) {
    if ($q == "hash") {
        return password_hash($p, PASSWORD_BCRYPT);
    } elseif ($q == "verify") {
        return password_verify($p, $h_p);
    }
}

$jwt_private_key = $web_settings['private_key'];

date_default_timezone_set('America/Chicago');
$date_calc = date("j");
$date_time = date("m/d/y h:ia");
$session_time = date("G");
$full_date_time = date("l, F d, Y @ h:i a");
$news_date = date("l, F d, Y");

function httpPost($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

require_once('./jwt.php');

function authenticateAgainstEmployee()
{
    global $connect;
    global $jwt_private_key;
    if (isset($_POST['submit'])) {
        $alert1 = "<script>alert('Invalid Login Attempt')</script>";
        $username = $connect->real_escape_string(protect($_POST["username"]));
        $password = $connect->real_escape_string(protect($_POST["password"]));
        if (!$username || !$password) {
            //print($alert1);
            return array (false, '', '');
        } else {
            $req = "SELECT * FROM users WHERE username = '".$username."'";
            if (mysqli_num_rows($connect->query($req)) == 0) {
                //print($alert1);
                return array (false, '', '');
            } else {
                //$authenticated = rjwtAuth($username, $password, "./rjwt.ini.php");
                $req = "SELECT * FROM users WHERE username = '".$username."' AND password = '".$password."'";
                if (mysqli_num_rows($connect->query($req)) == 1) {
                    $authenticated = true;
                    if ($authenticated === false) {
                        // false returned on failure
                        //print($alert1);
                        return array (false, '', '');
                    } else {
                        $jwtState = jwtCook($username, $authenticated, $jwt_private_key);
                        if ($jwtState[0] == false) {
                            return array (false, '', '');
                        } else {
                            //$_SESSION['auth_token'] = $jwtState[1];
                            setcookie('auth_token', $jwtState[1], 0);
                        }
                        //echo "Success! Proceeding.\n";
                        //header("Location: ./home.php");
                        return array (true, '', '');
                    }
                } else {
                    //print($alert1);
                    return array (false, '', '');
                }
            }
        }
    }
}

function logout($cookieName) {
    if ($_COOKIE[$cookieName]) {
        setcookie($cookieName, '', time() - 3600);
        unset($_COOKIE[$cookieName]);
    }
}

// Use &nbsp; in items with a space

function menuContents()
{
    print("<a href=\"index.php\">Home</a>");
    print("<a href=\"customer_submit.php\">Contact&nbsp;Form</a>");
    print("<a href=\"software.php\">Software</a>");
    print("<a href=\"openings.php\">Career</a>");
    print("<a href=\"data_security.php\">Data&nbsp;Management</a>");
    print("<a href=\"ops.php\">Operations</a>");
}

?>