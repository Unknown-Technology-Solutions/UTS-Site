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
  <title>UTS Software</title>
</head>

<body onload="startTime()">
  <div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <?php
      include_once('./functions.php');
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
    <div class="intro heading">Software</a>
      <div class="intro">
        Since we here at UTS develop software, we thought we should share it. Along with the following software being free, we also provide support! You can find our contact info <a href="index.html#contact">here</a>.
        <ul>
          <li>
            <a href="#"></a>Network Monitor and Reporter (NMR) | V0.60 <br />
          </li>
          <li>
            <a href="#"></a>Power-on Notifier | V0.1 <br />
            <a href="downloads/PowerOnNotifier.exe">Windows Download</a> <br />
            <!--<a href="/downloads/PowerOnNotifier" download>Linux Download</a>-->
          </li>
        </ul>
        For any of the above programs, feel free to <a href="mailto://support@unknownts.com">email us</a> if you need support or have questions.
      </div>
    </div>
    <footer>
      Unknown Technology Solutions 2017-<?php echo date('Y'); ?><br />
      <a href="tos.php">TOS</a>
      <a href="privacy.php">Privacy Policy</a>
      <a href="/uts_login.php">Employee Login</a>
    </footer>
  </div>
</body>

</html>
