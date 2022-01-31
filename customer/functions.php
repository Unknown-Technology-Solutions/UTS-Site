<?php

$web_settings = parse_ini_file("../web_settings.ini.php");

//Connection info for the database
$servername = $web_settings['ip'];
$username = $web_settings['username'];
$password = $web_settings['password'];
$database = $web_settings['database'];
$port = $web_settings['port'];
$connect = new mysqli($servername, $username, $password, $database, $port);
$GLOBALS['connect'] = $connect;
function escape($data)
{
    $connect = $GLOBALS['connect'];
    return $connect->real_escape_string($data);
}

function escape_html($html)
{
    $out = str_replace(">","&#62;",str_replace("<","&#60;",$html));
    $out = str_replace("javascript:","",$out);
    $out = str_replace("alert(","",$out);
    $out = str_replace("'", "&apos;", $out);
    $out = str_replace('"', '&quot;', $out);
    return $out;
}

function get_mysql_error()
{
	$connect = $GLOBALS['connect'];
	return $connect->error;
}

function execute($sql)
{
    $connect = $GLOBALS['connect'];
    $connect->query($sql);
	
	if($connect->error!="")
	{
		?>
		<div class="bs-component">
		  <div class="alert alert-dismissable alert-danger" style="margin:0px;padding-left:15px;display: inline-block !important;word-break: break-word !important;overflow-wrap: break-word !important;white-space:normal;margin-bottom:10px;color:black;">
			<strong>Error:</strong> <?php print($connect->error); ?>
		  </div>
		</div>
		<?php
	}
    //print('<span style="color:red;">'.$connect->error.'</span>');
    return $connect->insert_id;
}

function fetch($sql)
{
    $connect = $GLOBALS['connect'];
    $rows = array();
    if ($result = $connect->query($sql))
    {
        while($row = $result->fetch_assoc())
            array_push($rows, $row);
        $result -> free_result();
    }
    return $rows;
}
?>