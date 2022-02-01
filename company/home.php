<?php

if(!isset($_COOKIE['screen']) && isset($_GET['screen'])) {
	setcookie("screen", $_GET['screen'], time() + 12 * 30 * 7 * 24 * 60 * 60);
}

class BlogPost
{
    var $title;
	var $pubDate;
	var $link;
	var $description;
}

class News
{
    var $posts = array();

    function __construct($file_or_url)
    {
        $file_or_url = $this->resolveFile($file_or_url);
        if (!($x = simplexml_load_file($file_or_url)))
            return;

        foreach ($x->channel->item as $item)
        {
            $post = new BlogPost();
            $post->title = (string) $item->title;
			 $post->pubDate = (string) $item->pubDate;
			  $post->link = (string) $item->link;
			   $post->description = (string) $item->description;

            // Create summary as a shortened body and remove images, 
            // extraneous line breaks, etc.
            //$post->summary = $this->summarizeText($post->text);

            $this->posts[] = $post;
        }
    }

    private function resolveFile($file_or_url) {
        if (!preg_match('|^https?:|', $file_or_url))
            $feed_uri = $_SERVER['DOCUMENT_ROOT'] .'/shared/xml/'. $file_or_url;
        else
            $feed_uri = $file_or_url;

        return $feed_uri;
    }

    private function summarizeText($summary) {
        $summary = strip_tags($summary);

        // Truncate summary line to 100 characters
        $max_len = 100;
        if (strlen($summary) > $max_len)
            $summary = substr($summary, 0, $max_len) . '...';

        return $summary;
    }
}


class BlogPost2
{
    var $title;
	var $pubDate;
	var $link;
	var $description;
	var $media;
}
function string_contains($subject,$search)
{
	if (strpos($subject, $search) !== false)
		return true;
	else
		return false;
}


class News2
{
    var $posts = array();

    function __construct($file_or_url)
    {
        $file_or_url = $this->resolveFile($file_or_url);
        if (!($x = simplexml_load_file($file_or_url)))
            return;

        foreach ($x->channel->item as $item)
        {
            $post = new BlogPost2();
            $post->title = (string) $item->title;
			 $post->pubDate = (string) $item->pubDate;
			  $post->link = (string) $item->link;
			   $post->description = (string) $item->description;
			//$post->media = $item->media_content;
			 $media_group = $item->children( 'media', true );
			//$content = $media_group->content;
			//$post->media = $content;
			//print_r($media_group);
			//print_r($media_group->content);
			foreach($media_group->content as $i){
				$url = (string)$i->attributes()->url;
				
				if(string_contains($url,"300x"))
				{
					$post->media = $url;
					//print('<img src="'.$url.'">');
					//print('<BR>');
				}
			}
            // Create summary as a shortened body and remove images, 
            // extraneous line breaks, etc.
            //$post->summary = $this->summarizeText($post->text);

            $this->posts[] = $post;
        }
    }

    private function resolveFile($file_or_url) {
        if (!preg_match('|^https?:|', $file_or_url))
            $feed_uri = $_SERVER['DOCUMENT_ROOT'] .'/shared/xml/'. $file_or_url;
        else
            $feed_uri = $file_or_url;

        return $feed_uri;
    }

    private function summarizeText($summary) {
        $summary = strip_tags($summary);

        // Truncate summary line to 100 characters
        $max_len = 100;
        if (strlen($summary) > $max_len)
            $summary = substr($summary, 0, $max_len) . '...';

        return $summary;
    }
}



function rss_feed()
{
	print('<div style="padding-left:15px;display: inline-block !important;word-break: break-word !important;overflow-wrap: break-word !important;white-space:normal; ">');
	$url = "http://feeds.feedburner.com/TheHackersNews?format=xml";
	$news = new News($url);
	foreach($news->posts as $post)
	{
		print('<h4><img src="spinningskull.gif" style="width:40px;padding-right:5px"><a href="'.$post->link.'">'.$post->title.'</a></h4>');
		print('<B>'.$post->pubDate.'</b><BR>');
		print('<span style="display: inline-block !important;word-break: break-word !important;overflow-wrap: break-word !important;">'.$post->description.'...</span><BR>');
		print('');
	}
	print('</div>');
}

