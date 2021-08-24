<?php
include_once('./functions.php');
include_once('../jwt.php');
if (checkSessionValid()) {
    if (isset($_GET['completed']) && is_numeric($_GET['id'])) {
        $result = $connect->query("UPDATE `uts_modern_v1`.`customer_requests` SET `completed`='true' WHERE  `id`=" . $_GET['id'] . ";");
    }
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
    <div class="heading center">
        Customer contact requests
    </div>
    <div class="scrollable">
        <?php
        $header_array = ["Completed", "ID", "Name", "Company", "EMail", "Message", "Submit Time"];
        $column_array = ["completed", "id", "first_name", "company", "email", "request", "submit_time"];
        $result = $connect->query('SELECT * FROM `customer_requests` WHERE completed="false";');
        echo build_table($header_array, $column_array, $result, "requests");
        ?>
    </div>
    <div class="scrollable">
        <?php
        //TODO: Deliverability reports
        ?>
    </div>
</body>

</html>