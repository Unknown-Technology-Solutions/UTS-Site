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
$GLOBALS['auth'] = false;
$GLOBALS['return_array'] = [null];


if (isset($_POST['submit'])) {
    $connect_r = mail_db();

    if ($connect_r->connect_error) {
        die(jsonErrorOut(ErrorCode::InternErr));
    }

    $username = $connect_r->real_escape_string($_POST['username']);
    $password = $connect_r->real_escape_string($_POST['password']);

    $username_in_db =  "SELECT email,password FROM virtual_users WHERE email='" . $username . "';";
    $un_q = $connect_r->query($username_in_db);

    if (mysqli_num_rows($un_q) == 0) {
        //FAILED
        print(jsonErrorOut(ErrorCode::AuthNeeded));
    } else {
        $pass = $un_q->fetch_assoc();
        if (handlePassword($password, "verify", $pass['password'])) {
            //PASSED
            //print(jsonErrorOut(ErrorCode::Success));
            $GLOBALS['auth'] = true;
        } else {
            //FAILED
            print(jsonErrorOut(ErrorCode::AuthNeeded));
        }
    }
} else {
    print(jsonErrorOut(ErrorCode::NotAllowed));
}
//$mbox = imap_open("{localhost:993/imap/ssl}INBOX", "user_id", "password");

if (isset($_POST['submit']) && $GLOBALS['auth']) {
    //TODO: Mailbox handling
    $mailbox = new PhpImap\Mailbox(
        '{imap.unknownts.com:993/ssl/novalidate-cert/imap}INBOX',
        $username,
        $password,
        false // dont save attachments
    );
    $GLOBALS['return_array'] = ["mailbox_access" => true];

    // Read mail in mailbox since before mailserver existed to ensure all mail is seen
    $mailsIds = $mailbox->searchMailbox('SINCE "20170101"');
    if (!$mailsIds) {
        //TODO: Handle empty mailbox
    }

    $tmp_arr = ["inbox_count" => count($mailsIds)];
    $GLOBALS['return_array'] = array_merge($GLOBALS['return_array'], $tmp_arr);

    // Show total number of emails
    //echo 'n= ' . count($mailsIds) . '<br>';

    print(jsonErrorOut(ErrorCode::Success));
}



//die(json_encode(array("error" => false, "message" => "bleh")));
