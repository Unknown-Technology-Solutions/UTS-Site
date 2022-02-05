<?php

$web_settings = parse_ini_file("../web_settings.ini.php");

//Connection info for the database
$servername = $web_settings['ip'];
$username = $web_settings['username'];
$password = $web_settings['password'];
$database = $web_settings['database'];
$port = $web_settings['port'];
$connect = new mysqli($servername, $username, $password, $database, $port);
$GLOBALS['connect_default'] = $connect;
$GLOBALS['connect'] = $connect;
$GLOBALS['connect_mailserver'] = mail_db();
$GLOBALS['schema'] = 'uts_modern_v1';
function mail_db()
{
	global $web_settings;
    $rservername = $web_settings['r_ip'];
    $rusername = $web_settings['r_username'];
    $rpassword = $web_settings['r_password'];
    $rdatabase = $web_settings['r_database'];
    $rport = intval($web_settings['r_port']);
    $connect_r = new mysqli($rservername, $rusername, $rpassword, $rdatabase, $rport);
    return $connect_r;
}

function switch_db()
{
	if($GLOBALS['schema'] == 'uts_modern_v1')
	{
		$GLOBALS['connect'] = $GLOBALS['connect_mailserver'];
		$GLOBALS['schema'] = 'mailserver';
		
	}
	else
	{
		$GLOBALS['connect'] = $GLOBALS['connect_default'];
		$GLOBALS['schema'] = 'uts_modern_v1';
	}
	print("setting schema ".$GLOBALS['schema']);
}

/*
Completed | ID | Name | Company | EMail | Request body or link to request body | Submit time

<table>
    <th>Completed</th> <th>ID</th> <th>Name</th> <th>Company</th> <th>EMail</th> <th>Message</th> <th>Submit Time</th>
</table>

build_table($ASSOC_ARR, $header_arr);

*/
/*
function build_table_customer_records($header_arr, $column_array, $result, $column_overide = null)
{
	$html = '<div class="bs-component">';
    $html .= '<table class="table table-striped table-hover">'; // table start

    $html .= '<tr>'; //Start table header
    foreach ($header_arr as $single) {
        $html .= "<th>" . $single . "</th>";
    }
    $html .= '</tr>'; // End table header
    
    if (is_null($column_overide)) {
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            foreach ($column_array as $name) {
                $html .= '<td>';
                $html .= $row[$name];
                $html .= '</td>';
            }
            $html .= '</tr>';
        }
    } elseif ($column_overide == "requests") {
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            foreach ($column_array as $name) {
                $html .= '<td>';
                if ($name == "completed") {
                    $html .= '<form action="./home.php?completed=&id=' . $row['id'] . '" method="POST"><button class="btn btn-default" name="complete_task" type="submit"><i class="bi bi-check"></i>Complete</button></form>';
                } else {
                    $html .= $row[$name];
                }
                $html .= '</td>';
            }
            $html .= '</tr>';
        }
    }

    return $html.'</div>';
}
*/
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

function fetch($sql,$server=null)
{
    $connect = $GLOBALS['connect'];
	if($server != null)
	{
		$connect = $server;//$GLOBALS['connect_mailserver']
	}
    $rows = array();
    if ($result = $connect->query($sql))
    {
        while($row = $result->fetch_assoc())
            array_push($rows, $row);
        $result -> free_result();
    }
    return $rows;
}

