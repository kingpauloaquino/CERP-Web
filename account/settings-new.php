<?php
  /*
   * Module: Settings - Edit
  */
  $capability_key = 'add_settings';
  require('header.php');
	
	if($_POST['action'] == 'add_settings') {
		$id = $Posts->AddSettings($_POST['settings']); //function @ include/post.class.php
		redirect_to($Capabilities->All['show_settings']['url']);	
	}
	
	$settings = $DB->Get('settings', array('columns' => 'settings.*'));
	
	
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
					echo '<a href="'.$Capabilities->All['show_settings']['url'].'" class="nav">'.$Capabilities->All['show_settings']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" style="width:500px;" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
        <h3 class="form-title">Company Info</h3>
        
				<input type="hidden" name="action" value="add_settings">

        <div class="field">
		    	<label class="label">Name:</label>
		      <div class="input">
		    		<input type="text" id="settings[name]" name="settings[name]" />
	        </div>
	        <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Value:</label>
		      <div class="input">
		      	<input type="text" id="settings[value]" name="settings[value]" />
	        </div>
	        <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Options:</label>
		      <div class="input">
		      	<input type="text" id="settings[options]" name="settings[options]" />
	        </div>
	        <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label"></label>
          <div class="input">
            <button class="btn">Create</button>
            <button class="btn">Cancel</button>
          </div>
          <div class="clear"></div>
        </div>
			</form>
		</div>
	</div>

<?php require('footer.php'); ?>