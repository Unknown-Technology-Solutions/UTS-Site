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