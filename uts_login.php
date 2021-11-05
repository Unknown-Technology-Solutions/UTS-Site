<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->assign('title', 'Company Login');
$smarty->display('header.tpl');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$GLOBALS['passed'] = false;
$authState = array('state' => false, 'ErrorCode' => null, 'type' => null);
$domain = null;
$state = false;
if (isset($_POST['submit'])) {
    //print("sponge");
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        $GLOBALS['passed'] = false;
        //print("spongeA");
    } else {
        //print("spongeB");
        $exploded = explode('@', $_POST['username']);
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
//print_r($authState);
$state = checkSessionValid("login");
//error_log((string) $state);
if ($domain == "unknownts.com" && $authState['state'] == true) {
    header("Location: company/home.php");
} else if ($state[1] == "employee") {
    header("Location: company/home.php");
} elseif ($state[1] == "customer") {
    header("Location: customer/home.php");
} else {
    false;
}
// TODO: re-write login system
//if (isset($_GET['f'])) {
//    print("<div class=\"heading center failed\">Incorrect username or password.</div>");
//} elseif (isset($_POST['login_passed'])) {
//Login Passed
//}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$smarty->display('login.tpl');
$smarty->display('footer.tpl');
?>
