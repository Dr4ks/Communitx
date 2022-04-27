<!--top bar-->
<?php    #this is top bar which is displayed in all pages. It contains Communitx website name and search bar 

	$corner_image = "images/user_male.jpg";
	if(isset($USER)){
		
		if(file_exists($USER['profile_image']))
		{
			$image_class = new Image();
			$corner_image = $image_class->get_thumb_profile($USER['profile_image']);
		}else{

			if($USER['gender'] == "Female"){

				$corner_image = "images/user_female.jpg";
			}
		}
	}
?>

<div id="blue_bar">
	<form method="get" action="<?=ROOT?>search">
		<div style="width: 800px;margin:auto;font-size: 30px;">
			
			<a href="<?=ROOT?>home" style="color: white;">Communitx</a> 
			&nbsp &nbsp <input type="text" id="search_box" name="find" placeholder="Search for people/communitites" />

			<?php if(isset($USER)): ?>
				<a href="<?=ROOT?>profile">
				<img src="<?php echo ROOT . $corner_image ?>" style="width: 50px;float: right;">
				</a>
				<a href="<?=ROOT?>logout">
				<span style="font-size:14px;float: right;margin:10px;color:white;">Logout</span>
				</a>



				

			<?php else: ?>
				<a href="<?=ROOT?>login">
				<span style="font-size:13px;float: right;margin:10px;color:white;">Login</span>
				</a>
			<?php endif; ?>


		</div>
	</form>
</div>