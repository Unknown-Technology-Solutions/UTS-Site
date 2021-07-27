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
    $rport = $web_settings['r_port'];
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
    const Passed  = 200;
    const Invalid = 400;
    const Login   = 401;
    const Bad     = 403;
    const Failed  = 499;
}

function ec2text($ErrorCode) {
    $ect = [
        200 => "Successful",
        400 => "Invalid attempt",
        401 => "Authentication required",
        403 => "Bad/malformed request",
        499 => "Server side error"
    ];
    return $ect[$ErrorCode];
}