<?php

//Connection info for the remote database

include_once('./functions.php');
$connect_r = mail_db();

$GET_USER_IP = $_SERVER['REMOTE_ADDR'];

if ($connect_r->connect_error) {
	header("Content-Type: text/plain");
	print(json_encode(array("error" => true, "message" => "There has been an internal error.")));
	//die("Connection failed: " . $connect_r->connect_error);
	die();
}

abstract class CreateAccountResult
{
	const Skip = 0;
    const Fail = 1;
    const Error = 1;
	const Success = 2;
}


abstract class CreateAccountErrorCode {
    const Success = "200";
    const Login   = "401";
    const Invalid = "400";
    const Failure = "499";
    const Bad     = "403";
}

$GLOBALS['result'] = CreateAccountResult::Skip;
$GLOBALS['message'] = "no error";

// Handle submit

if (isset($_POST['submit'])||isset($_POST['submit_json'])) {

	$ip = $GET_USER_IP;
	$m_username = $connect_r->real_escape_string($_POST['m_username']);
	$m_password = $connect_r->real_escape_string($_POST['m_password']);
	$n_username = $connect_r->real_escape_string($_POST['n_username']);
	$n_password = $connect_r->real_escape_string($_POST['n_password']);
	$exploded = explode('@', $n_username);
	$domain = end($exploded);

	$domain_sql =  "SELECT id, name FROM virtual_domains WHERE name='" . $domain . "';";
	$domain_info = $connect_r->query($domain_sql);
	$di = $domain_info->fetch_assoc();

	if (mysqli_num_rows($domain_info) == 0) {
        $GLOBALS['result'] = CreateAccountResult::Error;
        $GLBOALS['errorcode'] = CreateAccountErrorCode::Invalid;
		$GLOBALS['message'] = "Invalid email! Check that the domain name is valid! (The domain you used: " . strval($domain) . ")";
	} else {
		if (!$m_username || !$m_password) {
            $GLOBALS['result'] = CreateAccountResult::Error;
            $GLBOALS['errorcode'] = CreateAccountErrorCode::Login;
			$GLOBALS['message'] = "Username and password required";
		} else {
			$m_req = "SELECT email,password,new_user_authorized,authorized_domains FROM virtual_users WHERE email='" . $m_username . "';";
			$m_res = $connect_r->query($m_req);
			$m_asc = $m_res->fetch_assoc();
			if (mysqli_num_rows($m_res) == 0) {
                $GLOBALS['result'] = CreateAccountResult::Error;
                $GLBOALS['errorcode'] = CreateAccountErrorCode::Login;
				$GLOBALS['message'] = "Bad username or password";
			} else {
				if (handlePassword($m_password, "verify", $m_asc['password'])) {
					if ($m_asc['new_user_authorized'] == "true" && isAuthorizedForDomain($di['id'], $m_asc['authorized_domains'])) {
						$submit_sql =  "INSERT INTO virtual_users (domain_id, password, email, ip, master) VALUES (" . $di['id'] . ", '" . handlePassword($n_password, "hash") . "', '" . $n_username . "', '" . $ip . "', '" . $m_username . "');";
						$output = $connect_r->query($submit_sql);
						//$output = 0; //temporary lockout
						if (strval($output) == strval(1)) {
                            $GLOBALS['result'] = CreateAccountResult::Success;
                            $GLBOALS['errorcode'] = CreateAccountErrorCode::Success;
							$GLOBALS['message'] = "Account successfully registered! (" . strip_tags($n_username) . ")";
						} else {
                            $GLOBALS['result'] = CreateAccountResult::Error;
                            $GLBOALS['errorcode'] = CreateAccountErrorCode::Failure;
							$GLOBALS['message'] = "Account failed to register. Try again, or contact an administrator. Error(s): " . strval($connect_r->error);
							//$GLOBALS['message'] = strval($connect_r->error);
						}
					} else {
                        $GLOBALS['result'] = CreateAccountResult::Error;
                        $GLBOALS['errorcode'] = CreateAccountErrorCode::Bad;
						$GLOBALS['message'] = "Bad permissions";
					}
				} else {
                    $GLOBALS['result'] = CreateAccountResult::Error;
                    $GLBOALS['errorcode'] = CreateAccountErrorCode::Login;
					$GLOBALS['message'] = "Bad username or password";
				}
			}
		}
	}
	// Respond with JSON if required
	if(isset($_GET['json'])||isset($_POST['json'])||isset($_POST['submit_json'])) {
		header("Content-Type: text/plain");
		die(json_encode(array("error" => $GLOBALS['result'] == CreateAccountResult::Error, "errorcode" => strval($GLOBALS['errorcode']), "message" => $GLOBALS['message'])));
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
	<title>Register Email Account</title>
</head>

<body>
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
                <button type="submit" name="submit_json">Submit & JSON</button>
			</form>
		</div>
		<?php
		if($GLOBALS['result'] != CreateAccountResult::Skip) {
		  if($GLOBALS['result'] == CreateAccountResult::Success)
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