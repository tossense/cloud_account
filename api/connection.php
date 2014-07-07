<?PHP

// in db.conf, just php lines
//$mysql_db_hostname = "localhost";
//$mysql_db_user = "xxx";
//$mysql_db_password = "xxx";
//$mysql_db_database = "ca_test";

include('db.conf');

mysql_connect($mysql_db_hostname, $mysql_db_user, $mysql_db_password) or DIE (mysql_error());
mysql_select_db($mysql_db_database) or DIE (mysql_error());
?>