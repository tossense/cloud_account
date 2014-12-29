<?PHP
require_once('../private/lib/ca_db.php');

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
    $link = link_ca_db();
    if($link->connect_errno)
    {
        die("Could not connect db: " . $link->connect_error);
    }

    $query = "SELECT name, balance FROM ca_users";
    $username = $_GET['username'];
    if($username)
        $query = $query." WHERE name='".$username."'";
    $users=array();
    $res = $link->query($query);
    if($res){
        while($row = $res->fetch_array(MYSQLI_ASSOC)){
            $users['users'][]=$row;
        }
        $res->free();
    }
    else{
        //echo 'NULL';
    }
    $link->close();
    if($_GET['ysdebug']=='1')
        $users['debug'] = $query;
    $users=json_encode($users);
    if($_GET['jsoncallback'])
        echo $_GET['jsoncallback'].'('.$users.')';
    else
        echo $users;
}

?>
