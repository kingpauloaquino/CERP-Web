<?php
  /*
   * Module: Users 
  */
  $capability_key = 'show_user';
  require('header.php');
  
  if(isset($_REQUEST['uid'])) {
  	$user = $DB->Find('users', array(
			'columns' 		=> 'users.*, lookups.description AS status, roles.name as role', 
			'joins'				=> 'LEFT OUTER JOIN lookups on users.status = lookups.id 
												LEFT OUTER JOIN roles on users.role = roles.id',
  	  'conditions' 	=> 'users.id = '.$_REQUEST['uid']
  	  )
		);	
  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['users']['url'].'" class="nav">'.$Capabilities->All['users']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['add_user']['url'].'" class="nav">'.$Capabilities->All['add_user']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_user']['url'].'?uid='.$_REQUEST['uid'].'" class="nav">'.$Capabilities->All['edit_user']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
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
      </form>
	</div>
</div>

<?php require('footer.php'); ?>