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
$servername = "127.0.0.1";
$username = "modern";
$password = "qK4kwsqi";
$database = "uts_modern";
$port = "3306";
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

?>
