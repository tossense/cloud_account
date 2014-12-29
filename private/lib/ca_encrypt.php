<?PHP
require_once(__DIR__.'/../db.conf');

function passwd_encrypt($passwd_text)
{
    global $ca_password_salt;
    return sha1($passwd_text.$ca_password_salt);
}

?>
