<?php
require_once('../private/lib/ca_db.php');
require_once('../private/lib/ca_encrypt.php');
require_once('../private/lib/misc.php');
require_once('group.php');

$postData = file_get_contents("php://input");
if($postData)
{
	$postJson = json_decode($postData, true);
	$ret = array();
	if($postJson["action"] == "addUser")
	{
		$ret = addUser($postJson);
	}

	echo(json_encode($ret));
}
else
{
	http_response_code(400);
}

function addUser($postJson)
{
	$u = $postJson['username'];
	$p = $postJson['password'];
	$ret = array();
	$ret["status"] = "OK";
	if( !isValidUserName($u) )
	{
		return retError($ret, "invalid username");
	}
	$link = connectCaDb($ret);
	if($link->connect_errno)
	{
		return $ret;
	}
	$u = $link->real_escape_string($u);
	$p = passwordEncrypt($p);
	$sql = "INSERT INTO tbUsers (name, password) VALUES ('$u', '$p')";
	if(!$link->query($sql))
	{
		return retError($ret, "sql insert error: ".$link->error);
	}
	$link->close();
	if($postJson['groups'] || $postJson['group'])
	{
		$addGroupJson = $postJson;
		$addGroupJson['user'] = $u;
		$retG = addGroups($addGroupJson);
		if($retG["status"] != "OK")
			$ret = $retG;
	}
	return $ret;
}

function isValidUserName($username) {
	$len = strlen($username);
	if( $len == 0 || $len > 20 || $len < 2 )
		return false;
	if( !ctype_alpha($username[0]) )
		return false;
	if(!preg_match('/^[A-Za-z0-9_.@]+$/u', $username))
		return false;
	 
	return true;
}
