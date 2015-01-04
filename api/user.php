<?php
require_once('../private/lib/ca_db.php');
require_once('../private/lib/ca_encrypt.php');

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
	$g = $postJson['group'];
	$ret = array();
	$ret["status"] = "OK";
	if( !isValidUserName($u) )
	{
		$ret["status"] = "ERROR";
		$ret["info"] = "invalid username";
		return $ret;
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
		$ret["status"] = "ERROR";
		$ret["info"] = "sql insert error: ".$link->error;
	}
	$link->close();
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
?>