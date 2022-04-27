<!DOCTYPE html>
<html>
    <head>

    </head>
    <style type="text/css">
    #blue_bar{

        height: 50px;
        width: 100%;
        background-color: #405d9b;
        color: #d9dfeb;

    }

    #search_box{

        width: 400px;
        height: 20px;
        border-radius: 5px;
        border:none;
        padding: 4px;
        font-size: 14px;
        background-image: url(search.png);
        background-repeat: no-repeat;
        background-position: right;

    }
    #login_button{

        background-color: #42b72a;
        width: 70px;
        height: 20px;
        text-align: center;
        padding:4px;
        border-radius: 4px;
        float:right;
        font-size: 18px;
        margin-top: 4px;
    }

    #bar2{

        background-color: white;
        width:800px;
        margin:auto;
        margin-top: 50px;
        padding:10px;
        padding-top: 50px;
        text-align: center;
        font-weight: bold;

    }
    </style>
    <body>
    <div id="blue_bar">
	<form method="get" action="<?=ROOT?>search">
		<div style="width: 800px;margin:auto;font-size: 30px;">
			<a href="<?=ROOT?>home" style="color: white;">Communitx</a> 
			&nbsp &nbsp <input type="text" id="search_box" name="find" placeholder="Search for people/communitites" />
            <a href="<?=ROOT?>login">
            <span id="login_button">Login</span>
            </a>
		</div>
	</form>
</div>
        <div id="bar2">
        <div style='text-align:center;font-size: 40px;color:black;'>
You have been successfully registered. Redirecting to login page...</div>
        </div>  
        <?php 
            header("refresh: 4; login");

        ?>
    </body>
</html>

