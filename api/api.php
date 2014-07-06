<?PHP
/*PHP Public API*/
//Include the database connection
include('connection.php');
//Now we check if the function exists
if(function_exists($_GET['method'])){
    //Call the passed function
    $_GET['method']();
}
else{
    echo 'Wrong Method.';
}
//Here is the function to get
function allUsers(){
    //Get all users from the database
    $sql_users=mysql_query("Select name FROM ca_users") or DIE (mysql_error());
    //New array called users
    $users=array();
    //Loop through each result and put each result into a single array
    while($user=mysql_fetch_array($sql_users)){
        $users[]=$user;
    }
    //Set $users to json encode $users
    $users=json_encode($users);
    //Okay here is the JSONP
    echo $_GET['jsoncallback'].'('.$users.')';
}
?>