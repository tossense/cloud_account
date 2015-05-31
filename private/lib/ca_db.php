<?PHP
require_once(__DIR__.'/../db.conf.php');

function connectCaDb($ret=null)
{
	global $ca_db_hostname;
	global $ca_db_user;
	global $ca_db_password;
	global $ca_db_database;
	$link = new mysqli($ca_db_hostname, $ca_db_user, $ca_db_password, $ca_db_database);
	if($link->connect_errno && $ret)
	{
		$ret["status"] = "ERROR";
		$ret["info"] = $link->connect_error;
	}
	return $link;
}

/**
 * @param $table
 * @param $name
 * @param $dbLink
 * @return array of (name => id)
 */
function getIdFromName($table, $name, $dbLink)
{
	// The table "$table" should have column "$name"
	$ret = array();
	$link = $dbLink;
	if(!$dbLink)
	{
		$link = connectCaDb();
		if($link->connect_errno)
			return $ret;
	}
	$table = $link->real_escape_string($table);
	$sql = "";
	if(is_string($name))
	{
		$name = $link->real_escape_string($name);
		$sql = "SELECT id, name FROM $table WHERE name='$name';";
	}
	elseif(is_array($name))
	{
		escapeArray($name, $link);
		$names = '("' . implode('","', $name) . '")';
		$sql = "SELECT id, name FROM $table WHERE name IN $names;";
	}
	$res = $link->query($sql);
	while($row = $res->fetch_assoc())
	{
		$ret[$row["name"]] = (int)$row["id"];
	}
	if(!$dbLink)
		$link->close();
	return $ret;
}

/**
 * @param $groupName
 * @param $dbLink
 * @return array
 */
function getGroupId($groupName, $dbLink=null)
{
	return getIdFromName("tbGroups", $groupName, $dbLink);
}

/**
 * @param $userName
 * @param $dbLink
 * @return array
 */
function getUserId($userName, $dbLink=null)
{
	return getIdFromName("tbUsers", $userName, $dbLink);
}

/**
 * @param $link
 * @param $sql
 * @param $ret
 * @return bool
 */
function queryAndLogError($link, $sql, $ret)
{
	$res = $link->query($sql);
	if(!$res)
	{
		$ret["status"] = "ERROR";
		$ret["info"] = "MySQL Error: ".$link->error;
	}
	return $res;
}

/**
 * @param $link
 * @param $sql
 * @param $ret
 * @return bool
 */
function multiQueryAndLogError($link, $sql, $ret)
{
	$res = $link->multi_query($sql);
	if(!$res)
	{
		$ret["status"] = "ERROR";
		$ret["info"] = "MySQL Error: ".$link->error;
	}
	return $res;
}

function makeInsertSql($table, $sqlArray)
{
	$cols = '(' . implode(',', array_keys($sqlArray)) . ')';
	$vals = '(' . implode(',', array_values($sqlArray)) . ')';
	$sql = "INSERT INTO $table $cols VALUES $vals;";
	return $sql;
}

function escapeArray($a, $link)
{
	for($i = 0; $i < count($a); $i++)
	{
		$a[$i] = $link->real_escape_string($a[$i]);
	}
}
?>