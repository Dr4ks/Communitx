
<div id="friends" style="display: inline-block; width: 200px;background-color: #eee;">
	<?php 

		$image = $image_class->get_thumb_profile("images/cover_image.jpg");
	
		if(file_exists($FRIEND_ROW['cover_image']))      #if community cover image exists it is displayed on the screen in the apprpriate field
		{
			$image = $image_class->get_thumb_profile($FRIEND_ROW['cover_image']);
		}
 

	?>

	<a style="text-decoration: none;" href="<?=ROOT?>community/<?php echo $FRIEND_ROW['userid']; ?>">
 		<img id="friends_img" src="<?php echo ROOT . $image ?>">
		<br>
		<?php echo $FRIEND_ROW['first_name'] ?>
		<br><br>
		<span>'<?php echo $FRIEND_ROW['community_type'] ?>'</span>
		
 	</a>
</div>