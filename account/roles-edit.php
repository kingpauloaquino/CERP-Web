<?php
  /*
   * Module: Roles 
  */
  $capability_key = 'edit_role';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		if(isset($_GET['rid'])) {
	  	$roles = $DB->Find('roles', array(
	  		'columns' => 'roles.*',
	  	  'conditions' => 'id = '.$_GET['rid']
		  ));	
	  }
	
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
        	echo '<a href="'.$Capabilities->All['roles']['url'].'" class="nav">'.$Capabilities->All['roles']['name'].'</a>';
        	echo '<a href="'.$Capabilities->All['show_role']['url'].'?rid='.$_GET['rid'].'" class="nav">'.$Capabilities->All['show_role']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
		<form class="form-container" method="post">
    	<input type="hidden" name="action" value="edit_role"> 
			<input type="hidden" name="rid" value="<?php echo $_GET['rid'] ?>"> 
			<h3 class="form-title">Details</h3>
      <table>
         <tr>
            <td width="150">Title:</td><td width="310"><input type="text" id="role[name]" name="role[name]" value="<?php echo $roles['name'] ?>" class="text-field" /></td>
            <td width="150"></td>
         </tr>      
         <tr>
            <td>Description:</td>
            <td colspan="99">
              <input type="text" id="role[description]" name="role[description]" value="<?php echo $roles['description'] ?>" class="text-field" style="width:645px" />
            </td>
         </tr>
         <tr><td height="5" colspan="99"></td></tr>
      </table>
      <br/>
      <h3 class="form-title">Capabilities</h3>
      <div class="grid jq-grid">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
          		<td width="12%" class="border-right text-center"><a>Title</a></td>
          		<td class="border-right text-center"><a>Capabilities</a></td>
            </tr>
          </thead>
          <tbody>
						<?php
							$role_caps = $Role->getRoleCapabilityIDs($_GET['rid']);
							function exists($roles, $key){
								foreach($roles as $r) {
									if($r['capability_id'] == $key)
										return TRUE;
								} return FALSE;
							}							
							$cap_titles = $DB->Get('capabilities', array(
								  			'columns' 		=> 'id, name',
								  	    'conditions' 	=> 'parent IS null ORDER BY name'));
												
							$init = TRUE;												
							foreach ($cap_titles as $title) {
								if(!$init)
									echo '</td></tr>';
								$init = FALSE;
								echo '<tr><td>'.$title['name'].'</td><td>';
								$capabilities = $DB->Get('capabilities', array(
									  			'columns' 		=> 'id, name',
									  	    'conditions' 	=> 'parent ='.$title['id']));
								foreach ($capabilities as $cap) {
									echo '<input type="checkbox" id="caps[]" name="caps[]" value="' .$cap['id']. '" '.
											($check = (exists($role_caps, $cap['id'])) ? 'checked' : '').
											' /> '.$cap['name'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
								}
							}
							?>
					</tbody>
				</table>
			</div>
			
			<div class="field-command">
     	   <div class="text-post-status"></div>
     	   <input type="submit" value="Update" class="btn"/>
         <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('roles-show.php?rid='.$_GET['rid']); ?>"/>
       </div>
    </form>
	</div>
</div>

<?php }
require('footer.php'); ?>