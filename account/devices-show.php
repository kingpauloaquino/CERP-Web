<?php
  /*
   * Module: Devices 
  */
  $capability_key = 'show_device';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
	  if(isset($_GET['did'])) {
	  	$device = $DB->Find('devices', array(
	  		'columns' => 'devices.*', 
	  	    'conditions' => 'devices.id = '.$_GET['did']
	  	  )
		);}
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['devices']['url'].'" class="nav">'.$Capabilities->All['devices']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['add_device']['url'].'" class="nav">'.$Capabilities->All['add_device']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_device']['url'].'?did='.$_GET['did'].'" class="nav">'.$Capabilities->All['edit_device']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
        <h3 class="form-title">Basic Information</h3>
        
        <div class="field">
          <label class="label">Device Code:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $device['device_code'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Make:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $device['make'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Model:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $device['model'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Serial No.:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $device['serial_no'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
				
        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea readonly="readonly"><?php echo $device['description'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>
        
				<br/>
				<h3 class="form-title">Device Users</h3>
	      <div class="grid jq-grid">
	      	<table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td class="border-right text-center"><a></a></td>
	              <td class="border-right text-center"><a>Code</a></td>
	              <td class="border-right text-center"><a>Name</a></td>
	              <td class="border-right text-center"><a>Role</a></td>
	            </tr>
	          </thead>
	          <tbody>
	        		<?php
								$users = $DB->Get('device_users', array(
								  			'columns' 		=> 'device_users.user_id, users.id AS uid, users.employee_id, CONCAT(users.first_name," ",users.last_name) AS username, roles.name',
								  			'joins'				=> 'INNER JOIN users ON users.id = device_users.user_id	INNER JOIN roles on roles.id = users.role',
								  	    'conditions' 	=> 'device_users.device_id='.$_GET['did']));
								if(!$users) {
									echo '<tr>';
									echo '<td colspan="4">No Record</td>';
									echo '</tr>';
								} else {
									$ctr = 1;
									foreach ($users as $user) {
										echo '<tr>';
										echo '<td class="border-right text-right">'.$ctr.'</td>';
										echo '<td class="border-right text-center"><a href="users-show.php?uid='.$user['uid'].'">'.$user['employee_id'].'</a></td>';
										echo '<td class="border-right">'.$user['username'].'</td>';
										echo '<td class="border-right text-center">'.$user['name'].'</td>';
										echo '</tr>';
										$ctr+=1;
									}	
								}								
							?>
						</tbody>
					</table>
				</div>
			</form>
		</div>
	</div>

<?php }
require('footer.php'); ?>