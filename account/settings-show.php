<?php
  /*
   * Module: Settings - Show
  */
  $capability_key = 'show_settings';
  require('header.php');
	
	
	
	
?>
	<script type="text/javascript">
		$(function(){
				$('#tabs').tabs();
		});
  </script>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
					echo '<a href="'.$Capabilities->All['add_settings']['url'].'" class="nav">'.$Capabilities->All['add_settings']['name'].'</a>';
					echo '<a href="'.$Capabilities->All['edit_settings']['url'].'" class="nav">'.$Capabilities->All['edit_settings']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="POST">
        
        
        <?php
        	$categories = $DB->Get('settings', array('columns' => 'DISTINCT(category)'));
					foreach($categories as $cat)
					{
						$settings = $DB->Get('settings', array('columns' => 'settings.*', 'conditions' => 'category="'.$cat['category'].'"'));
						switch ($cat['category']) {
							case 'COMPANY' : echo '<h3 class="form-title">Company Info</h3>'; break;
							case 'PRODUCTION' : echo '<h3 class="form-title">Production Area</h3>'; break;
						}
	        	foreach($settings as $set)
						{
							echo '<div class="field">';
			        echo '<label class="label">'.$set["name"].'</label>';
			        echo '<div class="input">';
			        echo '<input type="text" value="'.$set["value"].'" readonly="readonly"/>&nbsp;';
			        echo '</div>';
			        echo '<div class="clear"></div>';
			        echo '</div>';
						}
						echo '<br/>';
					}
        ?>
        
			</form>
		</div>
	</div>

<?php require('footer.php'); ?>