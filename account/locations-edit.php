<?php
  /*
   * Module: Locations - Edit
  */
  $capability_key = 'edit_location';
  require('header.php');
	
	if($_GET['clear_item'] == 1) {
		$args = array('variables' => array('item_id' => NULL, 'item_type' => ''), 'conditions' => 'address='.$_GET['lid']); 
		$num_of_records = $Posts->EditLocationAddressItem($args);
		redirect_to($Capabilities->All['show_location']['url'].'?lid='.$_GET['lid']);	
	}
	
	if($_POST['action'] == 'edit_location') {
		$_POST['location']['address'] = $_POST['bldg'].'-'.$_POST['bldg_no'].$_POST['location']['rack'].sprintf( '%03d', $_POST['location']['number']);
		$args = array('variables' => $_POST['location'], 'conditions' => 'id='.$_POST['lid']); 
		$num_of_records = $Posts->EditLocation($args);
		
		if($_POST['mat_id']!='') {
			$mid = $_POST['mat_id'];
			$typ = 'MAT';
			$args = array('variables' => array('item_id' => $mid, 'item_type' => $typ), 'conditions' => 'address='.$_POST['lid']); 
			$num_of_records = $Posts->EditLocationAddressItem($args);
		}
		
		
		redirect_to($Capabilities->All['show_location']['url'].'?lid='.$_POST['lid']);		
	} 
	
	if(isset($_GET['lid'])) {
  	$location = $DB->Find('location_addresses', array(
  		'columns' => 'location_addresses.*', 
  	  'conditions' => 'location_addresses.id = '.$_GET['lid']
  	  )
		);
		$item = $DB->Find('location_address_items', array(
				  			'columns' 		=> 'location_address_items.id, location_address_items.item_id AS mat_id, materials.material_code AS item_code', 
				  			'joins'				=> 'INNER JOIN materials ON materials.id = location_address_items.item_id',
				  	    'conditions' 	=> 'location_address_items.item_type="MAT" AND location_address_items.address = '.$_GET['lid']
  	  )
		);
	}
	
	$locations = $DB->Get('locations', array('columns' => 'id, location_code', 'conditions' => 'parent = "'.get_lookup_code('loc_bldg').'"'));
	$bldg_nos = $DB->Get('locations', array('columns' => 'id, CONCAT(location_code, "-", description) AS bldg_no', 'conditions' => 'parent = "'.get_lookup_code('loc_bldg_no').'"'));
  $terminals = $DB->Get('terminals', array('columns' => 'id, terminal_code'));
  $item_classifications = $DB->Get('item_classifications', array('columns' => 'id, classification')); 
  $decks = $DB->Get('locations', array('columns' => 'id, location', 'conditions' => 'parent = "'.get_lookup_code('loc_deck').'"'));
  $areas = $DB->Get('locations', array('columns' => 'id, location', 'conditions' => 'parent = "'.get_lookup_code('loc_area').'"'));
	$racks = range('A', 'Z');
?>
<script type="text/javascript" src="../javascripts/jquery.watermarkinput.js"></script>
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
    $(".searchbox").keydown(function(e) {
			if (e.keyCode == 9) {
				$('#live_search_display').hide();
			}
		});
		$(".searchbox").keyup(function() {
			var searchbox = $(this).val().toUpperCase();
			if(searchbox=='') {				
				$('#live_search_display').hide();
			}
			else {
				function ajax(ptype, purl, pout){
					$.ajax({
						type: ptype,
						url: purl,
						data: { searchword: searchbox, 
										searchtype: 'location',
										resultcount: 5,
										table: 			'materials',
										columns: 		'materials.id AS mat_id, materials.material_code AS item_code ',
										joins: 			' ',
										conditions: 'materials.material_code LIKE "%'+searchbox+'%"'
									},
						cache: false,
						success: function(data) {
							$(pout).html(data).show();		
						}
					});
				}
				ajax ("POST", "../include/livesearch.php", "#live_search_display");
			}
			return false;    
		});
	});
	jQuery(function($) {
		$('#item_code').Watermark("Material Code");
	});
</script>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['show_location']['url'].'?lid='.$_GET['lid'].'" class="nav">'.$Capabilities->All['show_location']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>

		<div id="content">
			<form class="form-container" method="POST">
				<input type="hidden" name="action" value="edit_location"> 
				<input type="hidden" name="bldg" id="bldg"> 
				<input type="hidden" name="bldg_no" id="bldg_no"> 
				<input type="hidden" name="lid" value="<?php echo $_GET['lid'] ?>">

				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Current:</td><td width="310"><input type="text" value="<?php echo $location['address'] ?>" class="text-field magenta" /></td>
              <td width="150">Building:</td><td><?php select_query_tag($locations, 'id', 'location_code', $location['bldg'], 'location[bldg]', 'location[bldg]', '', 'width:192px;'); ?></td>
           </tr>
           <tr>
              <td>Building Type:</td><td><?php select_query_tag($bldg_nos, 'id', 'bldg_no', $location['bldg_no'], 'location[bldg_no]', 'location[bldg_no]', '', 'width:192px;'); ?></td>
              <td>Terminal:</td><td><?php select_query_tag($terminals, 'id', 'terminal_code', $location['terminal_id'], 'location[terminal_id]', 'location[terminal_id]', '', 'width:192px;'); ?></td>
           </tr>
           <tr>
              <td>Classification [Rack]:</td><td><?php select_query_tag($item_classifications, 'id', 'classification', $location['item_classification'], 'location[item_classification]', 'location[item_classification]', 'N/A', 'width:192px;'); ?></td>
              <td>Deck:</td><td><?php select_query_tag($decks, 'id', 'location', $location['deck'], 'location[deck]', 'location[deck]', '', 'width:192px;'); ?></td>
           </tr>  
           <tr>
              <td>Rack:</td><td><?php select_tag($racks, $location['rack'], 'location[rack]', 'location[rack]', '', 'width:192px;', TRUE); ?></td>
              <td>Number:</td><td><input type="text" id="location[number]" name="location[number]" value="<?php echo $location['number'] ?>" class="text-field" /></td>
           </tr>            
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" id="location[description]" name="location[description]" value="<?php echo $location['description'] ?>" class="text-field" style="width:645px" />
              </td>
           </tr>  
           <tr>
							<input type="hidden" id="mat_id" name="mat_id"/>
              <td>Assigned Item:</td><td><input type="text" id="item_code" name="item_code" value="<?php echo $item['item_code'] ?>" class="text-field searchbox" autocomplete="off" placeholder="Material Code" />
              	<?php echo $linkto = ($item['item_code']!='') ? '&nbsp;<a href="locations-edit.php?lid='.$_GET['lid'].'&clear_item=1">[CLEAR]</a>' : '' ?>
              	<div id="live_search_display" class="live_search_display"></div>
              </td>
              <td></td><td></td>
           </tr>    
           <tr><td height="5" colspan="99"></td></tr>
        </table>	
        <div class="field-command">
       	   <div class="text-post-status"></div>
       	   <input type="submit" value="Update" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('locations-show.php?lid='.$_GET['lid']); ?>"/>
         </div>
			</form>
		</div>
	</div>

<?php require('footer.php'); ?>