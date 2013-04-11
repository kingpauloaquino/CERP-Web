<?php
  /*
   * Module: Roles 
  */
  $capability_key = 'delete_role';
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
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
        	echo '<a href="'.$Capabilities->All['roles']['url'].'" class="nav">'.$Capabilities->All['roles']['name'].'</a>';
        	echo '<a href="'.$Capabilities->All['show_role']['url'].'?rid='.$_GET['rid'].'" class="nav">'.$Capabilities->All['show_role']['name'].'</a>';
        	echo '<a href="'.$Capabilities->All['edit_role']['url'].'?rid='.$_GET['rid'].'" class="nav">'.$Capabilities->All['edit_role']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
	<div id="content">
	<form class="form-container" method="post">
    	<input type="hidden" name="action" value="delete_role"> 
			<input type="hidden" name="rid" value="<?php echo $_GET['rid'] ?>"> 
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
      
			<div class="field-command">
     	   <div class="text-post-status"></div>
     	   <input type="submit" value="Remove" class="btn"/>
         <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('roles-show.php?rid='.$_GET['rid']); ?>"/>
			</div>
    </form>
	</div>
</div>

<?php }
require('footer.php'); ?>