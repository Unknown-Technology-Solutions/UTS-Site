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
  <title>Data Security</title>
</head>

<body onload="startTime()">
  <noscript>Sorry, your browser does not support JavaScript!</noscript>
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
    <div class="intro heading" id="1">Data Security Services</div>
    <div class="intro">
        We here at Unknown Technology Solutions noticed a lack of local and secure data management and destruction services present in Omaha, and we set out to change that.<br />
    </div>
    <div class="intro heading" id="2">Title 2</div>
    <div class="intro">
        Paragraph 2
    </div>
    <div class="intro heading" id="3">Title 3</div>
    <div class="intro">
        Paragraph 3
    </div>
    <footer>
      Unknown Technology Solutions 2017-<?php echo date('Y'); ?><br />
      <a href="tos.php">TOS</a>
      <a href="privacy.php">Privacy Policy</a>
      <a href="/company/index.php">Employee Login</a>
    </footer>
  </div>
</body>

</html>
