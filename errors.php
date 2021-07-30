<?php
abstract class ErrorCode
{
    const Success    = 200;
    const InvalidReq = 400;
    const AuthNeeded = 401;
    const NotAllowed = 403;
    const IncmpltErr = 497;
    const DebugErr   = 498;
    const InternErr  = 499;
}

function ec2text($ErrorCode)
{
    $ect = [
        200 => "Successful",
        400 => "Invalid attempt",
        401 => "Authentication required",
        403 => "Request not allowed",
        497 => "Incomplete request data",
        498 => "Debugging trigger",
        499 => "Server side error"
    ];
    return $ect[$ErrorCode];
}

function jsonErrorOut($ec)
{
    $ErrorText = ec2text($ec);
    $AssembleDict = ['ErrorCode' => $ec, 'ErrorMessage' => $ErrorText, 'return' => $GLOBALS['return_array']];
    return json_encode($AssembleDict);
}
