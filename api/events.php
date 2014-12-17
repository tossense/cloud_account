<?PHP
$postdata = file_get_contents("php://input");
if($postdata)
{
	echo "POST:<br>";
	var_dump($postdata);
	$postdata = json_decode($postdata, true);
	var_dump($postdata);
}
else
{
	http_response_code(400);
}

?>