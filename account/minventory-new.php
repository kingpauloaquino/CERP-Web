<?php
  /*
   * Module: WH1 Terminal 
  */
  $capability_key = 'add_material_inventory';
  require('header.php');
	
	if($_POST['action'] == 'add_material_inventory') {
		$id = $DB->Find('warehouse_inventories', array('columns' => 'id', 'conditions'  => 'item_type="MAT" AND item_id='.$_POST['item_id'].' AND 
																														invoice_no ="'.$_POST['invoice_no'].'" AND lot_no="'.$_POST['lot_no'].'" ')); 
		if($id==NULL) {
			$invt = array(
	  	  'item_id'		=> $_POST['item_id'],'item_type'	=> $_POST['item_type'], 'invoice_no'=> $_POST['invoice_no'], 'lot_no'		=> $_POST['lot_no'],
	  	  'terminal'	=> $_POST['terminal'], 'qty'		=> $_POST['qty'], 'remarks'		=> $_POST['remarks'] );
			$in_id = $Posts->AddWarehouseInventory($invt);
			$invt = array(
	  	  'item_id'		=> $_POST['item_id'],'item_type'	=> $_POST['item_type'], 'invoice_no'=> $_POST['invoice_no'], 'lot_no'		=> $_POST['lot_no'],
	  	  'terminal'	=>  2, 'qty' => 0, 'remarks'		=> $_POST['remarks'] );
			$out_id = $Posts->AddWarehouseInventory($invt);
			
		} else {
			$args = array('type' => $_POST['inventory_type'], 'qty' => $_POST['qty'], 'remarks' => $_POST['remarks'], 'item_type' => 'MAT', 'terminal_id' => $_POST['terminal'],
										'item_id' => $_POST['item_id'], 'invoice_no' => $_POST['invoice_no'], 'lot_no' => $_POST['lot_no']); 
			$num_of_records = $Posts->EditWarehouseInventory($args);
			
		}
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

				<input type="hidden" name="action" value="add_material_inventory"> 
				<input type="hidden" name="item_type" value="MAT"> 
				<input type="hidden" name="terminal_id" value=""> 
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
          	$terminals = $DB->Get('terminals', array('columns' => 'id, CONCAT(terminal_code," - ", terminal_name) AS terminal', 'conditions' => 'location_id=2')); // location_id 4 : WIP
		        	select_query_tag($terminals, 'id', 'terminal', '', 'terminal', 'terminal', '', ''); ?>
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
          <label class="label">Invoice No.:</label>
          <div class="input">
            <input id="invoice_no" name="invoice_no" type="text"  />
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Lot No.:</label>
          <div class="input">
            <input id="lot_no" name="lot_no" type="text"  />
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Type:</label>
          <div class="input">
            <?php 
		        	$types = $DB->Get('lookups', array('columns' => 'id, description', 
		        																			'conditions'  => 'parent = "'.get_lookup_code('inventory_type').'" AND (description = "Input" OR description = "Output")', 'sort_column' => 'id'));
		        	select_query_tag($types, 'description', 'description', '', 'inventory_type', 'inventory_type', '', ''); ?>
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