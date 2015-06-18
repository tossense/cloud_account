<?PHP
// HTTP GET
// Read only methods
// Response Json(JsonP if there is jsoncallback param)

require_once('../private/lib/ca_db.php');

//Now we check if the function exists
if(function_exists($_GET['method'])){
    $ret = json_encode($_GET['method']());
    if( isset($_GET['jsoncallback']) )
        $ret = $_GET['jsoncallback'].'('.$ret.')';
    echo $ret;
}
else{
    http_response_code(400);
    echo 'Wrong Method.';
}

/**
 * @return array
 */
function userBalance()
{
    $ret = array();
    $ret["status"] = "OK";
    if(!isset($_GET['group']))
    {
        return retError($ret, "No Group Param");
    }
    $group = $_GET['group'];
    if(strlen($group) > 50)
    {
        return retError($ret, "Group name too long");
    }
    $link = connectCaDb($ret);
    if($link->connect_errno)
    {
        return $ret;
    }
    $group = $link->real_escape_string($group);
    $sql = <<<EOD
        SELECT tbUsers.name, tbUsers.nickname, tbGroupMembers.balance
        FROM tbUsers, tbGroupMembers, tbGroups
        WHERE tbGroupMembers.groupId=tbGroups.id
            AND tbGroups.name='$group'
            AND tbUsers.id=tbGroupMembers.userId
EOD;
    $user = null;
    if( isset($_GET['user']) )
        $user = $_GET['user'];
    if(strlen($user) > 30)
    {
        return retError($ret, "User name too long");
    }
    $user = $link->real_escape_string($user);
    if($user)
        $sql .= " AND tbUsers.name='$user'";
    $res = queryAndLogError($link, $sql, $ret);
    if($res)
    {
        $ret["result"] = array();
        $ret["nicknames"] = array();
        while($row = $res->fetch_assoc())
        {
            $ret["result"][$row["name"]] = $row["balance"];
            $ret["nicknames"][$row["name"]] = $row["nickname"];
        }
    }
    $link->close();
    return $ret;
}

function groupsList()
{
    $ret = array();
    $ret["status"] = "OK";
    $link = connectCaDb($ret);
    if($link->connect_errno)
    {
        return $ret;
    }
    $sql = "SELECT name, description FROM tbGroups";
    $res = queryAndLogError($link, $sql, $ret);
    if($res)
    {
        $ret["result"] = array();
        while($row = $res->fetch_assoc())
        {
            $ret["result"][$row["name"]] = $row["description"];
        }
    }
    $link->close();
    return $ret;
}

function groupList()
{
    return groupsList();
}

function eventsList()
{
    $ret = array();
    $ret["status"] = "OK";
    $link = connectCaDb($ret);
    if($link->connect_errno)
    {
        return $ret;
    }
    if(!isset($_GET['group']))
    {
        return retError($ret, "No Group Param");
    }
    if(!isset($_GET['last']))
    {
        return retError($ret, "No Last Param");
    }
    $group = $_GET['group'];
    $last = $_GET['last'];
    if($last > 100000 || $last<=0 )
        $last = 3;
    if(strlen($group) > 50)
    {
        return retError($ret, "Group name too long");
    }
    $group = $link->real_escape_string($group);
    $sql = "SELECT id, UNIX_TIMESTAMP(time) AS time, place, comment FROM tbEvents ORDER BY time DESC, id DESC LIMIT 3";
    $sql = "SELECT tbEvents.id, tbRecords.userId, tbRecords.money FROM tbRecords LEFT JOIN tbEvents ON tbEvents.id=tbRecords.eventId";
    $sql = <<<EOD
    SELECT UNIX_TIMESTAMP(tbEvents.time) AS time, tbEvents.place, tbEvents.comment, GROUP_CONCAT(tbUsers.name,":", tbRecords.money) AS records
    FROM tbUsers, tbEvents, tbRecords, tbGroups
    WHERE tbEvents.groupId=tbGroups.id
        AND tbGroups.name='$group'
        AND tbUsers.id=tbRecords.userId
        AND tbEvents.id=tbRecords.eventId
    GROUP BY tbEvents.id ORDER BY tbEvents.id DESC LIMIT $last
EOD;
    $res = queryAndLogError($link, $sql, $ret);
    if($res)
    {
        $ret["result"] = array();
        while($row = $res->fetch_assoc())
        {
            //$ret["result"][]["time"] = $row["time"];
            //$ret["result"][]["place"] = $row["place"];
            //$ret["result"][]["comment"] = $row["comment"];
            $event = $row;
            $ret["result"][] = $row;
        }
    }
    $link->close();
    return $ret;
}
function eventList()
{
    return eventsList();
}

function retError($ret, $info)
{
    $ret["status"] = "ERROR";
    $ret["info"] = $info;
    return $ret;
}
