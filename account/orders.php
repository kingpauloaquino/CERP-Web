<?php
  /*
   * Module: Orders 
  */
  $capability_key = 'orders';  
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
				  echo '<a href="'.$Capabilities->All['add_order']['url'].'" class="nav">'.$Capabilities->All['add_order']['name'].'</a>';
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
      <div id="grid-orders" class="grid jq-grid">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="110"><a class="sort default active up" column="po_number">PO Number</a></td>
              <td class="border-right text-center" width="100"><a class="sort" column="po_date">PO Date</a></td>
              <td class="border-right text-center"><a class="sort" column="description">Description</a></td>
              <td class="border-right text-center" width="160"><a class="sort" column="payment_terms">Payment Terms</a></td>
              <td class="border-right text-center" width="100"><a class="sort" column="delivery_date">Delivery</a></td>
              <td class="border-right text-center" width="100"><a class="sort" column="status">Status</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="orders-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/orders.php",
      "limit":"15",
			"data_key":"orders",
			"row_template":"row_template_orders",
      "pagination":"#orders-pagination"
		}
	
		$('#grid-orders').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>