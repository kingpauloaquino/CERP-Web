<?php
  /*
   * Module: Locations - Edit
  */
  $capability_key = 'edit_location';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
		if(isset($_GET['lid'])) {
	  	$location = $DB->Find('location_addresses', array(
	  		'columns' => 'location_addresses.*, location_addresses.item_id AS mid, materials.material_code AS code', 
	  		'joins' => 'LEFT OUTER JOIN materials ON materials.id = location_addresses.item_id AND item_type="MAT"',
	  	  'conditions' => 'location_addresses.id = '.$_GET['lid']
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
				<input type="hidden" name="old-item-id" value="<?php echo $location['mid'] ?>" />
				<input type="hidden" id="material-id" name="location[item_id]" value="<?php echo $location['item_id'] ?>"/>
				<input type="hidden" name="location[item_type]" value="MAT" />

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
              <td>Assigned Item:</td><td><input id="material-code" type="text" class="text-field" value="<?php echo $location['code'] ?>" readonly/>
              	<a id="btn-id" href="#modal-materials" rel="modal:open">Set</a>
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

	<div id="modal-materials" class="modal" style="display:none;width:920px;">
		<div class="modal-title"><h3>Warehouse Address</h3></div>
		<div class="modal-content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
      </div>
			<div id="grid-materials" class="grid jq-grid">
				<table cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td width="20" class="border-right text-center"></td>
              <td width="110" class="border-right text-center"><a class="sort default active up" column="code">Code</a></td>
              <td width="100" class="border-right text-center"><a class="sort" column="classification">Classification</a></td>
              <td class="border-right text-center"><a class="sort" column="description">Description</a></td>
              <td width="120" class="border-right text-center"><a class="sort" column="address">Address</a></td>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>	
			<div id="materials"></div>
      <!-- BOF Pagination -->
			<div id="locations-pagination"></div>
		</div>     
		<div class="modal-footer">
			<a class="btn modal-close" rel="modal:close">Close</a>
			<div class="clear"></div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			var data = { 
	    	"url":"/populate/location-materials.php",
	      "limit":"10",
				"data_key":"location_materials",
				"row_template":"row_template_location_materials_modal",
	      "pagination":"#locations-pagination",
	      "searchable":true
				}
				$('#grid-materials').grid(data);
				
			$('#btn-id').click(function(){
				// clear all checked
				// var group = "input:checkbox[name='materials[1]']";
	      // $(group).prop("checked", false);
	        
				$('#materials').find('tr.one-chk').each(function(){
					$(this).prop("checked", false);
				})
			})
				
			$('.one-chk').live('click', function() {
				// allow single selection only
		    if ($(this).is(":checked")) {
	        var group = "input:checkbox[name='" + $(this).attr("name") + "']";
	        $(group).prop("checked", false);
	        $(this).prop("checked", true);
		    } else {
	        $(this).prop("checked", false);
		    }
		    
		    $('#material-code').val($(this).attr('material-code'));
		    $('#material-id').val($(this).attr('material-id'));
		    $('.modal-close').click();
			});
			
		});
	</script>
<?php }
require('footer.php'); ?>