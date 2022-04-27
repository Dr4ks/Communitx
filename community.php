<?php

	include("classes/autoload.php");  #this part is for communities. Here all the information about specific community is displayed on the screen

	$login = new Login();
	$_SESSION['communitx_userid'] = isset($_SESSION['communitx_userid']) ? $_SESSION['communitx_userid'] : 0;
	
	$USER = $login->check_login($_SESSION['communitx_userid'],false);
 
 	$community_data = array();
 	
 	if(isset($URL[1]) && is_numeric($URL[1])){     #here url is checked if it is set and numeric or not

	 	$community = new community();
	 	$g_data = $community->get_community($URL[1]);

	 	if(is_array($g_data)){
	 		$community_data = $g_data[0];
	 	}

 	}
 	
	//posting starts here
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{

		include("change_image.php");
		
		if(isset($_POST['first_name'])){

			if(community_access($_SESSION['communitx_userid'],$community_data,'admin')){     #here community access for user is checked wheter user has admin privileges or not
				$settings_class = new Settings();
				$settings_class->save_settings($_POST,$community_data['userid']);
			}

			header("Location: " . ROOT . "community/" . $community_data['userid'] . "/settings");
			die;

		}elseif(isset($_POST['post'])){

			$post = new Post();
			$id = $_SESSION['communitx_userid'];
			$owner = $community_data['userid'];
			$result = $post->create_post($id, $_POST,$_FILES,$owner);
			
			if($result == "")
			{
				
				header("Location: " . ROOT . "community/" . $community_data['userid']);
				die;
			}else
			{

				echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
				echo "<br>The following errors occured:<br><br>";
				echo $result;
				echo "</div>";
			}
		}
			
	}

	if(count($community_data) > 0){

		//collect posts
		$post = new Post();
		$id = $community_data['userid'];
		
		$posts = $post->get_posts($id,'community');

		//collect friends
		$user = new User();
	 	
		//$friends = $user->get_following($community_data['userid'],"user");

		$image_class = new Image();

		//check if this is from a notification
		if(isset($URL[3])){
			notification_seen($URL[3]);
		}

	}



?>

