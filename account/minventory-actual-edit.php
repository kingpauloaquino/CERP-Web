<?php
  /*
   * Module: Actual Material Inventory - Edit
  */
  $capability_key = 'edit_actual_material_inventory';
  require('header.php');
  
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
	  if(isset($_GET['id'])) {
	  	$materials = $Query->material_by_id($_GET['id']);
	  }	
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['show_material_inventory']['url'].'?id='.$_GET['id'].'" class="nav">Details</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
				<input type="hidden" name="action" value="add_actual_minventory_items"/>
				<input type="hidden" name="mid" value="<?php echo $_GET['id']?>"/>
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Material Code:</td><td width="310"><input type="text" value="<?php echo $materials['material_code'] ?>" class="text-field" disabled/>
              	<?php echo $linkto = ($materials['material_code']!='') ? link_to('materials-show.php?mid='.$_GET['id']) : '' ?>
              </td>
              <td width="150">Type:</td><td><input type="text" value="<?php echo $materials['material_type'] ?>" class="text-field text-date" disabled/></td>
           </tr>
           <tr>
              <td>Classification:</td><td><input type="text" value="<?php echo $materials['classification'] ?>" class="text-field" disabled/></td>
              
              <td>Model:</td><td><input type="text" value="<?php echo $model = ($materials['material_type'] == 'Direct Material') ? $materials['brand_model'] : 'N/A' ?>" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>Person in-charge:</td><td><input type="text" value="<?php echo $materials['pic'] ?>" class="text-field" disabled/>
              	<?php echo $linkto = ($materials['pic']!='') ? link_to('users-show.php?uid='.$materials['user_id']) : '' ?>
              </td>
              <td>Status:</td><td><input type="text" value="<?php echo $materials['status'] ?>" class="text-field" disabled/></td>
           </tr>             
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" value="<?php echo $materials['description'] ?>" class="text-field" style="width:645px" disabled/>
              </td>
           </tr>
           <tr>
              <td>Entry Date:</td><td><input type="text" id="entry_date" name="entry_date" class="text-field date-pick" value="<?php echo date('F d, Y') ?>" readonly/></td>
              <td></td><td></td>
           </tr>   
           <tr><td height="5" colspan="99"></td></tr>
        </table>
      	<br/>

        <h3 class="form-title">Warehouse Stock </h3>
      	<a id="btn-inventory" href="#mdl-inventory" rel="modal:open"></a>
	      <div id="grid-materials" class="grid jq-grid" style="min-height:60px;">
           <table id="tbl-materials" cellspacing="0" cellpadding="0">
             <thead>
               <tr>
                 <td width="30" class="border-right text-center">No.</td>
                 <td width="100" class="border-right text-center">Invoice</td>
                 <td width="100" class="border-right text-center">Lot</td>
                 <td class="border-right">Remarks</td>
                 <td width="70" class="border-right text-center">Unit</td>
                 <td width="50" class="border-right text-center">System</td>
                 <td width="50" class="border-right text-center">Physical</td>
               </tr>
             </thead>
             <tbody id="materials"></tbody>
           </table>
         </div>
         <div>
	       	<table width="100%">
	             <tr><td height="5" colspan="99"></td></tr>
	             <tr>
	                <td></td>
	                <td align="right"><strong>Total:</strong>&nbsp;&nbsp;<input id="total_qty" type="text" class="text-right numbers" style="width:85px;" value="0" disabled/></td>
	             </tr>
	          </table>
	       </div>	
	      	
	      <br/>
				<div class="field-command">
					<div class="text-post-status">
					</div>
					<input type="submit" value="Save" class="btn"/>
					<input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('minventory-show.php?id=').$_GET['id']; ?>"/>
				</div>
      </form>
    	<br/>
		</div>
	</div>
	
	
	<script>
		$(function() {
			$('#out-of-stock').hide();
	  	var data = { 
	    	"url":"/populate/minventory-items.php?id=<?php echo $_GET['id'] ?>",
	      "limit":"15",
				"data_key":"minventory_items",
				"row_template":"row_template_actual_minventory_items",
			}
		
			$('#grid-materials').grid(data);
			
			$('#submit-inventory').adjustment();
			$('#submit-inventory-new').add_entry();
			
			$('#materials').live('keyup', function(e){
				compute_total();
			})
			
	  }) 
	  
	  function compute_total() {
	  	var total = 0;
			$('#materials tr').each(function(){
  			total += parseFloat($(this).find('.actual-qty').val());
  		});
  		$('#total_qty').val(total).digits();
	  }
	  
	  
  
  	$.fn.adjustment = function() {
    this.click(function(e) {
    	e.preventDefault();

      var form		= $(this).attr('href');
      var id = $(form).find('#inventory-id');
      var remarks = $(form).find('#inventory-remarks');
      var qty = $(form).find('#inventory-new_qty');
    	
    	$.post(document.URL, $(form).serialize(), function(data) {
    	   $('#materials').empty();
    	   
    	   var data = { 
			    	"url":"/populate/minventory-items.php?id=<?php echo $_GET['id'] ?>",
			      "limit":"15",
						"data_key":"minventory_items",
						"row_template":"row_template_minventory_items",
					}
				
					$('#grid-materials').grid(data);
	    	
	    	})
	    	
	    	setTimeout(function() {
		    compute_total();
			}, 200);
	    })
	  }
	  
	  $.fn.add_entry = function() {
    this.click(function(e) {
    	e.preventDefault();

      var form		= $(this).attr('href');
    	
    	$.post(document.URL, $(form).serialize(), function(data) {
    	   $('#materials').empty();
    	   
    	   var data = { 
			    	"url":"/populate/minventory-items.php?id=<?php echo $_GET['id'] ?>",
			      "limit":"15",
						"data_key":"minventory_items",
						"row_template":"row_template_minventory_items",
					}
				
					$('#grid-materials').grid(data);
	    	
	    	})
	    	
	    	setTimeout(function() {
		    compute_total();
			}, 200);
	    })
	  }
 </script>
<?php }
require('footer.php'); ?>