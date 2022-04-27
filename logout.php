<?php 

session_start();

if(isset($_SESSION['communitx_userid']))
{
	$_SESSION['communitx_userid'] = NULL;        #if user log out the user session is unset and user is directed to login page
	unset($_SESSION['communitx_userid']);

}

header("Location:" . ROOT ."login");
die;
