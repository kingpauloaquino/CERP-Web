<?php
  /*
   * Module: Locations - Show
  */
  $capability_key = 'show_terminal';
  require('header.php');
	
	if(isset($_REQUEST['tid'])) {
  	$terminal = $DB->Find('terminals', array(
  		'columns' => 'terminals.*, locations.location_code AS bldg', 
  		'joins' => 'INNER JOIN locations ON terminals.location_id=locations.id',
  	  'conditions' => 'terminals.id = '.$_REQUEST['tid']
  	  ));
	}
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['terminals']['url'].'" class="nav">'.$Capabilities->All['terminals']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['add_terminal']['url'].'" class="nav">'.$Capabilities->All['add_terminal']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_terminal']['url'].'?tid='.$_REQUEST['tid'].'" class="nav">'.$Capabilities->All['edit_terminal']['name'].'</a>'; 
										if(if_contains($terminal['bldg'],'WH1')) $url = 'terminal-wh-items.php?typ=MAT&tid='.$terminal['id'];
					if(if_contains($terminal['bldg'],'WH2')) $url = 'terminal-wh-items.php?typ=PRD&tid='.$terminal['id'];
					if(if_contains($terminal['bldg'],'WIP')) $url = 'terminal-prod-items.php?tid='.$terminal['id'];	
					
				  echo '<a href="'.$url.'" class="nav">Terminal Items</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
        <h3 class="form-title">Basic Information</h3>
        
        <div class="field">
          <label class="label">Location:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $terminal['bldg'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Terminal Code:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $terminal['terminal_code'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Terminal:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $terminal['terminal_name'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Type:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $terminal['type'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>

        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea readonly="readonly"><?php echo $terminal['description'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>
        
        <br/>
        
      	<h3 class="form-title">Devices</h3>
	      <div class="grid jq-grid">
	        <table cellspacing="0" cellpadding="0">
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
								if(!$devices) {
									echo '<tr>';
									echo '<td colspan="5">No Record</td>';
									echo '</tr>';
								} else {
									$ctr = 1;
									foreach ($devices as $device) {
										echo '<tr>';
										echo '<td class="border-right text-center">'.$ctr.'</td>';
										echo '<td class="border-right text-center"><a href="devices-show.php?did='.$device['device_id'].'">'.$device['device_code'].'</a></td>';
										echo '<td class="border-right text-center">'.$device['make'].'</td>';
										echo '<td class="border-right">'.$device['model'].'</td>';
										echo '<td class="border-right text-center">'.$device['serial_no'].'</td>';
										echo '</tr>';
										$ctr+=1;
									}
								}
							?>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>

<?php require('footer.php'); ?>