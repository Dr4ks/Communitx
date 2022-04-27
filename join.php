<?php

include("classes/autoload.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['communitx_userid']);
 
if(isset($_SERVER['HTTP_REFERER'])){

	$return_to = $_SERVER['HTTP_REFERER'];
}else{
	$return_to = "profile.php";
}


$communityid = isset($URL[1]) ? $URL[1] : null;
$userid = $_SESSION['communitx_userid'];

$DB = new Database();
$query = "insert into community_members (communityid,userid,role) values ('$communityid','$userid','member')";
$DB->save($query);

header("Location: " . $return_to);
die;
?>