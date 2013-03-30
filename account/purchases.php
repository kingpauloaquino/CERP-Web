<?php
  /*
   * Module: Purchases 
  */
  $capability_key = 'purchases';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
?>
	<div id="page">
		 <div id="page-title">
        <h2>
          <span class="title"><?php echo $Capabilities->GetName(); ?></span>
          <?php
				  	echo '<a href="'.$Capabilities->All['add_purchase']['url'].'" class="nav">'.$Capabilities->All['add_purchase']['name'].'</a>';
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
      <div id="grid-purchases" class="grid jq-grid">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td width="110" class="border-right text-center"><a class="sort default active up" column="purchase_number">Purchase #</a></td>
              <td class="border-right"><a class="sort down" column="supplier_name">Supplier</a></td>
              <td width="90" class="border-right text-center text-date"><a class="sort"column="delivery_date">Delivery</a></td>
              <td width="90" class="border-right text-center"><a class="sort" column="total_amount">Amount</a></td>
              <td width="70" class="border-right text-center"><a class="sort" column="status">Status</a></td>
              <td width="90" class="text-center text-date"><a class="sort" column="created_at">Date Added</a></td>
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
    	"url":"/populate/purchases.php",
      "limit":"15",
			"data_key":"purchases",
			"row_template":"row_template_purchases",
      "pagination":"#purchases-pagination"
		}
	
		$('#grid-purchases').grid(data);
  }) 
  </script>

<?php }
require('footer.php'); ?>