<?php
  /*
   * Module: Devices 
  */
  $capability_key = 'edit_device';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		if($_POST['action'] == 'edit_device') { 
			$args = array('variables' => $_POST['device'], 'conditions' => 'id='.$_POST['did']); 
			$num_of_records = $Posts->EditDevice($args);
			
			$users = $_POST['users'];
			
			if(!empty($users)) {
	      $fields = array('user_id');
			  foreach ($users as $user) {
			  	$new_users = array();
			    foreach (explode('|', $user) as $index => $field) {
			  	  $new_users[$fields[$index]] =  $field;
			    }
					$new_users['device_id'] = $_POST['did'];
					$Posts->AddDeviceUser($new_users);
			  }			
			}		
			redirect_to($Capabilities->All['show_device']['url'].'?did='.$_POST['did']);
		}
		if(isset($_GET['did'])) {
	  	$device = $DB->Find('devices', array(
	  		'columns' => 'devices.*', 
	  	    'conditions' => 'devices.id = '.$_GET['did']
	  	  )
		);} 
?>
<script>	
	$(document).ready(function() {
    $('#employee_id').change(function(){
    	$.ajax({
					type: 'POST',
					url: '..//include//livefilter.php',
					data: { filterType: 'users',
									table: 			'users',
									joins: 			'INNER JOIN roles on users.role = roles.id',
									columns: 		'CONCAT(users.first_name," ",users.last_name) AS username, roles.name AS role',
									conditions: 'users.id='+$('#employee_id option:selected').val()
								},
					contentType: 'application/x-www-form-urlencoded',
					dataType: 'json',
					cache: false,
					success: function(data) {	
						if(data==null) {
							$('#username').val('');
							$('#role').val('');
						}		
    				$.each(data, function(index, element) {   
            	index == 'username' ? $('#name').val(element) : '';
            	index == 'role' ? $('#role').val(element) : '';
		        });

					}
				});
    });
	});
</script>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['show_device']['url'].'?did='.$_GET['did'].'" class="nav">'.$Capabilities->All['show_device']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="POST">
				<input type="hidden" name="action" value="edit_device"/>
				<input type="hidden" name="did" value="<?php echo $_GET['did'] ?>"/>
        <h3 class="form-title">Basic Information</h3>
					<span class="notice">
	<!--           <p class="info"><strong>Notice!</strong> Material codes should be unique.</p> -->
	        </span>
        
        <div class="field">
          <label class="label">Device Code:</label>
          <div class="input">
            <input type="text" id="device[device_code]" name="device[device_code]" class="magenta" value="<?php echo $device['device_code'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Make:</label>
          <div class="input">
            <input type="text" id="device[make]" name="device[make]" value="<?php echo $device['make'] ?>" />
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Model:</label>
          <div class="input">
            <input type="text" id="device[model]" name="device[model]" value="<?php echo $device['model'] ?>" />
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Serial No.:</label>
          <div class="input">
            <input type="text" id="device[serial_no]" name="device[serial_no]" value="<?php echo $device['serial_no'] ?>" />
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea id="device[description]" name="device[description]"><?php echo $device['description'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>

				<br/>
				
				<h3 class="form-title">Device Users</h3>
	      <div class="grid jq-grid">
	      	<table id="tbl-users" cellspacing="0" cellpadding="0">
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
							?>
          	</tbody>
        	</table>
      	</div>
      	
      	<br/>
      	<div class="field">
          <label class="label"></label>
          <div class="input">
            <a class="btn" href="#add-user-modal" rel="modal:open">Add User</a>		
            <button class="btn">Update</button>
            <button class="btn" onclick="return cancel_btn();">Cancel</button>
          </div>
          <div class="clear"></div>
        </div>

			</form>
			
			<div id="add-user-modal" class="modal">
			   <h4 class="title">Add User</h4>
			   <div class="content">
			      <form id="add-user-form">
			   	     <span class="notice"></span>
			      	 <input type="hidden" id="item_action" value="1"/>
			         <div class="t-row">
			            <label>User Code</label>
			            <?php 
			            	$users = $DB->Get('users', array('columns' => 'users.id, users.employee_id'));
			            	select_query_tag($users, 'id', 'employee_id', '', 'employee_id', 'employee_id', '-', 'text w180'); ?>
			         </div>
			         
			         <div class="t-row">
			            <label>Name:</label>
			            <input type="text" id="name" name="name" value="" class="text w180 readonly" readonly="reaadonly" />
			         </div>
			         
			         <div class="t-row">
			            <label>Role:</label>
			            <input type="text" id="role" name="role" value="" class="text w180 readonly" readonly="reaadonly"/>
			         </div>
			         
			         <br/>
			         <div class="t-foot">
			           <input type="button" id="user-continue" value="Continue" alt="1" class="user-submit-reset"/>
			           <input type="button" id="user-add-close" value="Add & Close" alt="0" class="user-submit-reset"/>
			         </div>
			      </form>
			   </div>
			</div>
			
		</div>
	</div>
<script>
  $('#add-user-form').ready(function() {
  	$('#add-user-form').add_user();
    $('.user-submit-reset').submit_reset_user();
    
  });      
  
  jQuery.fn.submit_reset_user = function() {
  	this.click(function() {
  	  var x			= $(this).attr('alt');
  	  var form		= $(this).closest('form');
  	  var notice	= form.find('.notice');
  	  var complete	= true;
  	  
  	  notice.empty();
  	  form.find('#item_action').val(x);
  	  
  	  form.find('.required').each(function() {
        if($(this).val() == '') complete = false;
      });
      
      if(complete == false) {
      	notice.html('<p class="error">Please complete all the required fields</p>');
      	return false;
      }
      
  	  form.submit();
  	  form.find('.text').val('');
  	  if(x == 0) $('a.close-modal').click();
  	});
  }
  
  jQuery.fn.add_user = function() {
    this.submit(function(e) {
      e.preventDefault();
      
      var user_id		= $('#employee_id option:selected').val() || '';
      var user_code	= $('#employee_id option:selected').text() || '';
      var user_name	= $('#name').val() || '';
      var user_role	= $('#role').val() || '';   
            
      var user_values = user_id;
      var tr				= $('<tr style="background:#ffeef2"></tr>');
     
  	  tr.append('<td class="highlight_red border-right text-right"><input type="hidden" name="users[]" value="'+user_values+'"/></td>');
  	  tr.append('<td class="highlight_red border-right text-center">'+user_code+'</td>');
  	  tr.append('<td class="highlight_red border-right">'+user_name+'</td>');
  	  tr.append('<td class="highlight_red border-right text-center">'+user_role+'</td>');
  	  
      $('#tbl-users').find('tbody').append(tr);	 
    });
  }
</script>

<?php }
require('footer.php'); ?>