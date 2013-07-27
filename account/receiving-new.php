<?php
  /* Module: Receiving  */
  $capability_key = 'add_receiving';
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
          <form class="form-container" method="POST" >
		      	 <input type="hidden" name="action" value="add_receiving"/>
		      	 <input type="hidden" name="delivery[id]" value="<?php echo $_GET['id']; ?>"/>
		      	 <input type="hidden" name="delivery[purchase_id]" value="<?php echo $delivery['pid']; ?>"/>
             <!-- BOF TEXTFIELDS -->
             <div>
             	<table>
                   <tr>
                      <td width="120">P/O Number:</td><td width="340"><input type="text" value="<?php echo $delivery['po_number']; ?>" class="text-field magenta" disabled/>
                      	<?php echo $linkto = (isset($delivery['pid'])) ? link_to('purchases-show.php?id='.$delivery['pid']) : '' ?>
                      </td>
                      <td width="120">P/O Date:</td><td width="340"><input type="text" value="<?php echo date("F d, Y", strtotime($delivery['po_date'])); ?>" class="text-field" disabled/>
                   </tr>
                   <tr>
                      <td>Supplier:</td>
                      <td colspan="99">
                        <input type="text" value="<?php echo $delivery['supplier_name']; ?>" class="text-field" style="width:644px;" disabled/>
                      </td>
                   </tr>
                   <tr>
                      <td>Delivery Via:</td><td><input type="text" value="<?php echo $delivery['delivery_via']; ?>" class="text-field" disabled/></td>
                      <td>Delivery Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($delivery['delivery_date'])) ?>" class="text-field text-date" disabled/></td>
                   </tr>
                   <tr>
                      <td>Trade Terms:</td><td><input type="text" value="<?php echo $delivery['terms']; ?>" class="text-field" disabled/></td>
                      <td>Payment Terms:</td><td><input type="text" value="<?php echo $delivery['payment_terms']; ?>" class="text-field" disabled/></td>
                   </tr>
                   <tr>
                      <td>Invoice:</td><td><input id="invoice" name="delivery[invoice]" type="text" class="text-field" required autocomplete="off" notice="invoice_status"/>
                      	<span id="invoice_status" class="warning"></span>
                      </td>
                      <td>Receipt:</td><td><input id="receipt" name="delivery[receipt]" type="text" class="text-field" autocomplete="off"/></td>
                   </tr>
                   <tr>
                      <td>Lot No.:</td><td><input id="delivery[lot]" name="delivery[lot]" type="text" class="text-field magenta" value="<?php echo generate_new_code('material_lot_no') ?>" required autocomplete="off" readonly/></td>
                      <td></td><td></td>
                   </tr>
                   <tr><td height="5" colspan="99"></td></tr>
                </table>
             </div>
             
             <!-- BOF GRIDVIEW -->
             <div id="grid-receiving-items" class="grid jq-grid" style="min-height:200px;">
	            <table id="tbl-materials" cellspacing="0" cellpadding="0">
	              <thead>
	                <tr>
                 		<td width="20" class="border-right text-center"></td>
	                  <td class="border-right text-center" width="120"><a class="sort" column="material_code">Code</a></td>
	                  <td class="border-right" width="300"><a class="sort down" column="material_description">Description</a></td>
	                  <td class="border-right text-center text-date" width="70"><a class="sort" column="quantity">P/O Qty</a></td>
	                  <td class="border-right text-center" width="60"><a class="sort" column="delivered">Delivered</a></td>
	                  <td class="border-right text-center text-date" width="60"><a class="sort" column="unit">Unit</a></td>
	                  <td class="border-right text-center" width="60"><a class="sort" column="status">Status</a></td>
	                  <td class="border-right text-center" width="70"><a class="sort" column="received">Received</a></td>
	                  <td><a class="sort" column="remarks">Remarks</a></td>
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
                   <tr><td colspan="2">Remarks:<br/><textarea name="delivery[remarks]" style="min-width:650px;width:98.9%;height:50px;"><?php echo $delivery['remarks']; ?></textarea></td></tr>
                </table>
             </div>
	         
         <div class="field-command">
       	   <div class="text-post-status">
       	     <strong>Status:</strong>&nbsp;&nbsp;<?php echo $delivery['status'] ?>
           </div>
           <?php 
           	if($delivery['status'] != 'Close') {
           		echo '<input id="btn-submit" type="submit" value="Save" class="btn" disabled/>';
           	}
           ?>
           <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('deliveries-show.php?id='.$_GET['id']); ?>"/>
         </div>
          </form>
       </div>
     </div>
     
	<div id="mdl-receive-material" class="modal">
		<div class="modal-title"><h3></h3></div>
		<div class="modal-content">
			<form id="frm-receive-material" method="POST">
				<span class="notice"></span>     
