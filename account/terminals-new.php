<?php
  /*
   * Module: Terminals - New
  */
  $capability_key = 'add_terminal';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		if($_POST['action'] == 'add_terminal') {
			$terminal_id = $Posts->AddTerminal($_POST['terminal']);
	
			$devices = $_POST['devices'];
			
			if(!empty($devices)) {
	      $fields = array('device_id');
			  foreach ($devices as $device) {
			  	$new_devices = array();
			    foreach (explode('|', $device) as $index => $field) {
			  	  $new_devices[$fields[$index]] =  $field;
			    }
					$new_devices['terminal_id'] = $terminal_id;
					$Posts->AddTerminalDevice($new_devices);
			  }			
			}		
			redirect_to($Capabilities->All['show_terminal']['url'].'?tid='.$terminal_id);
		} 
		
		$bldgs = $DB->Get('locations', array('columns' => 'id, location_code', 'conditions' => 'parent = "'.get_lookup_code('loc_bldg').'"'));
		$devices = $DB->Get('devices', array('columns' => 'id, device_code'));
?>
<script>	
	$(document).ready(function() {
		$('[name*="terminal[terminal_code]"]').val($('[name*="terminal[location_id]"] option:selected').text()+'-'+$('[name*="terminal[terminal_code]"]').val());
    $('[name*="terminal[location_id]"]').change(function(){
    	$('[name*="terminal[terminal_code]"]').val('');
			$('[name*="terminal[terminal_code]"]').val($('[name*="terminal[location_id]"] option:selected').text()+'-'+$('[name*="terminal[terminal_code]"]').val());
    });       
    
    $('#device_code').change(function(){
    	$.ajax({
					type: 'POST',
					url: '..//include//livefilter.php',
					data: { filterType: 'devices',
									table: 			'devices',
									joins: 			'',
									columns: 		'devices.id, devices.device_code, devices.make, devices.model, devices.serial_no ',
									conditions: 'devices.id='+$('#device_code option:selected').val()
								},
					contentType: 'application/x-www-form-urlencoded',
					dataType: 'json',
					cache: false,
					success: function(data) {	
						if(data==null) {
							$('#make').val('');
							$('#model').val('');
							$('#serial_no').val('');
						}		
    				$.each(data, function(index, element) {    					
            	index == 'make' ? $('#make').val(element) : '';
            	index == 'model' ? $('#model').val(element) : '';
            	index == 'serial_no' ? $('#serial_no').val(element) : '';
		        });
					}
				});
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
			<form class="form-container" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
				<input type="hidden" name="action" value="add_terminal"> 
				<div class="form-container">
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Location:</td><td width="310"><?php select_query_tag($bldgs, 'id', 'location_code', '', 'terminal[location_id]', 'terminal[location_id]', '', 'width:192px;'); ?></td>
              <td width="150">Terminal Code:</td><td><input type="text" id="terminal[terminal_code]" name="terminal[terminal_code]"  class="text-field" /></td>
           </tr>
           <tr>
              <td>Terminal:</td><td><input type="text" id="terminal[terminal_name]" name="terminal[terminal_name]" class="text-field"/></td>
              <td>Type:</td><td><?php select_tag(array('IN', 'OUT'), $terminal['type'], 'terminal[type]', 'terminal[type]', '', 'width:192px;', TRUE); ?></td>
           </tr>          
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" id="terminal[description]" name="terminal[description]" class="text-field" style="width:645px"/>
              </td>
           </tr>
           <tr><td height="5" colspan="99"></td></tr>
        </table>
				</div>
        <br/>
        
        <h3 class="form-title">Devices</h3>
	      <div class="grid jq-grid">
	        <table id="tbl-devices" cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td width="80" class="border-right text-center"><a></a></td>
	              <td width="100" class="border-right text-center"><a>Device Code</a></td>
	              <td width="100" class="border-right text-center"><a>Make</a></td>
	              <td class="border-right text-center"><a>Model</a></td>
	              <td width="100" class="border-right text-center"><a>Serial No.</a></td>
	            </tr>
	          </thead>
	          <tbody></tbody>
					</table>
				</div>
					<br/>
					<div class="field-command">
						<div class="text-post-status"></div>
						<a class="btn" href="#add-device-modal" rel="modal:open">Add Device</a>		
       	    <input type="submit" value="Create" class="btn"/>
            <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('terminals.php'); ?>"/>
					</div>
			</form>
			
			<div id="add-device-modal" class="modal" style="width:485px;">
				<?php
					$devices = $DB->Get('devices', array('columns' => 'id, device_code'));
				?>
				<div class="modal-title"><h3>Add Device</h3></div>
				<div class="modal-content">
					<form id="add-device-form">
               <span class="notice"></span>
			      	 <input type="hidden" id="item_action" value="1"/>
			      	 
			      	 <div class="field">
                  <label>Device Code:</label>
                  <?php select_query_tag($devices, 'id', 'device_code', '', 'device_code', 'device_code', '-', 'width:192px;'); ?>
               </div>               
               
			      	 <div class="field">
                  <label>Make:</label>
                  <input type="text" id="make" name="make" value="" class="text-field" disabled />
               </div>
               
			      	 <div class="field">
                  <label>Model:</label>
                  <input type="text" id="model" name="model" value="" class="text-field" disabled />
               </div>
			         
			      	 <div class="field">
                  <label>Serial #:</label>
                  <input type="text" id="serial_no" name="serial_no" value="" class="text-field" disabled />
               </div>
			      </form>	
				</div>
				<div class="modal-footer">					
					<input type="button" id="device-continue" value="Continue" alt="1" class="device-submit-reset"/>
       		<input type="button" id="device-add-close" value="Add & Close" alt="0" class="device-submit-reset"/>
				</div>
			</div>
			
		</div>
	</div>
<script>
  $('#add-device-form').ready(function() {
  	$('#add-device-form').add_device();
    $('.device-submit-reset').submit_reset_device();
    
  });      
  
  jQuery.fn.submit_reset_device = function() {
  	this.click(function() {
  	  var x			= $(this).attr('alt');
  	  var form		= $('#add-device-form');
  	  var notice	= form.find('.notice');
  	  var complete	= true;
  	  
  	  notice.empty();
  	  form.find('#item_action').val(x);
  	  
  	  form.find('.required').each(function() {
        if($(this).val() == '') complete = false;
      });
      
      if(complete == false) {
      	notice.html('<p class="error">Please complete all the required fields</p>');
      	return false;
      }
      
  	  form.submit();
  	  form.find('.text').val('');
  	  if(x == 0) $('a.close-modal').click();
  	});
  }
  
  jQuery.fn.add_device = function() {
    this.submit(function(e) {
      e.preventDefault();
      
      var device_id			= $('#device_code option:selected').val() || '';
      var device_code		= $('#device_code option:selected').text() || '';
      var device_make		= $('#make').val() || '';
      var device_model	= $('#model').val() || '';
      var serial_no			= $('#serial_no').val() || '';      
            
      var device_values		= device_id;
      var tr				= $('<tr style="background-color:blue;"></tr>');
     
  	  tr.append('<td class="border-right red_highlyt"><input type="hidden" name="devices[]" value="'+device_values+'"/>#</td>');
  	  tr.append('<td class="border-right text-center red_highlyt">'+device_code+'</td>');
  	  tr.append('<td class="border-right text-center red_highlyt">'+device_make+'</td>');
  	  tr.append('<td class="border-right red_highlyt">'+device_model+'</td>');
  	  tr.append('<td class="border-right text-center red_highlyt">'+serial_no+'</td>');
  	  
      $('#tbl-devices').find('tbody').append(tr);	    
    });
  }
</script>

<?php }
require('footer.php'); ?>