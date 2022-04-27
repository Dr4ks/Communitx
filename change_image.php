<?php
     #this file is for changing images such as profile image,cover image or post images
if(isset($URL[2]) && isset($URL[2]) == "cover" && count($_FILES) > 0 && isset($community_data))
{
	$URL[1] = "cover";
}

if(isset($community_data)){

	$user_data = $community_data;
}

if(isset($URL[1]) && ($URL[1] == "profile" || $URL[1] == "cover"))  
{

		if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != "")
		{
 
			if($_FILES['file']['type'] == "image/jpeg")
			{

				$allowed_size = (1024 * 1024) * 7;
				if($_FILES['file']['size'] < $allowed_size)
				{
					//everything is fine
					$folder = "uploads/" . $user_data['userid'] . "/";

					//create folder
					if(!file_exists($folder))
					{

						mkdir($folder,0777,true);    #here a new directory is created for saving images
					}

					$image = new Image();

					$filename = $folder . $image->generate_filename(15) . ".jpg";  #here filename is genereated randomly and then images are uploaded to this file
					move_uploaded_file($_FILES['file']['tmp_name'], $filename);     
 
					$change = "profile";

						//check for mode
						if(isset($URL[1]))
						{

							$change = $URL[1];
						}

					

					if($change == "cover")
					{
						if(file_exists($user_data['cover_image']))
						{
							//unlink($user_data['cover_image']);
						}
						$image->resize_image($filename,$filename,1500,1500);
					}else
					{
						if(file_exists($user_data['profile_image']))
						{
							//unlink($user_data['profile_image']);
						}
						$image->resize_image($filename,$filename,1500,1500);   #here image is resized according to standards
					}

					if(file_exists($filename))
					{

						$userid = $user_data['userid'];
						if(isset($community_data)){
							$userid = $community_data['userid'];
						}

						if($change == "cover")
						{
							$query = "update users set cover_image = '$filename' where userid = '$userid' limit 1";
							$_POST['is_cover_image'] = 1;

						}else
						{
							$query = "update users set profile_image = '$filename' where userid = '$userid' limit 1";
							$_POST['is_profile_image'] = 1;

						}

						$DB = new Database();
						$DB->save($query);


						//create a post
						$post = new Post();

						if(isset($community_data)){
							$post->create_post($userid, $_POST,$filename,$community_data['userid']);
							header(("Location: ".ROOT."community/". $community_data['userid']));
						}else{
							$post->create_post($userid, $_POST,$filename);
							header(("Location: ".ROOT."profile"));
						}
						
						die;
					}


				}else
				{

					echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
					echo "<br>The following errors occured:<br><br>";
					echo "Only images of size 3Mb or lower are allowed!";
					echo "</div>";

				}
			}else
			{

				echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
				echo "<br>The following errors occured:<br><br>";
				echo "Only images of Jpeg type are allowed!";
				echo "</div>";

			}

		}else
		{
			echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
			echo "<br>The following errors occured:<br><br>";
			echo "please add a valid image!";
			echo "</div>";
		}

}
