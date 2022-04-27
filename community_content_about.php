<div style="min-height: 400px;width:100%;background-color: white;text-align: center;">
	<div style="padding: 20px;max-width:350px;display: inline-block;">
		<form method="post" enctype="multipart/form-data">   <!-- here it contains all the information about community which admin has entered -->

  						
			<?php
		 
				$settings_class = new Settings();

				$settings = $settings_class->get_settings($community_data['userid']);

				if(is_array($settings)){
 
					echo "<br>About the community:<br>
							<div id='textbox' style='height:200px;border:none;' >".htmlspecialchars($settings['about'])."</div>
						";

					 
				}
				
			?>

		</form>
	</div>
</div>