<?php
include_once('./functions.php');
include_once('../jwt.php');
if (checkSessionValid("employee")) {
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
    <link rel="stylesheet" type="text/css" href="../css/modern.css" />
    <link rel="stylesheet" type="text/css" href="../css/company.css" />
    <title>Home</title>
    <style>
        input, textarea, button, select {
            background-color: black;
            color: white;
        }

    </style>
</head>

<body>
    <form action="../uts_login.php" method="POST">
        <?php menu($user_department); ?>
        <button name="logout" type="submit">Logout</button>
    </form>
    <br>
    <?php
    $screen = 'customer_requests';
    $action = '';
    if(isset($_GET['screen']))
        $screen = $_GET['screen'];
    if(isset($_GET['action']))
        $action = $_GET['action'];
    ?>
    <div class="heading center">
        <?php
        if($screen == 'customer_requests') print('Customer contact requests');
        else if($screen == 'customer_records') print('Customer records');
        else if($screen == 'charge_types') print('Charge Types');
        else if($screen == 'acct_types') print('Account Types');
        else if($screen == 'security') print('Security');
        else print('404');
        ?>
    </div>
    <?php
    if($screen == 'customer_requests')
    {
        ?>
        <div class="scrollable">
            <?php
            $header_array = ["Completed", "ID", "Name", "Company", "EMail", "Message", "Submit Time"];
            $column_array = ["completed", "id", "first_name", "company", "email", "request", "submit_time"];
            $result = $connect->query('SELECT * FROM `customer_requests` WHERE completed="false";');
            echo build_table_customer_records($header_array, $column_array, $result, "requests");
            ?>
        </div>
        <!--
        <div class="scrollable">
            <?php
            //TODO: Deliverability reports
            ?>
        </div>
         -->
        <?php
    }
    else if($screen == 'acct_types') table_editor($screen, $action);
    else if($screen == 'charge_types') table_editor($screen, $action);
    else if($screen == 'customer_records')  table_editor($screen, $action);
    else if($screen == 'security' && $user_department == 'SECURITY')
    {
        ?>
        <div style="border: 1px solid white; width:100%;">
        2021/10/27 00:00:00 UTC<br>
        Apache Exploit CSV1337:<br>
        This is a very dangerous exploit
        </div>
        <?php
    }
    ?>
</body>

</html>
