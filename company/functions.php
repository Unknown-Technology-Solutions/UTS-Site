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
/*
Completed | ID | Name | Company | EMail | Request body or link to request body | Submit time

<table>
    <th>Completed</th> <th>ID</th> <th>Name</th> <th>Company</th> <th>EMail</th> <th>Message</th> <th>Submit Time</th>
</table>

build_table($ASSOC_ARR, $header_arr);

*/

function build_table_customer_records($header_arr, $column_array, $result, $column_overide = null)
{
    $html = '<table>'; // table start

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
                    $html .= "<form action='./home.php?completed=&id=" . $row['id'] . "' method='POST'><button name='complete_task' type='submit'>Complete</button></form>";
                } else {
                    $html .= $row[$name];
                }
                $html .= '</td>';
            }
            $html .= '</tr>';
        }
    }

    return $html;
}

function escape($data)
{
    $connect = $GLOBALS['connect'];
    return $connect->real_escape_string($data);
}

function execute($sql)
{
    $connect = $GLOBALS['connect'];
    $connect->query($sql);
    print('<span style="color:red;">'.$connect->error.'</span>');
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

function build_table($rows, $column_array, $screen)
{
    $html = '';
    $html .= '<table>';
    $html .= '<tr>';
    foreach($column_array as $col)
    {
        $html .= '<td style="color:green;">'.$col.'</td>';
    }
    $html .= '<td style="color:purple;">Actions</td>';
    $html .= '</tr>';
    foreach($rows as $row)
    {
        $html .= '<tr>';
        foreach($column_array as $col)
        {
            $html .= '<td>'.$row[$col].'</td>';
        }
        $html .= '<td><button onClick="javascript:location.replace(\'home.php?screen='.$screen.'&action=edit&id='.$row['id'].'\');">Edit</button> <button onClick="javascript:location.replace(\'home.php?screen='.$screen.'&action=delete&id='.$row['id'].'\');">Delete</button></td>';
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

function table_editor($table, $action, $show_add = true)
{
    $screen = $table;

    $cols_editable = array(); // array(false, true, true, true, true);
    $cols = array(); // array('id', 'name', 'description', 'standard', 'price_monthly');


    $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'uts_modern_v1' AND TABLE_NAME = '".escape($table)."'";
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
        <fieldset>
            <legend>Edit</legend>
            <?php
            $id = intval($_GET['id']);
            add($cols, $cols_editable, $screen, true, $id);
            ?>
        </fieldset>
        <?php
    }
    else
    {
        if($action == 'delete')
        {
            if(isset($_GET['id']))
            {
                $id = intval($_GET['id']);
                $sql = "DELETE FROM ".escape($screen)." WHERE id = ".$id;
                execute($sql);
            }
        }
        if($show_add)
        {
        ?>
        <fieldset>
            <legend>Add</legend>
            <?php
            add($cols, $cols_editable, $screen);
            ?>
        </fieldset>
        <?php
        }
        ?>
        <fieldset>
            <legend>Manage</legend>
            <?php
            $sql = "SELECT * FROM ".escape($table)." ORDER BY id ASC";
            $rows = fetch($sql);
            echo build_table($rows, $cols, $screen);
            ?>
        </fieldset>
        <?php
    }
}

function add($cols, $cols_editable, $screen, $is_edit = false, $edit_id = -1)
{
    $sql = "select column_name,data_type,CHARACTER_MAXIMUM_LENGTH  from information_schema.columns where table_schema = 'uts_modern_v1' and table_name = '".escape($screen)."';";
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
                    $sql .= $col." = '".escape($_POST['input_'.$screen.'_'.$col])."'";
                    if($i != count($cols) - 1)
                        $sql .= ',';
                }
            }
            $sql .= " WHERE id = ".intval($edit_id);
            print("executing sql ... ".$sql."<BR>");
            execute($sql);
        }
        else
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
                    $sql .= "'".escape($_POST['input_'.$screen.'_'.$col])."'";
                    if($i != count($cols) - 1)
                        $sql .= ',';
                }
            }
            $sql .= ");";
            print("executing sql ... ".$sql."<BR>");
            execute($sql);
        }
    }
    $default_values = array();
    if($is_edit)
    {
        $sql = "SELECT * FROM ".escape($screen)." WHERE id = ".intval($edit_id);
        $default_values = fetch($sql)[0];
    }
    ?>
    <div id="<?php print($screen); ?>_add"> <!-- style="display:none"> -->
        <form id = "frm_<?php print($screen); ?>_add" method="post" action="home.php?screen=<?php print($screen); ?><?php if($is_edit) print('&action=edit&id='.intval($_GET['id'])); ?>">
        <?php

        $sql = "SELECT TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA = 'uts_modern_v1' AND TABLE_NAME = '".escape($screen)."'";
        $foreign_columns = fetch($sql);

        $html = '';
        $html .= '<table>';
        if($info['column_name'] == $col)
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
                        $html .= '<tr>';
                        $html .= '<td>';
                        $html .= $col.'<BR><span style="font-size:8px">'.$info['data_type'].' ('.$info['CHARACTER_MAXIMUM_LENGTH'].')</span>';
                        $html .= '</td>';
                        if($foreign_restraint)
                        {
                            $html .= '<td>';
                            if($info['data_type'] == 'varchar' && $info['CHARACTER_MAXIMUM_LENGTH'] <= 50)
                            {
                                $html .= '<select name="input_'.$screen.'_'.$col.'">';
                                $sql = "SELECT ".escape($foreign_column).", name FROM ".escape($foreign_table);
                                $rows = fetch($sql);
                                foreach($rows as $row)
                                    $html .= '<option value="'.$row[$foreign_column].'" '.($row[$foreign_column] == $default_value ? 'selected' : '').'>'.$row['name'].'</option>';
                                $html .= '</select>';
                            }
                            $html .= '</td>';
                        }
                        else
                        {
                            $html .= '<td>';
                            if(($info['data_type'] == 'varchar' || $info['data_type'] == 'float') && $info['CHARACTER_MAXIMUM_LENGTH'] <= 50)
                                $html .= '<input name="input_'.$screen.'_'.$col.'" type="textbox" size="'.$info['CHARACTER_MAXIMUM_LENGTH'].'" value="'.$default_value.'"><br>';
                            else if($info['data_type'] == 'varchar' && $info['CHARACTER_MAXIMUM_LENGTH'] > 50)
                                $html .= '<textarea name="input_'.$screen.'_'.$col.'" cols="50" rows="10" size="'. $info['CHARACTER_MAXIMUM_LENGTH'] .'">'.$default_value.'</textarea><br>';
                            else
                                $html .= 'unknown data type';
                            $html .= '</td>';
                        }
                        $html .= '</tr>';
                    }
                }
            }
        }
        $html .= '</table>';
        print($html);
        ?>
        <input type="submit" value="Save">
    </form>
    </div>
    <?php
}
?>
