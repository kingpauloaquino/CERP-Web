<?php
  /*
   * Module: Profile 
  */
  $capability_key = 'show_profile';
  require('header.php');
  
  if(isset($Signed['id'])) {
  	$user = $DB->Find('users', array(
			'columns' 		=> 'users.*, lookups.description AS status, roles.name as role', 
			'joins'				=> 'LEFT OUTER JOIN lookups on users.status = lookups.id 
												LEFT OUTER JOIN roles on users.role = roles.id',
  	  'conditions' 	=> 'users.id = '.$Signed['id']
  	  )
		);	
  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['edit_profile']['url'].'" class="nav">'.$Capabilities->All['edit_profile']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<div class="form-container">
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Employee ID:</td><td width="310"><input type="text" value="<?php echo $user['employee_id'] ?>" class="text-field" disabled/></td>
              <td width="150">Position:</td><td><input type="text" value="<?php echo $user['position'] ?>" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>First Name:</td><td><input type="text" value="<?php echo $user['first_name'] ?>" class="text-field" disabled/></td>
              <td>Last Name:</td><td><input type="text" value="<?php echo $user['last_name'] ?>" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>Email:</td><td><input type="text" value="<?php echo $user['email'] ?>" class="text-field" disabled/></td>
              <td>Status:</td><td><input type="text" value="<?php echo $user['status'] ?>" class="text-field" disabled/></td>
           </tr>            
           <tr>
              <td>Remarks:</td>
              <td colspan="99">
                <input type="text"  class="text-field" style="width:645px" disabled/>
              </td>
           </tr>
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        
        <br/>
        <h3 class="form-title">Security Information</h3>        
        <table>
           <tr>
              <td width="150">Role:</td><td width="310"><input type="text" value="<?php echo $user['role'] ?>" class="text-field" disabled/></td>
              <td width="150"></td><td></td>
           </tr>
        </table>
        
        <h3 class="form-title">Capabilities</h3>
	      <div class="grid jq-grid">
	        <table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
            		<td width="5%" class="border-right text-center"><a></a></td>
            		<td width="20%" class="border-right text-center"><a>Title</a></td>
            		<td class="border-right text-center"><a>Capability</a></td>
	            </tr>
	          </thead>
	          <tbody>
						<?php
							foreach ($Role->getUserRoles() as $user_role) {
								echo '<tr>';
								echo '<td class="text-center"><input type="checkbox"/></td>';
								echo '<td colspan="2">'.$user_role['name'].'</td>';
								echo '</tr>';
								
								$roles = $Role->getUserCapabilities($user_role['role_id']);
								foreach ($roles as $role) {
									echo '<tr>';
									echo '<td></td>';
									echo '<td></td>';
									echo '<td><input type="checkbox"/> '.$role['name'].'</td>';
									echo '</tr>';									
								}
							}
							?>
						</tbody>
					</table>
				</form>
			</div>
      </div>
	</div>
</div>

<?php require('footer.php'); ?>