function rss_feed2()
{
	print('<div style="padding-left:15px;display: inline-block !important;word-break: break-word !important;overflow-wrap: break-word !important;white-space:normal; ">');
	$url = "https://threatpost.com/feed/";
	$news = new News2($url);
	foreach($news->posts as $post)
	{
		
		print('<h4><img src="spinningskull.gif" style="width:40px;padding-right:5px"><a href="'.$post->link.'">'.$post->title.'</a></h4>');
		print('<img src="'.$post->media.'"><BR><BR>');
		print('<B>'.$post->pubDate.'</b><BR>');
		print('<span style="display: inline-block !important;word-break: break-word !important;overflow-wrap: break-word !important;">'.$post->description.'</span><BR>');
		print('<BR>');
	}
	print('</div>');
}

$user_department = 'SECURITY';
include_once('./functions.php');
include_once('../jwt.php');
if (checkSessionValid("employee")) {
    if (isset($_GET['action']) && $_GET['action']=='complete' && is_numeric($_GET['id'])) {
		//print("UPDATE `uts_modern_v1`.`customer_requests` SET `completed`='true' WHERE  `id`=" . $_GET['id'] . ";");
        $result = $connect->query("UPDATE `uts_modern_v1`.`customer_requests` SET `completed`='true' WHERE  `id`=" . $_GET['id'] . ";");
    }
} else {
    header("Location: /uts_login.php");
}
##########################################
function table_exists($table_name)
{
	$sql ="SELECT * 
FROM information_schema.tables
WHERE table_schema = 'uts_modern_v1' 
    AND table_name = '".$table_name."'
LIMIT 1;";
	$t = fetch($sql);
	if(count($t)==0)
		return false;
	else
		return true;
}
if(!table_exists("news"))
{
	$sql = "CREATE TABLE `news` (
  `id` BIGINT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` BIGINT(12) NOT NULL DEFAULT UNIX_TIMESTAMP(),
  `content` TEXT  NOT NULL DEFAULT '',
  PRIMARY KEY (id) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	execute($sql);
	print("NEWS TABLE CREATED!");
}
if(!table_exists("support_tickets"))
{
	$sql = "CREATE TABLE `support_tickets` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_timestamp` BIGINT NOT NULL DEFAULT UNIX_TIMESTAMP(),
  `customer_id` BIGINT NOT NULL,
  `issue` TEXT NOT NULL,
  `assigned_employee_id` BIGINT DEFAULT NULL,
  `is_resolved` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (id) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	execute($sql);
	print("NEWS TABLE CREATED!");
}
/*
// permission denied
$sql = "ALTER TABLE mailserver.virtual_users ADD COLUMN IF NOT EXISTS customer_id BIGINT DEFAULT NULL;";
execute($sql);
*/
##########################################
    $screen = 'customer_requests';
    $action = '';
    if(isset($_GET['screen']))
        $screen = $_GET['screen'];
    if(isset($_GET['action']))
        $action = $_GET['action'];
    ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>UTS Employee Area</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="theme/bootstrap.css" media="screen">
    <link rel="stylesheet" href="theme/usebootstrap.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="bootstrap/html5shiv.js"></script>
      <script src="bootstrap/respond.min.js"></script>
    <![endif]-->
	<script>
	function findBootstrapEnvironment() {
		var envs = ['xs', 'sm', 'md', 'lg'];

		var $el = $('<div>');
		$el.appendTo($('body'));

		for (var i = envs.length - 1; i >= 0; i--) {
			var env = envs[i];

			$el.addClass('hidden-'+env);
			if ($el.is(':hidden')) {
				$el.remove();
				return env;
			}
		}
	}
	function setCookie(cname, cvalue, exdays) {
		//alert(cvalue);
	  const d = new Date();
	  d.setTime(d.getTime() + (exdays*24*60*60*1000));
	  let expires = "expires="+ d.toUTCString();
	  document.cookie = cname + "=" + cvalue + ";" + expires;
	  //alert(":"+getCookie(cname));
	}
	function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

	function init()
	{
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		  var target = $(e.target).attr("href") // activated tab
		  setCookie('screen',target.replace('#',''),20*365);
		});
		window.addEventListener('resize', resize);
		resize();
		$('a[data-toggle="tab"]').on('click', function(){
  if ($(this).parent('li').hasClass('disabled')) {
    return false;
  };
});
	}
	function resize()
	{
		var env = findBootstrapEnvironment();
		/*
		if(env == 'xs' || env == 'sm')
		{
			//hide tabs? show menu at top?
			document.getElementById("nav_tabs").style.display="none";
			document.getElementById("nav_dropdown").style.display="block";
			document.getElementById("myTabContent").style.marginTop="50px";
		}
		else
		{*/
			document.getElementById("nav_tabs").style.display="block";
			//document.getElementById("nav_dropdown").style.display="none";
			document.getElementById("myTabContent").style.marginTop="0px";
			
		//}
	}
	
	</script>
	<link href='https://fonts.googleapis.com/css?family=Anonymous%20Pro' rel='stylesheet'>
