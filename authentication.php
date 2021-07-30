<?php

//Include the anchor and import files based on location
include_once('./anchor.php');
if ($anchor == "root") {
    include_once('./errors.php');
    include_once('./functions.php');
} else {
    include_once('../errors.php');
    include_once('./functions.php');
}


function authenticateToMaster($conn, $username, $password)
{
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);


    $req = "SELECT email,password,new_user_authorized,authorized_domains FROM virtual_users WHERE email='" . $username . "';";
    $res = $conn->query($req);
    $asc = $res->fetch_assoc();

    if (mysqli_num_rows($res) == 0) {
        return array ('authenticated' => false, 'ErrorCode' => ErrorCode::InvalidReq, "new_user_authorized" => null, "authorized_domains" => null);
    } else {
        if (handlePassword($password, "verify", $asc['password'])) {
            return array ('authenticated' => true, 'ErrorCode' => ErrorCode::Success, "new_user_authorized" => $asc['new_user_authorized'], "authorized_domains" => $asc['authorized_domains']);
        } else {
            return array ('authenticated' => false, 'ErrorCode' => ErrorCode::InvalidReq, "new_user_authorized" => null, "authorized_domains" => null);
        }
    }
}
