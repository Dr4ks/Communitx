<?php 

class Database     #This is Database class which we use for connecting to, reading from database and saving data
{

	private $host = "localhost:3307";
	private $username = "root";
	private $password = "";      #these are all necessary datas to connect to database
	private $db = "communitx";
	
	function connect()         #this function is for connecting to mysql database
	{

		$connection = mysqli_connect($this->host,$this->username,$this->password,$this->db);
		return $connection;
	}

	function read($query)         #this function sends query to database and reading from database
	{
		$conn = $this->connect();
		$result = mysqli_query($conn,$query);

		if(!$result)         #it is checked whether query was successful or not
		{
			return false;
		}
		else
		{
			$data = false;
			while($row = mysqli_fetch_assoc($result))
			{

				$data[] = $row;    #here it reads all the information necessary from database and writes it to data variable in the form of associative array

			}

			return $data;
		}
	}

	function save($query)         #this function is for sending query. It doesn't read from database
	{
		$conn = $this->connect();
		$result = mysqli_query($conn,$query);     #here query is sent to mysql database

		if(!$result)
		{
			return false;
		}else
		{
			return true;
		}
	}

}
