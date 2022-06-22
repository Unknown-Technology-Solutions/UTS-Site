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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <div id="zammad-feedback-form">form will be placed in here</div>

  <script id="zammad_form_script" src="https://zd.unknownts.com/assets/form/form.js"></script>

  <script>
    $(function() {
      $('#zammad-feedback-form').ZammadForm({
        messageTitle: 'Customer Contact',
        messageSubmit: 'Submit',
        messageThankYou: 'Thank you for your inquiry (#%s)! We\'ll contact you as soon as possible. <a href="/">Click here to go to our home page</a>',
        showTitle: true
      });
    });
  </script>
  </form>
  <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
</div>
<?php
if (isset($_POST['email']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['company']) && isset($_POST['request']) && isset($_POST['g-recaptcha-response'])) {
  $email = isset($_POST['email']) ? $_POST['email'] : '';
  $connect->real_escape_string($_POST['email']);
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
  $RequestSubmit = "INSERT INTO customer_requests (first_name, last_name, email, request, company) VALUES (\"" . $first_name . "\", \"" . $last_name . "\", \"" . $email . "\", \"" . $request . "\", \"" . $company . "\")";
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