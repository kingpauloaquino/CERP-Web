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
      	<input type="hidden" name="action" value="add_material_request"/>
      	<input type="hidden" name="redirect" value="production-material-requests-show.php"/>
      	
         <!-- BOF TEXTFIELDS -->
         <div>
         	<table>
               <tr>
                  <td width="120">Type:</td><td width="340"><?php select_query_tag($request_types, 'id', 'description', '', 'request-type', 'request_type', '', 'width:192px;'); ?></td>
                  <td width="120">Batch Number:</td><td width="340"><input type="text" name="batch_no" class="text-field" autocomplete="off" required/></td>
               </tr>
               <tr>
                  <td>Expected Date:</td><td><input type="text" name="expected_date" class="text-field date-pick-week" autocomplete="off" required/></td>
                  <td>Status:</td><td><input type="text" value="Pending" class="text-field" disabled/></td>
               </tr>
               <tr>
                  <td>Requested Date:</td><td><input type="text" name="requested_date" value="<?php echo date("F d, Y") ?>" class="text-field date-pick-week" autocomplete="off" required/></td>
                  <td>Requested By:</td><td><input type="text" value="<?php echo $_SESSION['user']['first_name'].' '.$_SESSION['user']['last_name']; ?>" class="text-field" disabled/></td>
               </tr>
               <tr>
                  <td>Received Date:</td><td><input type="text" class="text-field" disabled/></td>
                  <td>Received By:</td><td><input type="text" class="text-field" disabled/></td>
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
                  <strong><a href="#modal-materials" class="" rel="modal:open">Add Item</a></strong>
                   &nbsp;|&nbsp;
                   <strong><a id="remove-materials" href="#" class="" grid="#grid-request-items">Remove Item</a></strong>
                  <td align="right"></td>
               </tr>
               <tr><td colspan="2">Remarks:<br/><textarea name="remarks" style="min-width:650px;width:98.9%;height:50px;"></textarea></td></tr>
            </table>
         </div>
         
         <div class="field-command">
       	   <div class="text-post-status">
       	     <strong>Save As:</strong>&nbsp;&nbsp;<select name="status" ><?php echo build_select_post_status(); ?></select>
           </div>
           <?php if($request['status'] != "Publish") { ?>
           <input type="submit" value="Request" class="btn"/>
       	   <?php } ?>
           <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('production-material-requests.php'); ?>"/>
         </div>
      </form>
   </div>

	<!-- BOF MODAL -->
	<div id="modal-materials" class="modal" style="display:none;width:820px;">
		<div class="modal-title"><h3>Materials</h3></div>
		<div class="modal-content">
			<!-- BOF Search -->
			<div class="search">
				<input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
			</div>
			<!-- BOF GRIDVIEW -->
			<div id="grid-materials" class="grid jq-grid grid-item">
				<table id="tbl-materials" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td class="border-right text-center" width="20"><input type="checkbox" class="chk-all"/></td> 
							<td class="border-right text-center" width="140"><a class="sort default active up" column="code">Code</a></td>
							<td class="border-right text-center" width="100"><a class="sort" column="type">Type</a></td> 
							<td class="border-right text-center"><a class="sort" column="description">Description</a></td> 
							<td class="border-right text-center" width="50"><a class="sort" column="unit">Unit</a></td>
						</tr>
					</thead>
					<tbody id="materials"></tbody>
				</table>
			</div>
 
			<!-- BOF Pagination -->
			<div id="materials-pagination"></div>
		</div>

		<div class="modal-footer">
			<a class="btn parent-modal" rel="modal:close">Close</a>
			<a id="add-item" class="btn" rel="modal:close">Add</a>
			<div class="clear"></div>
		</div>
	</div>
   
   <script>
   	$(function() {
			$('#grid-request-items').grid({}); 
			
	  	var data = { 
	    	"url":"/populate/materials-all.php",
	      "limit":"10",
				"data_key":"materials_all",
				"row_template":"row_modal_materials",
	      "pagination":"#materials-pagination",
	      "searchable": true
			}
			$('#grid-materials').grid(data); 
			
			$('#add-item').append_item();
		  $('#remove-materials').remove_item();
			
	  }) 
	  
	  function row_modal_materials(data) { 
		  var mid		= 'mat-'+ data['mid'];
		  var row		= $('<tr id="'+mid+'">' +
		  							//'<input type="hidden" name="array['+mid+'][id]" value="'+mid+'"/>' +
		  						'</tr>');
		  
			row.append('<td class="border-right text-center"><input type="checkbox" value="'+ data['mid'] +'" class="chk-item" /></td>');
			row.append('<td class="mat-code border-right">'+ data['code'] +'</td>');
			row.append('<td class="mat-type border-right text-center">'+ data['type'] +'</td>');
			row.append('<td class="mat-desc border-right">'+ data['description'] +'</td>');
			row.append('<td class="mat-unit border-right text-center">'+ data['unit'] +'</td>');
		  return row;   
		}
	  
	  $.fn.append_item = function() {
       this.click(function(e) {
         var table = $('.grid-item').find('table');
         var grid = $('#request-items'); 
         
       	 table.find('.chk-item:checked').each(function() { 
       	   var id		= $(this).val(); 
       	   var row_id	= "mat-"+ id; 

       	   $(this).prop('checked', false);
						
       	   if(grid.find('#'+ row_id).length == 0) { 
       	     var item = $('#mat-'+ $(this).val());
       	     var data = {
       	       'mid':id,
       	       'code':item.find('.mat-code').html(),
       	       'type':item.find('.mat-type').html(),
       	       'description':item.find('.mat-desc').html(),
       	       'unit':item.find('.mat-unit').html(),
       	     }
       	     
       	     var row = row_template_material_request_items(data);
       	     
       	     //build_options_unit(row.find("select"));
       	     grid.append(row);
       	   }
       	 });
       	 populate_index(grid);
       })
     }
     
     $.fn.remove_item = function() {
       this.click(function(e) {
         e.preventDefault();
         
         var grid = $($(this).attr('grid'));
         grid.find('.chk-item:checked').closest('tr').remove();
       	 populate_index(grid);
       })
     }
  </script>

<?php }
require('footer.php'); ?>