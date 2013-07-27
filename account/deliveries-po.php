<?php
  /*
   * Module: Deliveries  By P/O
  */
  $capability_key = 'deliveries_po';  
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		if($_GET['pid']) {
			$purchase = $DB->Find('purchases', array('columns' => 'po_number', 'conditions' => 'id='. $_GET['pid']));
		}
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle() ?> &raquo; <span class="red"><?php echo $purchase['po_number'] ?></span></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
      </div>
        
      <!-- BOF GridView -->
      <div id="grid-deliveries" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
							<td class="border-right text-center" width="130"><a class="sort default active up" column="po_number">P/O Number</a></td> 
              <td class="border-right text-center"><a class="sort" column="supplier_name">Supplier</a></td> 
              <td class="border-right text-center" width="100"><a class="sort" column="delivery_date">Delivery Date</a></td> 
              <td class="text-center" width="90"><a class="sort" column="completion_status">Completion</a></td>  
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="deliveries-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/deliveries-po.php?pid=<?php echo $_GET['pid'] ?>",
      "limit":"15",
			"data_key":"deliveries",
			"row_template":"row_template_deliveries",
      "pagination":"#deliveries-pagination",
      "searchable":true
		}
	
		$('#grid-deliveries').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>