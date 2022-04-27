<?php 
	
	session_start();

	include("classes/connect.php");
	include("classes/functions.php");    #here we make our job easier. Instead if typing all the names every time we only include autoload.php 
	include("classes/login.php");
	include("classes/user.php");
	include("classes/post.php");
 	include("classes/image.php");
 	include("classes/profile.php");
 	include("classes/settings.php");
 	include("classes/time.php");
 	include("classes/community.php");

 	if(!defined("ROOT")){

 		//create root variable
		$root = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
		$root = trim(str_replace("router.php", "", $root),"/");

		define("ROOT", $root . "/");

		$URL = split_url2();

 	}
