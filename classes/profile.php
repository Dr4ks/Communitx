<?php

Class Profile  #this class includes get profile function which return information about user such as userid using sql query
{
	
	function get_profile($id){

		$id = addslashes($id);
		$DB = new Database();
		$query = "select * from users where userid = '$id' limit 1";
		return $DB->read($query);

	}
}