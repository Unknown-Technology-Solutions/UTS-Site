<?php
/*
* POST Headers:
* submit   = (anything)
* username = (mailbox username)
* password = (mailbox password)
* request  = (request type)
*/

include_once('functions.php');

// Set the content type so we dont use html
header("Content-Type: application/json");



if (isset($_POST['submit'])) {
    $connect_r = mail_db();
    $username = $connect_r->real_escape_string($_POST['username']);
    $password = $connect_r->real_escape_string($_POST['password']);

    $username_in_db =  "SELECT username FROM virtual_users WHERE username='" . $username . "';";
    $un_q = $connect_r->query($username_in_db);
    if (mysqli_num_rows($un_q) == 0) {
        //TODO: FAILED
        print(jsonErrorOut(ErrorCode::AuthNeeded));
    } else {
        $password_in_db =  "SELECT username,password FROM virtual_users WHERE username='" . $username . "' AND password='" . $password . "';";
        $pw_q = $connect_r->query($username_in_db);
        if (mysqli_num_rows($pw_q) == 0) {
            //TODO: FAILED
            print(jsonErrorOut(ErrorCode::AuthNeeded));
        } else {
            $pass = $pw_q->fetch_assoc();
            if (handlePassword($password, "verify", $pass['password'])) {
                //TODO: PASSED
                print(jsonErrorOut(ErrorCode::Success));
            }
        }
    }
} else {
    print(jsonErrorOut(ErrorCode::NotAllowed));
}
//$mbox = imap_open("{localhost:993/imap/ssl}INBOX", "user_id", "password");




//die(json_encode(array("error" => false, "message" => "bleh")));
