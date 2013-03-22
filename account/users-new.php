<?php
  /*
   * Module: Users - New
  */
  $capability_key = 'add_user';
  require('header.php');
	
	if($_POST['action'] == 'add_user') {
		$id = $Posts->AddUser($_POST['user']);
		$x = $Capabilities->All['show_user']['url'].'?uid='.$id;
		if(isset($id)){ redirect_to($Capabilities->All['show_user']['url'].'?uid='.$id); }
	}
	
	$roles = $DB->Get('roles', array('columns' => 'id, name'));
	$statuss = $DB->Get('lookups', array('columns' => 'id, description', 'conditions' => 'parent = "'.get_lookup_code('user_status').'"'));
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>

				<div class="clear"></div>
      </h2>
		</div>
		
		<div id="content">
			<form action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
				<div class="form-container">
					<h3 class="form-title">Basic Information</h3>
					<input type="hidden" name="action" value="add_user">
					<span class="notice">
	<!--           <p class="info"><strong>Notice!</strong> Material codes should be unique.</p> -->
	        </span>
	
					<div class="field">
	          <label class="label">Employee ID:</label>
	          <div class="input">
	            <input type="text" id="user[employee_id]" name="user[employee_id]" value="<?php echo $user['employee_id'] ?>" class="magenta" />
	          </div>
	          <div class="clear"></div>
	        </div>	 
	        
	        <div class="field">
	          <label class="label">First Name:</label>
	          <div class="input">
	            <input type="text" id="user[first_name]" name="user[first_name]" autocomplete="off" />
	          </div>
	          <div class="clear"></div>
	        </div>	        
	        
					<div class="field">
	          <label class="label">Last Name:</label>
	          <div class="input">
	            <input type="text" id="user[last_name]" name="user[last_name]" autocomplete="off" />
	          </div>
	          <div class="clear"></div>
	        </div>	        
	        
					<div class="field">
	          <label class="label">Email:</label>
	          <div class="input">
	            <input type="text" id="user[email]" name="user[email]" autocomplete="off" />
	          </div>
	          <div class="clear"></div>
	        </div>
	        
	        <div class="field">
	          <label class="label">Position:</label>
	          <div class="input">
	            <input type="text" id="user[position]" name="user[position]" autocomplete="off" />
	          </div>
	          <div class="clear"></div>
	        </div>
	        
	        <div class="field">
	          <label class="label">Status:</label>
	          <div class="input">
	            <?php select_query_tag($statuss, 'id', 'description', '', 'user[status]', 'user[status]', '', 'text w250'); ?>
	          </div>
	          <div class="clear"></div>
	        </div>

					<br/>
					<h3 class="form-title">Security</h3>
					
					<div class="field">
	          <label class="label">Role:</label>
	          <div class="input">
	            <?php select_query_tag($roles, 'id', 'name', '', 'user[role]', 'user[role]', '', 'text w250'); ?>
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
              <button class="btn">Create</button>
              <button class="btn" onclick="return cancel_btn();">Cancel</button>
            </div>
            <div class="clear"></div>
          </div>
				</form>
			</div>
		</div>
	</div>

<?php require('footer.php'); ?>