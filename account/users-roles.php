<?php
  /*
   * Module: Users 
  */
  $capability_key = 'roles';
  $user_id = $_GET['id'];
  require('header.php');
  
  if(isset($user_id)) {
  	$user = $DB->Find('users', array('conditions' => 'id = '.$user_id));
  }
  $roles = $DB->Get('roles', array('columns' => 'id, name, capabilities', 'conditions' => 'NOT id = 1'));
?>

	<div id="page">
		<div id="side-bar">
			<?php require('sidebar.php'); ?>
		</div>
				
		<div id="content">
			<div id="title">
				<h1><?php echo $Title; ?></h1>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dapibus molestie fermentum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas</p>
			</div>

			<span class="notice"></span>
			
			<form id="user-role-add-form" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST" class="validate-on-submit">
				<input type="hidden" name="action" value="add_role"/>
				<strong>Add Role:</strong>&nbsp;&nbsp;&nbsp;<input type="text" id="role_name" name="role[name]" class="text w180 required" alt="Please enter a unique role name"/>&nbsp;&nbsp;&nbsp;<input type="submit" value="Add Role"/>
				<br/>
			</form>
			
			<hr/>
			
			<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas</p>
			<form id="user-role-edit-form" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST" class="validate-on-submit">
				<?php $role_capabilities = get_role_capabilities($roles, $_GET['id']); ?>
				<input type="hidden" name="action" value="edit_role"/>
				<input type="hidden" name="redirect" value="roles"/>
				<input type="hidden" id="role-capabilities" value="<?php echo implode(',', $role_capabilities); ?>"/>
				<strong>Edit Role:</strong>&nbsp;&nbsp;&nbsp;<?php select_query_tag($roles, 'name', 'id', $_GET['id'], 'roles', 'role[id]'); ?>
				<br/><br/>
				<div>
					<input type="hidden" value="">
	            	<ul id="capabilities" class="list horizontal">
						<?php
						  $all_capabilities = $Capabilities->All;
			              asort($all_capabilities);
			              foreach ($all_capabilities as $key => $value) { 
			            ?>
							<li style="width:210px;">
								<input type="checkbox" id="capability_<?php echo $key; ?>" name="capability[]" value="<?php echo $key; ?>" class="chkbox" <?php echo ((in_array($key, $role_capabilities)) ? 'checked' : ''); ?>/>
								<label><?php echo $key; ?></label>
							</li>
						<?php } ?>
					</ul>
					<div class="clear"></div>
					<hr/>
					<input type="button" id="check-all-capabilties" value="Select All"/>&nbsp;
					<input type="button" id="uncheck-all-capabilties" value="Unselect All"/>&nbsp;
					<input type="button" id="reset-capabilties" value="Reset"/>
					<div class="right">
						<input type="submit" value="Update Role"/>&nbsp;
						<input type="submit" value="Delete Role"/>
					</div>
					<div class="clear"></div>
				</div>
			</form>
		</div>
	</div>
	
	<script>
		$(function() {
		  $('#roles').change(function() {
		  	var id = $(this).val();
		  	var url = '<?php echo host($Capabilities->GetUrl()) ?>?id='+id;
		  	document.location.href = url;
		  })
		  
		  $('#check-all-capabilties').click(function() {
		    $('#capabilities').find('.chkbox').attr('checked', true);
		  });
		  
		  $('#uncheck-all-capabilties').click(function() {
		    $('#capabilities').find('.chkbox').attr('checked', false);
		  });
		  
		  $('#reset-capabilties').click(function() {
		  	var capabilities = $('#role-capabilities').val();
		  	$('#uncheck-all-capabilties').click();
		  	
		  	$.each(capabilities.split(','), function(index, capability) {
		      $('#capability_'+capability).attr('checked', true);
		  	});
		  });
		})
	</script>

<?php require('footer.php'); ?>