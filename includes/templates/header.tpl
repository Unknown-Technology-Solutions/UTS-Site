<!DOCTYPE html>
<html lang="en">
<head>
  <script defer src="{$documentRoot}/script-resources/menu.js"></script>
  <script defer src="{$documentRoot}/script-resources/clock.js"></script>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta property="og:type" content="website">
  <meta itemprop="name" content="Unknown Technology Solutions">
  <meta property="og:title" content="Unknown Technology Solutions" />
  <meta property="og:description" content="{$metaTags.description}" />
  <meta itemprop="description" content="{$metaTags.description}">
  <meta property="og:color" content="{$metaTags.color}" />
  <meta property="og:image" content="{$metaTags.image}" />
  <meta itemprop="image" content="{$metaTags.image}">
  <meta property="og:url" content="{$metaTags.url}" />
  <link rel="shortcut icon" href="{$documentRoot}/{$metaTags.favicon}">
  <link rel="stylesheet" type="text/css" href="{$documentRoot}/css/modern.css" />
  <link rel="stylesheet" type="text/css" href="{$documentRoot}/css/blinking_cursor.css" />
  <link rel="stylesheet" type="text/css" href="{$documentRoot}/css/menu.css" />
  <link rel="stylesheet" type="text/css" href="{$documentRoot}/css/login.css" />
  <link href='https://fonts.googleapis.com/css?family=Anonymous%20Pro' rel='stylesheet'>
  <style>
    body {
      font-family: 'Anonymous Pro', monospace;
      font-size: 22px;
    }
  </style>
  <title>{$title}</title>
  {if $captcha eq 1}
  <script type="text/javascript">
    var onloadCallback = function() {
      grecaptcha.render('capachta', {
        'sitekey' : "{$siteKey}",
        'theme' : 'dark'
      });
    };
  </script>
  {/if}
</head>

<body onload="startTime()">
  <noscript>Sorry, your browser does not support JavaScript!</noscript>
  <div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    {foreach from=$menu key=label item=page}
	   <a href="{$page}">{$label}</a>
	{/foreach}
    <br />
    <a href="#"><span id="clock"></span></a>
  </div>

  <div onclick="openNav()" class="open_menu" id="hams">
    <a href="javascript:void(0)">&times;</a>
  </div>

  <div id="main">
    <div class="title">Unknown<br />Technology<br />Solutions<span class="blinking-cursor" style="font-size: 1em;">|</span></div>
