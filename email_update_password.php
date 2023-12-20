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
    $source = $connect_r->real_escape_string($_POST['source']);
    $new_password = $connect_r->real_escape_string($_POST['new_password']);
    $exploded = explode('@', $source);
    $domain = end($exploded);

    $domain_sql =  "SELECT id, name FROM virtual_domains WHERE name='" . $domain . "';";
    $domain_info = $connect_r->query($domain_sql);
    $di = $domain_info->fetch_assoc();
    
    
    $user_id_sql = "SELECT id FROM virtual_users WHERE email='" . $source . "';";
    $user_id_q = $connect_r->query($user_id_sql);
    $user_id = $user_id_q->fetch_assoc();


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
                    // UPDATE `mailserver`.`virtual_users` SET `password`='[NEW_PASSWORD]' WHERE  `id`=[USER_ID];
                    
                    $submit_sql = "UPDATE mailserver.virtual_users SET 'password'='" . handlePassword($new_password, "hash") . "' WHERE  'id'=" . $user_id['id'] . ";";
                    
                    // $submit_sql =  "INSERT INTO virtual_aliases (domain_id, source, destination, ip, master) VALUES (" . $di['id'] . ", '" . $source . "', '" . $destination . "', '" . $USER_IP . "', '" . $connect_r->real_escape_string($m_username) . "');";
                    
                    
                    $output = $connect_r->query($submit_sql);
                    //$output = 0; //temporary lockout
                    if (strval($output) == strval(1)) {
                        $GLOBALS['message'] = "Email successfully updated! (" . strip_tags($source) . ")";
                        $GLOBALS['ErrorCode'] = ErrorCode::Success;
                        //die(jsonErrorOut(ErrorCode::Success));
                    } else {
                        $GLOBALS['message'] = "Email failed to update. Try again, or contact an administrator. Error(s): " . strval($connect_r->error);
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
$smarty->assign('title', 'Update Email Password');
$smarty->display('header.tpl');
?>
        <div class="intro heading">
            Register Email Alias<span class="blinking-cursor" style="font-size: 1em;">|</span>
        </div>
        <div class="intro">
            <form action="./email_update_password.php" method="POST">
                <div class="label">Master username (user.name@domain.tld): </div><input class="tbox" type="text" required name="m_username" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">Master password: </div><input class="tbox" type="password" required name="m_password" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">Email address to update (user.name@domain.tld): </div><input class="tbox" type="text" required name="source" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">New password: </div><input class="tbox" type="password" required name="new_password" style="width: 130px;" autocomplete="off"></input><br />
                <br />
                <button type="submit" name="submit">Submit</button>
                <!--<button type="submit" name="submit_json">Submit & JSON</button>-->
            </form>
        </div>
        <?php
        if($GLOBALS['ErrorCode'] != ErrorCode::Skip) {
          if($GLOBALS['ErrorCode'] == ErrorCode::Success)
            print("Updating account...");
          print($GLOBALS['message']);
        }
        ?>
<?php
$smarty->display('footer.tpl');
?>
