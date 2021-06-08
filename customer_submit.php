<!DOCTYPE html>
<?php
    include('./functions.php');
?>
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
  <link href='https://fonts.googleapis.com/css?family=Anonymous%20Pro' rel='stylesheet'>
  <style>
    body {
      font-family: 'Anonymous Pro', monospace;
      font-size: 22px;
    }
  </style>
  <title>UTS Contact Form</title>
  <script type="text/javascript">
    var onloadCallback = function() {
      grecaptcha.render('capachta', {
        'sitekey' : "<?php print($web_settings['site_key']); ?>",
        'theme' : 'dark'
      });
    };
  </script>
</head>

<body onload="startTime()">
  <div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <?php
      include('./functions.php');
      menuContents();
    ?>
    <br />
    <a href="#"><span id="clock"></span></a>
  </div>

  <div onclick="openNav()" class="open_menu" id="hams">
    <a href="javascript:void(0)">&times;</a>
  </div>

  <div id="main">
    <div class="title">Unknown<br />Technology<br />Solutions<span class="blinking-cursor" style="font-size: 1em;">|</span></div>
            <div class="intro heading">
              Contact Form
            </div>
            <div class="intro">
              <form action="?" method="POST">
                <div class="label">Email: </div><input class="tbox" type="email" required name="email" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">First Name: </div><input class="tbox" type="text" required name="first_name" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">Last Name: </div><input class="tbox" type="text" required name="last_name" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">Company: </div><input class="tbox" type="text" required name="company" style="width: 130px;" autocomplete="off"></input><br />
                <div class="label">What  you are wanting to contact us about: </div><textarea class="tbox" type="text" required name="request" rows="10" cols="30" autocomplete="off"></textarea><br /><br />
                <div id="capachta"></div>
                <br />
                <button type="submit" name="submit">Submit</button>
              </form>
              <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
            </div>
            <?php
            $email = $connect->real_escape_string($_POST['email']);
            $first_name = $connect->real_escape_string($_POST['first_name']);
            $last_name = $connect->real_escape_string($_POST['last_name']);
            $company = $connect->real_escape_string($_POST['company']);
            $request = $connect->real_escape_string($_POST['request']);


            $data = array('secret' => $web_settings['secret'], 'response' => $_POST['g-recaptcha-response'], 'remoteip' => $_SERVER['REMOTE_ADDR']);
            $url = "https://www.google.com/recaptcha/api/siteverify";
            $options = array(
              'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded",
                'method'  => 'POST',
                'content' => http_build_query($data)
              )
            );
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            if ($result === false) {
                print("Error! reCAPTCHA failed! Please try again!");
            }
            $proc_res = substr($result, 15, 5);
            //print("\"".$proc_res."\"");\
            $RequestSubmit = "INSERT INTO customer_requests (first_name, last_name, email, request, company) VALUES (\"".$first_name."\", \"".$last_name."\", \"".$email."\", \"".$request."\", \"".$company."\")";
            if (isset($_POST['submit'])) {
                if ($proc_res == "true,") {
                    $connect->query($RequestSubmit);
                    print("<div class=\"intro heading\">Thank you for contacting us! We will contact you back as soon as we can!</div>");
                } else {
                    print("<div class=\"intro heading\">The reCAPTCHA is required</div>");
                }
            }
            ?>
    <footer>
      Unknown Technology Solutions 2017-<?php echo date('Y'); ?><br />
      <a href="tos.php">TOS</a>
      <a href="privacy.php">Privacy Policy</a>
      <a href="/company/index.php">Employee Login</a>
    </footer>
  </div>
</body>
</html>
