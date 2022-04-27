<?php
include("signup.php");
class Login   
{

	private $error = "";      #this variable is for all the errors realted to login process
 
	public function evaluate($data)    #this function evaluates data coming from login
	{

		$email = addslashes($data['email']);
		$password = addslashes($data['password']);

		$query = "select * from users where email = '$email' limit 1 ";    #here we send query to database to search for email

		$DB = new Database();
		$result = $DB->read($query);

		if($result)
		{
			$functions=new Signup();
			$row = $result[0];
			$userid=$row["userid"];
			$key=$functions->key_creator($userid);
			$passw=$functions->encrypt($password,$key);

			if($this->hash_text($passw) == $row['password'])    #here password is checked
			{

				//create session data
				$_SESSION['communitx_userid'] = $row['userid'];  #here session is created for successfully logged in user

			}else
			{
				$this->error .= "wrong email or password<br>";    #if there is any error it is written to error variable
			}
		}else
		{

			$this->error .= "wrong email or password<br>";
		}

		return $this->error;
		
	}
	private function hash_text($text){
		$text = hash("sha1", $text);         #this function converts passwords to hash form using sha1 hashing algorithm
		return $text;
	}
	public function check_login($id,$redirect = true)      #this function checks userid for more security
	{
		if(is_numeric($id))
		{

			$query = "select * from users where userid = '$id' limit 1 ";

			$DB = new Database();
			$result = $DB->read($query);

			if($result)
			{

				$user_data = $result[0];
				return $user_data;
			}else
			{
				if($redirect){
					header("Location: ".ROOT."login");
					die;
				}else{

					$_SESSION['communitx_userid'] = 0;
				}
			}
 
			 
		}else
		{
			if($redirect){
				header("Location: ".ROOT."login");
				die;
			}else{
				$_SESSION['communitx_userid'] = 0;
			}
		}

	}
 
}