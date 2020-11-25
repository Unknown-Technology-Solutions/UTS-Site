<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="/css/modern.css" />
        <title>Login API</title>
    </head>
</html>
<?php
if (!isset($_POST['submit'])) {
    die();
}
echo($_SERVER['HTTP_REFERER']);
