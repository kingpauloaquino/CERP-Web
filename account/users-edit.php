<?php
  /*
   * Module: Users - Edit
  */
  $capability_key = 'edit_user';
  require('header.php');
	
	if($_POST['action'] == 'edit_user') {
		$args = array('variables' => $_POST['user'], 'conditions' => 'id='.$_POST['uid']); 
		$num_of_records = $Posts->EditUser($args);
		redirect_to($Capabilities->All['show_user']['url'].'?uid='.$_POST['uid']);		
	} 
  
  if(isset($_REQUEST['uid'])) {
  	$user = $DB->Find('users', array(
			'columns' 		=> 'users.id, users.employee_id, users.first_name, users.last_name, users.email, users.position, 
												users.status AS status_id, users.role AS role_id, lookups.description AS status, roles.name as role', 
			'joins'				=> 'LEFT OUTER JOIN lookups on users.status = lookups.id 
												LEFT OUTER JOIN roles on users.role = roles.id',
  	  'conditions' 	=> 'users.id = '.$_REQUEST['uid']
  	  )
		);	
  }
	
	$roles = $DB->Get('roles', array('columns' => 'id, name'));
	$statuss = $DB->Get('lookups', array('columns' => 'id, description', 'conditions' => 'parent = "'.get_lookup_code('user_status').'"'));
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['show_user']['url'].'?uid='.$_REQUEST['uid'].'" class="nav">'.$Capabilities->All['show_user']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
				<div class="form-container">
					<h3 class="form-title">Basic Information</h3>
					<input type="hidden" name="action" value="edit_user">
					<input type="hidden" name="uid" value="<?php echo $_REQUEST['uid'] ?>">
					<span class="notice">
	<!--           <p class="info"><strong>Notice!</strong> Material codes should be unique.</p> -->
	        </span>
	
					<div class="field">
	          <label class="label">Employee ID:</label>
	          <div class="input">
	            <input type="text" id="user[employee_id]" name="user[employee_id]" value="<?php echo $user['employee_id'] ?>" class="magenta" readonly="readonly" />
	          </div>
	          <div class="clear"></div>
	        </div>	        
	        
					<div class="field">
	          <label class="label">First Name:</label>
	          <div class="input">
	            <input type="text" id="user[first_name]" name="user[first_name]" value="<?php echo $user['first_name'] ?>" autocomplete="off" />
	          </div>
	          <div class="clear"></div>
	        </div>	        
	        
					<div class="field">
	          <label class="label">Last Name:</label>
	          <div class="input">
	            <input type="text" id="user[last_name]" name="user[last_name]" value="<?php echo $user['last_name'] ?>" autocomplete="off" />
	          </div>
	          <div class="clear"></div>
	        </div>	        
	        
					<div class="field">
	          <label class="label">Email:</label>
	          <div class="input">
	            <input type="text" id="user[email]" name="user[email]" value="<?php echo $user['email'] ?>" autocomplete="off" />
	          </div>
	          <div class="clear"></div>
	        </div>
	        
	        <div class="field">
	          <label class="label">Position:</label>
	          <div class="input">
	            <input type="text" id="user[position]" name="user[position]" value="<?php echo $user['position'] ?>" autocomplete="off" />
	          </div>
	          <div class="clear"></div>
	        </div>
	        
	        <div class="field">
	          <label class="label">Status:</label>
	          <div class="input">
	            <?php select_query_tag($statuss, 'id', 'description', $user['status_id'], 'user[status]', 'user[status]', '', 'text w250'); ?>
	          </div>
	          <div class="clear"></div>
	        </div>

					<br/>
					<h3 class="form-title">Security</h3>
					
					<div class="field">
	          <label class="label">Role:</label>
	          <div class="input">
	            <?php select_query_tag($roles, 'id', 'name', $user['role_id'], 'user[role]', 'user[role]', '', 'text w250'); ?>
	          </div>
	          <div class="clear"></div>
	        </div>
	        
	        <div class="field">
	          <label class="label">New Password:</label>
	          <div class="input">
	            <input type="password" id="user[password]" name="user[password]" autocomplete="off" />
	          </div>
	          <div class="clear"></div>
	        </div>
	        
	        <div class="field">
	          <label class="label">Confirm Password:</label>
	          <div class="input">
	            <input type="password" id="password2" name="password2" autocomplete="off" />
	          </div>
	          <div class="clear"></div>
	        </div>
	        
	        <br/>
	        <div class="field">
            <label class="label"></label>
            <div class="input">
              <button class="btn">Update</button>
              <button class="btn" onclick="return cancel_btn();">Cancel</button>
            </div>
            <div class="clear"></div>
          </div>
				</form>
		</div>
	</div>

<?php require('footer.php'); ?>