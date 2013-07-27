<?php
  /* Module: Material Requests Release */
  $capability_key = 'material_requests_release';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
  	$request = $Query->material_request_by_id($_GET['rid']);
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
    	<input type="hidden" name="action" value="release_materials" />
    	<input type="hidden" name="rid" value="<?php echo $_GET['rid'] ?>" />
    	<input type="hidden" name="completion_status" value="25" />
    	
       <!-- BOF TEXTFIELDS -->
       <div>
       	<table>
             <tr>
                <td width="120">Request Number:</td><td width="340"><input type="text" value="<?php echo $request['request_no']; ?>" class="text-field magenta" disabled/></td>
                <td width="120"></td><td width="340"></td>
             </tr>
             <tr>
                <td>Type:</td><td><input type="text" value="<?php echo $request['type']; ?>" class="text-field" disabled/></td>
                <td>Batch Number:</td><td><input type="text" value="<?php echo $request['batch_no']; ?>" class="text-field magenta" disabled/></td>
             </tr>
             <tr>
                <td>Expected Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($request['expected_date'])) ?>" class="text-field" disabled/></td>
                <td>Status:</td><td><input type="text" value="<?php echo $request['completion_status']; ?>" class="text-field" disabled/></td>
             </tr>
             <tr>
                <td>Requested Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($request['requested_date'])) ?>" class="text-field" disabled/></td>
                <td>Requested By:</td><td><input type="text" value="<?php echo $request['requested_by']; ?>" class="text-field" disabled/></td>
             </tr>
             <tr>
                <td>Received Date:</td><td><input type="text" value="<?php echo $rcv_date = ($request['received_date']!='') ? date("F d, Y", strtotime($request['received_date'])) : '' ?>" class="text-field" disabled/></td>
                <td>Received By:</td><td><input type="text" value="<?php echo $request['received_by']; ?>" class="text-field" disabled/></td>
             </tr>
             <tr><td height="5" colspan="99"></td></tr>
          </table>
       </div>
       
       <!-- BOF GRIDVIEW -->
       <div id="grid-request-items" class="grid jq-grid" style="min-height:146px;">
         <table cellspacing="0" cellpadding="0">
           <thead>
             <tr>
               <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all"/></td>
               <td width="30" class="border-right text-center">No.</td>
               <td width="120" class="border-right">Material</td>
               <td class="border-right">Description</td>
           			<td width="100" class="border-right text-center">Type</td>
           			<td width="50" class="border-right text-center">Unit</td>
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
      
      <div id="div-notice" style="display: none">
        <span class="notice">
          <p class="info"><strong>Notice: </strong>Unable to issue until warehouse stock is replenished.</p>
      	</span>	
      </div> 
			
    	
       <div class="field-command">
     	   <div class="text-post-status">
     	     <strong></strong>
						
         </div>
         <?php if($request['completion_status'] == "Issued") { ?>
         <input id="btn-submit" type="submit" value="Proceed" class="btn"/>
     	   <?php } ?>
         <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('production-material-requests.php'); ?>"/>
       </div>
    </form>
 </div>
       
       

       
   <script>
   	$(function() {
	  	var data = { 
	    	"url":"/populate/material-requests-issue.php?rid=<?php echo $request['id']; ?>",
	      "limit":"50",
				"data_key":"material_request_issue",
				"row_template":"row_template_material_request_release"
				
			}
		
			$('#grid-request-items').grid(data);
			
			$('#btn-submit').click(function(e){
				e.preventDefault();
				var ctr = 0;
				$('#request-items > tr').each(function(){
					if($(this).find('.wh-stock').hasClass('disable-issue')) {
						ctr++;
					}
				});
				if(ctr>0) {
					$('#div-notice').show('slow');
				} else {
					$('#div-notice').hide('slow');
					// submit
					$.post(document.URL, $($('#request-form')).serialize(), function(data) {
					  window.location = document.URL;
					});
				}
			});
	  }) 
	  
  </script>

<?php }
require('footer.php'); ?>