function build_table($rows, $column_array, $screen)
{
    $html = '';
    $html .= '<table class="table table-striped table-hover" style="margin-bottom:0px !important;">';
    $html .= '<tr>';
    foreach($column_array as $col)
    {
		$col = str_replace("first_name","First Name",$col);
		$col = str_replace("last_name","Last Name",$col);
		$col = str_replace("company","Company",$col);
		$col = str_replace("request","Request",$col);
		$col = str_replace("email","Email",$col);
		$col = str_replace("submit_time","Submitted",$col);
		$col = str_replace("completed","Completed",$col);
		$col = str_replace("acct_type","Account Type",$col);
		$col = str_replace("notes","Notes",$col);
		$col = str_replace("charges","Charges",$col);
		$col = str_replace("name","Name",$col);
		$col = str_replace("description","Description",$col);
		$col = str_replace("standard","Standard",$col);
		$col = str_replace("price_monthly","Price Monthly",$col);
		$col = str_replace("create_timestamp","Create Time-stamp",$col);
		$col = str_replace("customer_id","Customer",$col);
		$col = str_replace("issue","Issue",$col);
		$col = str_replace("timestamp","Time-stamp",$col);
		$col = str_replace("assigned_employee_id","Assigned Employee",$col);
		$col = str_replace("content","Content",$col);
		$col = str_replace("is_resolved","Resolved",$col);
		$col = str_replace("id","ID",$col);
		
        $html .= '<td style="color:#00bc8c;">'.$col.'</td>';
    }
    $html .= '<td style="color:#00bc8c;">Actions</td>';
    $html .= '</tr>';
    foreach($rows as $row)
    {
        $html .= '<tr>';
        foreach($column_array as $col)
        {
			if($col == 'assigned_employee_id')
			{
				$sql = "SELECT email FROM virtual_users WHERE id = ".intval($row[$col]);
				switch_db();
				$email = fetch($sql)[0]['email'];
				switch_db();
				$html .= '<td style="vertical-align: middle;overflow-wrap: break-word !important;white-space:normal;">'.$email.'</td>';
			}
			else if($col == "id")
				$html .= '<td style="vertical-align: middle;overflow-wrap: break-word !important;white-space:normal;">#'.intval($row[$col]).'</td>';
			else if($col == "timestamp" || $col == "create_timestamp")
				$html .= '<td style="vertical-align: middle;overflow-wrap: break-word !important;white-space:normal;">'.gmdate("Y-m-d\TH:i:s\Z", $row[$col]).'</td>';
			else if($col == 'is_resolved')
				$html .= '<td style="vertical-align: middle;overflow-wrap: break-word !important;white-space:normal;">'.str_replace(array("1","0"),array("Yes","No"),$row[$col]).'</td>';
			else
			/*
			margin:0px;padding-left:15px;display: inline-block !important;word-break: break-word !important;overflow-wrap: break-word !important;white-space:normal;margin-bottom:10px;color:black;
			*/
            $html .= '<td style="vertical-align: middle;overflow-wrap: break-word !important;white-space:normal;">'.str_replace(array("\r","\n"),array("","<BR>"),escape_html($row[$col])).'</td>';
        }
		if($screen=='customer_requests' && $row['completed'] == 'false')
		{
			$html .= '<td style="vertical-align: middle;overflow-wrap: break-word !important;white-space:normal;"><button  class="btn btn-default" onClick="javascript:location.replace(\'home.php?screen='.$screen.'&action=complete&id='.$row['id'].'\');"><i class="bi bi-check"></i> Complete</button></td>';
		}
		else
		{
			if($screen=='customer_requests')
			{
				$html .= '<td style="vertical-align: middle;overflow-wrap: break-word !important;white-space:normal;"><button  class="btn btn-default" onClick="javascript:location.replace(\'home.php?screen='.$screen.'&action=edit&id='.$row['id'].'\');"><i class="bi bi-pencil-square"></i> Edit</button> <button  class="btn btn-danger" onClick="javascript:location.replace(\'home.php?screen='.$screen.'&action=delete&id='.$row['id'].'\');"><i class="bi bi-exclamation-octagon"></i> Delete</button></td>';
			}
			else
			{
			$html .= '<td style="vertical-align: middle;overflow-wrap: break-word !important;white-space:normal;"><button  class="btn btn-default" onClick="javascript:location.replace(\'home.php?screen='.$screen.'&action=edit&id='.$row['id'].'\');"><i class="bi bi-pencil-square"></i> Edit</button> <button  class="btn btn-danger" onClick="javascript:location.replace(\'home.php?screen='.$screen.'&action=delete&id='.$row['id'].'\');"><i class="bi bi-exclamation-octagon"></i> Delete</button></td>';
			}
		}
        $html .= '</tr>';
    }
    $html .= '</table>';
    return $html;
}