<!DOCTYPE html>
	<html>
	<head>
		<title>community | Communitx</title>
	</head>

	<style type="text/css">
		
		#blue_bar{

			height: 50px;
			width: 100%;
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

	<body style="font-family: tahoma; background-color: #d0d8e4;">

		<br>
		<?php include("header.php"); ?>
 
 		<?php if(count($community_data) > 0): ?>
		
		
		

		<!--change cover image area-->
 		<div id="change_cover_image" style="display:none;position:absolute;width: 100%;height: 100%;background-color: #000000aa;">
 			<div style="max-width:600px;margin:auto;min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">
 					
 					<form method="post" action="<?=ROOT?>community/<?=$community_data['userid']?>/cover"  enctype="multipart/form-data">
	 					<div style="border:solid thin #aaa; padding: 10px;background-color: white;">

	 						<input type="file" name="file"><br>
	 						<input id="post_button" type="submit" style="width:120px;" value="Change">
	 						<br>
							<div style="text-align: center;">
								<br><br>
							<?php

 	 							echo "<img src='" . ROOT . "$community_data[cover_image]' style='max-width:500px;' >";
								 
	 						?>
							</div>
	 					</div>
  					</form>

 				</div>
 		</div>

		<!--cover area-->
		<div style="width: 800px;margin:auto;min-height: 400px;">
			
			<div style="background-color: white;text-align: center;color: #405d9b">

					<?php 

						$image = "images/cover_image.jpg";
						if(file_exists($community_data['cover_image']))
						{
							$image = $image_class->get_thumb_cover($community_data['cover_image']);
						}
					?>

				<img src="<?php echo ROOT . $image ?>" style="width:100%;">


				<span style="font-size: 12px;">
					
					<?php if(i_own_content($community_data)):?>
					
						<a onclick="show_change_cover_image(event)" style="text-decoration: none;color:#f0f;" href="<?=ROOT?>change_profile_image/cover">Change Background</a>
					
					<?php endif; ?>

				</span>
				<br>
					<div style="font-size: 20px;color: black;">
						<a href="<?=ROOT?>community/<?php echo $community_data['userid'] ?>">
							<?php echo $community_data['first_name'] ?><br>
							<span style="font-size: 12px;">[<?php echo $community_data['community_type'] ?> community]</span>
 						</a>
 					 
						<br>
 							<?php if(!community_access($_SESSION['communitx_userid'],$community_data,'member')):?>
								<?php if(!community_access($_SESSION['communitx_userid'],$community_data,'request')):?>
	  							
		  							<a href="<?=ROOT?>join/<?=$community_data['userid']?>">
										<input id="post_button" type="button" value="Join community" style="margin-right:10px;background-color: #821b91;width:auto;">
									</a>
								<?php else: ?>

									<input id="post_button" type="button" value="Request sent" style="margin-right:10px;background-color: #821b91;width:auto;">
								<?php endif; ?>
							<?php endif; ?>

							<?php if(community_access($_SESSION['communitx_userid'],$community_data,'member')):?>
							<a href="<?=ROOT?>community/<?php echo $community_data['userid'] ?>/invite">
								<input id="post_button" type="button" value="Invite" style="margin-right:10px;background-color: #1b9186;width:auto;">
							</a>
							<?php endif; ?>
							
 
					</div>
				<br>
				<br>

					<?php 
						$members_count = $community->get_members($community_data['userid']);
						$members_str = "";
						if(is_array($members_count)){
							$members_str = "(".count($members_count).")";
						}
					?>

				<a href="<?=ROOT?>community/<?php echo $community_data['userid'] ?>"><div id="menu_buttons">Discussion</div></a>
				<a href="<?=ROOT?>community/<?php echo $community_data['userid'] ?>/about"><div id="menu_buttons">About</div></a>
				<a href="<?=ROOT?>community/<?php echo $community_data['userid'] ?>/members"><div id="menu_buttons">Members <?=$members_str?></div></a>
				<a href="<?=ROOT?>community/<?php echo $community_data['userid'] ?>/photos"><div id="menu_buttons">Photos</div></a>
				
				<?php if(community_access($_SESSION['communitx_userid'],$community_data,'moderator')):?>
					<?php 
						$requests_count = $community->get_requests($community_data['userid']);
						$requests_str = "";
						if(is_array($requests_count)){
							$requests_str = "(".count($requests_count).")";
						}
					?>

					<a href="<?=ROOT?>community/<?php echo $community_data['userid'] ?>/requests"><div id="menu_buttons">Requests <?=$requests_str?></div></a>
				<?php endif;?>

				<?php 
					if(community_access($_SESSION['communitx_userid'],$community_data,'admin')){
						
 						echo '<a href="'.ROOT. 'community/'.$community_data['userid'].'/settings"><div id="menu_buttons">Settings</div></a>';
					}
				?>
			</div>

			<!--below cover area-->
	 
	 		<?php 

	 			$section = "default";

	 			if(isset($URL[2])){

	 				$section = $URL[2];
	 			}

	 			if($community_data['community_type'] == 'private' && !community_access($_SESSION['communitx_userid'],$community_data,'member')){
	 				$section = "denied";
	 			}

	 			if($section == "default" || $section == "cover"){

	 				include("community_content_default.php");
	 			 
	 			}elseif($section == "requests"){
	 				
	 				include("community_content_requests.php");
				}elseif($section == "invite"){
	 				
	 				include("community_content_invite.php");
				}elseif($section == "invited"){
	 				
	 				include("community_content_invited.php");

	 			}elseif($section == "members"){
	 				
	 				include("community_content_members.php");
	 			
	 			}elseif($section == "about"){

	 				include("community_content_about.php");

	 			}elseif($section == "settings"){

	 				include("community_content_settings.php");

	 			}elseif($section == "photos"){

	 				include("community_content_photos.php");
	 			}elseif($section == "communities"){

	 				include("community_content_communities.php");
	 			}elseif($section == "denied"){

	 				include("community_content_denied.php");
	 			}



	 		?>

		</div>
	<?php else: ?>

		<div style="background-color: grey;color: white;padding: 1em;text-align: center;margin:1em;">That community was not found!
			<br><br>
			<a href="<?=ROOT?>profile/<?=$_SESSION['communitx_userid']?>/communities">
				Go to communities
			</a>
		</div>
	
	<?php endif; ?>

	</body>
</html>

<script type="text/javascript">
	
	function show_change_profile_image(event){

		event.preventDefault();
		var profile_image = document.getElementById("change_profile_image");
		profile_image.style.display = "block";
	}


	function hide_change_profile_image(){

		var profile_image = document.getElementById("change_profile_image");
		profile_image.style.display = "none";
	}

	
	function show_change_cover_image(event){

		event.preventDefault();
		var cover_image = document.getElementById("change_cover_image");
		cover_image.style.display = "block";
	}


	function hide_change_cover_image(){

		var cover_image = document.getElementById("change_cover_image");
		cover_image.style.display = "none";
	}


	window.onkeydown = function(key){

		if(key.keyCode == 27){

			//esc key was pressed
			hide_change_profile_image();
			hide_change_cover_image();
		}
	}

	
</script>