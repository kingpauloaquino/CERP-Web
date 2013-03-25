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
  
  if(isset($_GET['uid'])) {
  	$user = $DB->Find('users', array(
			'columns' 		=> 'users.id, users.employee_id, users.first_name, users.last_name, users.email, users.position, 
												users.status AS status_id, users.role AS role_id, lookups.description AS status, roles.name as role', 
			'joins'				=> 'LEFT OUTER JOIN lookups on users.status = lookups.id 
												LEFT OUTER JOIN roles on users.role = roles.id',
  	  'conditions' 	=> 'users.id = '.$_GET['uid']
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
				  echo '<a href="'.$Capabilities->All['show_user']['url'].'?uid='.$_GET['uid'].'" class="nav">'.$Capabilities->All['show_user']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
				<div class="form-container">
					<input type="hidden" name="action" value="edit_user">
					<input type="hidden" name="uid" value="<?php echo $_GET['uid'] ?>">
					
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
                <input type="text"  class="text-field" style="width:645px" />
              </td>
           </tr>
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        
        <br/>
        <h3 class="form-title">Security Information</h3>
        
        <table>
           <tr>
              <td width="150">Role:</td><td width="310"><?php select_query_tag($roles, 'id', 'name', $user['role_id'], 'user[role]', 'user[role]', '', 'width:192px;'); ?></td>
              <td width="150"></td><td></td>
           </tr>
           <tr>
              <td>Password:</td><td><input type="password" id="user[password]" name="user[password]" autocomplete="off" class="text-field" /></td>
              <td>Confirm Password:</td><td><input type="password" id="password2" name="password2" autocomplete="off" class="text-field" /></td>
           </tr>
        </table>
        <br/>
         <div class="field-command">
       	   <div class="text-post-status"></div>
       	   <input type="submit" value="Update" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('users-show.php?uid='.$_GET['uid']); ?>"/>
         </div>
				</form>
		</div>
	</div>

<?php require('footer.php'); ?>