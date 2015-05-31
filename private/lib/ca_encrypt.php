<?PHP
require_once(__DIR__.'/../db.conf.php');

function passwordEncrypt($passwordText)
{
    global $ca_password_salt;
    return sha1($passwordText.$ca_password_salt);
}

?>
