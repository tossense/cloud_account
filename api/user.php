<?php
require_once('../private/lib/ca_db.php');
require_once('../private/lib/ca_encrypt.php');

$postdata = file_get_contents("php://input");
if($postdata)
{
	$postjson = json_decode($postdata, true);
	$ret = array();
	if($postjson["action"] == "addUser")
	{
		$ret = addUser($postjson["username"], $postjson["password"]);
	}

	echo(json_encode($ret));
}
else
{
	http_response_code(400);
}

function addUser($u, $p)
{
	$ret = array();
	$ret["status"] = "OK";
	if( !isValidUserName($u) )
	{
		$ret["status"] = "ERROR";
		$ret["info"] = "invalid username";
		return $ret;
	}
	$link = link_ca_db();
	$u = $link->real_escape_string($u);
	$p = passwd_encrypt($p);
	$sql = sprintf("INSERT INTO ca_users (name, password) VALUES ('%s', '%s')", $u, $p);
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