function menuItem($screen, $label)
{
    $style = "";
    if(isset($_GET['screen']) && $_GET['screen'] == $screen)
        $style = "color:lime;";
    //print('<a href="home.php?screen='.$screen.'" style="'.$style.'">'.$label.'</a> | ');
    print(' <input type="button" style="'.$style.'" value="'.$label.'" onClick="javascript:location.replace(\'home.php?screen='.$screen.'\');">');
}

function menu($user_department)
{
    menuItem('customer_requests', 'Customer Requests');
    menuItem('customer_records', 'Customer Records');
    menuItem('charge_types', 'Charge Types');
    menuItem('acct_types', 'Account Types');
    if($user_department == 'SECURITY')
        menuItem('security', 'Security');
}

function table_editor($table, $action, $show_add = true, $completed = false)
{
    $screen = $table;

    $cols_editable = array(); // array(false, true, true, true, true);
    $cols = array(); // array('id', 'name', 'description', 'standard', 'price_monthly');

    $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".$GLOBALS['schema']."' AND TABLE_NAME = '".escape($table)."'";
    $t = fetch($sql);
    foreach($t as $row)
    {
        if($row['COLUMN_NAME'] == 'id')
        {
            array_push($cols_editable,false);
            array_push($cols,'id');
        }
        else
        {
            array_push($cols_editable,true);
            array_push($cols,$row['COLUMN_NAME']);
        }
    }

    if($action == 'edit')
    {
        ?>

            <?php
            $id = intval($_GET['id']);
            add($cols, $cols_editable, $screen, true, $id);
            ?>

        <?php
    }
    else
    {
        if($action == 'delete')
        {
            if(isset($_GET['id']) && (isset($_GET['screen']) && $screen == $_GET['screen']))
            {
                $id = intval($_GET['id']);
				$sql = "SELECT id FROM ".escape($screen)." WHERE id = ".$id;
				$t = fetch($sql);
				if(count($t)!=0)
				{
				
                $sql = "DELETE FROM ".escape($screen)." WHERE id = ".$id;
				if($screen != 'customer_requests')
				{
							?>
											<div class="panel-footer" style="background-color:black;"><i class="bi bi-trash"></i> Record Delete</div>
				<div class="panel-footer" style="background-color:black;">
				<?php
				}
				?>
				
			<div class="bs-component">
              <div class="alert alert-dismissable alert-info" style="margin:0px;padding-left:15px;display: inline-block !important;word-break: break-word !important;overflow-wrap: break-word !important;white-space:normal;margin-bottom:10px;color:black;">
                <strong>Executing SQL:</strong> <?php print($sql); ?>
              </div>
            </div>
			<?php
                execute($sql);
				if(get_mysql_error()=="")
				{
				?>

				<div class="bs-component">
				  <div class="alert alert-dismissable alert-warning" style="margin:0px;padding-left:15px;display: inline-block !important;word-break: break-word !important;overflow-wrap: break-word !important;white-space:normal;margin-bottom:10px;color:black;">
					<strong>Success!</strong> Record deleted.
				  </div>
				</div>
				
				
				<?php
				}
				if($screen != 'customer_requests')
				print('</div>');
				}
            }
        }
        if($show_add)
        {
        ?>
		<div class="panel-footer" style="background-color:black;"><i class="bi bi-journal-plus"></i> Add <?php print(str_replace(array("customer_records","charge_types","acct_types","notes"),array("Customer Record","Charge Type","Account Type","Notes"),$screen)); ?></div>
		<div class="panel-footer" style="background-color:black;">
		

            <?php
            add($cols, $cols_editable, $screen);
            ?>
</div>
        <?php
        }
		
		$sql = "SELECT * FROM ".escape($table)." ORDER BY id ASC";
		if($screen=='customer_requests')
		{
			$sql = "SELECT * FROM ".escape($table)." WHERE completed = 'false' ORDER BY id ASC";
			if($completed)
				$sql = "SELECT * FROM ".escape($table)." WHERE completed = 'true' ORDER BY id DESC";
		}
		else if($screen=='news')
		{
			$sql = "SELECT * FROM ".escape($table)." ORDER BY id DESC";
		}
		else if($screen=='support_tickets')
		{
			$sql = "SELECT id,create_timestamp,(SELECT REPLACE(CONCAT(company,' ',last_name,' ',first_name),'  ',' ') FROM uts_modern_v1.customer_records WHERE uts_modern_v1.customer_records.id = customer_id) AS customer_id, 'Open to read' as issue, assigned_employee_id, is_resolved FROM ".escape($table)." ORDER BY is_resolved ASC, id ASC";
			//print($sql);
		}
		else if($screen =='accounts'||$table=='virtual_users')
		{
			$sql = "SELECT id,create_time,domain_id,email,user_type,department,ip,master,new_user_authorized,authorized_domains, FROM virtual_users ORDER BY id DESC";
		}
		$rows = fetch($sql);
		if(count($rows)==0 && $screen=='customer_requests' && !$completed)
		{
			?>
			<div class="bs-component">
              <div class="alert alert-dismissable alert-info" style="margin:0px;background-color:#00bc8c;border-color:#00bc8c;color:black">
                
				<i class="bi bi-check"></i><strong>YAY!</strong> <?php if(rand(0,100) >= 98) { ?><img src="theme/icons.gif"> <?php } ?>There are no customer requests that need your attention <a href="home.php">Click here to refresh</a>
              </div>
            </div>
			<?php
		}
		else if(count($rows)==0 && $screen=='customer_requests' && $completed)
		{
			?>
			
                There are no processed customer requests.
              
			<?php
		}
		else if (count($rows)==0)
		{
			if($screen == 'charge_types')
			{
			?>
			<div class="panel-footer" style="background-color:black;"><i class="bi bi-credit-card-2-back"></i> Charge Types</div>
			<div class="panel-footer" style="background-color:black;">
			<?php
			}
			else if($screen == 'acct_types')
			{
			?>
			<div class="panel-footer" style="background-color:black;"><i class="bi bi-box"></i> Account Types</div>
			<div class="panel-footer" style="background-color:black;">
			<?php
			}
			else if($screen == 'news')
			{
			?>
			<div class="panel-footer" style="background-color:black;"><i class="bi bi-newspaper"></i> News Posts</div>
			<div class="panel-footer" style="background-color:black;">
			<?php
			}
			else if($screen == 'support_tickets')
			{
			?>
			<div class="panel-footer" style="background-color:black;"><i class="bi bi-ticket"></i> Support Tickets</div>
			<div class="panel-footer" style="background-color:black;">
			<?php
			}
			else if($screen != 'customer_requests')
			{
			?>
			<div class="panel-footer" style="background-color:black;"><i class="bi bi-people"></i> Customer Records</div>
			<div class="panel-footer" style="background-color:black;">
			<?php
			}

			print("No records to display");
			if($screen != 'customer_requests')
			{
			?>
			</div>
			<?php
			}
		}
		else
		{
			if($screen == 'charge_types')
			{
			?>
			<div class="panel-footer" style="background-color:black;"><i class="bi bi-credit-card-2-back"></i> Charge Types</div>
			<div class="panel-footer" style="background-color:black;">
			<?php
			}
			else if($screen == 'acct_types')
			{
			?>
			<div class="panel-footer" style="background-color:black;"><i class="bi bi-box"></i> Account Types</div>
			<div class="panel-footer" style="background-color:black;">
			<?php
			}
			else if($screen == 'news')
			{
			?>
			<div class="panel-footer" style="background-color:black;"><i class="bi bi-newspaper"></i> News Posts</div>
			<div class="panel-footer" style="background-color:black;">
			<?php
			}
			else if($screen == 'support_tickets')
			{
			?>
			<div class="panel-footer" style="background-color:black;"><i class="bi bi-ticket"></i> Support Tickets</div>
			<div class="panel-footer" style="background-color:black;">
			<?php
			}
			else if($screen != 'customer_requests')
			{
			?>
			<div class="panel-footer" style="background-color:black;"><i class="bi bi-people"></i> Customer Records</div>
			<div class="panel-footer" style="background-color:black;">
			<?php
			}
			

			echo build_table($rows, $cols, $screen);
			if($screen != 'customer_requests')
			{
				?>
			</div>
			<?php
			}
		}
    }
}

