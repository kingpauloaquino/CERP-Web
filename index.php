<?php
require('include/general.class.php');

if(!empty($Signed)) redirect_to($HostAccount);
if(isset($_GET['signout'])) {
  session_destroy();
}

if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['action'])) {
  $notice = '<p class="error">Please enter a valid employee ID and password</p>';
  $action = $_POST['action'];
  $params = $_POST['user'];
  
  if(!empty($params['employee_id']) and !empty($params['password'])) {
    $notice = '<p class="error">The employee ID or password is incorrect</p>';
    $user = $Posts->Authenticate($params);
    if(!empty($user)) {
      $_SESSION['user'] = $user;
      redirect_to($HostAccount);
    } 
  }
}
?>
<html>
    <head>
		<script type="text/javascript" src="javascripts/jquery-1.7.2.min.js"></script>
    	<style>
    	    *, html, body { margin:0;padding:0; }
    	    body { color:#555;font-style:normal;font-size:85%;font-family:'Helvetica Neue', Helvetica, Arial, Georgia, Verdana, Sans-serif; }
    	    /* Custom Element */
    	    #wrapper { position:absolute;width:680px;padding-bottom:10px; }
    	    #signin-form { margin-right:30px;padding:20px 0 40px 40px;width:250px;border-left:solid 1px #eee; }
    	    #signin-form .title { margin:0 0 20px; }
    	    #signin-form .error { margin:15px 0 0;color:#cc0000;font-size:95%; }
    	    #username, #password { margin-bottom:10px;padding:5px 7px 5px 28px;width:240px;border:solid 1px #ddd;background:url('images/ico-user.png') no-repeat 6px;color:#555;font-size:1.2em;font-weight:bold;outline:none; }
    	    #password { background:url('images/ico-lock.png') no-repeat 6px; }
    	    #signin { margin:10px 0 0;font-size:1.3em;font-weight;bold; }
  
  
    	    .left { float:left; }
    	    .right { float:right; }
    	</style>
    	<script type="text/javascript">
    	  $(function() {
    	  	$('#wrapper').css('left', ($(document).width() - $('#wrapper').width()) / 2);
    	  	//$('#wrapper').css('top', ($(document).height() - $('#wrapper').height()) / 2);
    	  });
    	</script>
    </head>
    <body>

    	<div id="wrapper">
    	    <div class="left">
	            <img src="images/cresc-logo.jpeg" alt="Cresc"/>
    	    </div>
    	    <div id="signin-form" class="right">
    	        <h3 class="title">Sign In to your account:</h3>
    	        <form action="" method="POST">
    	            <input type="hidden" name="action" value="user-signin"/>
    	            <input id="username" type="text" name="user[employee_id]" value="<?php echo $params['employee_id']; ?>"/><br/>
    	            <input id="password" type="password" name="user[password]"/><br/>
    	            <input id="signin" type="submit" value="Sign In"/>
    	            <span class="error"><?php if(isset($action)) echo $notice; ?></span>
    	         </form>
    	    </div>
    	</div>	
    </body>
</html>