<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="/css/modern.css" />
        <title>Login API</title>
    </head>
</html>
<?php
ob_start();
include_once('./functions.php');

if (!isset($_POST['submit'])) {
    die();
}

//$REF_FWD = str_replace('?f', '', $_SERVER['HTTP_REFERER']);
$REF_FWD = "Location: ".str_replace('?f', '', $_SERVER['HTTP_REFERER'])."?f";


$REF_PAS = "Location: ".str_replace('?f', '', str_replace('index.php', 'home.php', $_SERVER['HTTP_REFERER']));
//$REF_PAS = str_replace('index.php', 'home.php', $REF_PAS);

$USERNAME = protect($_POST['username']);
$PASSWORD = protect($_POST['password']);

if (!$USERNAME || !$PASSWORD) {
//    echo("no username || no password");
//    echo(" ".$REF_FWD);
    header($REF_FWD);
} else {
//    echo("username && password");
//    echo(" ".$REF_PAS);
    header($REF_PAS);
}

ob_end_flush();