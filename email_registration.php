<!DOCTYPE html>
<html lang="en">

<head>
    <script defer src="/script-resources/menu.js"></script>
    <script defer src="/script-resources/clock.js"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" type="text/css" href="/css/modern.css" />
    <link rel="stylesheet" type="text/css" href="/css/blinking_cursor.css" />
    <link rel="stylesheet" type="text/css" href="/css/menu.css" />
    <link rel="stylesheet" type="text/css" href="/css/login.css" />
    <link href='https://fonts.googleapis.com/css?family=Anonymous%20Pro' rel='stylesheet'>
    <style>
        body {
            font-family: 'Anonymous Pro', monospace;
            font-size: 22px;
        }
    </style>
    <title>Register Email Account</title>
</head>

<body>
    <?php
    //Connection info for the remote database

    include_once('./functions.php');
    $connect_r = mail_db();

    $GET_USER_IP = $_SERVER['REMOTE_ADDR'];

    if ($connect_r->connect_error) {
        die("Connection failed: " . $connect_r->connect_error);
    }
    ?>
    <div id="main">
        <div class="title">Register Email Account<span class="blinking-cursor" style="font-size: 1em;">|</span></div>
        <div class="intro heading">
        </div>
        <div class="intro">
            <form action="./email_registration.php" method="POST">
                <div class="label">Master username (user.name@domain.tld): </div><input class="tbox" type="text" required name="m_username" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">Master password: </div><input class="tbox" type="password" required name="m_password" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">New username (user.name@domain.tld): </div><input class="tbox" type="text" required name="n_username" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">New password: </div><input class="tbox" type="password" required name="n_password" style="width: 130px;" autocomplete="off"></input><br />
                <br />
                <button type="submit" name="submit">Submit</button>
            </form>
        </div>
        <?php
        //$connect->real_escape_string
        if (isset($_POST['submit'])) {
            $ip = $GET_USER_IP;
            $m_username = $connect_r->real_escape_string($_POST['m_username']);
            $m_password = $connect_r->real_escape_string($_POST['m_password']);
            $n_username = $connect_r->real_escape_string($_POST['n_username']);
            $n_password = $connect_r->real_escape_string($_POST['n_password']);
            $exploded = explode('@', $n_username);
            $domain = end($exploded);

            $domain_sql =  "SELECT * FROM virtual_domains WHERE name='".$domain."';";
            $domain_id_sql =  "SELECT id FROM virtual_domains WHERE name='".$domain."';";
            $domain_id = $connect_r->query($domain_id_sql);
            
            $domain_res = $connect_r->query($domain_sql);

            if (mysqli_num_rows($domain_res) > 0) {
                print("Invalid email! Check that the domain name is valid! (The domain you used: " . strval($domain) . ")");
            } else {
                $submit_sql =  "INSERT INTO virtual_users (domain_id, password, email, ip) VALUES (".$domain_id['id'].", ENCRYPT('" . $password . "', CONCAT('$6$', SUBSTRING(SHA(RAND()), -16))), '" . $username . "', '" . $ip . "');";
                if (strval($connect->query($submit_sql)) == strval(1)) {
                    print("Account successfully registered! (" . $username . ")");
                } else {
                    print("Account failed to register. Try again, or contact an administrator.");
                }
            }
        }
        ?>
        <footer>
            Unknown Technology Solutions 2017-<?php echo date('Y'); ?><br />
        </footer>
    </div>
</body>

</html>