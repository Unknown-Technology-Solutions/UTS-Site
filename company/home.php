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
        <?php menu(); ?>
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
    else if($screen == 'acct_types')
    {
        $cols_editable = array(false, true, true);
        $cols = array('id', 'name', 'description');
        if($action == 'edit')
        {
            $id = intval($_GET['id']);
            add($cols, $cols_editable, $screen, true, $id);
        }
        else
        {
            if($action == 'delete')
            {
                if(isset($_GET['id']))
                {
                    $id = intval($_GET['id']);
                    $sql = "DELETE FROM ".escape($screen)." WHERE id = ".$id;
                    execute($sql);
                }
            }
            ?>
            <fieldset>
                <legend>Add</legend>
                <?php
                add($cols, $cols_editable, $screen);
                ?>
            </fieldset>
            <fieldset>
                <legend>Manage</legend>
                <?php
                $sql = "SELECT id, name, description FROM acct_types ORDER BY id ASC";
                $rows = fetch($sql);
                echo build_table($rows, $cols, $screen);
                ?>
            </fieldset>
            <?php
        }
    }
    else if($screen == 'charge_types')
    {
        $cols_editable = array(false, true, true, true, true);
        $cols = array('id', 'name', 'description', 'standard', 'price_monthly');
        if($action == 'edit')
        {
            $id = intval($_GET['id']);
            add($cols, $cols_editable, $screen, true, $id);
        }
        else
        {
            if($action == 'delete')
            {
                if(isset($_GET['id']))
                {
                    $id = intval($_GET['id']);
                    $sql = "DELETE FROM ".escape($screen)." WHERE id = ".$id;
                    execute($sql);
                }
            }
            ?>
            <fieldset>
                <legend>Add</legend>
                <?php
                add($cols, $cols_editable, $screen);
                ?>
            </fieldset>
            <fieldset>
                <legend>Manage</legend>
                <?php
                $sql = "SELECT id, name, description, standard, price_monthly FROM charge_types ORDER BY id ASC";
                $rows = fetch($sql);
                echo build_table($rows, $cols, $screen);
                ?>
            </fieldset>
            <?php
        }
    }
    else if($screen == 'customer_records')
    {
        $cols_editable = array(false,true,true,true,true,true,true);
        $cols = array('id', 'first_name', 'last_name', 'company', 'acct_type', 'notes', 'charges');
        if($action == 'edit')
        {
            $id = intval($_GET['id']);
            add($cols, $cols_editable, $screen, true, $id);
        }
        else
        {
            if($action == 'delete')
            {
                if(isset($_GET['id']))
                {
                    $id = intval($_GET['id']);
                    $sql = "DELETE FROM ".escape($screen)." WHERE id = ".$id;
                    execute($sql);
                }
            }
            ?>
            <fieldset>
                <legend>Add</legend>
                <?php
                add($cols, $cols_editable, $screen);
                ?>
            </fieldset>
            <fieldset>
                <legend>Manage</legend>
                <?php
                $sql = "SELECT id, first_name, last_name, company, acct_type, notes, charges FROM customer_records ORDER BY id ASC";
                $rows = fetch($sql);
                echo build_table($rows, $cols, $screen);
                ?>
            </fieldset>
            <?php
        }
    }
    ?>
</body>

</html>
