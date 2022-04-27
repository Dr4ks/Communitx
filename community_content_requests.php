<div style="min-height: 400px;width:100%;background-color: white;text-align: center;">
	<div style="padding: 20px;">   <!--this file contains all the requests made in specific community -->

	<?php if(community_access($_SESSION['communitx_userid'],$community_data,'moderator')):?>
	<?php
 
		$image_class = new Image();
		//$post_class = new Post();
		$user_class = new User();

		$requests = $community->get_requests($community_data['userid']);

		if(is_array($requests)){

			foreach ($requests as $request) {
				# code...
				$FRIEND_ROW = $user_class->get_user($request['userid']);
				include("user_community_request.inc.php");
			}

		}else{

			echo "No requests were found!";
		}

	?>
	<?php else: ?>
		You dont have access to this content!
	<?php endif; ?>
	</div>
</div>