<?php 

Class Settings   #this class contains all the necessary functions related to user account
{

	public function get_settings($id)
	{
		$DB = new Database();
		$sql = "select * from users where userid = '$id' limit 1";
		$row = $DB->read($sql);
		if(is_array($row)){
			return $row[0];
		}
	}

	public function save_settings($data,$id){

		$DB = new Database();

		$password = isset($data['password']) ? $data['password'] : "";

		if(strlen($password) < 30 && isset($data['password2'])){

			if($data['password'] == $data['password2']){
				$functions=new Signup();
				$key=$functions->key_creator($id);
				$password=$functions->encrypt($password,$key);
				$data['password'] = hash("sha1", $password);
			}else{

				unset($data['password']);
			}
		}

		unset($data['password2']);

		$sql = "update users set ";
		foreach ($data as $key => $value) {
			# code...

			$sql .= $key . "='" . $value. "',";
		}

		$sql = trim($sql,",");
		$sql .= " where userid = '$id' limit 1";
		$DB->save($sql);
	}
}