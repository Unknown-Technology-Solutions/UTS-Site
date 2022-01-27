<?php

include_once './anchor.php';
//include_once '../anchor.php';

if ($anchor == "root") {
    include_once './vendor/autoload.php';
    if (isset($web_settings) == false) {
        $web_settings = parse_ini_file("./web_settings.ini.php");
    }
}
if ($anchor == "company" || $anchor == "customer") {
    include_once '../vendor/autoload.php';
    $web_settings = parse_ini_file("../web_settings.ini.php");
}

use Firebase\JWT\Key;

$public_key = $web_settings['public_key'];

/**
 * jwtVerf
 *
 * @param  String $token
 * @param  String $public_key
 * @return Array
 */
function jwtVerf($token, $public_key)
{
    //$rjwtConfig = parse_ini_file($configLocation); //Config File location
    //$file = fopen($rjwtConfig['keyFile'], "r") or die("Unable to read key file!");
    //$ServerKey = fread($file, filesize($rjwtConfig['keyFile']));
    //fclose($file);
    try {
        date_default_timezone_set("America/Chicago");
        //$payload = JWT::decode($token, $public_key, array('RS256')); //OUTDATED
        $payload = Firebase\JWT\JWT::decode($token, new Key($public_key, 'RS256'));
        $returnArray['nbf'] = $payload->nbf;
        $returnArray['type'] = $payload->type;
        $expire = date($returnArray['nbf'] + (3600 * 2));
        #echo "Expire Time: ".$expire."<br>";

        $time = date("U");
        #echo "Curren Time: ".$time."<br>";
        if ($time > $expire) {
            return array("tf" => false, "exp" => "expired", "type" => $returnArray['type']);
        } else {
            return array("tf" => true, "exp" => "valid", "type" => $returnArray['type']);
        }
        #echo $payload['username']."<br>";
        #if (isset($payload->exp)) {
        #    $returnArray['exp'] = date(DateTime::ISO86016, $payload->exp);
        #}
    } catch (Exception $e) {
        $returnArray = array('error' => $e->getMessage());
        //echo $returnArray['error']."<br>";
        if ($returnArray['error'] == "Expired token") {
            return array("tf" => true, "exp" => "invalid", "type" => $returnArray['type']);
        } else {
            return array("tf" => true, "exp" => "invalid", "type" => $returnArray['type']);
        }
    }
}



/**
 * jwtCook
 *
 * @param  String $username
 * @param  Bool $authenticated
 * @param  String $private_key
 * @return Array
 */
function jwtCook($username, $authenticated, $private_key, $type)
{
    date_default_timezone_set("America/Chicago");
    $nbf = date('U');


    if ($authenticated == false) {
        return array(false, 'null');
    } else {
        $JWT_Payload_Array = array();
        $JWT_Payload_Array['username'] = $username;
        $JWT_Payload_Array['authenticated'] = $authenticated;
        $JWT_Payload_Array['nbf'] = $nbf;
        $JWT_Payload_Array['type'] = $type;

        //$file = fopen($rjwtConfig['keyFile'], "r") or die("Unable to read key file!");
        //$ServerKey = fread($file, filesize($rjwtConfig['keyFile']));
        //fclose($file);

        $token = Firebase\JWT\JWT::encode($JWT_Payload_Array, $private_key, 'RS256');
        //$_SESSION['jwt_token'] = $token;

        return array(true, $token);
    }
}


/**
 * checkSessionValid
 *
 * @param String $area
 * 
 * @return Bool
 */
function checkSessionValid($area)
{
    global $pubkey;
    if (isset($_COOKIE['auth_token'])) {
        $v = jwtVerf($_COOKIE['auth_token'], $pubkey);
        if ($v['exp'] == "valid" && $v['type'] == $area) {
            return true;
        } elseif ($v['exp'] == "valid" && "login" == $area) {
            return array(true, $v['type']);
        } else {
            return false;
        }
    }
}
