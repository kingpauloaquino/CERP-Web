<?php
  /* Module: Material Requests  */
  $capability_key = 'add_material_requests';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
  	$request = $Query->material_request_by_id($_GET['rid']);
		$request_types = $Query->get_lookups('request_type');
?>
      <!-- BOF PAGE -->
      <div id="page">
        <div id="page-title">
          <h2>
            <span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
		        <?php
		        	echo '<a href="'.$Capabilities->All['material_requests']['url'].'" class="nav">Requests List</a>';
						?>
            <div class="clear"></div>
          </h2>
        </div>

        <div id="content">
          <form id="request-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
          	<input type="hidden" id="request-status" value="<?php echo $purchase['status']?>"/>
          	<input type="hidden" id="purchase-aprroved-by" value="<?php echo $purchase['approved_by']?>"/>
          	<input type="hidden" id="user-level" value="<?php echo $_SESSION['user']['level']?>"/>
          	
             <!-- BOF TEXTFIELDS -->
             <div>
             	<table>
                   <tr>
                      <td width="120">Type:</td><td width="340"><?php select_query_tag($request_types, 'id', 'description', '', 'request-type', 'request[request_type]', '', 'width:192px;'); ?></td>
                      <td width="120">Batch Number:</td><td width="340"><input type="text" name="request[batch_no]" class="text-field" required/></td>
                   </tr>
                   <tr>
                      <td>Expected Date:</td><td><input type="text" name="request[expected_date]" class="text-field date-pick-week" required/></td>
                      <td>Status:</td><td><input type="text" value="Pending" class="text-field" disabled/></td>
                   </tr>
                   <tr>
                      <td>Requested Date:</td><td><input type="text" value="<?php echo date("F d, Y") ?>" class="text-field date-pick-week" required/></td>
                      <td>Requested By:</td><td><input type="text" value="<?php echo $_SESSION['user']['first_name'].' '.$_SESSION['user']['last_name']; ?>" class="text-field" disabled/></td>
                   </tr>
                   <tr>
                      <td>Received Date:</td><td><input type="text" class="text-field" disabled/></td>
                      <td>Received By:</td><td><input type="text" value="<?php echo $_SESSION['user']['first_name'].' '.$_SESSION['user']['last_name']; ?>" class="text-field" disabled/></td>
                   </tr>
                   <tr><td height="5" colspan="99"></td></tr>
                </table>
             </div>
             
             <!-- BOF GRIDVIEW -->
             <div id="grid-request-items" class="grid jq-grid" style="min-height:146px;">
               <table cellspacing="0" cellpadding="0">
                 <thead>
                   <tr>
                     <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all" disabled/></td>
                     <td width="30" class="border-right text-center">No.</td>
                     <td class="border-right">Material</td>
                 			<td width="130" class="border-right text-center">Type</td>
                 			<td width="70" class="border-right text-center">Unit</td>
                     <td width="70" class="border-right text-center">Qty</td>
                   </tr>
                 </thead>
                 <tbody id="request-items"></tbody>
               </table>
             </div>
             
           
             
             <!-- BOF REMARKS -->
             <div>
             	<table width="100%">
                   <tr><td height="5" colspan="99"></td></tr>
                   <tr>
                      <td></td>
                      <td align="right"></td>
                   </tr>
                   <tr><td colspan="2">Remarks:<br/><textarea style="min-width:650px;width:98.9%;height:50px;" disabled><?php echo $request['remarks']; ?></textarea></td></tr>
                </table>
             </div>
             
             <div class="field-command">
           	   <div class="text-post-status">
           	     <strong></strong>
									
               </div>
               <?php if($request['status'] != "Publish") { ?>
               <input type="button" value="Issue" class="btn redirect-to" rel="<?php echo host('production-material-requests-edit.php?rid='. $request['id']); ?>" disabled/>
           	   <?php } ?>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('production-material-requests.php'); ?>"/>
             </div>
          </form>
       </div>

       
       <script>
       	$(function() {
			  	var data = { 
			    	"url":"/populate/material-request-items.php?rid=<?php echo $request['id']; ?>",
			      "limit":"50",
						"data_key":"material_request_items",
						"row_template":"row_template_material_request_items_read_only"
					}
				
					$('#grid-request-items').grid(data);
			  }) 
			  
			  $.fn.approve = function() {
			  	this.click(function(e) {
			  		e.preventDefault();
						var form = $(this).attr('href');
						
						$.post(document.URL, $(form).serialize(), function(data) {
			      }).done(function(data){
			      	$('#btn-approval').hide(); 
			      	window.location = document.URL;
			      });	
			  	})
			  }
      </script>

<?php }
require('footer.php'); ?>