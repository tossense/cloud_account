<?php
require_once('../private/lib/group_.php');

$postData = file_get_contents("php://input");
if($postData)
{
    $postJson = json_decode($postData, true);
    $ret = array();
    if($postJson["action"] == "addGroup")
    {
        $ret = addGroups($postJson);
    }

    echo(json_encode($ret));
}
else
{
    http_response_code(400);
}

