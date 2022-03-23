<?php
include_once('./functions.php');
include_once('../jwt.php');
if (checkSessionValid("customer") || checkSessionValid("employee")) {
    if (isset($_GET['completed']) && is_numeric($_GET['id'])) {
        $result = $connect->query("UPDATE `uts_modern_v1`.`customer_requests` SET `completed`='true' WHERE  `id`=" . $_GET['id'] . ";");
    }
} else {
    header("Location: ../uts_login.php");
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>UTS Customer Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="theme/bootstrap.css" media="screen">
	<script src="fontawesome-free-6.0.0-web/js/all.min.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="bootstrap/html5shiv.js"></script>
      <script src="bootstrap/respond.min.js"></script>
    <![endif]-->
	<script>
	function findBootstrapEnvironment() {
		var envs = ['xs', 'sm', 'md', 'lg'];

		var $el = $('<div>');
		$el.appendTo($('body'));

		for (var i = envs.length - 1; i >= 0; i--) {
			var env = envs[i];

			$el.addClass('hidden-'+env);
			if ($el.is(':hidden')) {
				$el.remove();
				return env;
			}
		}
	}
	function setCookie(cname, cvalue, exdays) {
		//alert(cvalue);
	  const d = new Date();
	  d.setTime(d.getTime() + (exdays*24*60*60*1000));
	  let expires = "expires="+ d.toUTCString();
	  document.cookie = cname + "=" + cvalue + ";" + expires;
	  //alert(":"+getCookie(cname));
	}
	function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

	
	</script>
	<link href='https://fonts.googleapis.com/css?family=Anonymous%20Pro' rel='stylesheet'>
<style>
    body {
      font-family: 'Anonymous Pro', monospace;
      font-size: 18px/*22px*/;
	  padding-top:0px;/*19px;*/
    }
th, td {
            white-space: nowrap;
         }
		 textarea{ 
    background-color:black !important;
	color: white !important;
	font-size: 18px !important;
	border:1px solid white !important;
}
input[type="textbox"]{
background-color:black !important;
color: white !important;
font-size: 18px;
border:1px solid white !important;
}
select {
background-color:black !important;
color: white !important;
font-size: 18px;
border:1px solid white !important;
}
.navbar-default .navbar-nav > .open > a,
.navbar-default .navbar-nav > .open > a:hover,
.navbar-default .navbar-nav > .open > a:focus {
  background-color: #464545;
  color: #00bc8c;
}

  </style>
  </head>
  <body style="background-color:black;">
	



<div class="navbar navbar-default">
                <div class="navbar-header" style="background-color:#464545;">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="#" style="color: #00bc8c;"><i class="fa-solid fa-cloud"></i> UTS Customer Portal</a>
                </div>
                <div class="navbar-collapse collapse navbar-responsive-collapse" style="background-color:#141414;">
                  <ul class="nav navbar-nav">
                    <li class="active" ><a href="home.php?screen=news"  style="background-color:#222222;"><i class="fa-solid fa-newspaper"></i> News</a></li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa-solid fa-bell-concierge"></i> My Services <b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        
                        <li><a href="home.php?screen=email"><i class="fa-solid fa-envelope"></i> Email</a></li>
						<li><a href="home.php?screen=backupftp"><i class="fa-solid fa-floppy-disk"></i> Backup & FTP</a></li>
                      </ul>
                    </li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa-solid fa-circle-user"></i> Ordante <b class="caret"></b></a>
                      <ul class="dropdown-menu">
					    <li><a href="home.php?screen=support_tickets"><i class="fa-solid fa-ticket"></i> Support Tickets</a></li>
                        <li><a href="#"><i class="fa-solid fa-cog"></i> Settings</a></li>
                        <li><a href="../uts_login.php?logout=true"><i class="fa-solid fa-door-open"></i> Logout</a></li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
			  
			  
			  <?php
			  if(!isset($_GET['screen']) || (isset($_GET['screen']) && $_GET['screen'] == 'news'))
			  {
				  $sql = "SELECT * FROM news ORDER BY id DESC";
				  $t = fetch($sql);
				  foreach($t as $row)
				  {
					  ?>
					  <div class="panel panel-primary">
						<div class="panel-heading">
						  <h3 class="panel-title"><?php echo date("Y-m-d H:i:s", substr($row['timestamp'], 0, 10)); ?></h3>
						</div>
						<div class="panel-body">
						  <?php print($row['content']); ?>
						</div>
					  </div>
					  <?php
				  }
			  }
			  else if(isset($_GET['screen']) && $_GET['screen'] == 'support_tickets')
			  {
				  // todo: finish this
				  //$sql = "SELECT * FROM support_tickets WHERE customer_id "
				  ?>
				  <div class="panel panel-primary">
					<div class="panel-heading">
					  <h3 class="panel-title">Support Tickets</h3>
					</div>
					<div class="panel-body">
					  You have 0 open tickets. Would you like to open a new ticket? Well ya can't the code doesnt exist.
					</div>
				  </div>
				  <?php
			  }
			  else if(isset($_GET['screen']) && $_GET['screen'] == 'backupftp')
			  {
				  ?>
				  <div class="panel panel-primary">
					<div class="panel-heading">
					  <h3 class="panel-title">Backup & FTP - Not Implemented</h3>
					</div>
					<div class="panel-body">
					  This page requires collaboration with Zane for it to be operational.
					</div>
				  </div>
				  <?php
			  }
			  else if(isset($_GET['screen']) && $_GET['screen'] == 'email')
			  {
				  ?>
				  <div class="panel panel-primary">
					<div class="panel-heading">
					  <h3 class="panel-title">Email</h3>
					</div>
					<div class="panel-body">
<?php


       
chdir("../");
include_once('./functions.php');
include_once('./errors.php');
include_once('./authentication.php');
 $GLOBALS['ErrorCode'] = ErrorCode::Skip;

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
}
?>
        <div class="intro heading">
            Register Email Alias<span class="blinking-cursor" style="font-size: 1em;">|</span>
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
					</div>
				  </div>
				  <?php
			  }
			  else
			  {
				  print("404");
			  }
			  ?>

		
		<script src="jquery-1.10.2.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
		
	</body>
</html>