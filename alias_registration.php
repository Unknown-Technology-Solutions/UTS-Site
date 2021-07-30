<?php
// Include needed files
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

if (isset($_POST['submit'])) { //we have to be backwards compatable

    $m_username = $_POST['m_username'];
    $m_password = $_POST['m_password'];    
    $source = $connect_r->real_escape_string($_POST['source']);
    $destination = $connect_r->real_escape_string($_POST['destination']);
    $exploded = explode('@', $source);
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
                    $submit_sql =  "INSERT INTO virtual_aliases (domain_id, source, destination, ip, master) VALUES (" . $di['id'] . ", '" . $source . "', '" . $destination . "', '" . $USER_IP . "', '" . $connect_r->real_escape_string($m_username) . "');";
                    $output = $connect_r->query($submit_sql);
                    //$output = 0; //temporary lockout
                    if (strval($output) == strval(1)) {
                        $GLOBALS['message'] = "Alias successfully registered! (" . strip_tags($source) . ")";
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
?>

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
	<title>Register Email Alias</title>
</head>

<body>
	<div id="main">
		<div class="title">Register Email Alias<span class="blinking-cursor" style="font-size: 1em;">|</span></div>
		<div class="intro heading">
		</div>
		<div class="intro">
			<form action="./alias_registration.php" method="POST">
				<div class="label">Master username (user.name@domain.tld): </div><input class="tbox" type="text" required name="m_username" style="width: 130px;" autocomplete="off"></input><br />
				<div class="label">Master password: </div><input class="tbox" type="password" required name="m_password" style="width: 130px;" autocomplete="off"></input><br />
				<div class="label">Source (new) email address (user.name@domain.tld): </div><input class="tbox" type="text" required name="source" style="width: 130px;" autocomplete="off"></input><br />
				<div class="label">Destination (existing) email address (user.name@domain.tld): </div><input class="tbox" type="text" required name="destination" style="width: 130px;" autocomplete="off"></input><br />
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
		<footer>
			Unknown Technology Solutions 2017-<?php echo date('Y'); ?><br />
		</footer>
	</div>
</body>

</html>
