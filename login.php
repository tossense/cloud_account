<?php
require_once('private/lib/ca_encrypt.php');
require_once('private/lib/ca_db.php');
if($_SERVER['REQUEST_METHOD']=='POST')
{
	if(!isset($_POST["username"]) || !isset($_POST["password"]))
	{
		header("Location:" . urlencode($_SERVER['REQUEST_URI']));
    	exit();
	}
	$posts = $_POST;
	foreach ($posts as $key => $value) {
	    $posts[$key] = trim($value);
	}
	$password = passwordEncrypt($posts["password"]);
	$username = $posts["username"]; 

	$ret = array();
	$ret["status"] = "OK";
	$link = connectCaDb($ret);

	$query = "SELECT `name` FROM `tbUsers` WHERE `password` = '$password' AND `name` = '$username'";
	//  取得查询结果
	$userInfo = $link->query($query);
	if($userInfo && $userInfo->num_rows>0)
	{
		if(isset($posts["rememberme"]) && $posts["rememberme"]=="y")
		{
			$lifeTime = 3600 * 24 * 365;
			session_set_cookie_params($lifeTime);
		    //  当验证通过后，启动 Session
		    session_start();
		    //  注册登陆成功的 admin 变量，并赋值 true
		    $_SESSION["login"] = true;
		}
	}
	if(isset($_SESSION["login"]) && $_SESSION["login"] == true)
	{
		if(isset($_POST["location"]))
		{
			$redirect = $_POST["location"];
			header("Location:". $redirect);
			exit();
		}
	}

	header("Location:" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}
else if($_SERVER['REQUEST_METHOD']=='GET')
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sample</title>
</head>
<body> 
    <form name="form1" method="post" action="login.php">
<?php
    echo '<input type="hidden" name="location" value="';
	if(isset($_GET['location'])) {
	    echo htmlspecialchars($_GET['location']);
	}
	echo '" />';
?>
        <table width="300" border="0" align="center" cellpadding="2" cellspacing="2"> 
            <tr> 
                <td width="150"><div align="right">用户名：</div></td> 
                <td width="150"><input type="text" name="username"></td> 
            </tr> 
            <tr> 
                <td><div align="right">密码：</div></td> 
                <td><input type="password" name="password"></td> 
            </tr> 
            <tr>
            	<td></td>
                <td><label class="remember-me">
				<input type="checkbox" name="rememberme" checked="" value="y">下次自动登录
				</label></td>
            </tr> 
        </table> 
        <p align="center"> 
            <input type="submit" name="Submit" value="Submit"> 
            <input type="reset" name="Reset" value="Reset"> 
        </p> 
    </form>
</body> 
</html>
<?php
}
?>
