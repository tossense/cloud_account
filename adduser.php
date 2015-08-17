<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Add User - Cloud Account</title>
	<script type="text/javascript" src="http://libs.baidu.com/jquery/2.0.3/jquery.min.js"></script>
	<script type="text/javascript">
		function getUrlParameter(sParam)
		{
			var sPageURL = window.location.search.substring(1);
			var sURLVariables = sPageURL.split('&');
			for (var i = 0; i < sURLVariables.length; i++) 
			{
				var sParameterName = sURLVariables[i].split('=');
				if (sParameterName[0] == sParam) 
				{
					return sParameterName[1];
				}
			}
			return "";
		}
	</script>
</head>
<body> 
	<form name="form1" method="post" action="api/user.php">
		<table width="300" border="0" align="center" cellpadding="2" cellspacing="2"> 
			<tr> 
				<td width="150"><div align="right">User Name</div></td> 
				<td width="150"><input type="text" name="username"></td> 
			</tr>
			<tr> 
				<td><div align="right">Nickname</div></td> 
				<td><input type="text" name="nickname"></td> 
			</tr>
			<tr> 
				<td><div align="right">Group</div></td> 
				<td><input type="text" name="group" value="dataminers"></td> 
			</tr>
		</table>
		<input type="hidden" name="action" value="addUser">
		<p align="center">
			<input type="submit" name="Submit" value="Submit">
			<input type="reset" name="Reset" value="Reset">
		</p>
	</form>
</body>
</html>