<?php
  /*
   * Module: Users - New
  */
  $capability_key = 'add_user';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		if($_POST['action'] == 'add_user') {
			$id = $Posts->AddUser($_POST['user']);
			$x = $Capabilities->All['show_user']['url'].'?uid='.$id;
			
			if(isset($id)) {
				$Posts->AddUserRole(array('user_id' => $id , 'role_id' => $_POST['role_id']));
				
				redirect_to($Capabilities->All['show_user']['url'].'?uid='.$id);	
			}
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
			<form id="user-form" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
				<div class="form-container">
					<input type="hidden" name="action" value="add_user">
					
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Employee ID:</td><td width="310"><input type="text" id="user[employee_id]" name="user[employee_id]" autocomplete="off" class="text-field magenta" /></td>
              <td width="150">Position:</td><td><input type="text" id="user[position]" name="user[position]" autocomplete="off" class="text-field" /></td>
           </tr>
           <tr>
              <td>First Name:</td><td><input type="text" id="user[first_name]" name="user[first_name]" autocomplete="off" class="text-field" /></td>
              <td>Last Name:</td><td><input type="text" id="user[last_name]" name="user[last_name]" autocomplete="off" class="text-field" /></td>
           </tr>
           <tr>
              <td>Email:</td><td><input type="text" id="user[email]" name="user[email]" autocomplete="off" class="text-field" /></td>
              <td>Status:</td><td><?php select_query_tag($statuss, 'id', 'description', '', 'user[status]', 'user[status]', '', 'width:192px;'); ?></td>
           </tr>            
           <tr>
              <td>Remarks:</td>
              <td colspan="99">
                <input type="text" id="user[description]" name="user[description]" class="text-field" style="width:645px" />
              </td>
           </tr>
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        
        <br/>
        <h3 class="form-title">Security Information</h3>
        
        <table>
           <tr>
              <td width="150">Role:</td><td width="310"><?php select_query_tag($roles, 'id', 'name', '', 'role_id', 'role_id', '', 'width:192px;'); ?></td>
              <td width="150"></td><td></td>
           </tr>
           <tr>
              <td>Password:</td><td><input type="password" id="user[password]" name="user[password]" autocomplete="off" class="text-field required" /></td>
              <td>Confirm Password:</td><td><input type="password" id="password-check" name="password-check" autocomplete="off" class="text-field required" /></td>
           </tr> 
        </table>
        <br/>
        <span class="notice">
	        <p class="error"><strong>Required!</strong> Password mismatch</p>
	      </span> 
        <br/>
         <div class="field-command">
       	   <div class="text-post-status"></div>
       	   <input id="submit" type="submit" value="Create" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('users.php'); ?>"/>
         </div>
	
				</form>
			</div>
		</div>
	</div>
<script type="text/javascript">
  $(document).ready(function() {
    $(".notice").hide();
    $("#submit").click(function(){
      
      var hasError = false;
      var password = $("#[name*=\'user[password]\']");
      var check = $("#password-check");
      if (password.val() == '') {
      	$('.notice').html('<p class="error">Enter a password.</p>');
          hasError = true;
      } else if (check.val() == '') {
      	$('.notice').html('<p class="error">Re-enter a password.</p>');
          hasError = true;
      } else if (password.val() != check.val()) {
      	$('.notice').html('<p class="error">Passwords mismatch.</p>');
          hasError = true;
      }
      if(hasError == true) {$(".notice").show(); return false;}
  	});
  });
  </script>

<?php }
require('footer.php'); ?>