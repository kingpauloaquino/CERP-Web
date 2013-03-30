<?php
  /*
   * Module: Profile - Edit
  */
  $capability_key = 'edit_profile';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		if($_POST['action'] == 'edit_profile') {
			$args = array('variables' => $_POST['user'], 'conditions' => 'id='.$Signed['id']); 
			if($_POST['user']['password'] == '') {
				unset($args['variables']['password']);
			}
			$num_of_records = $Posts->EditUser($args);
			if($_POST['role_id'] == 1) {
				$args = NULL;
				$args = array('variables' => array('role_id' => $_POST['role_id']), 'conditions' => 'user_id='.$Signed['id']); 
				$num_of_records = $Posts->EditUserRole($args);
			}		
			redirect_to($Capabilities->All['show_profile']['url']);		
		} 
	  
	  if(isset($Signed['id'])) {
	  	$user = $DB->Find('users', array(
				'columns' 		=> 'users.*, lookups.description AS status, roles.id AS role_id, roles.name as role', 
				'joins'				=> 'INNER JOIN user_roles ON user_roles.user_id = users.id
													INNER JOIN roles ON roles.id = user_roles.role_id
													LEFT OUTER JOIN lookups on users.status = lookups.id',
	  	  'conditions' 	=> 'users.id = '.$Signed['id']
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
				  echo '<a href="'.$Capabilities->All['show_profile']['url'].'" class="nav">'.$Capabilities->All['show_profile']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
				<input type="hidden" name="action" value="edit_profile">
				<input type="hidden" name="uid" value="<?php echo $Signed['id'] ?>">
					
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Employee ID:</td><td width="310"><input type="text" id="user[employee_id]" name="user[employee_id]" autocomplete="off" value="<?php echo $user['employee_id'] ?>" class="text-field magenta" /></td>
              <td width="150">Position:</td><td><input type="text" id="user[position]" name="user[position]" autocomplete="off" value="<?php echo $user['position'] ?>" class="text-field" /></td>
           </tr>
           <tr>
              <td>First Name:</td><td><input type="text" id="user[first_name]" name="user[first_name]" autocomplete="off" value="<?php echo $user['first_name'] ?>" class="text-field" /></td>
              <td>Last Name:</td><td><input type="text" id="user[last_name]" name="user[last_name]" autocomplete="off" value="<?php echo $user['last_name'] ?>" class="text-field" /></td>
           </tr>
           <tr>
              <td>Email:</td><td><input type="text" id="user[email]" name="user[email]" autocomplete="off" value="<?php echo $user['email'] ?>" class="text-field" /></td>
              <td>Status:</td><td><?php select_query_tag($statuss, 'id', 'description', $user['status'], 'user[status]', 'user[status]', '', 'width:192px;'); ?></td>
           </tr>            
           <tr>
              <td>Remarks:</td>
              <td colspan="99">
                <input type="text" id="user[description]" name="user[description]" value="<?php echo $user['description'] ?>" class="text-field" style="width:645px" />
              </td>
           </tr>
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        
        <br/>
        <h3 class="form-title">Security Information</h3>
        
        <table>
           <tr>
              <td width="150">Role:</td><td width="310">
              	<?php
              		if($user['role_id'] == 1) {
              			select_query_tag($roles, 'id', 'name', $user['role_id'], 'role_id', 'role_id', '', 'width:192px;'); 
              		} else {
            				echo '<input type="text" value="'.$user['role'].'" class="text-field" disabled/>';

              		}              		
            		?></td>
              <td width="150"></td><td></td>
           </tr>
           <tr>
              <td>Password:</td><td><input type="password" id="user[password]" name="user[password]" autocomplete="off" class="text-field" /></td>
              <td>Confirm Password:</td><td><input type="password" id="password-check" name="password-check" autocomplete="off" class="text-field" /></td>
           </tr>
        </table>
        <br/>
        <span class="notice">
	        <p class="error"><strong>Required!</strong> Password mismatch</p>
	      </span> 
        <br/>
         <div class="field-command">
       	   <div class="text-post-status"></div>
       	   <input id="submit" type="submit" value="Update" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('profile-show.php'); ?>"/>
         </div>
				</form>
		</div>
	</div>
<script type="text/javascript">
  $(document).ready(function() {
    $(".notice").hide();
    $("#submit").click(function(){
      
      var hasError = false;
      var password = $("#[name*=\'user[password]\']");
      var check = $("#password-check");
      if (password.val() != check.val()) {
      	$('.notice').html('<p class="error">Passwords mismatch.</p>');
          hasError = true;
      }
      if(hasError == true) {$(".notice").show(); return false;}
  	});
  });
  </script>

<?php }
require('footer.php'); ?>