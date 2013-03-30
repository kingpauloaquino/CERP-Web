<?php
  /*
   * Module: Production Plan Parts Request
  */
  $capability_key = 'send_production_part_request';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
	  if(isset($_GET['popid'])) {
	  	$ppop = $DB->Find('production_purchase_order_products', array(
					  			'columns' 		=> 'id AS ppopid, product_id, lot_no, init',
					  	    'conditions' 	=> 'id = '.$_GET['popid']
			));			
	  }
	
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				   echo '<a href="'.$Capabilities->All['show_production_plan']['url'].'?ppoid='.$_GET['ppoid'].'&oid='.$_GET['oid'].'" class="nav">'.$Capabilities->All['show_production_plan']['name'].'</a>'; 
					 echo '<a href="'.$Capabilities->All['show_production_parts']['url'] . '?' . 					 
					 												http_build_query(array('ppoid' => $_GET['ppoid'], 'oid' => $_GET['oid'], 'popid' => $_GET['popid'], 'prod_lot_no' => $_GET['prod_lot_no'], 
					 												'prod' => $_GET['prod'], 'po_no' => $_GET['po_no'], 'po_date' => $_GET['po_date'], 'delivery_date' => $_GET['delivery_date'], 
					 												'target_date' => $_GET['target_date'], 'status' => $_GET['status'])) 
																	. '" class="nav">'.$Capabilities->All['show_production_parts']['name'].'</a>';
				  // echo '<a href="'.$Capabilities->All['edit_product_tree']['url'].'?pid='.$_GET['pid'].'&code='.$_GET['code'].'" class="nav">'.$Capabilities->All['edit_product_tree']['name'].'</a>';
					// echo '<a href="'.$Capabilities->All['show_product']['url'].'?pid='.$_GET['pid'].'" class="nav">'.$Capabilities->All['show_product']['name'].'</a>'; 

				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" placeholder="Search" value="<?php echo $_GET['popid']; ?>" style="display: none" />
      </div>
      <form id="order-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
				<input type="hidden" name="action" value="parts_request"/>		
				<input type="hidden" name="order_id" value="<?php echo $_GET['oid']?>"/>		
				<input type="hidden" name="prod_lot_no" value="<?php echo $_GET['prod_lot_no']?>"/>		
				
				
				<h3 class="form-title">Details</h3>
				<table>
           <tr>
              <td width="150">Production Plan ID:</td><td width="340"><input type="text" value="CPP-<?php echo $_GET['ppoid'] ?>" class="text-field" disabled/></td>
              <td width="150">P/O Number:</td><td width="340"><input type="text" value="<?php echo $_GET['po_no'] ?>" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>P/O Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($_GET['po_date'])) ?>" class="text-field text-date" disabled/></td>
              <td>P/O Delivery Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($_GET['delivery_date'])) ?>" class="text-field text-date" disabled/></td>
           </tr>
           <tr>
              <td>Production Target Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($_GET['target_date'])) ?>" class="text-field text-date" disabled/></td>
              <td>Status:</td><td><input type="text" value="<?php echo $_GET['status'] ?>" class="text-field" disabled/></td>
           </tr> 
           <tr><td height="5" colspan="99"></td></tr>
        </table>
				<br/>
        <h3 class="form-title"><a href="products-show.php?pid=<?php echo $_GET['pid'] ?>" target="_blank"><?php echo $_GET['prod']?></a> Parts</h3>
				<div class="grid jq-grid" style="min-height:146px;">
		      <table id="tbl-parts" cellspacing="0" cellpadding="0">
		        <thead>
		          <tr>
		            <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all"/></td>
		            <td width="30" class="border-right"><a></a></td>
		            <td width="15%" class="border-right text-center"><a>Material Code</a></td>
		            <td width="20%" class="border-right text-center"><a>Expected</a></td>
		            <td class="border-right text-center"><a>Status</a></td>
		            <td width="10%" class="border-right text-center"><a>UOM</a></td>
		            <td width="10%" class="border-right text-center"><a>Qty</a></td>
		            <td width="10%" class="border-right text-center"><a>Plan Qty</a></td>
		            <td width="10%" class="border-right text-center"><a>Location</a></td>
		          </tr>
		        </thead>
		        <tbody></tbody>
					</table>
				</div>	
				<div class="field-command">
					<input type="submit" value="Request" class="btn"/>
					<input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('production-plan-parts-show.php?ppoid='.$_GET['ppoid'].'&oid='.$_GET['oid'].'&popid='.$_GET['popid'].'&prod_lot_no='.$_GET['prod_lot_no'].'&prod='.$_GET['prod']); ?>"/>
				</div>
				
			</form>
			<br/>
		</div>
	</div>
<script>
	$(function() {
		var data = { 
    	"url":"/populate/parts-requests.php",
      "limit":"50",
			"data_key":"parts_requests",
			"row_template":"row_template_parts_requests"
		}
		
		$('#tbl-parts').grid(data);
  	$('.timepick').datetimepicker();
  }) 
 </script>

<?php }
require('footer.php'); ?>