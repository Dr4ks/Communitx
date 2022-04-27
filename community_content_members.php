<div style="min-height: 400px;width:100%;background-color: white;text-align: center;">
	<div style="padding: 20px;">
	<?php if(community_access($_SESSION['communitx_userid'],$community_data,'member')):?>    <!-- here all the gorup members are shown in specific format-->
	<?php
 
		$image_class = new Image();
		//$post_class = new Post();
		$user_class = new User();

		if(isset($_GET['remove_comfirmed']) && (community_access($_SESSION['communitx_userid'],$community_data,'admin'))){ #if someone wants to remove user from community first that person's privileges are checked

			$community->remove_member($community_data['userid'],$_GET['remove_comfirmed']);

			echo "This user was successfuly removed from the community!!<br><br>";
			$FRIEND_ROW = $user_class->get_user($_GET['remove_comfirmed']);    #if user is admin then removal is confirmed
			include("user.php");

			echo '<br><br>
			<a href="'.ROOT.'community/'.$community_data['userid'].'/members">
				<input id="post_button" type="button" value="Back" style="font-size:11px;margin-right:10px;background-color: #1b9186;width:auto;">
			</a>
			';
		}else
		if(isset($_GET['edit_access']) && (community_access($_SESSION['communitx_userid'],$community_data,'admin'))){

			if(isset($_POST['role']) && isset($_POST['userid'])){

				$community->edit_member_access($community_data['userid'],$_GET['edit_access'],$_POST['role']);
			}

				echo "<form method='post'>
				Change user access<br><br>
				<div style='background-color:orange;color:white;padding:1em;text-align:center;'>
				Warning! giving users admin access also gives them the power to remove you as admin</div>
				";
				$FRIEND_ROW = $user_class->get_user($_GET['edit_access']);
				include("user.php");

				$role = "Unknown";
				$role = $community->get_member_role($community_data['userid'],$_GET['edit_access']);
				echo '<br><br>
					<select name="role" style="padding:5px;width:200px;">
						<option>'.$role.'</option>
						<option>member</option>
						<option>moderator</option>
						<option>admin</option>
					</select>
					<input type="hidden" name="userid" value="'.htmlspecialchars($_GET['edit_access']).'">
				<br>
				
	 			<input id="post_button" type="submit" value="Save" style="font-size:11px;margin-right:10px;background-color: #91261b;width:auto;">
	 			<a href="'.ROOT.'community/'.$community_data['userid'].'/members">
					<input id="post_button" type="button" value="Cancel" style="float:left;font-size:11px;margin-right:10px;background-color: #1b9186;width:auto;">
				</a>
				</form>
				';
 
		}else
		if(isset($_GET['remove']) && (community_access($_SESSION['communitx_userid'],$community_data,'admin'))){

			echo "Are you sure you want to remove this user from the community??<br><br>";
			$FRIEND_ROW = $user_class->get_user($_GET['remove']);
			include("user.php");

			echo '<br><br>

			<a href="'.ROOT.'community/'.$community_data['userid'].'/members?remove_comfirmed='.$FRIEND_ROW['userid'].'">
				<input id="post_button" type="button" value="Remove" style="font-size:11px;margin-right:10px;background-color: #91261b;width:auto;">
			</a>
			<a href="'.ROOT.'community/'.$community_data['userid'].'/members">
				<input id="post_button" type="button" value="Cancel" style="float:left;font-size:11px;margin-right:10px;background-color: #1b9186;width:auto;">
			</a>
			';

		}else{

			$members = $community->get_members($community_data['userid']);       

			if(is_array($members)){

				foreach ($members as $member) {
					# code...
					$FRIEND_ROW = $user_class->get_user($member['userid']);
					include("user_community_member.inc.php");
				}

			}else{

				echo "This community has no members";
			}
		}

	?>
	<?php else: ?>
		You dont have access to this content!
	<?php endif; ?>
	</div>
</div>