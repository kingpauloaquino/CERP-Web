<?php
  /*
   * Module: Purchase Orders 
  */
  $capability_key = 'purchase_orders';  
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['add_purchase_order']['url'].'" class="nav">'.$Capabilities->All['add_purchase_order']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
      </div>
        
      <!-- BOF GridView -->
      <div id="grid-purchase-orders" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="140"><a class="sort default active up" column="po_number">P/O Number</a></td>
              <td class="border-right text-center" width="120"><a class="sort" column="po_date">P/O Date</a></td>
              <td class="border-right text-center"><a class="sort" column="payment_terms">Payment Terms</a></td>
              <td class="border-right text-center" width="120"><a class="sort" column="ship_date">Ship Date</a></td>
              <td class="border-right text-center" width="120"><a class="sort" column="status">Approval</a></td>
              <td class="text-center" width="120"><a class="sort" column="completion_status">Completion</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="purchase-orders-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/purchase-orders.php",
      "limit":"15",
			"data_key":"purchase_orders",
			"row_template":"row_template_purchase_orders",
      "pagination":"#purchase-orders-pagination",
      "searchable":true
		}
	
		$('#grid-purchase-orders').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>