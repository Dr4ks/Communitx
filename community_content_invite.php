<div style="min-height: 400px;width:100%;background-color: white;text-align: center;">
	<div style="padding: 20px;">
	<?php if(community_access($_SESSION['communitx_userid'],$community_data,'member')):?>
	<?php
 
		$image_class = new Image();
		$post_class = new Post();
		$user_class = new User();

		$followers = $community->get_invites($community_data['userid'],$USER['userid'],"user");

		if(is_array($followers)){

			foreach ($followers as $follower) {
				# code...
				$FRIEND_ROW = $user_class->get_user($follower['userid']);
				include("user_community_invite.php");
			}

		}else{

			echo "No followers to invite were found!";
		}


	?>
	<?php else: ?>
		You must be a member to invite others
	<?php endif; ?>
	</div>
</div>