<?php
  /*
   * Module: Settings Lookups - Edit
  */
  $capability_key = 'edit_settings_lookups';
  require('header.php');
	
	$trmpays = $DB->Get('lookups', array('columns' => 'lookups.*', 'conditions' => 'parent="'.get_lookup_code("term_of_payment").'"')); //function get_lookup_code @ /include/functions.php
	$suptyps = $DB->Get('lookups', array('columns' => 'lookups.*', 'conditions' => 'parent="'.get_lookup_code("supplier_type").'"'));
	
?>
<script>
$(function() {
    $( ".accordion" ).accordion({
        collapsible: true, active: false, autoHeight: false
    });
});
</script>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
					echo '<a href="'.$Capabilities->All['show_settings_lookups']['url'].'" class="nav">'.$Capabilities->All['show_settings_lookups']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="POST">
				<div class="accordion">
					<h3><a href="#">Terms of Payment</a></h3>
					<div>
		        <?php
		        $ctr = 1;
		        	foreach($trmpays as $trmpay)
							{
								echo '<div class="field">';
				        echo '<label class="label">'.$ctr.'.</label>';
				        echo '<div class="input">';
				        echo '<input type="text" value="'.$trmpay['description'].'" class="w320"/>';
				        echo '</div>';
				        echo '<div class="clear"></div>';
				        echo '</div>';
								$ctr+=1;
							}
		        ?>
					</div>
					<h3><a href="#">Supplier Types</a></h3>
					<div>
		        <?php
		        $ctr = 1;
		        	foreach($suptyps as $suptyp)
							{
								echo '<div class="field">';
				        echo '<label class="label">'.$ctr.'.</label>';
				        echo '<div class="input">';
				        echo '<input type="text" value="'.$suptyp['description'].'" class="w320"/>';
				        echo '</div>';
				        echo '<div class="clear"></div>';
				        echo '</div>';
								$ctr+=1;
							}
		        ?>
					</div>
	        <br/>
				</div>
				<div class="field">
          <label class="label"></label>
          <div class="input">
            <button class="btn">Update</button>
            <button class="btn" onclick="return cancel_btn();">Cancel</button>
          </div>
          <div class="clear"></div>
        </div>
			</form>
		</div>
	</div>

<?php require('footer.php'); ?>