<!-- 					<input type="hidden" name="action" value="edit_receiving_items"/> -->
					<input type="hidden" id="material-index" name="material-index" value="0"/>
					<input type="hidden" id="receiving-item-id" name="receiving[item_id]"/>
					<input type="hidden" id="rid" name="rid"/>
					
						 <div class="field">
						    <label>P/O Quantity:</label>
						    <input type="text" id="receiving-quantity" class="text-field disabled" default="0" disabled="disabled"/>
						 </div>
						 
						 <div class="field">
						    <label>Delivered:</label>
						    <input type="text" id="receiving-delivered" class="text-field" default="0" disabled="disabled"/>
						 </div>
						 
						 <div class="field">
						    <label>Received:</label>
						    <input type="text" id="receiving-received" name="receiving[received]" class="text-field"/>
						 </div>
						 
						 <div class="field">
						    <label>Remarks:</label>
						    <textarea rows="2" id="receiving-remarks" name="receiving[remarks]" class="text-field" style="width:220px;"></textarea>
						    <input type="hidden" id="receiving-status" name="receiving[status]" value=""/>
						 </div>
			</form>
		</div>
		<div class="modal-footer">
			<a id="closeModal" rel="modal:close" class="close btn" style="width:50px;">Cancel</a>
			<a id="submit-receive-material" rel="modal:close" href="#frm-receive-material" class="btn" style="width:50px;">Receive</a>
		</div>
	</div>
       
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/receiving-items.php?did=<?php echo $_GET['id']; ?>",
      "limit":"50",
			"data_key":"delivery_items",
			"row_template":"row_template_receiving",
      "pagination":"#receiving-items-pagination"
		}	
		$('#grid-receiving-items').grid(data);
    
    $('#tbl-materials').find('tbody tr .chk-item').show_receiving_modal();
    $('#submit-receive-material').add_receiving();
    
    $('#invoice').keyup(function() {
			($(this).is_existing('delivery_items', 'id', '', 'invoice="' +$(this).val()+ '"', 'invoice')) 
				? $('#btn-submit').attr('disabled', true)
				: $('#btn-submit').attr('disabled', false);
		});
  })
  
  
  
        
  $.fn.show_receiving_modal = function() {
  	$()
    this.live('click', function(e) {
    	//e.preventDefault();
    	
    	//var row = $('#tbl-materials').find('tbody tr');
    	//$(row).find('.chk-item').prop('checked', true);
    	if($(this).prop('checked')) {
	    	var row = $(this).closest('tr');
	    	var chkbox = $(row).find('.chk-item');
	    	var index		= $(row).index();
	    	var modal		= $('#btn-receive-material').attr('href');
	    	var id	= $(row).attr('id');
	    	var item_id		= $(row).attr('item');
	    	var title		= 'Receive: ' + $(row).attr('title');
	    	var quantity	= $(row).attr('quantity');
	    	var delivered	= $(row).attr('delivered');
	    	var received	= $(row).attr('received');
	    	var remarks	= $(row).attr('remarks');
	    	
	    	reset_form($(modal).find(':input'));
	    	
	    	$(modal).find('.notice').empty();
	    	$(modal).find('.modal-title h3').html(title);
	    	$(modal).find('#material-index').val(index);
	    	$(modal).find('#rid').val(id);
	    	$(modal).find('#receiving-item-id').val(item_id);
	    	$(modal).find('#receiving-quantity').val(quantity);
	    	$(modal).find('#receiving-delivered').val(delivered);
	    	$(modal).find('#receiving-received').val((parseFloat(quantity) - parseFloat(delivered)));
	    	$(modal).find('#receiving-received').focus();
	    	
	    	$(modal).find('.close').live('click', function(e) {
			  	chkbox.prop('checked', false);
			  })
	    	
	    	$('#btn-receive-material').click();	
    	} else {
	    		var row = $(this).closest('tr');
    			row.find('.item-received').val('0');
			  	row.find('.item-remarks').val('');
    	}
    	
    })
  }
  
  $.fn.add_receiving = function() {
    this.click(function(e) {
    	e.preventDefault();

      var form		= $(this).attr('href');
      var index		= $(form).find('#material-index').val();
      var trow		= $('#tbl-materials tbody tr:eq('+ index +')');
      var quantity	= $(form).find('#receiving-quantity').val();
      var received	= $(form).find('#receiving-received').val();
      var delivered	= $(form).find('#receiving-delivered').val();
      var remarks	= $(form).find('#receiving-remarks').val();
      var status = $(trow).attr('status');
      
      // if(parseFloat(delivered) > (parseFloat(quantity) - parseFloat(received)) || delivered == 0) {
      	// $(form).find('.notice').html('<p>Delivered must not be 0 or greater than the remaining quantity.</p>');
      	// return false;
      // }
      
      if((parseFloat(delivered) + parseFloat(received)) == parseFloat(quantity)) {
      	$(trow).find('.status').val('21'); // complete status
      }
      
      if((parseFloat(delivered) + parseFloat(received)) < parseFloat(quantity)) {
      	$(trow).find('.status').val('22'); // incomplete status
      }
      
      if((parseFloat(delivered) + parseFloat(received)) > parseFloat(quantity)) {
      	return false;
      }
      
      var total_qty = (status == 'Partial') ? parseFloat(received) : parseFloat(received) + parseFloat(delivered); 
      
      $(trow).find('.received').val(total_qty);
      $(trow).find('.item-received').val(received);
      $(trow).find('.item-remarks').val(remarks);

			$(trow).find('.chk-item').prop('checked', true);
    })
  }
  
  function reset_form(form) {
    // ex: form.not('#accountType')
    form.each( function() {
      if(this.type == "text" || this.type == 'textarea')  {
        this.value = "";
        $(this).val($(this).attr('default'));
      }
      
      if(this.type == 'radio' || this.type == 'checkbox') this.checked = false;
      if(this.type == 'select-one' || this.type == 'select-multiple') $(this).val($(this).find('option:first').val());
      // $(this).find('option:first-child').attr("selected", "selected");
    });
  }      	
</script>

<?php }
require('footer.php'); ?>