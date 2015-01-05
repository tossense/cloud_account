<?PHP
require_once('../private/lib/ca_db.php');

$postData = file_get_contents("php://input");
$postJsonArray = json_decode($postData, true);
if($postJsonArray)
{
	$ret = array();
	if($postJsonArray["action"] == "addEvent")
	{
		$ret = addEvent($postJsonArray);
	}

	echo(json_encode($ret));
}
else
{
	echo $postData,"\n";
	echo "ja: ", $postJsonArray;
	http_response_code(400);
}

/**
 * @param $eventJsonArray
 * @return array
 */
function addEvent($eventJsonArray)
{
	$ret = array();
	$ret["status"] = "OK";
	$ts = $eventJsonArray["time"];
	$group = $eventJsonArray["group"];
	$records = $eventJsonArray["records"];
	if(!$ts || !$group || !$records)
	{
		return retError($ret, "Invalid Data");
	}

	//$keyToColumn = array("time"=>"time", "place"=>"place", "comment"=>"comment");
	$keyToColumn = array("place"=>"place", "comment"=>"comment");

	$link = connectCaDb($ret);
	if($link->connect_errno)
	{
		return $ret;
	}
	$sqlArray = toSqlArray($eventJsonArray, $keyToColumn, $link);
	$sqlArray["groupId"] = getGroupId($group, $link)[$group];
	if(!$sqlArray["groupId"])
	{
		return retError($ret, "Invalid group name");
	}
	$sql = makeInsertSql("tbEvents", $sqlArray);
	if( queryAndLogError($link, $sql, $ret) )
	{
		$eventId = $link->insert_id;
		$users = array_keys($records);
		$userNameIds = getUserId($users, $link);
		if(count($userNameIds) == count($records))
		{
			$sql = "";
			foreach($userNameIds as $name => $userId)
			{
				$sql .= "INSERT INTO tbRecords (eventId, userId, money) VALUES ($eventId, $userId, $records[$name]);";
			}
			multiQueryAndLogError($link, $sql, $ret);
		}
		else
		{
			$unknownUsers = array_diff($users, array_keys($userNameIds));
			return retError($ret, "Unknown User: ".implode(", ", $unknownUsers));
		}

	}

	$link->close();
	return $ret;

}

function toSqlArray($json, $keyToColumn, $link=null)
{
	if(!$link)
		$link = connectCaDb();
	$sqlArray = array();
	foreach($keyToColumn as $key => $column)
	{
		if($json[$key])
			$sqlArray[$column] = $link->real_escape_string($json[$key]);
	}
	return $sqlArray;
}

function retError($ret, $info)
{
	$ret["status"] = "ERROR";
	$ret["info"] = $info;
	return $ret;
}
