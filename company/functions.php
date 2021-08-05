<?php

$web_settings = parse_ini_file("../web_settings.ini.php");

//Connection info for the database
$servername = $web_settings['ip'];
$username = $web_settings['username'];
$password = $web_settings['password'];
$database = $web_settings['database'];
$port = $web_settings['port'];
$connect = new mysqli($servername, $username, $password, $database, $port);

/*
Completed | ID | Name | Company | EMail | Request body or link to request body | Submit time

<table>
    <th>Completed</th> <th>ID</th> <th>Name</th> <th>Company</th> <th>EMail</th> <th>Message</th> <th>Submit Time</th>
</table>

build_table($ASSOC_ARR, $header_arr);

*/

function build_table($header_arr, $column_array, $result, $column_overide = null)
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
