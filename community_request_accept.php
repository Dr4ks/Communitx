<?php 

include("classes/autoload.php");             #this part is displayed when user has received a request to join a community 
                                            #in this request all the necessary information about the community are shown to the user
	$login = new Login();
	$user_data = $login->check_login($_SESSION['communitx_userid']);

 
if(isset($_SERVER['HTTP_REFERER'])){

	$return_to = $_SERVER['HTTP_REFERER'];
}else{
	$return_to = "profile.php";
}


$communityid = isset($URL[1]) ? $URL[1] : null;
$userid = isset($URL[2]) ? $URL[2] : null;
$action = isset($URL[3]) ? $URL[3] : null;


$community_class = new community();
$community_class->accept_request($communityid,$userid,$action);    #here user can accept the request or not

header("Location: " . $return_to);
die;