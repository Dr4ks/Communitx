<div style="min-height: 400px;width:100%;background-color: white;text-align: center;">
                 <!-- this part displays all the communities user participate in. Here community name and other important details aboput the communities are shown  -->
	<br>
	<a href="<?=ROOT?>create_community">
		<input id="post_button" type="button" value="Create community" style="float:none;margin-right:10px;background-color: #1b9186;width:auto;">
	</a>

	<div style="padding: 20px;">
	<?php
 
		$image_class = new Image();
		$community_class = new community();
		$user_class = new User();

		$communities = $community_class->get_my_communities($user_data['userid']);

		if(is_array($communities)){

			foreach ($communities as $community) {
				# code...
				$FRIEND_ROW = $user_class->get_user($community['userid']);
				include("community.inc.php");
			}

		}else{

			echo "No communities were found!";
		}


	?>

	</div>
</div>