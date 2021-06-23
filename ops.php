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
  <title>Operations</title>
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
    <div class="intro heading" id="1">The Panopticon</div>
    <div class="intro">
        When faced with tomorrow's security challenges today, we decided we needed a new approach to security; enter The&nbsp;Panopticon.<br />
        The&nbsp;Panopticon is our network and security center, physical and digital.
        Here we monitor all network traffic and security systems. With The&nbsp;Panopticon active, we are not only able to respond to threats that are active, but threats that are developing.
        By analyzing traffic we are able to spot trends and attacks before they even happen saving time and money.<br />
        The&nbsp;Panopticon is our defense, what's yours? 
    </div>
    <div class="intro heading" id="2">Current Security Notices</div>
    <div class="intro">
        TBA
    </div>
    <div class="intro heading" id="3">Network Status</div>
    <div class="intro">
        TBA
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
