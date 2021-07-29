<?php
function protect($string)
{
    $string = trim(strip_tags(addslashes($string)));
    return $string;
}

$web_settings = parse_ini_file("../web_settings.ini.php");

function mail_db()
{
    global $web_settings;
    $rservername = $web_settings['r_ip'];
    $rusername = $web_settings['r_username'];
    $rpassword = $web_settings['r_password'];
    $rdatabase = $web_settings['r_database'];
    $rport = intval($web_settings['r_port']);
    $connect_r = new mysqli($rservername, $rusername, $rpassword, $rdatabase, $rport);
    return $connect_r;
}


function handlePassword($p, $q, $h_p = null)
{
    if ($q == "hash") {
        return password_hash($p, PASSWORD_BCRYPT);
    } elseif ($q == "verify") {
        return password_verify($p, $h_p);
    }
}

abstract class ErrorCode {
    const Success    = 200;
    const InvalidReq = 400;
    const AuthNeeded = 401;
    const NotAllowed = 403;
    const IncmpltErr = 497;
    const DebugErr   = 498;
    const InternErr  = 499;
}

function ec2text($ErrorCode) {
    $ect = [
        200 => "Successful",
        400 => "Invalid attempt",
        401 => "Authentication required",
        403 => "Request not allowed",
        497 => "Incomplete request data",
        498 => "Debugging trigger",
        499 => "Server side error"
    ];
    return $ect[$ErrorCode];
}

function jsonErrorOut($ec) {
    $ErrorText = ec2text($ec);
    $AssembleDict = ['ErrorCode' => $ec, 'ErrorMessage' => $ErrorText, 'return' => $GLOBALS['return_array']];
    return json_encode($AssembleDict);
}

include_once('../vendor/autoload.php');
