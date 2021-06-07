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
  <link href='https://fonts.googleapis.com/css?family=Anonymous%20Pro' rel='stylesheet'>
  <style>
    body {
      font-family: 'Anonymous Pro', monospace;
      font-size: 22px;
    }
  </style>
  <title>Unknown Technology Solutions</title>
</head>

<body onload="startTime()">
  <noscript>Sorry, your browser does not support JavaScript!</noscript>
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
    <div class="intro heading" id="waw">Who are we?</div>
    <div class="intro">
      We are Unknown Technology Solutions. Ever since our founding in 2017 by an aspiring software engineer, we've made it our goal to provide high quality technology solutions with the support and knowledge to back it.
    </div>
    <div class="intro heading" id="wdwd">What do we do?</div>
    <div class="intro">
      We create and provide high quality software, hardware, and general technology solutions for an affordable price.
    </div>
    <div class="intro heading" id="contact">Contact Us</div>
    <div class="intro">
      If you have any questions, please feel free to contact us!<br />
      E-Mail: <a href="mailto://support@unknownts.tk">support@unknownts.tk</a><br />
      You can also <a href="customer_submit.php">fill out this form</a> and we will contact you back as soon as we are available!
    </div>
    <footer>
      Unknown Technology Solutions 2017-2021<br />
      <a href="tos.html">TOS</a>
      <a href="privacy.html">Privacy Policy</a>
      <a href="/company/index.php">Employee Login</a>
    </footer>
  </div>
</body>

</html>
