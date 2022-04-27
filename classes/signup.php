<?php 
class Signup          #this class contains all functions related to signup
{

	private $error = "";         #this error variable contains all errors related to signup process

	public function check_data($input){
		$input=trim($input);
		$input=stripslashes($input);
		$input=htmlspecialchars($input);
		return $input;
	}
	public function key_creator($id){    #this function creates key for encrypting password
		$n=7;                        #key is created using userid
		$characters = "ABCDEFGHIJ";    #this is alhpabet that I use for secret key
		$randomString = ""; 
		for ($i = 0; $i < $n; $i++){ 
			$index = intval($id[$i]);
			$randomString .= $characters[$index]; 
		}
		return $randomString;
	}
	public function encrypt($message,$key){     #here password is encrypted using vigenere cipher
		$result="";
		$k=0;
		for ($n=0;$n<strlen($message);$n++){           #this is vigenere cipher encryption algorithm
			$result = $result . chr((ord($message[$n])-33+ord($key[$k]))%94+33);
			$k++;
			if ($k==strlen($key)){
				$k=0;
			}
		}
		return $result;
	}

	public function evaluate($data)
	{

		foreach ($data as $key => $value) {
			# code...

			if(empty($value))    #none of values from user input should be empty
			{
				$this->error = $this->error . $key . " is empty!<br>";
			}
			if ($key=="email"){      #here it checks if email has been written in correct way or not
                if (!preg_match("/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-\.]+$/",$value)){
                    $this->error = $this->error . "invalid email address!<br>";
                }
                $query="SELECT * FROM users WHERE email='$value' limit 1";
                $DB = new Database();
                $result = $DB->read($query);   #here it checks if email has been already used by other people
                if ($result){
                    $this->error = $this->error . "this email address has been already used<br>";
                }
            }
			
			if($key == "first_name")  #first_name should start with letter and should only contain uppercae, lowercase letters, numbers and underscore
			{

    			if (!preg_match("/^[a-zA-Z][a-zA-Z0-9_]+$/",$value) || strstr($value," ")) {
        
 					$this->error = $this->error . "invalid first name<br>";
    			}
 
			}

			if($key == "last_name")  #last_name should start with letter and should only contain uppercae, lowercase letters, numbers and underscore
			{
				
    			if (!preg_match("/^[a-zA-Z][a-zA-Z0-9_]+$/",$value) || strstr($value," ")) {
        
 					$this->error = $this->error . "invalid last name<br>";
    			}

			}
			if ($key=="password"){   #password should contain at least 8 characters and must contain uppercase, lowercase letters and numbers
                if (preg_match("/.{8,}/",$value) && preg_match("/[A-Z]/",$value) && preg_match("/[a-z]/",$value) && preg_match("/[0-9]/",$value)){

                }
                else{
                    $this->error= $this->error . "invalid password Password should contain at least 8 characters, capital and small letters and number!<br>";
                }
            }
            if ($key=="password2"){   #password retyping should be correct
                if ($value!=$data["password"]){
                    $this->error= $this->error . "password is not the same!<br>";
                }
            }
  
			
		}

		$DB = new Database();

		//check tag name
		$data['tag_name'] = strtolower($data['first_name'] . $data['last_name']);    

		$sql = "select id from users where tag_name = '$data[tag_name]' limit 1"; #here tag name is checked
		$check = $DB->read($sql);
		while(is_array($check)){        #if any other users have the same tag name we change it until there is no same tag name

			$data['tag_name'] = strtolower($data['first_name'] . $data['last_name']) . rand(0,9999);
			$sql = "select id from users where tag_name = '$data[tag_name]' limit 1";
			$check = $DB->read($sql);
		}
		$data['userid'] = $this->create_userid();
		//check userid
		$sql = "select id from users where userid = '$data[userid]' limit 1";      #here userid is checked
		$check = $DB->read($sql);
		while(is_array($check)){       #if any other users have the same userid we change it until there is no same userid

			$data['userid'] = $this->create_userid();
			$sql = "select id from users where userid = '$data[userid]' limit 1";
			$check = $DB->read($sql);
		}
 

		if($this->error == "")
		{

			//no error
			$this->create_user($data);
		}else
		{
			return $this->error;
		}
	}

	public function create_user($data)     #here user is created in database
	{

		$first_name = ucfirst($data['first_name']);
		$last_name = ucfirst($data['last_name']);
		$gender = $data['gender'];
		$email = $data['email'];
		$password = $data['password'];
		$userid = $data['userid'];
		$tag_name = $data['tag_name'];
		$date = date("Y-m-d H:i:s");    
		$type = "profile";

		$key=$this->key_creator($userid);  #secret key is created 
		$passw=$this->encrypt($password,$key); #here password is encrypted using Vigenere cipher
		$passw = hash("sha1", $passw);  #password is saved in hashed form to provide security
		

		
		//create these
		$url_address = strtolower($first_name) . "." . strtolower($last_name);

		$query = "insert into users 
		(type,userid,first_name,last_name,gender,email,password,url_address,tag_name,date) 
		values 
		('$type','$userid','$first_name','$last_name','$gender','$email','$passw','$url_address','$tag_name','$date')";


		$DB = new Database();
		$DB->save($query);
	}
 
	private function create_userid()      #this function creates random userid
	{

		$length = rand(9,19);     #userid should contain between 8 and 19 characters
		$number = "";
		for ($i=0; $i < $length; $i++) { 
			# code...
			$new_rand = rand(0,9);      #here random numbers are generated for userid

			$number = $number . $new_rand;
		}

		return $number;
	}
}