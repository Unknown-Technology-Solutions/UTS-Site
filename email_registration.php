<?php
include_once('./functions.php');
include_once('./errors.php');
include_once('./authentication.php');

// Define variables
$USER_IP = $_SERVER['REMOTE_ADDR'];
$connect_r = mail_db(); // Remote database connection


if ($connect_r->connect_error) {
    header("Content-Type: application/json");
    die(jsonErrorOut(ErrorCode::InternErr));
}

if (isset($_POST['submit']) || isset($_POST['submit_json'])) { //we have to be backwards compatable

    $m_username = $_POST['m_username'];
    $m_password = $_POST['m_password'];    
    $n_username = $connect_r->real_escape_string($_POST['n_username']);
    $n_password = $connect_r->real_escape_string($_POST['n_password']);
    $exploded = explode('@', $n_username);
    $domain = end($exploded);

    $domain_sql =  "SELECT id, name FROM virtual_domains WHERE name='" . $domain . "';";
    $domain_info = $connect_r->query($domain_sql);
    $di = $domain_info->fetch_assoc();

    if (mysqli_num_rows($domain_info) == 0) {
        $GLOBALS['message'] = "Invalid email! Check that the domain name is valid! (The domain you used: " . strval($domain) . ")";
        $GLOBALS['ErrorCode'] = ErrorCode::InvalidReq;
        //die(jsonErrorOut(ErrorCode::InvalidReq));
    } else {
        if (!$m_username || !$m_password) {
            $GLOBALS['message'] = "Username and password required";
            $GLOBALS['ErrorCode'] = ErrorCode::InvalidReq;
            //die(jsonErrorOut(ErrorCode::InvalidReq));
        } else {
            $AuthReturnArr = authenticateToMaster($connect_r, $m_username, $m_password);
            if ($AuthReturnArr['authenticated'] == false) {
                $GLOBALS['message'] = "Bad username or password";
                $GLOBALS['ErrorCode'] = $AuthReturnArr['ErrorCode'];
                //die(jsonErrorOut($AuthReturnArr['ErrorCode']));
            } else {
                if ($AuthReturnArr['new_user_authorized'] == "true" && isAuthorizedForDomain($di['id'], $AuthReturnArr['authorized_domains'])) {
                    $submit_sql =  "INSERT INTO virtual_users (domain_id, password, email, ip, master) VALUES (" . $di['id'] . ", '" . handlePassword($n_password, "hash") . "', '" . $n_username . "', '" . $USER_IP . "', '" . $connect_r->real_escape_string($m_username) . "');";
                    $output = $connect_r->query($submit_sql);
                    //$output = 0; //temporary lockout
                    if (strval($output) == strval(1)) {
                        $GLOBALS['message'] = "Account successfully registered! (" . strip_tags($n_username) . ")";
                        $GLOBALS['ErrorCode'] = ErrorCode::Success;
                        //die(jsonErrorOut(ErrorCode::Success));
                    } else {
                        $GLOBALS['message'] = "Account failed to register. Try again, or contact an administrator. Error(s): " . strval($connect_r->error);
                        $GLOBALS['ErrorCode'] = ErrorCode::InternErr;
                        //$GLOBALS['message'] = strval($connect_r->error);
                        //die(jsonErrorOut(ErrorCode::InternErr));
                    }
                } else {
                    $GLOBALS['message'] = "Bad permissions";
                    $GLOBALS['ErrorCode'] = ErrorCode::NotAllowed;
                    //die(jsonErrorOut(ErrorCode::NotAllowed));
                }
            }
        }
    }
    // Respond with JSON if required
    if (isset($_GET['json']) || isset($_POST['json']) || isset($_POST['submit_json']) || isset($_POST['submit'])) {
        header("Content-Type: application/json");
        die(json_encode(array_merge(OnlyErrorOut($GLOBALS['ErrorCode']), array("message" => $GLOBALS['message']))));
    }
}

$smarty = create_smarty();
$smarty->assign('title', 'Data Security');
$smarty->display('header.tpl');
?>
        <div class="intro heading">
            Register Email Account<span class="blinking-cursor" style="font-size: 1em;">|</span>
        </div>
        <div class="intro">
            <form action="./email_registration.php" method="POST">
                <div class="label">Master username (user.name@domain.tld): </div><input class="tbox" type="text" required name="m_username" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">Master password: </div><input class="tbox" type="password" required name="m_password" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">New username (user.name@domain.tld): </div><input class="tbox" type="text" required name="n_username" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">New password: </div><input class="tbox" type="password" required name="n_password" style="width: 130px;" autocomplete="off"></input><br />
                <br />
                <button type="submit" name="submit">Submit</button>
                <!--<button type="submit" name="submit_json">Submit & JSON</button>-->
            </form>
        </div>
        <?php
        if($GLOBALS['ErrorCode'] != ErrorCode::Skip) {
          if($GLOBALS['ErrorCode'] == ErrorCode::Success)
            print("Creating account...");
          print($GLOBALS['message']);
        }
        ?>
<?php
$smarty->display('footer.tpl');
?>
