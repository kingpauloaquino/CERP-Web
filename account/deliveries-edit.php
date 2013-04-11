<?php
  $capability_key = 'users';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
	  $delivery = $Query->get_delivery_detail($_GET['id']);  
	  if($delivery['status'] == 83) {
	  	echo '<script>window.location.href="'. host('deliveries-show.php?id='. $delivery['id']) .'"</script>';
	  }
?>
      <!-- BOF PAGE -->
      <div id="page">
        <div id="page-title">
          <h2>
            <span class="title">Deliveries &raquo; <span class="red"><?php echo $delivery['delivery_receipt']; ?></span></span>
            <div class="clear"></div>
          </h2>
        </div>

        <div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" placeholder="Search" value="<?php echo $delivery['id']; ?>" style="display: none" />
      </div>
          <form id="delivery-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
          	 <input type="hidden" name="action" value="edit_delivery"/>
          	 <input type="hidden" name="delivery[id]" value="<?php echo $delivery['id']; ?>"/>
             <!-- BOF TEXTFIELDS -->
             <div>
             	<table>
                   <tr>
                      <td width="120">Delivery Receipt:</td><td width="340"><input type="text" name="delivery[receipt]" value="<?php echo $delivery['delivery_receipt']; ?>"/></td>
                      <td width="120"></td><td width="340"></td>
                   </tr>
                   <tr>
                      <td>Supplier:</td>
                      <td colspan="99">
                        <input type="text" value="<?php echo $delivery['supplier_name']; ?>" class="text-field" style="width:644px;" disabled="disabled"/>
                      </td>
                   </tr>
                   <tr>
                      <td>Delivery Via:</td><td><input type="text" name="delivery[via]" value="<?php echo $delivery['delivery_via']; ?>" class="text-field"/></td>
                      <td>Delivery Date:</td><td><input type="text" name="delivery[date]" value="<?php echo dformat($delivery['delivery_date'], 'm/d/Y'); ?>" class="text-field text-date"/></td>
                   </tr>
                   <tr>
                      <td>Trade Terms:</td><td><input type="text" name="delivery[trade_terms]" value="<?php echo $delivery['trade_terms']; ?>" class="text-field"/></td>
                      <td>Payment Terms:</td><td><input type="text" name="delivery[payment_terms]" value="<?php echo $delivery['payment_terms']; ?>" class="text-field"/></td>
                   </tr>
                   <tr><td height="5" colspan="99"></td></tr>
                </table>
             </div>
             
             <!-- BOF GRIDVIEW -->
             <div id="grid-purchase-materials" class="grid jq-grid" style="min-height:146px;">
               <table cellspacing="0" cellpadding="0">
                 <thead>
                   <tr>
                     <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all"/></td>
                     <td width="140" class="border-right">Item Code</td>
                     <td class="border-right">Description</td>
                     <td width="70" class="border-right text-center">Received</td>
                     <td width="50" class="border-right text-center">Passed</td>
                     <td width="100">Remarks</td>
                   </tr>
                 </thead>
                 <tbody id="purchase-materials"></tbody>
               </table>
             </div>
             
             <!-- BOF REMARKS -->
             <div>
             	<table width="100%">
                   <tr><td height="5" colspan="99"></td></tr>
                   <tr>
                      <td colspan="2">
                         <strong><a id="remove-purchase-materials" href="#" class="" grid="#purchase-materials">Remove Item</a></strong>
                      </td>
                   </tr>
                   <tr><td colspan="2">Remarks:<br/><textarea name="delivery[remarks]" style="min-width:650px;width:98.9%;height:50px;"><?php echo $delivery['remarks']; ?></textarea></td></tr>
                </table>
             </div>
             
             <div class="field-command">
           	   <div class="text-post-status">
           	     <strong>Save As:</strong>&nbsp;&nbsp;<select name="delivery[status]"><?php echo build_select_post_status($delivery['status'], ""); ?></select>
               </div>
           	   <input type="submit" value="Save" class="btn"/>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('receiving.php'); ?>"/>
             </div>
          </form>
       </div>
       
       <script>
        $(function() {
          var grid = $('#purchase-materials');
          var data = { 
            "url":"/populate/receive-items.php",
            "data_key":"materials",
            "row_template":"row_template_verify_deliver_materials"
          }
  
          $('.jq-grid').grid(data);
        })


        function row_template_verify_deliver_materials(data) {
          var status = '<?php echo $delivery['status']; ?>';
          var row = $("<tr></tr>");
          var passed = $("<input type=\"checkbox\" name=\"items["+ data['id'] +"][passed]\" disabled=\"disabled\"/>");
  
          if(status == 82) passed.removeAttr('disabled');
          if(data['passed'] == 1) passed.attr('checked', 'checked');
          
          row.append("<td class=\"border-right text-center\"><input type=\"checkbox\" class=\"chk-all\"/></td>");
          row.append("<td class=\"border-right\">"+ data['material_code'] +"</td>");
          row.append("<td class=\"border-right\">"+ data['material_description'] +"</td>");
          row.append("<td class=\"border-right text-right\">"+ data['quantity'] +"</td>");
          row.append("<td class=\"border-right text-center\"></td>");
          row.append("<td>"+ (data['remarks'] || '--') +"</td>");
  
          row.find("td:eq(4)").append(passed);
          return row;
        }
      </script>

<?php }
require('footer.php'); ?>