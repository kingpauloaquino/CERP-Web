<?php
  /*
   * Module: Locations - Add
  */
  $capability_key = 'add_location';
  require('header.php');
	
	if($_POST['action'] == 'add_location') {
		if($_POST['location']['item_classification'] == 0) { // for single class rack
			$_POST['location']['item_classification'] = NULL;
		} 
		$_POST['location']['address'] = $_POST['bldg'].'-'.$_POST['bldg_no'].$_POST['location']['rack'].sprintf( '%03d', $_POST['location']['number']);
		$id = $Posts->AddLocation($_POST['location']);
		$id = $Posts->AddLocationAddressItem(array('address' => $id));
		
		if(isset($id)){ redirect_to($Capabilities->All['show_location']['url'].'?lid='.$id); }
	} 
	
  $locations = $DB->Get('locations', array('columns' => 'id, location_code', 'conditions' => 'parent = "'.get_lookup_code('loc_bldg').'"'));
	$bldg_nos = $DB->Get('locations', array('columns' => 'id, CONCAT(location_code, "-", description) AS bldg_no', 'conditions' => 'parent = "'.get_lookup_code('loc_bldg_no').'"'));
  $terminals = $DB->Get('terminals', array('columns' => 'id, terminal_code'));
  $item_classifications = $DB->Get('item_classifications', array('columns' => 'id, classification')); 
  $decks = $DB->Get('locations', array('columns' => 'id, location', 'conditions' => 'parent = "'.get_lookup_code('loc_deck').'"'));
  $areas = $DB->Get('locations', array('columns' => 'id, location', 'conditions' => 'parent = "'.get_lookup_code('loc_area').'"'));
	$racks = range('A', 'Z');
?>
<script>	
	$(document).ready(function() {
		$('#bldg').val($('[name*="location[bldg]"] option:selected').text());
    $('[name*="location[bldg]"]').change(function(){
			$('#bldg').val($('[name*="location[bldg]"] option:selected').text());
    });
    
    $('#bldg_no').val($('[name*="location[bldg_no]"] option:selected').text().substring(0,$('[name*="location[bldg_no]"] option:selected').text().indexOf("-")+1));
    $('[name*="location[bldg_no]"]').change(function(){
			$('#bldg_no').val($('[name*="location[bldg_no]"] option:selected').text().substring(0,$('[name*="location[bldg_no]"] option:selected').text().indexOf("-")+1));
    });
	});
</script>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="POST">
				<input type="hidden" name="action" value="add_location"> 
				<input type="hidden" name="bldg" id="bldg"> 
				<input type="hidden" name="bldg_no" id="bldg_no"> 
        <h3 class="form-title">Basic Information</h3>
        
        <span class="notice">
<!--           <p class="info"><strong>Notice</strong> Message</p> -->
        </span>
        
        <div class="field">
          <label class="label">Building:</label>
          <div class="input">
            <?php select_query_tag($locations, 'id', 'location_code', '', 'location[bldg]', 'location[bldg]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Building Type:</label>
          <div class="input">
            <?php select_query_tag($bldg_nos, 'id', 'bldg_no', '', 'location[bldg_no]', 'location[bldg_no]', 'N/A', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Terminal:</label>
          <div class="input">
            <?php select_query_tag($terminals, 'id', 'terminal_code', '', 'location[terminal_id]', 'location[terminal_id]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Classification [Rack]:</label>
          <div class="input">
            <?php select_query_tag($item_classifications, 'id', 'classification', '', 'location[item_classification]', 'location[item_classification]', 'N/A', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Deck:</label>
          <div class="input">
            <?php select_query_tag($decks, 'id', 'location', '', 'location[deck]', 'location[deck]', 'N/A', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>        
        
        <!-- <div class="field">
          <label class="label">Area:</label>
          <div class="input">
            <?php select_query_tag($areas, 'id', 'location', '', 'location[area]', 'location[area]', 'N/A', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div> -->
                
        <div class="field">
          <label class="label">Rack:</label>
          <div class="input">
            <?php select_tag($racks, 'A', 'location[rack]', 'location[rack]', '', 'text w180', TRUE); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Number:</label>
          <div class="input">
            <input type="text" id="location[number]" name="location[number]" />
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea id="location[description]" name="location[description]"></textarea>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label"></label>
          <div class="input">
            <button class="btn">Create</button>
            <button class="btn" onclick="return cancel_btn();">Cancel</button>
          </div>
          <div class="clear"></div>
        </div>
			</form>
		</div>
	</div>

<?php require('footer.php'); ?>