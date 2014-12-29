<?PHP
require_once(__DIR__.'/../db.conf');

function link_ca_db()
{
	global $ca_db_hostname;
	global $ca_db_user;
	global $ca_db_password;
	global $ca_db_database;
	return new mysqli($ca_db_hostname, $ca_db_user, $ca_db_password, $ca_db_database);
}

?>