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
 * Checks if a string is a valid timestamp.
 *
 * @param  string $timestamp Timestamp to validate.
 * 
 * @return bool
 */
function is_timestamp($timestamp)
{
	$check = (is_int($timestamp) OR is_float($timestamp))
		? $timestamp
		: (string) (int) $timestamp;

	return  ($check === $timestamp)
			AND ( (int) $timestamp <=  PHP_INT_MAX)
			AND ( (int) $timestamp >= ~PHP_INT_MAX);
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
	if(!$group || !$records)
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
	$groupId = getGroupId($group, $link)[$group];
	if(!$groupId)
	{
		return retError($ret, "Invalid group name");
	}

	$users = array_keys($records);
	$userNameIds = getUserId($users, $link);
	if(count($userNameIds) != count($records))
	{
		$unknownUsers = array_diff($users, array_keys($userNameIds));
		return retError($ret, "Unknown User: ".implode(", ", $unknownUsers));
	}

	foreach($userNameIds as $name => $userId)
	{
		$money = $records[$name];
		if(!is_numeric($money))
			return retError($ret, "Has Not Valid Number: ".$money);
	}

	$sqlArray["groupId"] = $groupId;
	if($ts && is_timestamp($ts))
		$sqlArray["time"] = "FROM_UNIXTIME(".$ts.")";

	$sql = makeInsertSql("tbEvents", $sqlArray);
	if( queryAndLogError($link, $sql, $ret) )
	{
		$eventId = $link->insert_id;
		$sqlRecords = "";
		$sqlBalance = "";
		foreach($userNameIds as $name => $userId)
		{
			$money = $records[$name];
			$sqlRecords .= "INSERT INTO tbRecords (eventId, userId, money) VALUES ($eventId, $userId, $money);";
			$sqlBalance .= "UPDATE tbGroupMembers SET balance=balance+($money) WHERE groupId=$groupId AND userId=$userId;";
		}
		$sql = $sqlRecords.$sqlBalance;
		if(multiQueryAndLogError($link, $sql, $ret))
		{
			while ($link->next_result()) {;} // flush multi_queries
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
		$val = $json[$key];
		if($val)
		{
			if(is_string($val))
				$sqlArray[$column] = '"'.$link->real_escape_string($val).'"';
			else
				$sqlArray[$column] = $val;
		}
	}
	return $sqlArray;
}

function retError($ret, $info)
{
	$ret["status"] = "ERROR";
	$ret["info"] = $info;
	return $ret;
}
