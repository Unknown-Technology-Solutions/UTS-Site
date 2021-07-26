<?php

header("Content-Type: application/json");


$mbox = imap_open("{localhost:993/imap/ssl}INBOX", "user_id", "password");


die(json_encode(array("error" => false, "message" => "bleh")));