<div style="min-height: 400px;width:100%;background-color: white;text-align: center;">
	<div style="padding: 20px;">   <!-- this is for inviting users to community -->
	
	<?php
 
		$image_class = new Image();
		$post_class = new Post();
		$user_class = new User();

		$invites = $community->get_invited($community_data['userid']);

		if(is_array($invites)){

			foreach ($invites as $invite) {
				# code...
				$INVITER_ROW = $user_class->get_user($invite['inviter']);
				$FRIEND_ROW = $user_class->get_user($invite['userid']);
				include("user_community_request.inc.php");
			}

		}else{

			echo "No invitations were found!";
		}


	?>
 
	</div>
</div>