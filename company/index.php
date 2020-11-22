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
        <link rel="stylesheet" type="text/css" href="/css/login.css" />
        <link href='https://fonts.googleapis.com/css?family=Anonymous Pro' rel='stylesheet'>
        <style>
            body {
                font-family: 'Anonymous Pro', monospace;
                font-size: 22px;
            }
        </style>
        <title>Company Login</title>
    </head>
    <body onload="startTime()">
        <noscript>Sorry, your browser does not support JavaScript!</noscript>
        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="/index.html">UTS Homepage</a>
            <br />
            <a href="#"><span id="clock"></span></a>
        </div>
        <div onclick="openNav()" class="open_menu" id="hams">
            <a href="javascript:void(0)">&times;</a>
        </div>
        <div id="main">
            <div class="title">Unknown<br />Technology<br />Solutions<span class="blinking-cursor" style="font-size: 1em;">|</span></div>
            <div class="intro">
                <form action="/api/login.php" method="POST">
                    <input type="text" name="username" id="login" /> <br />
                    <input type="password" name="password" id="login" /> <br />
                    <input type="checkbox" name="remember" id="login" /> <br />
                </form>
            </div>
        </div>
    </body>
</html>
