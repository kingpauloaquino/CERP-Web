<?php
  /*
   * Module: Terminals 
  */
  $capability_key = 'terminals';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['add_terminal']['url'].'" class="nav">'.$Capabilities->All['add_terminal']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<div class="form-container">
				<div class="grid jq-grid">
	        <table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td class="border-right text-center"><a>Location</a></td>
	              <td class="border-right text-center"><a>Terminal Code</a></td>
	              <td class="border-right text-center"><a>Terminal</a></td>
	              <td class="border-right text-center"><a>Type</a></td>
	              <td class="border-right text-center"><a>Description</a></td>
	              <td class="border-right text-center"><a>Items</a></td>
	            </tr>
	          </thead>
	          <tbody>
	          	<?php
								$bldgs = $DB->Get('locations', array(
									  			'columns' 		=> 'locations.id AS loc_id, locations.location_code, locations.location, locations.description',
									  	    'conditions' 	=> 'parent = "BLDG"', 
													'order'	=> 'locations.id'));
								foreach ($bldgs as $bldg) {
									echo '<tr class="highlight">';
									echo '<td colspan="6"><b>'.$bldg['location_code'].'</b></td>';
									echo '</tr>';
									
									$trmls = $DB->Get('terminals', array(
										  			'columns' 		=> 'terminals.*',
										  	    'conditions' 	=> 'terminals.location_id='.$bldg['loc_id'], 
														'order'	=> 'terminals.terminal_code'));
									foreach ($trmls as $trml) {
										echo '<tr>';
										echo '<td class="border-right text-center"></td>';
										echo '<td class="border-right text-center"><a href="terminals-show.php?tid='.$trml['id'].'">'.$trml['terminal_code'].'</a></td>';
										echo '<td class="border-right text-center">'.$trml['terminal_name'].'</td>';
										echo '<td class="border-right text-center">'.$trml['type'].'</td>';
										echo '<td class="border-right">'.$trml['description'].'</td>';										
										if(if_contains($trml['terminal_code'],'WH1')) $url = 'terminal-wh-items.php?typ=MAT&tid='.$trml['id'];
										if(if_contains($trml['terminal_code'],'WH2')) $url = 'terminal-wh-items.php?typ=PRD&tid='.$trml['id'];
										if(if_contains($trml['terminal_code'],'WIP')) $url = 'terminal-prod-items.php?tid='.$trml['id'];								
										echo '<td class="border-right text-center"><a href="'.$url.'">view</a></td>';
										echo '</tr>';
									}
								}
							?>
	        	</tbody>
	      	</table>
	    	</div>
    	</div>
		</div>
	</div>

<?php }
require('footer.php'); ?>