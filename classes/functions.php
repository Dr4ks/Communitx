<?php

function pagination_link(){   #technically this function is used to create paginated links for images
	
	$page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  	$page_number = ($page_number < 1) ? 1 : $page_number;

	$arr['next_page'] = "";
	$arr['prev_page'] = "";

	//get current url
	//$url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
	$url = ROOT . $_GET['url'];
	$url .= "?";

	$next_page_link = $url;
	$prev_page_link = $url;
	$page_found = false;

	$num = 0;
	foreach ($_GET as $key => $value) {
		# code...
		if($key == 'url')
		{
			continue;
		}
		
		$num++;
		
		if($num == 1){
			if($key == "page"){
				
				$next_page_link .= $key ."=" . ($page_number + 1);
				$prev_page_link .= $key ."=" . ($page_number - 1);
				$page_found = true;
			}else{
				$next_page_link .= $key ."=" . $value;
				$prev_page_link .= $key ."=" . $value;
			}

		}else{
			if($key == "page"){
				
				$next_page_link .= "&" . $key ."=" . ($page_number + 1);
				$prev_page_link .= "&" . $key ."=" . ($page_number - 1);
				$page_found = true;

			}else{
				$next_page_link .= "&" . $key ."=" . $value;
				$prev_page_link .= "&" . $key ."=" . $value;
			}
		}
		
	}

	$arr['next_page'] = $next_page_link;
	$arr['prev_page'] = $prev_page_link;

	if(!$page_found){

		$arr['next_page'] = $next_page_link . "&page=2";
		$arr['prev_page'] = $prev_page_link . "&page=1";
	}
	
	return $arr;
}

function i_own_content($row){

	$myid = $_SESSION['communitx_userid'];
	
	//profiles
	if(isset($row['gender']) && $row['type'] == "profile" && $myid == $row['userid']){

		return true;
	}

	//communities
	if(isset($row['gender']) && $row['type'] == "community" && $myid == $row['owner']){

		return true;
	}

	//messages
	if(isset($row['sender']) && $myid == $row['sender']){

		return true;
	}

	//comments and posts
	if(isset($row['postid'])){

		if($myid == $row['userid']){
			return true;
		}else{

			$Post = new Post();
			$one_post = $Post->get_one_post($row['parent']);
			if($one_post && $myid == $one_post['userid']){
				return true;
			}

			//communities posts moderator or added admin
			$DB = new Database();
			if ($one_post){
				$communityid = $one_post['owner'];
				$sql = "select * from community_members where (role = 'admin' || role = 'moderator') && communityid = '$communityid' limit 1";
				$check = $DB->read($sql);
				if(is_array($check)){
					return true;
				}
			}
			

			

			

			//community owner
			$communityid = $row['userid'];
			$sql = "select * from users where owner = '$myid' && userid = '$communityid'  limit 1";
			$check = $DB->read($sql);

			if(is_array($check)){
				return true;
			}


		}
	}
 
	return false;
}

function tag($postid,$new_post_text = "")    #here user tag is checked whether there ia ny attempt to hack the sysytem or not
{

	$DB = new Database();
	$sql = "select * from posts where postid = '$postid' limit 1";
	$mypost = $DB->read($sql);

	if(is_array($mypost)){
		$mypost = $mypost[0];

		if($new_post_text != ""){
			$old_post = $mypost;
			$mypost['post'] = $new_post_text;
		}

		$tags = get_tags($mypost['post']);
		foreach ($tags as $tag) {
			# code...
			$sql = "select * from users where tag_name = '$tag' limit 1";
			$tagged_user = $DB->read($sql);
			if(is_array($tagged_user)){

				$tagged_user = $tagged_user[0];

				if($new_post_text != ""){
					$old_tags = get_tags($old_post['post']);
					if(!in_array($tagged_user['tag_name'], $old_tags)){
						add_notification($_SESSION['communitx_userid'],"tag",$mypost,$tagged_user['userid']);
					}
				}else{
					
					//tag
					add_notification($_SESSION['communitx_userid'],"tag",$mypost,$tagged_user['userid']);
 				}

			}
		}
	}
}



function content_i_follow($userid,$row)
{

	$row = (object)$row;

	$userid = esc($userid);
 	$date = date("Y-m-d H:i:s");
	$contentid = 0;
	$content_type = "";

	if(isset($row->postid)){
		$contentid = $row->postid;
		$content_type = "post";

		if($row->parent > 0){
			$content_type = "comment";
		}
	}
	
	if(isset($row->gender)){
		$content_type = "profile";
	}

	$query = "insert into content_i_follow (userid,date,contentid,content_type) 
	values ('$userid','$date','$contentid','$content_type')";
	$DB = new Database();
	$DB->save($query);
}


function esc($value)
{

	return addslashes($value);
}

function notification_seen($id)
{
	;
}

