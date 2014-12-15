<?PHP

include('../private/db.conf');

mysql_connect( $mysql_db_hostname, $mysql_db_user, $mysql_db_password )
    or die("Could not connect: " . mysql_error());
mysql_select_db($mysql_db_database) or die("Could not select db: " . mysql_error());


//Now we check if the function exists
if(function_exists($_GET['method'])){
    //Call the passed function
    $_GET['method']();
}
else{
    http_response_code(400);
    echo 'Wrong Method.';
}
//Here is the function to get
function userBalance(){
    $query = "SELECT name, balance FROM ca_users";
    $username = $_GET['username'];
    if($username)
        $query = $query." WHERE name='".$username."'";
    $users=array();
    $res = mysql_query($query);
    if($res){
        while($row=mysql_fetch_array($res, MYSQL_ASSOC)){
            $users['users'][]=$row;
        }
    }
    else{
        //echo 'NULL';
    }
    if($_GET['ysdebug']=='1')
        $users['debug'] = $query;
    $users=json_encode($users);
    if($_GET['jsoncallback'])
        echo $_GET['jsoncallback'].'('.$users.')';
    else
        echo $users;
}

?>
