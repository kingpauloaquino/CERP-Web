<?php
  /*
   * Module: Settings - Show
  */
  $capability_key = 'show_settings';
  require('header.php');
	
	$settings = $DB->Get('setting', array('columns' => 'setting.*'));
	
	
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
					echo '<a href="'.$Capabilities->All['edit_settings']['url'].'" class="nav">'.$Capabilities->All['edit_settings']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="POST">
        <h3 class="form-title">Data Archive</h3>
        
        <?php
        	foreach($settings as $setting)
					{
						echo '<div class="field">';
		        echo '<label class="label">'.$setting["name"].'</label>';
		        echo '<div class="input">';
		        echo '<input type="text" value="'.$setting["value"].'" readonly="reeadonly"/>';
		        echo '</div>';
		        echo '<div class="clear"></div>';
		        echo '</div>';
					}
        ?>
        
			</form>
		</div>
	</div>

<?php require('footer.php'); ?>