<?php
  /*
   * Module: Terminals - Edit
  */
  $capability_key = 'edit_terminal';
  require('header.php');
	
	if($_POST['action'] == 'edit_terminal') {
		$args = array('variables' => $_POST['terminal'], 'conditions' => 'id='.$_POST['tid']); 
		$num_of_records = $Posts->EditTerminal($args);
		
		$devices = $_POST['devices'];
		
		if(!empty($devices)) {
      $fields = array('device_id');
		  foreach ($devices as $device) {
		  	$new_devices = array();
		    foreach (explode('|', $device) as $index => $field) {
		  	  $new_devices[$fields[$index]] =  $field;
		    }
				$new_devices['terminal_id'] = $_POST['tid'];
				$Posts->AddTerminalDevice($new_devices);
		  }						
		}
		redirect_to($Capabilities->All['show_terminal']['url'].'?tid='.$_POST['tid']);	
	} 
	
	if(isset($_REQUEST['tid'])) {
  	$terminal = $DB->Find('terminals', array(
  		'columns' => 'terminals.*, locations.location_code AS bldg', 
  		'joins' => 'INNER JOIN locations ON terminals.location_id=locations.id',
  	  'conditions' => 'terminals.id = '.$_REQUEST['tid']
  	  ));
	}
	
	$bldgs = $DB->Get('locations', array('columns' => 'id, location_code', 'conditions' => 'parent = "'.get_lookup_code('loc_bldg').'"'));
	
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
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['show_terminal']['url'].'?tid='.$_REQUEST['tid'].'" class="nav">'.$Capabilities->All['show_terminal']['name'].'</a>';
				  echo '<a href="'.$Capabilities->All['add_terminal']['url'].'" class="nav">'.$Capabilities->All['add_terminal']['name'].'</a>';  
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
				<div class="form-container">
					<h3 class="form-title">Basic Information</h3>
					<span class="notice">
	<!--           <p class="info"><strong>Notice!</strong> Material codes should be unique.</p> -->
	        </span>
	        
					<input type="hidden" name="action" value="edit_terminal"/>
					<input type="hidden" name="tid" value="<?php echo $_REQUEST['tid'] ?>"/>
	
					<div class="field">
	          <label class="label">Location:</label>
	          <div class="input">
	            <?php select_query_tag($bldgs, 'id', 'location_code', $terminal['location_id'], 'terminal[location_id]', 'terminal[location_id]', '', 'text w180'); ?>
	          </div>
	          <div class="clear"></div>
	        </div>
	        
	        <div class="field">
	          <label class="label">Terminal Code:</label>
	          <div class="input">
	            <input type="text" id="terminal[terminal_code]" name="terminal[terminal_code]" value="<?php echo substr($terminal['terminal_code'], 4) ?>"/>
	          </div>
	          <div class="clear"></div>
	        </div>        
	        
	        <div class="field">
	          <label class="label">Terminal:</label>
	          <div class="input">
	            <input type="text" id="terminal[terminal_name]" name="terminal[terminal_name]" value="<?php echo $terminal['terminal_name'] ?>"/>
	          </div>
	          <div class="clear"></div>
	        </div>
	
	        <div class="field">
	          <label class="label">Type:</label>
	          <div class="input">
	          	<?php select_tag(array('IN', 'OUT'), $terminal['type'], 'terminal[type]', 'terminal[type]', NULL, 'text w180', TRUE); ?>
	          </div>
	          <div class="clear"></div>
	        </div>
	        
	        <div class="field">
	          <label class="label">Description:</label>
	          <div class="input">
	            <textarea id="terminal[description]" name="terminal[description]"><?php echo $terminal['description'] ?></textarea>
	          </div>
	          <div class="clear"></div>
	        </div>	
	        
					<br/>
					
	      	<h3 class="form-title">Devices</h3>
		      <div class="grid jq-grid">
						<table id="tbl-devices" cellpadding="0" cellspacing="0">
				      <thead>
				         <tr>
	              		<td width="5%" class="border-right text-center"><a></a></td>
	              		<td width="20%" class="border-right text-center"><a>Device Code</a></td>
	              		<td width="20%" class="border-right text-center"><a>Make</a></td>
	              		<td width="" class="border-right text-center"><a>Model</a></td>
	              		<td width="20%" class="border-right text-center"><a>Serial No.</a></td>
				         </tr>
				      </thead>
				      <tbody>
								<?php
									$devices = $DB->Get('terminal_devices', array(
									  			'columns' 		=> 'terminal_devices.*, devices.device_code, devices.make, devices.model, devices.serial_no',
									  			'joins'				=> 'INNER JOIN devices ON terminal_devices.device_id = devices.id',
									  	    'conditions' 	=> 'terminal_devices.terminal_id='.$_REQUEST['tid']));
									$ctr = 1;
									foreach ($devices as $device) {
										echo '<tr>';
										echo '<td class="border-right text-right">'.$ctr.'</td>';
										echo '<td class="border-right text-center"><a href="devices-show.php?did='.$device['device_id'].'">'.$device['device_code'].'</a></td>';
										echo '<td class="border-right text-center">'.$device['make'].'</td>';
										echo '<td class="border-right">'.$device['model'].'</td>';
										echo '<td class="border-right text-center">'.$device['serial_no'].'</td>';
										echo '</tr>';
										$ctr+=1;
									}
								?>
							</tbody>
				   </table>
					</div>
					<br/>
					<div class="field">
            <label class="label"></label>
            <div class="input">
              <a class="btn" href="#add-device-modal" rel="modal:open">Add Device</a>		
              <button class="btn">Update</button>
              <button class="btn" onclick="return cancel_btn();">Cancel</button>
            </div>
            <div class="clear"></div>
          </div>
          
				</div>
			</form>
			
			<div id="add-device-modal" class="modal">
				<?php
					$devices = $DB->Get('devices', array('columns' => 'id, device_code'));
				?>
			   <h4 class="title">Add Device</h4>
			   <div class="content">
			      <form id="add-device-form">
			   	     <span class="notice"></span>
			      	 <input type="hidden" id="item_action" value="1"/>
			         <div class="t-row">
			            <label>Device Code</label>
			            <?php select_query_tag($devices, 'id', 'device_code', '', 'device_code', 'device_code', '-', 'text w180'); ?>
			         </div>
			         
			         <div class="t-row">
			            <label>Make:</label>
			            <input type="text" id="make" name="make" value="" class="text w180 readonly" readonly="reaadonly" />
			         </div>
			         
			         <div class="t-row">
			            <label>Model:</label>
			            <input type="text" id="model" name="model" value="" class="text w180 readonly" readonly="reaadonly"/>
			         </div>
			         
			         <div class="t-row">
			            <label>Serial #:</label>
			            <input type="text" id="serial_no" name="serial_no" value="" class="text w180 readonly" readonly="reaadonly"/>
			         </div>
			         
			         <br/>
			         <div class="t-foot">
			           <input type="button" id="device-continue" value="Continue" alt="1" class="device-submit-reset"/>
			           <input type="button" id="device-add-close" value="Add & Close" alt="0" class="device-submit-reset"/>
			         </div>
			      </form>
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
  	  var form		= $(this).closest('form');
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
      var tr				= $('<tr style="background:#ffeef2"></tr>');
     
  	  tr.append('<td class="highlight_red border-right text-center"><input type="hidden" name="devices[]" value="'+device_values+'"/></td>');
  	  tr.append('<td class="highlight_red border-right text-center">'+device_code+'</td>');
  	  tr.append('<td class="highlight_red border-right text-center">'+device_make+'</td>');
  	  tr.append('<td class="highlight_red border-right">'+device_model+'</td>');
  	  tr.append('<td class="highlight_red border-right text-center">'+serial_no+'</td>');
  	  
      $('#tbl-devices').find('tbody').append(tr);	    
    });
  }
</script>
<?php require('footer.php'); ?>