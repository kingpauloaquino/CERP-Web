<?php
  /* Module: Dashboard  */
  $capability_key = 'receiving';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
?>
      <!-- BOF PAGE -->
      <div id="page">
        <div id="page-title">
          <h2>
            <span class="title">Receiving Materials</span>
            <a id="btn-create-directory" href="#mdl-create-delivery" rel="modal:open" class="nav">Create New Delivery</a>
            <div class="clear"></div>
          </h2>
        </div>
        
        <div id="content">
          <a id="btn-receive-material" href="#mdl-receive-material" rel="modal:open"></a>
          
          <!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" placeholder="Search" />
      </div>
            
          <!-- BOF GridView -->
          <div id="grid-receiving-items" class="grid jq-grid">
            <table id="tbl-materials" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <td class="border-right text-center" width="100"><a class="sort default active up" column="purchase_number">Purchase #</a></td>
                  <td class="border-right text-center" width="100"><a class="sort" column="material_code">Code</a></td>
                  <td class="border-right"><a class="sort down" column="material_description">Description</a></td>
                  <td class="border-right text-center text-date" width="80"><a class="sort" column="unit">Unit</a></td>
                  <td class="border-right text-center text-date" width="60"><a class="sort" column="quantity">Quantity</a></td>
                  <td class="border-right text-center" width="60"><a class="sort" column="received">Received</a></td>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          
          <!-- BOF Pagination -->
      <div id="receiving-items-pagination"></div>
        </div>
      </div>
      
      <!-- Material Receiving -->
      <div id="mdl-receive-material" class="modal">
         <div class="modal-title"><h3></h3></div>
         <div class="modal-content">
            <form id="frm-receive-material" method="POST">
               <span class="notice"></span>
               
               <input type="hidden" name="action" value="add_receiving"/>
               <input type="hidden" id="material-index" name="material-index" value="0"/>
               <input type="hidden" name="quantity-received" value="0"/>
               <input type="hidden" id="receiving-item-id" name="receiving[item_id]"/>
               
               <div class="field">
                  <label>Quantity:</label>
                  <input type="text" id="receiving-quantity" class="text-field disabled" default="0" disabled="disabled"/>
               </div>
               
               <div class="field">
                  <label>Received:</label>
                  <input type="text" id="receiving-received" class="text-field disabled" default="0" disabled="disabled"/>
               </div>
               
               <div class="field">
                  <label>Delivery Receipt:</label>
                  <select name="receiving[delivery_id]" class="text-select" style="width:191px;"><?php echo build_select_delivery_receipts(); ?></select>
               </div>
               
               <div class="field">
                  <label>Delivered:</label>
                  <input type="text" id="receiving-delivered" name="receiving[quantity]" class="text-field" default="0"/>
               </div>
               
               <div class="field">
                  <label>Additional:</label>
                  <input type="text" name="receiving[addons]" class="text-field" default="0"/>
               </div>
               
               <div class="field">
                  <label>Remarks:</label>
                  <textarea rows="2" name="receiving[remarks]" class="text-field" style="width:220px;"></textarea>
               </div>
            </form>
         </div>
         <div class="modal-footer">
         	<a rel="modal:close" class="close btn" style="width:50px;">Cancel</a>
         	<a id="submit-receive-material" href="#frm-receive-material" class="btn" style="width:50px;">Receive</a>
         </div>
      </div>
      
      <!-- Create Delivery -->
      <div id="mdl-create-delivery" class="modal" style="width:485px;">
         <div class="modal-title"><h3>New Delivery</h3></div>
         <div class="modal-content">
            <form id="frm-create-delivery" method="POST">
               <span class="notice"></span>
               
               <input type="hidden" name="action" value="add_delivery"/>
               
               <div class="field">
                  <label>Delivery Receipt:</label>
                  <input type="text" name="delivery[receipt]" value="" class="text-field" autofocus="autofocus"/>
               </div>
               
               <div class="field">
                  <label>Delivery Date:</label>
                  <input type="text" name="delivery[date]" class="text-field" default="<?php echo dformat(now(), 'm/d/Y'); ?>"/>
               </div>
               
               <div class="field">
                  <label>Supplier Name:</label>
                  <select name="delivery[supplier]" class="text-select w320"><?php echo build_select_suppliers(); ?></select>
               </div>
            
               <div class="field">
                  <label>Delivery Via:</label>
                  <input type="text" name="delivery[via]" value="" class="text-field w305"/>
               </div>
            
               <div class="field">
                  <label>Trade Terms:</label>
                  <input type="text" name="delivery[trade_terms]" value="" class="text-field w305"/>
               </div>
            
               <div class="field">
                  <label>Payment Terms:</label>
                  <input type="text" name="delivery[payment_terms]" value="" class="text-field w305"/>
               </div>
            </form>
         </div>
         <div class="modal-footer">
         	<a rel="modal:close" class="close btn" style="width:50px;">Cancel</a>
         	<a id="submit-create-delivery" href="#frm-create-delivery" class="btn" style="width:50px;">Create</a>
         </div>
      </div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/receiving-items.php",
      "limit":"15",
			"data_key":"receiving",
			"row_template":"row_template_receiving",
      "pagination":"#receiving-items-pagination"
		}
	
		$('#grid-receiving-items').grid(data);
    $('#btn-create-directory').show_delivery_modal();
    $('#submit-create-delivery').create_delivery();
    
    $('#tbl-materials').find('tbody tr').show_receiving_modal();
    $('#submit-receive-material').add_receiving();
  })
        
  $.fn.show_receiving_modal = function() {
    this.live('click', function(e) {
    	e.preventDefault();
    	
    	var index		= $(this).index();
    	var modal		= $('#btn-receive-material').attr('href');
    	var item_id		= $(this).attr('item');
    	var title		= $(this).attr('title');
    	var quantity	= $(this).attr('quantity');
    	var received	= $(this).attr('received');
    	
    	reset_form($(modal).find(':input'));
    	
    	$(modal).find('.notice').empty();
    	$(modal).find('.modal-title h3').html(title);
    	$(modal).find('#material-index').val(index);
    	$(modal).find('#receiving-item-id').val(item_id);
    	$(modal).find('#receiving-quantity').val(quantity);
    	$(modal).find('#receiving-received').val(received);
    	
    	$('#btn-receive-material').click();
    })
  }
  
  $.fn.add_receiving = function() {
    this.click(function(e) {
    	e.preventDefault();
    	
      var form		= $(this).attr('href');
      var index		= $(form).find('#material-index').val();
      var quantity	= $(form).find('#receiving-quantity').val();
      var received	= $(form).find('#receiving-received').val();
      var delivered	= $(form).find('#receiving-delivered').val();
      var trow		= $('#tbl-materials tbody tr:eq('+ index +')');
      
      if(parseFloat(delivered) > (parseFloat(quantity) - parseFloat(received)) || delivered == 0) {
      	$(form).find('.notice').html('<p>Delivered must not be 0 or greater than the remaining quantity.</p>');
      	return false;
      }
    	
    	$.post(document.URL, $(form).serialize(), function(data) {
    	  var total_receive = (parseFloat(received) + parseFloat(delivered));
    	  
    	  $('#mdl-create-delivery').find('.close').click();
    	    
    	  if(total_receive != quantity) {
    	    trow.attr('received', total_receive);
    	    trow.find('td:eq(5)').html(trow.attr('received'));
    	    return false;
    	  }
    	  trow.remove();
    	});
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
  
  $.fn.show_delivery_modal = function() {
    this.click(function(e) {
    	e.preventDefault();
    	
    	var modal = $(this).attr('href');
    	$(modal).find('.notice').empty();
    	reset_form($(modal).find(':input'));
    })
  }
  
  $.fn.create_delivery = function() {
    this.click(function(e) {
    	e.preventDefault();
    	
      var form = $(this).attr('href');
      var params = $(form).serialize();
      
      $.post(document.URL, params, function(data) {
      	if(data == 0) {
      		$(form).find('.notice').html('<p>Delivery receipt is already exist.</p>');
      	} else if(data == -1) {
      		$(form).find('.notice').html('<p>Delivery receipt is required</p>');
      	} else {
      		$('#mdl-create-delivery').find('.close').click();
      	}
      });
    })
  }
</script>

<?php }
require('footer.php'); ?>