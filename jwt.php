<?php
try {
    include_once './vendor/autoload.php';
} catch (Exception $e) {
    include_once '../vendor/autoload.php';
}
use \Firebase\JWT\JWT;

function jwtVerf($token, $public_key)
{
    //$rjwtConfig = parse_ini_file($configLocation); //Config File location
    //$file = fopen($rjwtConfig['keyFile'], "r") or die("Unable to read key file!");
    //$ServerKey = fread($file, filesize($rjwtConfig['keyFile']));
    //fclose($file);
    try {
        date_default_timezone_set("America/Chicago");
        $payload = JWT::decode($token, $public_key, array('RS256'));
        $returnArray['nbf'] = $payload->nbf;
        $expire = date($returnArray['nbf'] + 3600);
        #echo "Expire Time: ".$expire."<br>";

        $time = date("U");
        #echo "Curren Time: ".$time."<br>";
        if ($time > $expire) {
            return false;
        } else {
            return true;
        }
        #echo $payload['username']."<br>";
        #if (isset($payload->exp)) {
        #    $returnArray['exp'] = date(DateTime::ISO8601, $payload->exp);
        #}
    } catch (Exception $e) {
        $returnArray = array('error' => $e->getMessage());
        echo $returnArray['error']."<br>";
        if ($returnArray['error'] == "Expired token") {
            return false;
        } else {
            return false;
        }
    }
}



function jwtCook($username, $authenticated, $private_key)
{
    date_default_timezone_set("America/Chicago");
    $nbf = date('U');


    if ($authenticated == false) {
        return array (false, 'null');
    } else {
        $JWT_Payload_Array = array();
        $JWT_Payload_Array['username'] = $username;
        $JWT_Payload_Array['authenticated'] = $authenticated;
        $JWT_Payload_Array['nbf'] = $nbf;

        //$file = fopen($rjwtConfig['keyFile'], "r") or die("Unable to read key file!");
        //$ServerKey = fread($file, filesize($rjwtConfig['keyFile']));
        //fclose($file);

        $token = JWT::encode($JWT_Payload_Array, $private_key, 'RS256');
        //$_SESSION['jwt_token'] = $token;

        return array (true, $token);
    }
}