<?php
  /*
   * Module: Production Terminal
  */
  $capability_key = 'add_terminal_production';
  require('header.php');
	
	if($_POST['action'] == 'add_terminal_production') {
		$qty_col = '';
		switch($_POST['inventory_type']) {
			case 'Input' : $qty_col = 'qty'; break;
			case 'Output' : $qty_col = 'qty'; break;
		}
																															
		//TODO: if tracking_no, item_id, lot_no, terminal_id  existing
		// $args = array($qty_col => $_POST['qty'], 'tracking_no' => $_POST['tracking_no'], 'item_id' => $_POST['item_id'], 'type' => $_POST['inventory_type'], 
									// 'prod_lot_no' => $_POST['prod_lot_no'], 'mat_lot_no' => $_POST['mat_lot_no'],'terminal_id' => $_POST['terminal'], 'remarks' => $_POST['remarks']);			
		// //$num_of_records = $Posts->EditProductionInventory($args);																										
		// $num_of_records = $Posts->AddProductionInventory($args);
		
		if($_POST['inventory_type'] == 'OUTPUT') {
			
		} else {
			if($_POST['terminal'] == 3){ //Pre-Prod IN
				$status = 103;
				$vars = array('qty' => $_POST['qty'], 'status' => $status, 'remarks' => $_POST['remarks']);
				$Posts->AdjustProductionInventory(array('variables' => $vars, 
																								'conditions' => 'item_id='.$_POST['item_id'].' AND item_type="MAT" AND 
																								tracking_no = "'.$_POST['tracking_no'].'" AND prod_lot_no="'.$_POST['prod_lot_no'].'" AND mat_lot_no="'.$_POST['mat_lot_no'].'"'));	
			} 
		}
		
		// $args = array('type' => $_POST['inventory_type'], 'item_id' => $_POST['item_id'], 'tracking_no' => $_POST['tracking_no'], 'prod_lot_no' => $_POST['prod_lot_no'], 
									// 'mat_lot_no' => $_POST['mat_lot_no'], 'src_terminal_id' => $_POST['src_terminal'], 'terminal_id' => $_POST['terminal'], 
									// $qty_col => $_POST['qty'], 'remarks' => $_POST['remarks']);		
		// $num_of_records = $Posts->AdjustProductionInventory($args);
		
		
	} 
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
        <h3 class="form-title">Information</h3>

				<input type="hidden" name="action" value="add_terminal_production"> 
				<input type="hidden" name="device_id" value=""> 
      	<input type="hidden" name="item_id" id="item_id" />
				
				<span class="notice">
<!--           <p class="info"><strong>Notice!</strong> Material codes should be unique.</p> -->
        </span>
				
				<div class="field">
          <label class="label">User:</label>
          <div class="input">
            <?php
          	$users = $DB->Get('users', array('columns' => 'id, CONCAT(employee_id, " - ", CONCAT(first_name," ", last_name)) AS user_name', 'conditions' => 'role=6')); // role 6 = Terminal
		        	select_query_tag($users, 'id', 'user_name', '', 'user_id', 'user_id', '', ''); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Terminal:</label>
          <div class="input">
          	<?php
          	$terminal_ins = $DB->Get('terminals', array('columns' => 'id, CONCAT(terminal_code," - ", terminal_name) AS terminal', 'conditions' => 'location_id=4')); // location_id 4 : WIP
		        	select_query_tag($terminal_ins, 'id', 'terminal', '', 'terminal', 'terminal', '', ''); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Device:</label>
          <div class="input">
            <?php
          	$devices = $DB->Get('devices', array('columns' => 'id, CONCAT(device_code," - ", model) AS device'));
		        	select_query_tag($devices, 'id', 'device', '', 'device', 'device', '', ''); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Barcode:</label>
          <div class="input">
            <input id="entry_barcode" name="entry_barcode" type="text" class="magenta" readonly="readonly" />
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Item Code:</label>
          <div class="input">
            <input id="item_code" name="item_code" type="text" class="magenta" readonly="readonly" />
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Tracking No.:</label>
          <div class="input">
            <input id="tracking_no" name="tracking_no" type="text"  />
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Prod. Lot No.:</label>
          <div class="input">
            <input id="prod_lot_no" name="prod_lot_no" type="text"  />
          </div>
          <div class="clear"></div>
        </div>
        
        
        <div class="field">
          <label class="label">Mat. Lot No.:</label>
          <div class="input">
            <input id="mat_lot_no" name="mat_lot_no" type="text"  />
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Type:</label>
          <div class="input">
            <?php 
		        	$types = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('inventory_type').'"', 'sort_column' => 'id'));
		        	select_query_tag($types, 'description', 'description', '', 'inventory_type', 'inventory_type', '', ''); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Endorsed From:</label>
          <div class="input">
          	<?php
          	$terminals = $DB->Get('terminals', array('columns' => 'id, CONCAT(terminal_code," - ", terminal_name) AS terminal')); // location_id 4 : WIP
		        	select_query_tag($terminals, 'id', 'terminal', '', 'src_terminal', 'src_terminal', '', ''); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Quantity:</label>
          <div class="input">
            <input id="qty" name="qty" type="text"  />
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Remarks:</label>
          <div class="input">
          	<textarea id="remarks" name="remarks"></textarea>
          </div>
          <div class="clear"></div>
        </div>
        
				<br/>
				<div class="field">
          <label class="label"></label>
          <div class="input">
            <a id="btn_scan" class="btn" href="#add-scan-modal" rel="modal:open" style="display: none">Scan</a>		
            <button class="btn" disabled="disabled" id="btn_create">Create</button>
            <button class="btn" onclick="return cancel_btn();">Cancel</button>
          </div>
          <div class="clear"></div>
        </div>
				</form>
				
				
				<div id="add-scan-modal" class="modal" style="width:200px;">
					<div class="modal-title"><h3>Barcode Entry</h3></div>
					<div class="modal-content">
						<form id="add-scan-form">
							<span class="notice"></span>               
							<input type="hidden" id="item_action" value="1"/>
               
							<div class="field">
								<label></label>
								<input type="text" id="barcode" name="barcode" autocomplete="off" class="required text-field-max" autofocus="autofocus"/>
							</div>
			      </form>
        	 </div>
					<div class="modal-footer">
						<input type="button" id="scan-add-close" value="OK" alt="0" class="scan-submit-reset"/>
					</div>	
				</div>
			
		</div>
	</div>
