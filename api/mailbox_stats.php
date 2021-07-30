<?php
/*
* POST Headers:
* submit   = (anything)
* username = (mailbox username)
* password = (mailbox password)
* request  = (request type)
*/

include_once('./functions.php');
include_once('../authentication.php');


// Set the content type so we dont use html
header("Content-Type: application/json");
$GLOBALS['auth'] = false;
$GLOBALS['return_array'] = ['mailbox_access' => false];
$GLOBALS['input_array_read'] = false;
$GLOBALS['requested_data'] = [null];

if (isset($_POST['submit'])) {
    $connect_r = mail_db();

    if ($connect_r->connect_error) {
        die(jsonErrorOut(ErrorCode::InternErr));
    }

    //$username = $connect_r->real_escape_string($_POST['username']);
    $username = $_POST['username'];
    //$password = $connect_r->real_escape_string($_POST['password']);
    $password = $_POST['password'];

    $AuthReturnArr = authenticateToMaster($connect_r, $username, $password);

    if ($AuthReturnArr['authenticated'] == false) {
        //FAILED
        print(jsonErrorOut(ErrorCode::AuthNeeded));
    } else {
        //PASSED
        $GLOBALS['auth'] = true;
    }
} else {
    die(jsonErrorOut(ErrorCode::NotAllowed));
}
//$mbox = imap_open("{localhost:993/imap/ssl}INBOX", "user_id", "password");


if (!$GLOBALS['input_array_read'] && isset($_POST['request'])) {
    $request_data = json_decode($_POST['request'], true);
    if (isset($request_data['mailbox'])) {
        $mailbox_folder = $request_data['mailbox'];
        array_push($GLOBALS['requested_data'], "mailbox");
    }
    $GLOBALS['input_array_read'] = true;
    if ($GLOBALS['requested_data'] == [null]) {
        die(jsonErrorOut(ErrorCode::IncmpltErr));
    }
} else {
    die(jsonErrorOut(ErrorCode::InvalidReq));
}


if (isset($_POST['submit']) && $GLOBALS['auth'] && $GLOBALS['input_array_read']) {
    //TODO: Mailbox handling
    $mailbox = new PhpImap\Mailbox(
        '{imap.unknownts.com:993/ssl/novalidate-cert/imap}' . $mailbox_folder,
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
} else {
    die(jsonErrorOut(ErrorCode::InvalidReq));
}



//die(json_encode(array("error" => false, "message" => "bleh")));
