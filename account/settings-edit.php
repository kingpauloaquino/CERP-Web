<?php
  /*
   * Module: Settings - Edit
  */
  $capability_key = 'edit_settings';
  require('header.php');
	
	if($_POST['action'] == 'edit_settings') {
		for($i=1; $i <= $_POST['settings_count']; $i++){
			$stngs['value'] = $_POST['value_'.$i];
			$stngs['options'] = $_POST['options_'.$i];
			$args = array('variables' => $stngs, 'conditions' => 'id='.$_POST['id_'.$i]); 
			$num_of_records = $Posts->EditSettings($args); // function @ /include/post.class.php
		}
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
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
					echo '<a href="'.$Capabilities->All['show_settings']['url'].'" class="nav">'.$Capabilities->All['show_settings']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" style="width:550px;" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
        <h3 class="form-title">Company Info</h3>
        
				<input type="hidden" name="action" value="edit_settings">
        
        <?php
        $ctr=1;
        	foreach($settings as $setting)
					{
						echo '<div class="field">';
		        echo '<label class="label">'.$setting["name"].'</label>';
		        echo '<div class="input">';
						echo '<input type="hidden" id="id_'.$ctr.'" name="id_'.$ctr.'" value="'.$setting['id'].'" />';
		        echo '<input type="text" id="value_'.$ctr.'" name="value_'.$ctr.'" value="'.$setting["value"].'" />&nbsp;';
		        echo '<input type="text" id="options_'.$ctr.'" name="options_'.$ctr.'" value="'.$setting["options"].'" placeholder="options" />';
		        echo '</div>';
		        echo '<div class="clear"></div>';
		        echo '</div>';
						$ctr+=1;
					}
					
        ?>
        <input type="hidden" id="settings_count" name="settings_count" value="<?php echo $ctr ?>"/>
        
        
        <div class="field">
          <label class="label"></label>
          <div class="input">
            <button class="btn">Update</button>
            <button class="btn" onclick="cancel_btn();">Cancel</button>
          </div>
          <div class="clear"></div>
        </div>
			</form>
		</div>
	</div>

<?php require('footer.php'); ?>