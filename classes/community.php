<?php 

class community      #this class contains all the functions related to communities 
{

	private $error = "";     #this variable contains all the errors
 
 		public function evaluate($data)
	{

		foreach ($data as $key => $value) {
			# code...

			if(empty($value))   #all the fields should be filled with the appropriate data
			{
				$this->error = $this->error . $key . " is empty!<br>";
			}

 			if($key == "community_name")    #community name can not be numeric
			{
				if (is_numeric($value)) {
        
 					$this->error = $this->error . "community name cant be a number<br>";
    			}
 
			}
			
			if($key == "community_type" && ($value != "Public" && $value != "Private"))   #user can only enter public or private for community type
			{
         
 				$this->error = $this->error . "Please enter a valid community type<br>";
  
			}

	 
		}

		$DB = new Database();

		//check url address
		$data['url_address'] = str_replace(" ","_",strtolower($data['community_name']));

		$sql = "select id from users where url_address = '$data[url_address]' limit 1";
		$check = $DB->read($sql);
		while(is_array($check)){

			$data['url_address'] = str_replace(" ","_",strtolower($data['community_name'])) . rand(0,9999);
			$sql = "select id from users where url_address = '$data[url_address]' limit 1";
			$check = $DB->read($sql);
		}

		$data['userid'] = $this->create_userid();
		//check userid
		$sql = "select id from users where userid = '$data[userid]' limit 1";
		$check = $DB->read($sql);
		while(is_array($check)){

			$data['userid'] = $this->create_userid();
			$sql = "select id from users where userid = '$data[userid]' limit 1";
			$check = $DB->read($sql);
		}
 

		if($this->error == "")
		{

			//no error
			$this->create_community($data);     #if there is not any error then community is successfully created
		}else
		{
			return $this->error;
		}

		
	}

	public function remove_member($communityid,$userid){   #this function is for removing members of community

		$DB = new Database();

		$communityid = addslashes($communityid);
		$userid = addslashes($userid);

		$query = "update community_members set disabled = 1 where userid = '$userid' && communityid = '$communityid' ";
		$DB->save($query);

		$query = "update users set owner = 1 where userid = '$communityid' limit 1";
		$DB->save($query);
		
	}

	public function edit_member_access($communityid,$userid,$role){    #this function is for editing member access such as changing user privileges

		$DB = new Database();

		$communityid = addslashes($communityid);
		$userid = addslashes($userid);
		$role = addslashes($role);
		$me = addslashes($_SESSION['communitx_userid']);
		
		$query = "update community_members set role = '$role' where userid = '$userid' && communityid = '$communityid' ";
		$DB->save($query);
	
		//notify user of this change
 		$row = $this->get_community($communityid);
 		if(is_array($row)){

 			$row = $row[0];
 			$row['owner'] = $userid;
 			add_notification($me,"role",$row);
 		}
	}

	public function get_member_role($communityid,$userid){     #this function returns user role in the community such as admin, moderator, simple user

		$DB = new Database();
		$role = "Unknown";

		$communityid = addslashes($communityid);
		$userid = addslashes($userid);

		$query = "select role from community_members where userid = '$userid' && communityid = '$communityid' && disabled = 0";
		$result = $DB->read($query);
		if(is_array($result)){
			return $result[0]['role'];
		}

		$query = "select id from users where userid = '$communityid' && owner = '$userid' limit 1";
		$result = $DB->read($query);
		if(is_array($result)){
			return "admin";
		}

		return $role;
	}

	
	public function create_community($data)     #this function creates community
	{

		$community_name = ucfirst(addslashes($data['community_name']));
		$userid = $data['userid'];
		$url_address = $data['url_address'];
		$type = 'community';
		$community_type = addslashes($data['community_type']);
		$date = date("Y-m-d H:i:s");
		$owner = addslashes($_SESSION['communitx_userid']);

		//create these
		$url_address = strtolower($community_name) . "." . rand(0,9999);

		$query = "insert into users 
		(userid,type,community_type,first_name,url_address,date,owner) 
		values 
		('$userid','$type','$community_type','$community_name','$url_address','$date','$owner')";

		$DB = new Database();
		$DB->save($query);
	}
 
 	public function join_community($communityid,$userid){

 		$DB = new Database();
 		$communityid = esc($communityid);
 		$userid = esc($userid);

 		$query = "select * from community_requests where userid = '$userid' && communityid = '$communityid' limit 1";
 		$check = $DB->read($query);

 		if($check){
 			$check = $check[0];
 			$query = "update community_requests set disabled = 0 where id = '$check[id]' limit 1";
 		}else{
 			$query = "insert into community_requests (communityid,userid) values ('$communityid','$userid')";
 		}

		$DB->save($query);
 	}

