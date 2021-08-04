<?php
include_once('./functions.php');
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
    <link rel="stylesheet" type="text/css" href="/css/company.css" />
    <title>Home</title>
</head>

<body>
    <form action="/uts_login.php" method="POST">
        <button name="logout" type="submit">Logout</button>
    </form>
<?php
$header_array = ["Completed", "ID", "Name", "Company", "EMail", "Message", "Submit Time"];
$column_array = ["completed", "id", "first_name", "company", "email", "request", "submit_time"];
$result = $connect->query('SELECT * FROM `customer_requests` WHERE completed="false";');
    echo build_table($header_array, $column_array, $result);
?>
</body>

</html>