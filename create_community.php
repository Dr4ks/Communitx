<?php 

	include("classes/autoload.php");     #this section is for creating a new community 

	$login = new Login();
	$_SESSION['communitx_userid'] = isset($_SESSION['communitx_userid']) ? $_SESSION['communitx_userid'] : 0;
	
	$user_data = $login->check_login($_SESSION['communitx_userid'],false);
 
 	$USER = $user_data;
 	
 	if(isset($URL[1]) && is_numeric($URL[1])){    #first url is checked whether it is set or not

	 	$profile = new Profile();
	 	$profile_data = $profile->get_profile($URL[1]);

	 	if(is_array($profile_data)){
	 		$user_data = $profile_data[0];
	 	}

 	}
 	

	$community_name = "";
 
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{


		$community = new community();
		$result = $community->evaluate($_POST);
		
		if($result != "")
		{

			echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
			echo "<br>The following errors occured:<br><br>";
			echo $result;
			echo "</div>";
		}else
		{

			header("Location:" . ROOT ."profile/".$_SESSION['communitx_userid']. "/communities");
			die;
		}
 

		$community_name = $_POST['community_name'];
		

	}


	

?>

<html> 

	<head>
		
		<title>Communitx | Create community</title>
	</head>

	<style>
		
		#bar{
			height:100px;
			width: 100%;
			background-color: rgb(59,89,152);
			color: #d9dfeb;
			padding: 4px;
		}

		#signup_button{

			background-color: #42b72a;
			width: 70px;
			text-align: center;
			padding:4px;
			border-radius: 4px;
			float:right;
		}

		#bar2{

			background-color: white;
			width:800px;
			margin:auto;
			margin-top: 50px;
			padding:10px;
			padding-top: 50px;
			text-align: center;
			font-weight: bold;

		}

		#text{

			height: 40px;
			width: 300px;
			border-radius: 4px;
			border:solid 1px #ccc;
			padding: 4px;
			font-size: 14px;
		}

		#button{

			width: 300px;
			height: 40px;
			border-radius: 4px;
			font-weight: bold;
			border:none;
			background-color: rgb(59,89,152);
			color: white;
		}

			#blue_bar{

			height: 50px;
			background-color: #405d9b;
			color: #d9dfeb;

		}

		#search_box{

			width: 400px;
			height: 20px;
			border-radius: 5px;
			border:none;
			padding: 4px;
			font-size: 14px;
			background-image: url(search.png);
			background-repeat: no-repeat;
			background-position: right;

		}

		#textbox{

			width: 100%;
			height: 20px;
			border-radius: 5px;
			border:none;
			padding: 4px;
			font-size: 14px;
			border: solid thin grey;
			margin:10px;
 
		}

		#profile_pic{

			width: 150px;
			margin-top: -300px;
			border-radius: 50%;
			border:solid 2px white;
		}

		#menu_buttons{

			width: 100px;
			display: inline-block;
			margin:2px;
		}

		#friends_img{

			width: 75px;
			float: left;
			margin:8px;

		}

		#friends_bar{

			background-color: white;
			min-height: 400px;
			margin-top: 20px;
			color: #aaa;
			padding: 8px;
		}

		#friends{

		 	clear: both;
		 	font-size: 12px;
		 	font-weight: bold;
		 	color: #405d9b;
		}

		textarea{

			width: 100%;
			border:none;
			font-family: tahoma;
			font-size: 14px;
			height: 60px;

		}

		#post_button{

			float: right;
			background-color: #405d9b;
			border:none;
			color: white;
			padding: 4px;
			font-size: 14px;
			border-radius: 2px;
			width: 50px;
			min-width: 50px;
			cursor: pointer;
		}
 
 		#post_bar{

 			margin-top: 20px;
 			background-color: white;
 			padding: 10px;
 		}

 		#post{

 			padding: 4px;
 			font-size: 13px;
 			display: flex;
 			margin-bottom: 20px;
 		}


	</style>

	<body style="font-family: tahoma;background-color: #e9ebee;">
		
		<?php include("header.php"); ?>

		<div id="bar2">
			
			Create community<br><br>

			<form method="post" action="">

				<input value="<?php echo $community_name ?>" name="community_name" type="text" id="text" placeholder="Community Name" autofocus required><br><br>
 
 				<select id="text" name="community_type">
 					<option>Public</option>       <!-- here user chooses community type public or private -->
 					<option>Private</option>
 				</select><br>
  				<br>
				<input type="submit" id="button" value="Create">
				<br><br><br>

			</form>

		</div>

	</body>


</html>