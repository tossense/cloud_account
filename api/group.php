<?php
require_once('../private/lib/ca_db.php');
require_once('../private/lib/ca_encrypt.php');

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

function addGroups($postJson)
{
    $u = $postJson['user'];
    $g = $postJson['group'];
    $gs = array();
    if(is_array($postJson['groups']))
        $gs = $postJson['groups'];
    if($g)
        $gs[] = $g;
    $gs = array_unique($gs);
    $ret = array();
    $ret["status"] = "OK";

    $link = connectCaDb($ret);
    if($link->connect_errno)
    {
        return $ret;
    }
    $userNameId = getUserId($u, $link);
    $userId = $userNameId[$u];
    if(!$userId)
    {
        return retError($ret, "Invalid User");
    }

    if( count($gs) > 0 )
    {
        $groupNameId = getGroupId($gs, $link);
        $sql = "";
        foreach($groupNameId as $groupName => $groupId)
        {
            $sqlArray = array("groupId" => $groupId, "userId" => $userId);
            $sql .= makeInsertSql("tbGroupMembers", $sqlArray);
        }
        if(!multiQueryAndLogError($link, $sql, $ret))
        {
            return $ret;
        }
        else{
            while ($link->next_result()) {;} // flush multi_queries
        }
    }

    $link->close();
    return $ret;
}

function retError($ret, $info)
{
    $ret["status"] = "ERROR";
    $ret["info"] = $info;
    return $ret;
}
