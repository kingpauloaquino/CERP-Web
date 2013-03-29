<?php
  /*
   * Module: Roles 
  */
  $capability_key = 'show_role';
  require('header.php');
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
        	echo '<a href="'.$Capabilities->All['edit_role']['url'].'?rid='.$_GET['rid'].'&title='.$_GET['title'].'" class="nav">'.$Capabilities->All['edit_role']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
	<div id="content">
		<form class="form-container">
      <h3 class="form-title"><?php echo $_GET['title'] ?> Capabilities</h3>
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