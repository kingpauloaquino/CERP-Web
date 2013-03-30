<?php
  /*
   * Module: Roles 
  */
  $capability_key = 'add_role';
  require('header.php');
	
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
        	echo '<a href="'.$Capabilities->All['roles']['url'].'" class="nav">'.$Capabilities->All['roles']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
		<form class="form-container" method="post">
    	<input type="hidden" name="action" value="add_role"> 
			<h3 class="form-title">Details</h3>
      <table>
         <tr>
            <td width="150">Title:</td><td width="310"><input type="text" id="role[name]" name="role[name]" class="text-field" /></td>
            <td width="150"></td>
         </tr>      
         <tr>
            <td>Description:</td>
            <td colspan="99">
              <input type="text" id="role[description]" name="role[description]" class="text-field" style="width:645px" />
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
									echo '<input type="checkbox" id="caps[]" name="caps[]" value="' .$capa['id']. '" /> '.$capa['name'].'&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
								}
							}
							?>
					</tbody>
				</table>
			</div>
			
			<div class="field-command">
     	   <div class="text-post-status"></div>
     	   <input type="submit" value="Create" class="btn"/>
         <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('roles.php'); ?>"/>
       </div>
    </form>
	</div>
</div>

<?php require('footer.php'); ?>