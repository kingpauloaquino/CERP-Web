<?php
  /*
   * Module: Locations - Edit
  */
  $capability_key = 'edit_location';
  require('header.php');
	
	if($_REQUEST['clear_item'] == 1) {
		$args = array('variables' => array('item_id' => NULL, 'item_type' => ''), 'conditions' => 'address='.$_REQUEST['lid']); 
		$num_of_records = $Posts->EditLocationAddressItem($args);
		redirect_to($Capabilities->All['show_location']['url'].'?lid='.$_REQUEST['lid']);	
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
	
	if(isset($_REQUEST['lid'])) {
  	$location = $DB->Find('location_addresses', array(
  		'columns' => 'location_addresses.*', 
  	  'conditions' => 'location_addresses.id = '.$_REQUEST['lid']
  	  )
		);
		$item = $DB->Find('location_address_items', array(
				  			'columns' 		=> 'location_address_items.id, location_address_items.item_id AS mat_id, materials.material_code AS item_code', 
				  			'joins'				=> 'INNER JOIN materials ON materials.id = location_address_items.item_id',
				  	    'conditions' 	=> 'location_address_items.item_type="MAT" AND location_address_items.address = '.$_REQUEST['lid']
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
				  echo '<a href="'.$Capabilities->All['show_location']['url'].'?lid='.$_REQUEST['lid'].'" class="nav">'.$Capabilities->All['show_location']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>

		<div id="content">
			<form class="form-container" method="POST">
				<input type="hidden" name="action" value="edit_location"> 
				<input type="hidden" name="bldg" id="bldg"> 
				<input type="hidden" name="bldg_no" id="bldg_no"> 
				<input type="hidden" name="lid" value="<?php echo $_REQUEST['lid'] ?>">
        <h3 class="form-title">Basic Information</h3>
        
        <span class="notice">
<!--           <p class="info"><strong>Notice</strong> Message</p> -->
        </span>

				<div class="field">
          <label class="label">Current:</label>
          <div class="input">
            <input type="text" class="magenta" value="<?php echo $location['address'] ?>" readonly="readonly" />
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Building:</label>
          <div class="input">
            <?php select_query_tag($locations, 'id', 'location_code', $location['bldg'], 'location[bldg]', 'location[bldg]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Building Type:</label>
          <div class="input">
            <?php select_query_tag($bldg_nos, 'id', 'bldg_no', $location['bldg_no'], 'location[bldg_no]', 'location[bldg_no]', 'N/A', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Terminal:</label>
          <div class="input">
            <?php select_query_tag($terminals, 'id', 'terminal_code', $location['terminal_id'], 'location[terminal_id]', 'location[terminal_id]', 'N/A', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Classification [Rack]:</label>
          <div class="input">
            <?php select_query_tag($item_classifications, 'id', 'classification', $location['item_classification'], 'location[item_classification]', 'location[item_classification]', 'N/A', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Deck:</label>
          <div class="input">
            <?php select_query_tag($decks, 'id', 'location', $location['deck'], 'location[deck]', 'location[deck]', 'N/A', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <!-- <div class="field">
          <label class="label">Area:</label>
          <div class="input">
            <?php select_query_tag($areas, 'id', 'location', $location['area'], 'location[area]', 'location[area]', 'N/A', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div> -->
        
        <div class="field">
          <label class="label">Rack:</label>
          <div class="input">
            <?php select_tag($racks, $location['rack'], 'location[rack]', 'location[rack]', '', 'text w180', TRUE); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Number:</label>
          <div class="input">
            <input type="text" id="location[number]" name="location[number]" value="<?php echo $location['number'] ?>" />
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Assigned Item:</label>
          <div class="input">
          	<input type="hidden" id="mat_id" name="mat_id"/>
            <input type="text" id="item_code" name="item_code" value="<?php echo $item['item_code'] ?>" class="searchbox" autocomplete="off" />
          	<?php echo $linkto = ($item['item_code']!='') ? '&nbsp;<a href="'.$Capabilities->All['edit_location']['url'].'?lid='.$_REQUEST['lid'].'&clear_item=1">clear</a>' : '' ?>
            
						<div id="live_search_display"></div>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea id="location[description]" name="location[description]"><?php echo $location['description'] ?></textarea>
          </div>
          <div class="clear"></div>
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