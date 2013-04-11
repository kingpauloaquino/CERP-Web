<?php
  /*
   * Module: Locations - Add
  */
  $capability_key = 'add_location';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
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
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="POST">
				<input type="hidden" name="action" value="add_location"> 
				<input type="hidden" name="bldg" id="bldg"> 
				<input type="hidden" name="bldg_no" id="bldg_no"> 

				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Building:</td><td width="310"><?php select_query_tag($locations, 'id', 'location_code', '', 'location[bldg]', 'location[bldg]', '', 'width:192px;'); ?></td>
              <td width="150"></td><td></td>
           </tr>
           <tr>
              <td>Building Type:</td><td><?php select_query_tag($bldg_nos, 'id', 'bldg_no', '', 'location[bldg_no]', 'location[bldg_no]', 'N/A', 'width:192px;'); ?></td>
              <td>Terminal:</td><td><?php select_query_tag($terminals, 'id', 'terminal_code', '', 'location[terminal_id]', 'location[terminal_id]', '', 'width:192px;'); ?></td>
           </tr>
           <tr>
              <td>Classification [Rack]:</td><td><?php select_query_tag($item_classifications, 'id', 'classification', '', 'location[item_classification]', 'location[item_classification]', 'N/A', 'width:192px;'); ?></td>
              <td>Deck:</td><td><?php select_query_tag($decks, 'id', 'location', '', 'location[deck]', 'location[deck]', 'N/A', 'width:192px;'); ?></td>
           </tr>  
           <tr>
              <td>Rack:</td><td><?php select_tag($racks, '', 'location[rack]', 'location[rack]', '', 'width:192px;', TRUE); ?></td>
              <td>Number:</td><td><input type="text" id="location[number]" name="location[number]" class="text-field" /></td>
           </tr>            
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" id="location[description]" name="location[description]" class="text-field" style="width:645px" />
              </td>
           </tr>  
           <!-- <tr>
							<input type="hidden" id="mat_id" name="mat_id"/>
              <td>Assigned Item:</td><td><input type="text" id="item_code" name="item_code" value="<?php echo $item['item_code'] ?>" class="text-field searchbox" autocomplete="off" placeholder="Material Code" />
              	<?php echo $linkto = ($item['item_code']!='') ? '&nbsp;<a href="locations-edit.php?lid='.$_GET['lid'].'&clear_item=1">[CLEAR]</a>' : '' ?>
              	<div id="live_search_display" class="live_search_display"></div>
              </td>
              <td></td><td></td>
           </tr>  -->   
           <tr><td height="5" colspan="99"></td></tr>
        </table>	
        <div class="field-command">
       	   <div class="text-post-status"></div>
       	   <input type="submit" value="Create" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('locations.php'); ?>"/>
         </div>
         
			</form>
		</div>
	</div>

<?php }
require('footer.php'); ?>