function check_tags($text)
{
	$str = "";
	$words = explode(" ", $text);
	if(is_array($words) && count($words)>0)
	{
		$DB = new Database();
		foreach ($words as $word) {

			if(preg_match("/@[a-zA-Z_0-9\Q,.\E]+/", $word)){
				
				$word = trim($word,'@');
				$word = trim($word,',');
				$tag_name = esc(trim($word,'.'));

				$query = "select * from users where tag_name = '$tag_name' limit 1";
				$user_row = $DB->read($query);

				if(is_array($user_row)){
					$user_row = $user_row[0];
					$str .= "<a href='".ROOT."profile/$user_row[userid]'>@" . $word . "</a> ";
				}else{

					$str .= htmlspecialchars($word) . " ";
				}
 			
			}else{
				$str .= htmlspecialchars($word) . " ";
			}
		}

	}

	if($str != ""){
		return $str;
	}

	return htmlspecialchars($text);
}

function get_tags($text)
{
	$tags = array();
	$words = explode(" ", $text);
	if(is_array($words) && count($words)>0)
	{
		$DB = new Database();
		foreach ($words as $word) {

			if(preg_match("/@[a-zA-Z_0-9\Q,.\E]+/", $word)){
				
				$word = trim($word,'@');
				$word = trim($word,',');
				$tag_name = esc(trim($word,'.'));

				$query = "select * from users where tag_name = '$tag_name' limit 1";
				$user_row = $DB->read($query);

				if(is_array($user_row)){
					
					$tags[] = $word;
				}
 			
			}
		}

	}
 
	return $tags;
}


if(isset($_SESSION['communitx_userid'])){
	set_online($_SESSION['communitx_userid']);
}

function set_online($id){

	if(!is_numeric($id)){
		return;
	}

	$online = time();
	$query = "update users set online = '$online' where userid = '$id' limit 1";
	$DB = new Database();
	$DB->save($query);

}

function show($data){

	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

$URL = split_url2();
function split_url2()
{
	$url = isset($_GET['url']) ? $_GET['url'] : "index";
	$url = explode("/", filter_var(trim($url,"/"),FILTER_SANITIZE_URL));

	return $url;
}

function split_url_from_string($str)
{
	$url = isset($str) ? $str : "index";
	$url = explode("/", filter_var(trim($url,"/"),FILTER_SANITIZE_URL));

	return $url;
}

function check_messages(){

	$DB = new Database();
	$me = esc($_SESSION['communitx_userid']);
 
	$query = "select * from messages where (receiver = '$me' && deleted_receiver = 0 && seen = 0) limit 100";
	$data = $DB->read($query);

	if(is_array($data)){
		return count($data);
	}
	return 0;
}

function check_seen_thread($msgid){

	$DB = new Database();
	$me = esc($_SESSION['communitx_userid']);
 
	$query = "select * from messages where (receiver = '$me' && deleted_receiver = 0 && seen = 0 && msgid = '$msgid') limit 100";
	$data = $DB->read($query);

	if(is_array($data)){
		return count($data);
	}
	return 0;
}


function is_invited($communityid,$userid){

	$userid = addslashes($userid);
	$communityid = addslashes($communityid);

	$DB = new Database();

	$query = "select userid from community_invites where communityid = '$communityid' && userid = '$userid' && disabled = 0 limit 1";
	$data = $DB->read($query);
	if(is_array($data)){
		return true;
	}
	return false;
}


function community_access($userid,$community_data,$access){

	//$admins = array($community_data["owner"]); 
	//$moderators = array($community_data["owner"]); 
	//$members = array($community_data["owner"]);

	//$community_type = $community_data["community_type"];
	if($community_data["owner"] == $userid){
		return true;
	}

	$DB = new Database();
	$communityid = $community_data["userid"];

	if($access == 'member'){
	
		$query = "select userid from community_members where communityid = '$communityid' && userid = '$userid' && (role = 'member' || role = 'admin' || role = 'moderator') limit 1";
		$data = $DB->read($query);
		if(is_array($data)){
			return true;
		}
		return false;
	}else
	if($access == 'moderator'){
		$query = "select userid from community_members where communityid = '$communityid' && userid = '$userid' && (role = 'admin' || role = 'moderator') limit 1";
		$data = $DB->read($query);
		if(is_array($data)){
			return true;
		}
		return false;
	}else
	if($access == 'admin'){
		$query = "select userid from community_members where communityid = '$communityid' && userid = '$userid' && (role = 'admin') limit 1";
		$data = $DB->read($query);
		if(is_array($data)){
			return true;
		}
		return false;
	}else
	if($access == 'request'){

		$query = "select * from community_requests where userid = '$userid' && communityid = '$communityid' && disabled = 0 limit 1";
		$data = $DB->read($query);
		if(is_array($data)){
			return true;
		}
		return false;
	}

	return false;
	//admin, moderator, member, nonmember
}

