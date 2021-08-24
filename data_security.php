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
      We noticed an issue with secure data destruction. They come, pick up your data, load it into a truck, and drive accross town, or sometimes even out of state, and then destroy the data. 
      The issue with that might not be apparent at first, and it wasn't to us either. After we thought about it, we realized that between when your private data is picked up to when it is destroyed, a lot of time passes. 
      A lot of time for something to be stolen or leaked. <br />
      We sought out to solve this issue by extending our Panopticon security program to close that time and security gap. <!--If that doesn't put your mind at easy we will also incinerate on site.-->
      <!--We here at Unknown Technology Solutions noticed a lack of local and secure data management and destruction services present in Omaha, and we set out to change that.<br />-->
    </div>
    <div class="intro heading" id="2">Destruction</div>
    <div class="intro">
      We offer a variety of levels of data destruction, from securely shredding paper, to completely melting storage media. 


      <div class="intro plate">
        <div class="intro box center">
          <div class="smaller_heading">Tier 0</div>
          <ul>
            <li>Confidential item destruction</li>
          </ul>
        </div>
        <div class="intro box center">
          <div class="smaller_heading">Tier 1</div>
          <ul>
            <li>Full system wipe</li>
            <li>Optional OS reinstall</li>
          </ul>
        </div>
      </div>


      <div class="intro plate">
        <div class="intro box center">
          <div class="smaller_heading">Tier 2</div>
          <ul>
            <li>Full system wipe</li>
            <li>Optional OS reinstall</li>
            <li>Storage replacement</li>
          </ul>
        </div>
        <div class="intro box center">
          <div class="smaller_heading">Tier 3</div>
          <ul>
            <li>Full system wipe</li>
            <li>Optional OS reinstall</li>
            <li>Hardware destruction</li>
            <li>Storage medium destruction</li>
            <li>Hardware replacements</li>
          </ul>
        </div>
      </div>


      Need something that isn't listed but falls under this type of service? <a href="/customer_submit.php">Contact us</a> and we will see what we can do for you.


    </div>
    <div class="intro heading" id="3"></div>
    <div class="intro">
      Unknown Technology Solutions not only securely destroys confidential information, but we are also commited to recycling and reducing our waste.<br />
      Anything that we destroy that is able to be recycled will be recycled. This may sound insecure or dangerous, but before we recycle anything, we completly remove all traces of any information.
      All paper waste is cleaned and is pulped, any melted down storage media is cast into "muffins" and recycled, and all solid state media has its flash completely destroyed.
      If a storage medium is cleaned and still functional (Such as a HDD or SSD), it may be refurbished and sold if the customer (You) allow.
    </div>
    <div class="intro heading" id="4"></div>
    <div class="intro">
      For more information or to request a quote, contact us at <a href="mailto://support@unknownts.tk">support@unknownts.tk</a>
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