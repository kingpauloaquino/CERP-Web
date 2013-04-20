<?php
  /* Module: Receiving  */
  $capability_key = 'show_receiving';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
  	$delivery = $Query->delivery_by_id($_GET['id']);
?>
      <!-- BOF PAGE -->
      <div id="page">
        <div id="page-title">
          <h2>
            <span class="title"><?php echo $Capabilities->GetTitle(); ?></span></span>
            <div class="clear"></div>
          </h2>
        </div>

        <div id="content">
        	<a id="btn-receive-material" href="#mdl-receive-material" rel="modal:open"></a>
          <form class="form-container">
             <!-- BOF TEXTFIELDS -->
             <div>
             	<table>
                   <tr>
                      <td width="120">Purchase Number:</td><td width="340"><input type="text" value="<?php echo $delivery['purchase_number']; ?>" class="text-field" disabled/>
                      	<?php echo $linkto = (isset($delivery['pid'])) ? link_to('purchases-show.php?id='.$delivery['pid']) : '' ?>
                      </td>
                      <td width="120"></td><td width="340"></td>
                   </tr>
                   <tr>
                      <td>Supplier:</td>
                      <td colspan="99">
                        <input type="text" value="<?php echo $delivery['supplier_name']; ?>" class="text-field" style="width:644px;" disabled/>
                      </td>
                   </tr>
                   <tr>
                      <td>Trade Terms:</td><td><input type="text" value="<?php echo $delivery['trade_terms']; ?>" class="text-field" disabled/></td>
                      <td>Payment Terms:</td><td><input type="text" value="<?php echo $delivery['payment_terms']; ?>" class="text-field" disabled/></td>
                   </tr>
                   <tr>
                      <td>Invoice:</td><td><input type="text" value="<?php echo $delivery['invoice']; ?>" class="text-field" disabled/></td>
                      <td>Receipt:</td><td><input type="text" value="<?php echo $delivery['receipt'] ?>" class="text-field" disabled/></td>
                   </tr>
                   <tr>
                      <td>Delivery Via:</td><td><input type="text" value="<?php echo $delivery['delivery_via']; ?>" class="text-field" disabled/></td>
                      <td>Delivery Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($delivery['delivery_date'])) ?>" class="text-field text-date" disabled/></td>
                   </tr>
                   <tr><td height="5" colspan="99"></td></tr>
                </table>
             </div>
             
             <!-- BOF GRIDVIEW -->
             <div id="grid-receiving-items" class="grid jq-grid" style="min-height:200px;">
	            <table id="tbl-materials" cellspacing="0" cellpadding="0">
	              <thead>
	                <tr>
	                  <td class="border-right text-center" width="100"><a class="sort" column="material_code">Code</a></td>
	                  <td class="border-right"><a class="sort down" column="material_description">Description</a></td>
	                  <td class="border-right text-center text-date" width="80"><a class="sort" column="unit">Unit</a></td>
	                  <td class="border-right text-center text-date" width="60"><a class="sort" column="quantity">Quantity</a></td>
	                  <td class="border-right text-center" width="60"><a class="sort" column="delivered">Delivered</a></td>
	                  <td class="border-right text-center" width="60"><a class="sort" column="received">Received</a></td>
	                  <td class="border-right text-center" width="60"><a class="sort" column="status">Status</a></td>
	                </tr>
	              </thead>
	              <tbody id="receiving-items"></tbody>
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
                   <tr><td colspan="2">Remarks:<br/><textarea style="min-width:650px;width:98.9%;height:50px;" disabled><?php echo $delivery['remarks']; ?></textarea></td></tr>
                </table>
             </div>
             
             <div class="field-command">
           	   <div class="text-post-status">
           	     <strong>Saved As:</strong>&nbsp;&nbsp;<?php echo $delivery['status']; ?>
               </div>
           	   <input type="button" value="Download" class="btn btn-download" rel="<?php echo excel_file('?category=receiving&id='. $delivery['id']); ?>"/>
               	<?php if($delivery['status'] != "Publish") { ?>
               <input type="button" value="Edit" class="btn redirect-to" rel="<?php echo host('receiving-edit.php?id='. $delivery['id']); ?>"/>
           	   	<?php } ?>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('receiving.php'); ?>"/>
             </div>
          </form>
       </div>
     </div>
     
	<div id="mdl-receive-material" class="modal">
		<div class="modal-title"><h3></h3></div>
		<div class="modal-content">
			<form id="frm-receive-material" method="POST">
				<span class="notice"></span>     
					<input type="hidden" name="action" value="edit_receiving"/>
					<input type="hidden" id="material-index" name="material-index" value="0"/>
					<input type="hidden" id="receiving-item-id" name="receiving[item_id]"/>
					<input type="hidden" id="rid" name="rid"/>
     
						 <div class="field">
						    <label>Quantity:</label>
						    <input type="text" id="receiving-quantity" class="text-field disabled" default="0" disabled="disabled"/>
						 </div>
						 
						 <!-- <div class="field">
						    <label>Delivery Receipt:</label>
						    <select name="receiving[delivery_id]" class="text-select" style="width:191px;"><?php echo build_select_delivery_receipts(); ?></select>
						 </div> -->
						 
						 <div class="field">
						    <label>Delivered:</label>
						    <input type="text" id="receiving-delivered" name="receiving[delivered]" class="text-field" default="0"/>
						 </div>
						 
						 <div class="field">
						    <label>Received:</label>
						    <input type="text" id="receiving-received" name="receiving[received]" class="text-field" default="0"/>
						 </div>
						 
						 <div class="field">
						    <label>Additional:</label>
						    <input type="text" id="receiving-additional" name="receiving[additional]" class="text-field" default="0"/>
						 </div>
						 
						 <div class="field">
						    <label>Remarks:</label>
						    <textarea rows="2" id="receiving-remarks" name="receiving[remarks]" class="text-field" style="width:220px;"></textarea>
						 </div>
			</form>
		</div>
		<div class="modal-footer">
			<a rel="modal:close" class="close btn" style="width:50px;">Cancel</a>
			<a id="submit-receive-material" href="#frm-receive-material" class="btn" style="width:50px;">Receive</a>
		</div>
	</div>
       
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/delivery-items.php?did=<?php echo $_GET['id']; ?>",
      "limit":"50",
			"data_key":"delivery_items",
			"row_template":"row_template_receiving_read_only",
      "pagination":"#receiving-items-pagination"
		}	
		$('#grid-receiving-items').grid(data);
	});
</script>

<?php }
require('footer.php'); ?>