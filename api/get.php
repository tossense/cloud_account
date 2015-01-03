<?PHP
// HTTP GET
// Read only methods
// Response Json(JsonP if there is jsoncallback param)

require_once('../private/lib/ca_db.php');

//Now we check if the function exists
if(function_exists($_GET['method'])){
    $ret = json_encode($_GET['method']());
    if($_GET['jsoncallback'])
        $ret = $_GET['jsoncallback'].'('.$ret.')';
    echo $ret;
}
else{
    http_response_code(400);
    echo 'Wrong Method.';
}

//Here is the function to get
function userBalance(){
    $ret = array();
    $ret["status"] = "OK";
    if(!$_GET['group'])
    {
        $ret["status"] = "ERROR";
        $ret["info"] = "No Group Param";
        return $ret;
    }
    $link = connectCaDb($ret);
    if($link->connect_errno)
    {
        return $ret;
    }

    $group = $_GET['group'];
    $groupId = getGroupId($group, $link)[$group];
    $sql = "SELECT tbUsers.name, tbGroupMembers.balance FROM tbUsers, tbGroupMembers WHERE tbGroupMembers.groupId=$groupId AND tbUsers.id=tbGroupMembers.userId";
    $res = queryAndLogError($link, $sql, $ret);
    if($res)
    {
        $ret["result"] = array();
        while($row = $res->fetch_assoc())
        {
            $ret["result"][$row["name"]] = $row["balance"];
        }
    }
    $link->close();
    return $ret;
}

?>
