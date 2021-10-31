<?php

// DOCUMENT ROOT & WEB SETTINGS ///////////////////////////////////////////////////////////////////////////////////

// not needed ?
function documentRoot()
{
    return str_replace(str_replace("\\","/",$_SERVER['DOCUMENT_ROOT']),"",str_replace("\\","/",dirname(__FILE__)));
}

$web_settings = parse_ini_file("./web_settings.ini.php");
$GLOBALS['web_settings'] = $web_settings;

// MENUS //////////////////////////////////////////////////////////////////////////////////////////////////////////

$GLOBALS['menu'] = array(
    'Home' => documentRoot().'/index.php',
    'Contact&nbsp;Form' => documentRoot().'/customer_submit.php',
    'Software' => documentRoot().'/software.php',
    'Career' => documentRoot().'/openings.php',
    'Data&nbsp;Management' => documentRoot().'/data_security.php',
    'Operations' => documentRoot().'/ops.php',
);

$GLOBALS['footerMenu'] = array(
    'TOS' => documentRoot().'/tos.php',
    'Privacy Policy' => documentRoot().'/privacy.php',
    'Employee Login' => documentRoot().'/uts_login.php',
    'Status' => 'https://stats.uptimerobot.com/wZlOJCLZ9E',
);

// META TAGS //////////////////////////////////////////////////////////////////////////////////////////////////////

$GLOBALS['metaTags'] = array(
    'url' => 'https://unknownts.com',
    'favicon' => 'images/favicon.ico',
    'description' => 'We create and provide high quality software, hardware, and general technology solutions for an affordable price',
    'color' => '#000000',
    'image' => 'https://upload.wikimedia.org/wikipedia/commons/6/65/No-Image-Placeholder.svg',
);

// TEMPLATING SYSTEM //////////////////////////////////////////////////////////////////////////////////////////////

// change to create_template?
// $template = create_template();
// $template->display('header.tpl');
function create_smarty()
{
    define('SMARTY_DIR',str_replace("\\","/",getcwd()).'/includes/smarty/');
    require_once(SMARTY_DIR . 'Smarty.class.php');
    $smarty = new Smarty();
    $smarty->setTemplateDir(dirname(__FILE__).'/includes/templates/');
    $smarty->setCompileDir(dirname(__FILE__).'/includes/templates_c/');
    $smarty->setConfigDir(dirname(__FILE__).'/includes/configs/');
    $smarty->setCacheDir(dirname(__FILE__).'/includes/cache/');
    $smarty->compile_check = true;
    $smarty->debugging = false;
    $smarty->assign('title', 'Unknown Technology Solutions');
    $smarty->assign('documentRoot', documentRoot());
    $smarty->assign('menu', $GLOBALS['menu']);
    $smarty->assign('footerMenu', $GLOBALS['footerMenu']);
    $smarty->assign('metaTags', $GLOBALS['metaTags']);
    $smarty->assign('currentYear', date('Y'));
    $smarty->assign('siteKey', $GLOBALS['web_settings']['site_key']);
    $smarty->assign('captcha', false);
    return $smarty;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Connection info for the database
$servername = $web_settings['ip'];
$username = $web_settings['username'];
$password = $web_settings['password'];
$database = $web_settings['database'];
$port = $web_settings['port'];
$connect = new mysqli($servername, $username, $password, $database, $port);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
        $domain = explode("@", $username);
        if ($domain[1] == "unknownts.com") {
            $IsAllowed = true;
        } else {
            $IsAllowed = false;
        }
        //$IsAllowed = isAuthorizedForDomain(retreiveDomainID($connect, "unknownts.com"), $authState['authorized_domains']);
        if ($IsAllowed) {
            $jwtState = jwtCook($username, $authState['authenticated'], $jwt_private_key, "employee");
            if ($jwtState[0] == false) {
                return array ('state' => false, 'ErrorCode' => $authState['ErrorCode'], 'type' => "employee");
            } else {
                setcookie('auth_token', $jwtState[1], 0);
            }
            return array ('state' => true, 'ErrorCode' => $authState['ErrorCode'], 'type' => "employee");
        }
    } else {
        return array ('state' => false, 'ErrorCode' => $authState['ErrorCode'], 'type' => "employee");
    }
}

function authenticateAgainstCustomer($jwt_private_key, $username, $password, $connect)
{
    $authState = authenticateToMaster($connect, $username, $password);
    if ($authState['authenticated']) {
        $domain = explode("@", $username);
        if ($domain[1] == "unknownts.com") {
            $IsAllowed = true;
        } else {
            $IsAllowed = false;
        }
        //$IsAllowed = isAuthorizedForDomain(retreiveDomainID($connect, "unknownts.com"), $authState['authorized_domains']);
        if ($IsAllowed) {
            $jwtState = jwtCook($username, $authState['authenticated'], $jwt_private_key, "customer");
            if ($jwtState[0] == false) {
                return array ('state' => false, 'ErrorCode' => $authState['ErrorCode'], 'type' => "customer");
            } else {
                setcookie('auth_token', $jwtState[1], 0);
            }
            return array ('state' => true, 'ErrorCode' => $authState['ErrorCode'], 'type' => "customer");
        }
    } else {
        return array ('state' => false, 'ErrorCode' => $authState['ErrorCode'], 'type' => "customer");
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
