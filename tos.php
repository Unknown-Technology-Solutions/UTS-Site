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
  <title>Terms Of Service</title>
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
    <div class="intro heading" id="waw">Website Terms and Conditions of Use</div>
    <div class="intro">



      <h2>1. Terms</h2>

      <p>By accessing this Website, accessible from unknownts.tk, you are agreeing to be bound by these Website Terms and Conditions of Use and agree that you are responsible for the agreement with any applicable local laws. If you disagree with any
        of these terms, you are prohibited from accessing this site. The materials contained in this Website are protected by copyright and trade mark law. These Terms of Service has been created with the help of the <a
          href="https://www.termsofservicegenerator.net">Terms of Service Generator</a> and the <a href="https://www.termsconditionsexample.com">Terms & Conditions Example</a>.</p>

      <h2>2. Use License</h2>

      <p>Permission is granted to temporarily download one copy of the materials on Unknown Technology Solutions's Website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this
        license you may not:</p>

      <ul>
        <li>modify or copy the materials;</li>
        <li>use the materials for any commercial purpose or for any public display;</li>
        <li>attempt to reverse engineer any software contained on Unknown Technology Solutions's Website;</li>
        <li>remove any copyright or other proprietary notations from the materials; or</li>
        <li>transferring the materials to another person or "mirror" the materials on any other server.</li>
      </ul>

      <p>This will let Unknown Technology Solutions to terminate upon violations of any of these restrictions. Upon termination, your viewing right will also be terminated and you should destroy any downloaded materials in your possession whether it
        is printed or electronic format.</p>

      <h2>3. Disclaimer</h2>

      <p>All the materials on Unknown Technology Solutions’s Website are provided "as is". Unknown Technology Solutions makes no warranties, may it be expressed or implied, therefore negates all other warranties. Furthermore, Unknown Technology
        Solutions does not make any representations concerning the accuracy or reliability of the use of the materials on its Website or otherwise relating to such materials or any sites linked to this Website.</p>

      <h2>4. Limitations</h2>

      <p>Unknown Technology Solutions or its suppliers will not be hold accountable for any damages that will arise with the use or inability to use the materials on Unknown Technology Solutions’s Website, even if Unknown Technology Solutions or an
        authorize representative of this Website has been notified, orally or written, of the possibility of such damage. Some jurisdiction does not allow limitations on implied warranties or limitations of liability for incidental damages, these
        limitations may not apply to you.</p>

      <h2>5. Revisions and Errata</h2>

      <p>The materials appearing on Unknown Technology Solutions’s Website may include technical, typographical, or photographic errors. Unknown Technology Solutions will not promise that any of the materials in this Website are accurate, complete,
        or current. Unknown Technology Solutions may change the materials contained on its Website at any time without notice. Unknown Technology Solutions does not make any commitment to update the materials.</p>

      <h2>6. Links</h2>

      <p>Unknown Technology Solutions has not reviewed all of the sites linked to its Website and is not responsible for the contents of any such linked site. The presence of any link does not imply endorsement by Unknown Technology Solutions of the
        site. The use of any linked website is at the user’s own risk.</p>

      <h2>7. Site Terms of Use Modifications</h2>
      <p>Unknown Technology Solutions may revise these Terms of Use for its Website at any time without prior notice. By using this Website, you are agreeing to be bound by the current version of these Terms and Conditions of Use.</p>

      <h2>8. Your Privacy</h2>

      <p>Please read <a href="privacy.php">our Privacy Policy</a>.</p>

      <h2>9. Governing Law</h2>

      <p>Any claim related to Unknown Technology Solutions's Website shall be governed by the laws of us without regards to its conflict of law provisions.</p>
      <footer>
      Unknown Technology Solutions 2017-<? echo date('Y'); ?><br />
      <a href="tos.php">TOS</a>
      <a href="privacy.php">Privacy Policy</a>
      <a href="/company/index.php">Employee Login</a>
    </footer>
    </div>
</body>

</html>
