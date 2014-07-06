<?php
$mysql_db_hostname = "localhost";
$mysql_db_user = "root";
$mysql_db_password = "root";
$mysql_db_database = "ca_test";
$con = @mysqli_connect($mysql_db_hostname, $mysql_db_user, $mysql_db_password, $mysql_db_database);
if (!$con) {
	trigger_error('Could not connect to MySQL: ' . mysqli_connect_error());
}
$var = array();
$sql = "SELECT * FROM ca_users";
$result = mysqli_query($con, $sql);
while($obj = mysqli_fetch_object($result)) {
	$var[] = $obj;
}
echo '{"users":'.json_encode($var).'}';
?>