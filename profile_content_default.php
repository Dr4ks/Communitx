		<div style="display: flex;">	
                            <!-- this part is for default profile page. This page is displayed when user logs in successfully  -->
				<!--friends area-->			
				<div style="min-height: 400px;flex:1;">
					

				</div>

				<!--posts area-->
 				<div style="min-height: 400px;flex:1000;padding: 10px;padding-right: 10px;">
 					
 					<div style="border:solid thin #aaa; padding: 10px;background-color: white;">

 						<form method="post" enctype="multipart/form-data">

	 						<textarea name="post" placeholder="Whats on your mind?"></textarea>
	 						<input type="file" name="file">
	 						<input id="post_button" type="submit" value="Post">
	 						<br>
 						</form>
 					</div>
 
	 				<!--posts-->
	 				<div id="post_bar">
	 					
 	 					 <?php 

 	 					 	if($posts)
 	 					 	{

 	 					 		foreach ($posts as $ROW) {
 	 					 			# code...

 	 					 			$user = new User();
 	 					 			$ROW_USER = $user->get_user($ROW['userid']);        #here all the posts are siplayed on the screen

 	 					 			include("post.php");
 	 					 		}
 	 					 	}
 	 			 
 	 					 	//get current url
 							$pg = pagination_link();
	 					 ?>
  	 					
  	 					<a href="<?= $pg['next_page'] ?>">
	 					 <input id="post_button" type="button" value="Next Page" style="float: right;width:100px;position:relative; top: -12px; font-size: 12px;">
	 					 </a>
	 					 <a href="<?= $pg['prev_page'] ?>">
	 					 <input id="post_button" type="button" value="Prev Page" style="float: left;width:100px;position:relative; top: -12px; font-size: 12px;">
	 					 </a>
	 				</div>

 				</div>
			</div>