<?php
  /*
   * Module: Roles 
  */
  $capability_key = 'show_role';
  require('header.php');
	
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
        	echo '<a href="'.$Capabilities->All['edit_role']['url'].'?rid='.$_GET['rid'].'" class="nav">'.$Capabilities->All['edit_role']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
	<div id="content">
	<form class="form-container">
			<h3 class="form-title">Details</h3>
      <table>
         <tr>
            <td width="150">Title:</td><td width="310"><input type="text" value="<?php echo $roles['name'] ?>" class="text-field" disabled/></td>
            <td width="150"></td>
         </tr>      
         <tr>
            <td>Description:</td>
            <td colspan="99">
              <input type="text" value="<?php echo $roles['description'] ?>" class="text-field" style="width:645px" disabled/>
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

							$init = TRUE;
							foreach ($Role->getAllCapabilities() as $capa) {
								if(!isset($capa['parent'])) {
									if(!$init)
										echo '</td></tr>';
									$init = FALSE;
									echo '<tr>';
									echo '<td>'.$capa['name'].'</td>';
									echo '<td>';					
								} else {
									echo '<input type="checkbox" '.($check = (exists($role_caps, $capa['id'])) ? 'checked' : '').' disabled/> '.$capa['name'].'&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
								}
							}
							?>
					</tbody>
				</table>
			</div>
    </form>
	</div>
</div>

<?php require('footer.php'); ?>