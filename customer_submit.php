<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->assign('title', 'UTS Contact Form');
$smarty->assign('captcha', true);
$smarty->display('header.tpl');
?>
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
            if(isset($_POST['email']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['company']) && isset($_POST['request']) && isset($_POST['g-recaptcha-response']))
            {
              $email = isset($_POST['email']) ? $_POST['email'] : '';$connect->real_escape_string($_POST['email']);
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
            }
            ?>
<?php
$smarty->display('footer.tpl');
?>
