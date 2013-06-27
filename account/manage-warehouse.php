<?php
  /* Module: Name  */
  $capability_key = 'manage_warehouse';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
		$mat_latest_count = $Query->latest_inventory_count('MAT');
		$prd_latest_count = $Query->latest_inventory_count('PRD');
		
		$mat_inventory_status = $Query->inventory_status_by_current_date('MAT', date('Y-m-d'));
?>
	<!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
      <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
				<input type="hidden" id="mat-locked" value="<?php echo $mat_inventory_status['is_locked']?>" />
				<input type="hidden" id="mat-updated" value="<?php echo $mat_inventory_status['is_updated']?>" />
				<input type="hidden" id="mydate" value="<?php echo date('Y-m-d')?>" />
				
				<h3 class="form-title">Raw Materials</h3>
        <table> 
           <tr>
              <td width="300">Latest Physical Inventory:</td><td><strong><?php echo strtoupper(date("F Y", strtotime($mat_latest_count['count_month']))) ?></strong></td>
           </tr>  
           <tr>
              <td>Lock Inventory Transactions:</td><td width="310"><input id="mat-lock" type="button" class="btn strd" value="LOCK" />&nbsp;
									<a href="#" title="Prevents changes on W/H raw-material inventory and marks start of physical count for the current month." class="tooltip"><span title="More">What's this?</span></a>
							</td>
           </tr>  
           <tr>
              <td>Update System Inventory:</td><td><input id="mat-update" type="button" class="btn strd" value="UPDATE" />&nbsp;
									<a href="#" title="Updates the system quantity of raw-materials with the current month's physical count." class="tooltip"><span title="More">What's this?</span></a>
							</td>
           </tr>  
           <tr>
              <td>Month-End Inventory:</td><td><input id="mat-report" type="button" value="VIEW" class="btn strd redirect-to" rel="<?php echo host('report-material-inventory.php'); ?>" /></td>
           </tr>  
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        <div class="field-command"></div><br/>
        
				<h3 class="form-title">Finished Goods</h3>
        <table> 
           <tr>
              <td width="300">Latest Physical Inventory:</td><td><strong><?php echo strtoupper(date("F Y", strtotime($prd_latest_count['count_month']))) ?></strong></td>
           </tr>  
           <tr>
              <td>Lock Inventory Transactions:</td><td width="310"><input type="button" class="btn strd" value="LOCK" />&nbsp;
									<a href="#" title="Prevents changes on W/H finished-goods inventory and marks start of physical count for the current month." class="tooltip"><span title="More">What's this?</span></a>
							</td>
           </tr>  
           <tr>
              <td>Update System Inventory:</td><td><input type="button" class="btn strd" value="UPDATE" />&nbsp;
									<a href="#" title="Updates the system quantity of finished-goods with the current month's physical count." class="tooltip"><span title="More">What's this?</span></a>
							</td>
           </tr>  
           <tr>
              <td>Month-End Inventory:</td><td><input id="prd-report" type="button" value="VIEW" class="btn strd redirect-to" rel="<?php echo host('report-product-inventory.php'); ?>" /></td>
           </tr>  
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        <div class="field-command"></div><br/>
        
				<h3 class="form-title">WIP</h3>
        <table> 
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        <div class="field-command"></div><br/>
        
				<h3 class="form-title">Shipping</h3>
        <table>  
           <tr>
              <td width="300">Quantity & Price:</td><td><input type="button" class="btn strd" value="VIEW" />&nbsp;<a href="#"></a></td>
           </tr>  
           <tr><td height="5" colspan="99"></td></tr>
        </table>
      </form>
		</div>
	</div>
	<script>
		$(function() {
			($('#mat-updated').val() == 0) ? $('#mat-update').attr('disabled', false).val('UPDATE') : $('#mat-update').attr('disabled', true).val('UPDATED');
			$('#mat-update').click(function(){
				$.post(document.URL, { action: "update_inventory_count", type: "MAT", mydate: $('#mydate').val() })
					.done(function(data) {
					$('#mat-update').attr('disabled', true).val('UPDATED'); 
				});
			});
			
			($('#mat-locked').val() == 0) ? $('#mat-lock').val('LOCK') : $('#mat-lock').val('UNLOCK');
			$('#mat-lock').click(function(){ 
				if($(this).val() == 'LOCK') {
					$.post(document.URL, { action: "update_inventory_count_date_and_lock", type: "MAT", mydate: $('#mydate').val() })
						.done(function(data) {
						$('#mat-lock').val('UNLOCK'); 
					});	
				} else {
					$.post(document.URL, { action: "update_inventory_and_unlock", type: "MAT", mydate: $('#mydate').val() })
						.done(function(data) {
						$('#mat-lock').val('LOCK'); 
					});	
				}
			})
	  });
	  
		
	</script>
<?php }
require('footer.php'); ?>