<style>
    body {
      font-family: 'Anonymous Pro', monospace;
      font-size: 18px/*22px*/;
	  padding-top:0px;/*19px;*/
    }
th, td {
            white-space: nowrap;
         }
		 textarea{ 
    background-color:black !important;
	color: white !important;
	font-size: 18px !important;
	border:1px solid white !important;
}
input[type="textbox"]{
background-color:black !important;
color: white !important;
font-size: 18px;
border:1px solid white !important;
}
select {
background-color:black !important;
color: white !important;
font-size: 18px;
border:1px solid white !important;
}
  </style>
  </head>
  <body style="background-color:black;" onload="init()">


	  <div class="container-fluid">
		<?php
		function mix($colour_a,$colour_b,$length,$index)
		{
			$m = array(
				"red"=> ($colour_b['red'] - $colour_a['red']) / $length,
				"green"=> ($colour_b['green'] - $colour_a['green']) / $length,
				"blue"=> ($colour_b['blue'] - $colour_a['blue']) / $length
			);
			return array(
				"red"=> $m['red'] * $index + $colour_a['red'],
				"green"=> $m['green'] * $index + $colour_a['green'],
				"blue"=> $m['blue'] * $index + $colour_a['blue']
			);
		}
		function mix_print($str,$size="")
		{
			for($i=0;$i<strlen($str);$i++)
			{
				$c = mix(array("red"=>1.0,"green"=>1.0,"blue"=>1.0),array("red"=>0,"green"=>1.0/255*188,"blue"=>1.0/255*140),strlen($str),strlen($str)-$i);
				if($size=="")
					print('<span style="color:rgb('.(255 * $c['red']).','.(255 * $c['green']).','.(255 * $c['blue']).');">'.$str[$i].'</span>');
				else
					print('<span style="font-size: '.$size.';color:rgb('.(255 * $c['red']).','.(255 * $c['green']).','.(255 * $c['blue']).');">'.$str[$i].'</span>');
			}
		}
		
		mix_print("Unknown Technology Solutions","3em");
		?>
		<br>
		<div class="bs-component" style="white-space: nowrap;min-width:1000px;">
		  <div id="nav_tabs">
		  <ul class="nav nav-tabs" style="margin-bottom: 5px;">
			<li <?php
				if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['screen']) && $_GET['screen'] != 'customer_requests')
					print('class="disabled"');
				else if(((isset($_COOKIE['screen'])&&$_COOKIE['screen']=='customer_requests' || (!isset($_COOKIE['screen']) && isset($_GET['screen']) && $_GET['screen']=='customer_requests'))))
					print('class="active"');
				
				
				?>><a href="#customer_requests" data-toggle="tab"><i class="bi <?php
			
			
			?>bi-envelope"></i><i class="bi bi-envelope-exclamation"></i> Customer Requests<?php
			$sql = "SELECT COUNT(*) AS count FROM customer_requests WHERE completed = 'false' ORDER BY id ASC";
			$rows = fetch($sql);
			$cnt = $rows[0]['count'];
			if($cnt > 0)
				print(' ('.$cnt.')');
			?></a></li>
			<!--
			<li class="dropdown">
			  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="bi bi-people"></i> Accounting <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu" style="background-color:black;">
				<li ><a href="../customer/home.php" style="color:white;"><i class="bi bi-people"></i> Customer Records</a></a></li>
				<li ><a href="" style="color:white;"><i class="bi bi-credit-card-2-back"></i> Charge Types</a></li>
				<li ><a href="" style="color:white;"><i class="bi bi-box"></i> Account Types</a></li>
			  </ul>
			</li>
			-->
			<li <?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='customer_records')
					print('class="active"');
				else if(isset($_GET['action']) && $_GET['action'] == 'edit')
					print('class="disabled"');
				?>><a href="#customer_records" data-toggle="tab"><i class="bi bi-people"></i> Customer Records</a></li>
			<li <?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='charge_types')
					print('class="active"');
				else if(isset($_GET['action']) && $_GET['action'] == 'edit')
					print('class="disabled"');
				?>><a href="#charge_types" data-toggle="tab"><i class="bi bi-credit-card-2-back"></i> Charge Types</a></li>
			<li <?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='account_types')
					print('class="active"');
				else if(isset($_GET['action']) && $_GET['action'] == 'edit')
					print('class="disabled"');
				?>><a href="#account_types" data-toggle="tab"><i class="bi bi-box"></i> Account Types</a></li>
			<li <?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='support_tickets')
					print('class="active"');
				else if(isset($_GET['action']) && $_GET['action'] == 'edit')
					print('class="disabled"');
				?>><a href="#support_tickets" data-toggle="tab"><i class="bi bi-box"></i> Support Tickets</a></li>
			<li <?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='accounts')
					print('class="active"');
				else if(isset($_GET['action']) && $_GET['action'] == 'edit')
					print('class="disabled"');
				?>><a href="#accounts" data-toggle="tab"><i class="bi bi-box"></i> Accounts</a></li>
			
			<li <?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='news_posts')
					print('class="active"');
				else if(isset($_GET['action']) && $_GET['action'] == 'edit')
					print('class="disabled"');
				?>><a href="#news_posts" data-toggle="tab"><i class="bi bi-newspaper"></i> News Posts</a></li>
			<li <?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='security')
					print('class="active"');
				else if(isset($_GET['action']) && $_GET['action'] == 'edit')
					print('class="disabled"');
				?>><a href="#security" data-toggle="tab"><i class="bi bi-exclamation-diamond"></i> Security</a></li>
			<!--<li <?php
				print('class="disabled"');
				?>><a href="#employees" data-toggle="tab"><i class="bi bi-file-person"></i> Employees</a></li>-->
			<li class="dropdown">
			  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="bi bi-person-circle"></i> Account <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu" style="background-color:black;">
				<li ><a href="../customer/home.php" style="color:white;"><i class="bi bi-gear"></i> Goto Customer Portal</a></li>
				<li ><a style="color:white;"><i class="bi bi-gear"></i> Settings</a></li>
				<li ><a href="../uts_login.php?logout=true" style="color:white;"><i class="bi bi-door-closed"></i> Logout</a></li>
			  </ul>
			</li>
		  </ul>
		  </div>
		  <div id="myTabContent" class="tab-content">
		  <!------------------------>
		  		<div class="tab-pane fade<?php
				if((isset($_COOKIE['screen'])&&$_COOKIE['screen']=='customer_requests') || !isset($_COOKIE['screen']))
					print(' active in');
				?>" id="customer_requests">
					<div class="panel panel-default">
						<?php
						$show_second_panel = true;
						$caption = '<i class="bi bi-check2-circle"></i> Complete a Customer Request';
						if(isset($_GET['action']) && isset($_GET['screen']) && $_GET['action'] == 'edit' && $_GET['screen']=='customer_requests')
						{
							$caption = '<i class="bi bi-check2-circle"></i> Edit a customer request';
							$show_second_panel = false;
						}
						?>
						<div class="panel-heading" style="background-color:black;"><?php print($caption); ?></div>
						<div class="panel-body" style="background-color:black;">
							<?php table_editor('customer_requests', $action, false);?>
						</div>
						<?php 
						if($show_second_panel)
						{
							?>
						<div class="panel-footer" style="background-color:black;"><i class="bi bi-pencil-square"></i> Edit a Processed Customer Request</div>
						<div class="panel-footer" style="background-color:black;">
							<?php table_editor('customer_requests', $action, false, true);?>
						</div>
						<?php
						}
						?>
					</div>
				</div>
			<!------------------------>
			
			<div class="tab-pane fade<?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='news_posts')
					print(' active in');
				?>" id="news_posts">
			  <div class="panel panel-default">


        <?php
		table_editor("news", $action);
		?>

			  </div>
			  </div>
			
			<?php
			 ?>

			
			<!------------------------>
						<!------------------------>
			
			<div class="tab-pane fade<?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='customer_records')
					print(' active in');
				?>" id="customer_records">
			  <div class="panel panel-default">


        <?php
		table_editor("customer_records", $action);
		?>

			  </div>
			  </div>
			
			<?php
			 ?>

			
			<!------------------------>
			
			<div class="tab-pane fade<?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='charge_types')
					print(' active in');
				?>" id="charge_types">
			  <div class="panel panel-default">
			

        <?php
		table_editor("charge_types", $action);
		?>

			</div>
			</div>		
			<!------------------------>
			
			<div class="tab-pane fade<?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='account_types')
					print(' active in');
				?>" id="account_types">
			  <div class="panel panel-default">
			 

        <?php
		table_editor("acct_types", $action);
		?>

			</div>  
			</div>
			<!------------------------>
						
			<div class="tab-pane fade<?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='support_tickets')
					print(' active in');
				?>" id="support_tickets">
			  <div class="panel panel-default">
			 

        <?php
		table_editor("support_tickets", $action);
		?>

			</div>  
			</div>
			<!------------------------>
									
			<div class="tab-pane fade<?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='accounts')
					print(' active in');
				?>" id="accounts">
			  <div class="panel panel-default">
			 

        <?php
		$GLOBALS['schema'] = 'mailserver';
		table_editor("virtual_users", $action);
		$GLOBALS['schema'] = 'uts_modern_v1';
		?>

			</div>  
			</div>
			<!------------------------>
			<div class="tab-pane fade<?php
				if(isset($_COOKIE['screen'])&&$_COOKIE['screen']=='security')
					print(' active in');
				?>" id="security">
				
			  
			  
			  

	<!------------------------------------------------------------>
	<div class="bs-docs-section">
		<div class="row">
			<div class="col-lg-6" style="padding-right:2px;">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color:black;"><i class="bi bi-rss"></i> The Hacker News</div>
					<div class="panel-body" style="background-color:black;">
						<?php  rss_feed(); ?>
					</div>
				</div>
			</div>
			<div class="col-lg-6" style="padding-left:3px;">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color:black;"><i class="bi bi-rss"></i> Threat Post</div>
					<div class="panel-body" style="background-color:black;">
						<?php  rss_feed2(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	   <!------------------------------------------------------------>
			  
			

			<!------------------------>
		  </div>
		</div>
	  </div>

    <script src="jquery-1.10.2.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
	
  </body>
</html>
<!-- sponge 2 -->