 	public function accept_request($communityid,$userid,$action){

 		$DB = new Database();
 		$communityid = esc($communityid);
 		$userid = esc($userid);
 		$action = esc($action);
 		$role = "member";

 		if($action == "accept"){
	 		
	 		$query = "select * from community_members where userid = '$userid' && communityid = '$communityid' limit 1";
	 		$check = $DB->read($query);

	 		if($check){
	 			$check = $check[0];
	 			$query = "update community_members set disabled = 0 where id = '$check[id]' limit 1";
				$DB->save($query);
				
	 		}else{
	 			$query = "insert into community_members (communityid,userid,role) values ('$communityid','$userid','$role')";
				$DB->save($query);
	 		}

		}
		
		$query = "update community_requests set disabled = 1 where  userid = '$userid' && communityid = '$communityid' limit 1";
		$DB->save($query);

		$query = "update community_invites set disabled = 1 where  userid = '$userid' && communityid = '$communityid' limit 1";
		$DB->save($query);

		
 	}


 	public function get_invited($communityid){

 		$DB = new Database();

 		$communityid = addslashes($communityid);
 		$me = addslashes($_SESSION['communitx_userid']);
 		$query = "select * from community_invites where communityid = '$communityid' && userid = '$me' && disabled = 0 ";
 		$check = $DB->read($query);
 		if(is_array($check)){

 			return $check;
 		}

 		return false;
 	}

 	public function invite_to_community($communityid,$userid,$me){

 		$communityid = addslashes($communityid);
 		$userid = addslashes($userid);
 		$me = addslashes($me);

 		$DB = new Database();
 		
 		$query = "select * from community_invites where communityid = '$communityid' && userid = '$userid' && inviter = '$me' ";
 		$check = $DB->read($query);
 		if(is_array($check)){

 			$id = $check[0]['id'];
 			$query = "update community_invites set disabled = 0 where id = '$id' limit 1";
 			$check = $DB->save($query);
 			
 		}else{
 			$query = "insert into community_invites (communityid,userid,inviter) values ('$communityid','$userid','$me')";
 			$check = $DB->save($query);
 		}

 		//notify user of invitation
 		$row = $this->get_community($communityid);
 		if(is_array($row)){

 			$row = $row[0];
 			$row['owner'] = $userid;
 			add_notification($me,"invite",$row);
 		}


 	}
 	

 	public function get_requests($communityid){

 		$DB = new Database();
 		$communityid = esc($communityid);

 		$query = "select * from community_requests where communityid = '$communityid' && disabled = 0 ";
 		$check = $DB->read($query);

 		if($check){
 			return $check;
 		}

 		return false;

 	}

 	public function get_members($communityid,$limit = 100){

 		$DB = new Database();
 		$communityid = esc($communityid);

 		$query = "select owner from users where userid = '$communityid' limit 1";
 		$check1 = $DB->read($query);
 		$result = false;

 		if($check1){
			
			$check1[0]['userid'] = $check1[0]['owner'];
			$check1[0]['role'] = "admin";
			
			$result = $check1;
			$query = "select * from community_members where communityid = '$communityid' && disabled = 0 limit $limit";
	 		$check = $DB->read($query);

	 		if($check){

	 			$result = array_merge($check1, $check);
	 			return $result;
	 		}

	 		return $result;
 		}


 		return false;

 	}

 	public function get_invites($community_id,$id,$type){

 		$community_id = addslashes($community_id);

		$DB = new Database();
		$type = addslashes($type);

		if(is_numeric($id)){
 
			//get like details
			$sql = "select likes from likes where type='$type' && contentid = '$id' limit 1";
			$result = $DB->read($sql);
			if(is_array($result)){

				$likes = json_decode($result[0]['likes'],true);

				//get all members of the community
				$members = $this->get_members($community_id,100000);
				if(is_array($members)){

					$members = array_column($members, "userid");
					if(is_array($likes)){
						foreach ($likes as $key => $like) {
							# code...
							if(in_array($like['userid'], $members)){
								unset($likes[$key]);
							}
						}

						$likes = array_values($likes);
					}
				}
				return $likes;
			}
		}


		return false;
	}

	private function create_userid()
	{

		$length = rand(4,19);
		$number = "";
		for ($i=0; $i < $length; $i++) { 
			# code...
			$new_rand = rand(0,9);

			$number = $number . $new_rand;
		}

		return $number;
	}

	public function get_my_communities($owner)
	{

		$DB = new Database();
		$query = "select * from users where owner = '$owner' && type = 'community' ";
		$result = $DB->read($query);

		//check in community members as well
		$query = "select * from community_members where disabled = 0 && userid = '$owner' ";
		$result2 = $DB->read($query);

		if(is_array($result2))
		{
			$communityids = array_column($result2, "communityid");
			$communityids = "'" . implode("','", $communityids) . "'";
			
			//check in community members as well
			$query = "select * from users where userid in ($communityids) && type = 'community' ";
			$community_rows = $DB->read($query);

			if(is_array($community_rows))
			{
				foreach ($community_rows as $row) {
					# code...
					$result[] = $row;
				}
			}

		}

		if($result)
		{

			return $result;
		}else
		{
			return false;
		}
	}

	function get_community($id){

		$id = addslashes($id);
		$DB = new Database();
		$query = "select * from users where userid = '$id' && type = 'community' limit 1";
		return $DB->read($query);

	}

}