<script type="text/javascript" src="../javascripts/jquery.watermarkinput.js"></script>
<script>
$(document).ready(function() {
	$('#inventory_type').change(function(e) {
		if($(this).val()!='Input') {
			$('#terminal_from').attr('disabled', 'disabled'); return false;
		}
		$('#terminal_from').removeAttr('disabled'); return false;
	});
	
	
	
	$('#add-scan-modal').keydown(function(e) { 
		if (e.keyCode == 13) { 
			if($('#barcode').val()!='') {
				get_barcode_data('materials', 'materials.id, materials.material_code AS item_code ', ' ', 'materials.bar_code = "' + $('#barcode').val()  + '" ');
			}
			
			
			if($('#barcode').val() != '') {
				$('a.close-modal').click();
				$(this).find('.notice').html('');    
			} else {				
				$(this).find('.notice').html('<p class="error">Please scan barcode</p>');
      	$('#barcode').focus();
			}
			
	}});
			
	$('#btn_scan').click(function() {
		setTimeout(function(){
			$('#barcode').focus();	
			$('#barcode').val(''); 
		},50);		 
	});
	
	// $('#item_code').on('input', function(e) {
		// alert('test');
	// });
	
	$('#entry_barcode').click(function() {
		$('#btn_scan').click();
	});	
	
	$('#entry_barcode').Watermark("Click here and scan");
	
	$('#add-scan-form').ready(function() {
  	$('#add-scan-form').add_scan();
    $('.scan-submit-reset').submit_reset_scan();
  });  
  
  jQuery.fn.submit_reset_scan = function() {
  	
  	this.click(function() {
  		
  	  var x			= $(this).attr('alt');
  	  var form		= $('#add-scan-form');// $(this).closest('form');
  	  var notice	= form.find('.notice');
  	  var complete	= true;
  	  
  	  notice.empty();
  	  form.find('#item_action').val(x);
  	  
  	  form.find('.required').each(function() {
        if($(this).val() == '') complete = false;
      });
      
      if(complete == false) {
      	notice.html('<p class="error">Please scan barcode</p>');
      	$('#barcode').focus();
      	return false;
      }
      
  	  form.submit();
  	  form.find('.text').val('');
  	  $('#item_code').focus();
  	  if(x == 0) $('a.close-modal').click();
  	});
  }
  
  jQuery.fn.add_scan = function() {
    this.submit(function(e) {
      e.preventDefault();            
      
      if($('#barcode').val()!='') {
				get_barcode_data('materials', 'materials.id, materials.material_code AS item_code ', ' ', 'materials.bar_code = "' + $('#barcode').val()  + '" ');
			}
			$('#entry_barcode').val($('#barcode').val());	
    });
  }
	
});
</script>
<?php require('footer.php'); ?>