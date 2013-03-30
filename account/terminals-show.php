<?php
  /*
   * Module: Locations - Show
  */
  $capability_key = 'show_terminal';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
		if(isset($_GET['tid'])) {
	  	$terminal = $DB->Find('terminals', array(
	  		'columns' => 'terminals.*, locations.location_code AS bldg', 
	  		'joins' => 'INNER JOIN locations ON terminals.location_id=locations.id',
	  	  'conditions' => 'terminals.id = '.$_GET['tid']
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
				  echo '<a href="'.$Capabilities->All['edit_terminal']['url'].'?tid='.$_GET['tid'].'" class="nav">'.$Capabilities->All['edit_terminal']['name'].'</a>'; 
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
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Location:</td><td width="310"><input type="text" value="<?php echo $terminal['bldg'] ?>" class="text-field" disabled/></td>
              <td width="150">Terminal Code:</td><td><input type="text" value="<?php echo $terminal['terminal_code'] ?>" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>Terminal:</td><td><input type="text" value="<?php echo $terminal['terminal_name'] ?>" class="text-field" disabled/></td>
              <td>Type:</td><td><input type="text" value="<?php echo $terminal['type'] ?>" class="text-field" disabled/></td>
           </tr>          
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text"  class="text-field" value="<?php echo $terminal['description']?>" style="width:645px" disabled/>
              </td>
           </tr>
           <tr><td height="5" colspan="99"></td></tr>
        </table>
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
								  	    'conditions' 	=> 'terminal_devices.terminal_id='.$_GET['tid']));
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

<?php }
require('footer.php'); ?>