function add($cols, $cols_editable, $screen, $is_edit = false, $edit_id = -1)
{
	if(isset($_GET['screen']) && $is_edit && $screen != $_GET['screen'])
		$is_edit = false;
	
	$changes_saved = false;
    $sql = "select column_name,data_type,CHARACTER_MAXIMUM_LENGTH from information_schema.columns where table_schema = '".$GLOBALS['schema']."' and table_name = '".escape($screen)."';";
    $column_info = fetch($sql);
    $all_columns_obtained = true;
    //print_r($_POST);
    for($i = 0; $i < count($cols); $i += 1)
    {
        $col_editable = $cols_editable[$i];
        $col = $cols[$i];
        if($col_editable)
        {
            foreach($column_info as $info)
            {
                if($info['column_name'] == $col)
                {
                    //print('input_'.$screen.'_'.$col."<BR>");
                    if(!isset($_POST['input_'.$screen.'_'.$col]))
                    {
                        $all_columns_obtained = false;
                        //print("missing column ".'input_'.$screen.'_'.$col."<BR>");
                    }
                }
            }
        }
    }
    //print("columns obtained: ".$all_columns_obtained);
    if($all_columns_obtained)
    {
        if($is_edit)
        {
            $sql = "UPDATE ".escape($screen)." SET ";
            for($i = 0; $i < count($cols); $i += 1)
            {
                $col_editable = $cols_editable[$i];
                $col = $cols[$i];
                if($col_editable)
                {
					if($_POST['input_'.$screen.'_'.$col] == 'NULL')
						$sql .= $col." = NULL";
					else
						$sql .= $col." = '".escape($_POST['input_'.$screen.'_'.$col])."'";
                    if($i != count($cols) - 1)
                        $sql .= ',';
                }
            }
            $sql .= " WHERE id = ".intval($edit_id);
						?>
			<div class="panel-footer" style="background-color:black;"><i class="bi bi-save"></i> Save Changes</div>
				<div class="panel-footer" style="background-color:black;">
			<div class="bs-component">
              <div class="alert alert-dismissable alert-info" style="margin:0px;padding-left:15px;display: inline-block !important;word-break: break-word !important;overflow-wrap: break-word !important;white-space:normal;margin-bottom:10px;color:black;">
                <strong>Executing SQL:</strong> <?php print($sql); ?>
              </div>
            </div>
			<?php
            //print("executing sql ... ".$sql."<BR>");
            execute($sql);
			if(get_mysql_error()=="")
			{
						?>
			<div class="bs-component">
              <div class="alert alert-dismissable alert-info" style="margin:0px;background-color:#00bc8c;border-color:#00bc8c;padding-left:15px;display: inline-block !important;word-break: break-word !important;overflow-wrap: break-word !important;white-space:normal;margin-bottom:10px;color:black;">
                <strong>Success!</strong> Changes saved.
              </div>
            </div>
			<?php
			$changes_saved = true;
			}
			
        }
        else  if ($_SERVER['REQUEST_METHOD'] == 'POST')// this should also check if is add post
        {
            $sql = "INSERT INTO ".escape($screen)." (";
            for($i = 0; $i < count($cols); $i += 1)
            {
                $col_editable = $cols_editable[$i];
                $col = $cols[$i];
                if($col_editable)
                {
                    $sql .= $col;
                    if($i != count($cols) - 1)
                        $sql .= ',';
                }
            }
            $sql .= ") VALUES (";
            for($i = 0; $i < count($cols); $i += 1)
            {
                $col_editable = $cols_editable[$i];
                $col = $cols[$i];
                if($col_editable)
                {
					if($_POST['input_'.$screen.'_'.$col] == 'NULL')
						$sql .= "NULL";
					else
						$sql .= "'".escape($_POST['input_'.$screen.'_'.$col])."'";
                    if($i != count($cols) - 1)
                        $sql .= ',';
                }
            }
            $sql .= ");";
					?>
			<div class="bs-component">
              <div class="alert alert-dismissable alert-info" style="margin:0px;padding-left:15px;display: inline-block !important;word-break: break-word !important;overflow-wrap: break-word !important;white-space:normal;margin-bottom:10px;color:black;">
                <strong>Executing SQL:</strong> <?php print($sql); ?>
              </div>
            </div>
			<?php
            //print("executing sql ... ".$sql."<BR>");
            execute($sql);
			if(get_mysql_error()=="")
			{
						?>
			<div class="bs-component">
              <div class="alert alert-dismissable alert-info" style="margin:0px;background-color:#00bc8c;border-color:#00bc8c;padding-left:15px;display: inline-block !important;word-break: break-word !important;overflow-wrap: break-word !important;white-space:normal;margin-bottom:10px;color:black;">
                <strong>Success!</strong> Changes saved.
              </div>
            </div>
			<?php
			$changes_saved = true;
			}
        }
    }
    $default_values = array();
    if($is_edit)
    {
        $sql = "SELECT * FROM ".escape($screen)." WHERE id = ".intval($edit_id);
        $default_values = fetch($sql)[0];
    }
    if(!$changes_saved)
	{
		?>
	
    <div id="<?php print($screen); ?>_add"> <!-- style="display:none"> -->
        <form id = "frm_<?php print($screen); ?>_add" method="post" action="home.php?screen=<?php print($screen); ?><?php if($is_edit) print('&action=edit&id='.intval($_GET['id'])); ?>">
        <?php

        $sql = "SELECT TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA = '".$GLOBALS['schema']."' AND TABLE_NAME = '".escape($screen)."'";
        $foreign_columns = fetch($sql);
		$html = '';
		if(isset($_GET['action']) && $_GET['action']!='delete' && isset($_GET['screen']) && $_GET['screen'] != 'customer_requests')
		{
        $html .= '<div class="panel-footer" style="background-color:black;"><i class="bi bi-pen"></i> Edit Record</div>
				<div class="panel-footer" style="background-color:black;">';
		}
        $html .= '<table>';
        //if($info['column_name'] == $col)
        for($i = 0; $i < count($cols); $i += 1)
        {
            $col_editable = $cols_editable[$i];
            $col = $cols[$i];
            if($col_editable)
            {
                foreach($column_info as $info)
                {
                    if($info['column_name'] == $col)
                    {
                        $foreign_restraint = false;
                        $foreign_table = '';
                        $foreign_column = '';
                        foreach($foreign_columns as $fcol)
                        {
                            if($fcol['COLUMN_NAME'] == $col)
                            {
                                $foreign_restraint = true;
                                $foreign_table = $fcol['REFERENCED_TABLE_NAME'];
                                $foreign_column = $fcol['REFERENCED_COLUMN_NAME'];
                            }
                        }
                        $default_value = "";
                        if($is_edit)
                        {
                            $default_value = $default_values[$col];
                        }
						/*
						<div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Email</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                  </div>
						*/
                        $html .= '<tr>';
                        $html .= '<td style="width:150px;text-align:right;padding-right:10px">';
						
						$col_name = $col;
						$col_name = str_replace("first_name","First Name",$col_name);
						$col_name = str_replace("last_name","Last Name",$col_name);
						$col_name = str_replace("company","Company",$col_name);
						$col_name = str_replace("acct_type","Account Type",$col_name);
						$col_name = str_replace("notes","Notes",$col_name);
						$col_name = str_replace("charges","Charges",$col_name);
						$col_name = str_replace("name","Name",$col_name);
						$col_name = str_replace("description","Description",$col_name);
						$col_name = str_replace("standard","Standard",$col_name);
						$col_name = str_replace("price_monthly","Price Monthly",$col_name);
						$col_name = str_replace("create_timestamp","Created Time-stamp",$col_name);
						$col_name = str_replace("timestamp","Time-stamp",$col_name);
						$col_name = str_replace("content","Content",$col_name);
						$col_name = str_replace("customer_id","Customer",$col_name);
						$col_name = str_replace("assigned_employee_id","Assigned Employee",$col_name);
						$col_name = str_replace("is_resolved","Resolved",$col_name);
						$col_name = str_replace("issue","Issue",$col_name);
						
                        $html .= $col_name;//.'<BR><span style="font-size:8px">'.$info['data_type'].' ('.$info['CHARACTER_MAXIMUM_LENGTH'].')</span>';
                        $html .= '</td>';
                        if($foreign_restraint)
                        {
                            $html .= '<td valign="middle">';
                            if($info['data_type'] == 'varchar' && $info['CHARACTER_MAXIMUM_LENGTH'] <= 50)
                            {
                                $html .= '<select style="margin-bottom:5px" class="form-control" name="input_'.$screen.'_'.$col.'">';
                                $sql = "SELECT ".escape($foreign_column).", name FROM ".escape($foreign_table);
                                $rows = fetch($sql);
                                foreach($rows as $row)
                                    $html .= '<option value="'.escape_html($row[$foreign_column]).'" '.($row[$foreign_column] == $default_value ? 'selected' : '').'>'.escape_html($row['name']).'</option>';
                                $html .= '</select>';
                            }
                            else if($info['data_type'] == 'int')
                            {
                                $html .= '<select style="margin-bottom:5px" class="form-control" name="input_'.$screen.'_'.$col.'">';
                                $sql = "SELECT ".escape($foreign_column).", name FROM ".escape($foreign_table);
								print($sql);
                                $rows = fetch($sql);
                                foreach($rows as $row)
                                    $html .= '<option value="'.escape_html($row[$foreign_column]).'" '.($row[$foreign_column] == $default_value ? 'selected' : '').'>'.escape_html($row['name']).'</option>';
                                $html .= '</select>';
                            }
							else{
								$html.='unknown foreign restraint data type';
							}
                            $html .= '</td>';
                        }
                        else
                        {
                            $html .= '<td valign="middle">';
							//$html.=json_encode($info);  
							if($col == 'assigned_employee_id')
							{
								switch_db();
								$sql = "SELECT id, email FROM virtual_users WHERE user_type = 'employee' ORDER BY email";
								$html .= $sql;
                                $html .= '<select style="margin-bottom:5px" class="form-control" name="input_'.$screen.'_'.$col.'">';
                                
								$html .= '<option value="NULL">None Selected</option>';
                                $rows = fetch($sql);
								print_r($rows);
                                foreach($rows as $row)
                                    $html .= '<option value="'.escape_html($row['id']).'" '.($row['id'] == $default_value ? 'selected' : '').'>'.escape_html($row['email']).'</option>';
                                $html .= '</select>';
								switch_db();
							}
							else if($col == 'customer_id')
							{
                                $html .= '<select style="margin-bottom:5px" class="form-control" name="input_'.$screen.'_'.$col.'">';
                                $sql = "SELECT id, company, last_name, first_name FROM uts_modern_v1.customer_records ORDER BY company ASC, last_name ASC, first_name ASC";
								$html .= '<option value="NULL">None Selected</option>';
                                $rows = fetch($sql);
                                foreach($rows as $row)
                                    $html .= '<option value="'.escape_html($row['id']).'" '.($row['id'] == $default_value ? 'selected' : '').'>'.escape_html($row['company'].' '.$row['last_name'].' '.$row['first_name']).'</option>';
                                $html .= '</select>';
							}
                            else if($info['data_type'] == 'datetime')
                            {
                                $html .= escape_html($default_value).'<input class="form-control" type="hidden" name="input_'.$screen.'_'.$col.'" value="'.escape_html($default_value).'">';
                                //$html .= '<br>';
                            }
                            else if($info['data_type'] == 'tinyint' && $info['CHARACTER_MAXIMUM_LENGTH'] == null)
                            {
                                $html .= '<input class="form-control" style="width:24px;display:inline" type="checkbox" name="input_'.$screen.'_'.$col.'" value="1" '.($default_value == 1 ? 'checked' : '').'>';
                                //$html .= json_encode($info).$default_value.'<br>';
                            }
                            else if(($info['data_type'] == 'varchar' || $info['data_type'] == 'float') && $info['CHARACTER_MAXIMUM_LENGTH'] <= 50)
                                $html .= '<input style="margin-bottom:5px" class="form-control" name="input_'.$screen.'_'.$col.'" type="textbox" size="'.$info['CHARACTER_MAXIMUM_LENGTH'].'" value="'.escape_html($default_value).'">';
                            else if($info['data_type'] == 'varchar' && $info['CHARACTER_MAXIMUM_LENGTH'] > 50)
                                $html .= '<textarea style="margin-bottom:5px" class="form-control" name="input_'.$screen.'_'.$col.'" cols="50" rows="5" size="'. $info['CHARACTER_MAXIMUM_LENGTH'] .'">'.escape_html($default_value).'</textarea>';
                            else if($info['data_type'] == 'text')
                                $html .= '<textarea style="margin-bottom:5px" class="form-control" name="input_'.$screen.'_'.$col.'" cols="50" rows="10" size="'. $info['CHARACTER_MAXIMUM_LENGTH'] .'">'.escape_html($default_value).'</textarea>';
							else if($info['data_type'] == 'int')
								$html .= '<input style="margin-bottom:5px" class="form-control" name="input_'.$screen.'_'.$col.'" type="textbox" size="'.$info['CHARACTER_MAXIMUM_LENGTH'].'" value="'.escape_html($default_value).'">';
							else if($info['data_type'] == 'bigint'  && ($col_name == 'Time-stamp' || $col == 'create_timestamp'))
								 $html .= gmdate("Y-m-d\TH:i:s\Z", time()).'<input type="hidden" name="input_'.$screen.'_'.$col.'" value="'.time().'">';
                            else
                                $html .= 'Unknown data type '.$info['data_type'];
                            $html .= $info['data_type'].'</td>';
                        }
                        $html .= '</tr>';
                    }
                }
            }
        }
		$html .= '<tr><td></td><td>';
		if(isset($_GET['action']) && $_GET['action'] == 'edit')
		{
		//if((isset($_GET['action']) && $_GET['action'] != 'add') || !isset($_GET['action']))
		$html .= '<button onclick="location.replace(\'home.php\');return false;" class="btn btn-default" style="margin-top:10px"><i class="bi bi-arrow-left"></i> Cancel</button> ';
		}
		$html .= '<button class="btn btn-default" style="margin-top:10px"><i class="bi bi-check"></i> Save</button></td></tr>';
        $html .= '</table>';
		if(isset($_GET['action']) && $_GET['action']!='delete' && isset($_GET['screen']) && $_GET['screen'] != 'customer_requests')
		{
			$html.='</div>';
		}
        print($html);
        ?>
    </form>
    </div>
    <?php
	}
	else
	{
		?><button  class="btn btn-default" onClick="javascript:location.replace('home.php?screen=customer_requests');"><i class="bi bi-arrow-left"></i> Back</button><?php
		if($all_columns_obtained)
		{
			if($is_edit)
				print('</div>');
		}
	}
}
?>
