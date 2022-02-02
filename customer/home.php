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
  </style>
  </head>
  <body style="background-color:black;">
	



<div class="navbar navbar-default">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="#">UTS Customer Portal</a>
                </div>
                <div class="navbar-collapse collapse navbar-responsive-collapse">
                  <ul class="nav navbar-nav">
                    <li class="active"><a href="#">News</a></li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Services <b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li><a href="#">Backup & FTP</a></li>
                        <li><a href="#">Email</a></li>
                      </ul>
                    </li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <b class="caret"></b></a>
                      <ul class="dropdown-menu">
					    <li><a href="#">Support Tickets</a></li>
                        <li><a href="#">Settings</a></li>
						
                        <li><a href="#">Logout</a></li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
			  
			  
			  <?php
			  $sql = "SELECT * FROM news ORDER BY id DESC";
			  $t = fetch($sql);
			  foreach($t as $row)
			  {
				  print($row['timestamp'].'<BR>');
				  print($row['content'].'<BR><BR>');
			  }
			  ?>
		
		
		<script src="jquery-1.10.2.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
		
	</body>
</html>