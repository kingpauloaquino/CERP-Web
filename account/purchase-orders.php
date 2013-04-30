<?php
  /*
   * Module: Purchases 
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
        <input type="text" id="keyword" name="keyword" placeholder="Search" />
      </div>
        
      <!-- BOF GridView -->
      <div id="grid-purchases" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td width="110" class="border-right text-center"><a class="sort default active up" column="purchase_number">P/O #</a></td>
              <td class="border-right"><a class="sort down" column="supplier_name">Supplier</a></td>
              <td width="90" class="border-right text-center"><a class="sort" column="total_amount">Amount</a></td>
              <td width="70" class="border-right text-center"><a class="sort" column="completion_status">Completion</a></td>
              <td width="90" class="border-right text-center text-date"><a class="sort"column="delivery_date">Delivery</a></td>
              <td width="90" class="text-center text-date"><a class="sort" column="created_at">P/O Date</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="purchases-pagination"></div>
		</div>
	</div>
	
	<script>
	$(function() {
  	var data = { 
    	"url":"/populate/purchase-orders.php",
      "limit":"15",
			"data_key":"purchase-orders",
			"row_template":"row_template_purchases",
      "pagination":"#purchases-pagination"
		}
	
		$('#grid-purchases').grid(data);
  }) 
  </script>

<?php }
require('footer.php'); ?>