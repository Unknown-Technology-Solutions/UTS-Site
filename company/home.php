<?php
include_once('../jwt.php');
if (checkSessionValid()) {
    true;
} else {
    header("Location: /uts_login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="/css/modern.css" />
        <title>Home</title>
    </head>
    <body>
        <form action="/uts_login.php" method="POST">
            <button name="logout" type="submit">Logout</button>
        </form>

    </body>
</html>