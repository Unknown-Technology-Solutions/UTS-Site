<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" type="text/css" href="modern.css" />
    <link rel="stylesheet" type="text/css" href="blinking_cursor.css" />
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
            <form action="?" method="POST">
                <div class="label">Username (user.name@domain.tld): </div><input class="tbox" type="text" required name="username" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">Password: </div><input class="tbox" type="password" required name="password" style="width: 130px;" autocomplete="off"></input><br />
                <br />
                <button type="submit" name="submit">Submit</button>
            </form>
        </div>
        <?php
        //$connect->real_escape_string
        if (isset($_POST['submit'])) {
            $ip = $GET_USER_IP;
            $username = $connect_r->real_escape_string($_POST['username']);
            $password = $connect_r->real_escape_string($_POST['password']);
            $exploded = explode('@', $username);
            $domain = end($exploded);



            if (strval($domain) == $MYSQL_DOMAIN_QUERY) {
                print("Invalid email! Check that the domain name is valid! (The domain you used: " . strval($domain) . ")");
            } else {
                $sql =  "INSERT INTO virtual_users (domain_id, password, email, ip) VALUES (MYSQL_DOMAIN_QUERY, ENCRYPT('" . $password . "', CONCAT('$6$', SUBSTRING(SHA(RAND()), -16))), '" . $username . "', '" . $ip . "');";
                if (strval($connect->query($sql)) == strval(1)) {
                    print("Account successfully registered! Log in using your full DMail address (" . $username . ")");
                } else {
                    print("Account failed to register. Try again, or contact an administrator.");
                }
            }
            #$pass_state = shell_exec("sudo /var/lib/roundcube/config/.new_user.sh");
            #if ($pass_state == "success") {
            #  print("Account Registered! Do not abuse this service, you will be banned if you do.");
            #} else {
            #  print("Registration failed. Try again or contact support@unknownts.tk or zane_reick9 on #DN42");
            #}
        }
        ?>
        <footer>
            Unknown Technology Solutions 2017-2021<br />
        </footer>
    </div>
</body>

</html>