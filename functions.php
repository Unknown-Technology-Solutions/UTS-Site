<?php
include_once('./authentication.php');
//Create a protect function to keep the database safe
/**
 * protect
 *
 * @param  String $string
 * @return String
 */
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

function retreiveDomainID($connect, $domainName)
{
    $sql = "SELECT * FROM virtual_domains WHERE domain='" . $domainName . "';";
    $res = $connect->query($sql);
    if (mysqli_num_rows($res) == 1) {
        $asc = $res->fetch_assoc();
        return $asc['id'];
    }
}

function isAuthorizedForDomain($domain_id, $auth_list)
{
    if ($auth_list == "all") {
        return true;
    } elseif ($auth_list == "none") {
        return false;
    } elseif (in_array(strval($domain_id), explode(":", $auth_list))) {
        return true;
    } else {
        return false;
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

function authenticateAgainstEmployee($jwt_private_key, $username, $password, $connect)
{
    $authState = authenticateToMaster($connect, $username, $password);
    if ($authState['authenticated']) {
        $IsAllowed = isAuthorizedForDomain(retreiveDomainID($connect, "unknownts.com"), $authState['authorized_domains']);
        if ($IsAllowed) {
            $jwtState = jwtCook($username, $authState['authenticated'], $jwt_private_key);
            if ($jwtState[0] == false) {
                return array ('state' => false, 'ErrorCode' => $authState['ErrorCode'], 'future' => null);
            } else {
                setcookie('auth_token', $jwtState[1], 0);
            }
            return array ('state' => true, 'ErrorCode' => $authState['ErrorCode'], 'future' => null);
        }
    } else {
        return array ('state' => false, 'ErrorCode' => $authState['ErrorCode'], 'future' => null);
    }
}

/**
 * logout
 *
 * @param  String $cookieName
 * @return void
 */
function logout($cookieName)
{
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
