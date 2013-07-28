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
          <span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
          <?php
				  	echo '<a href="'.$Capabilities->All['add_purchase']['url'].'" class="nav">'.$Capabilities->All['add_purchase']['name'].'</a>';
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
      <div id="grid-purchases" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="130"><a class="sort default active up" column="purchase_number">P/O Number</a></td>
              <td class="border-right text-center" width="90"><a class="sort" column="po_date">P/O Date</a></td>
              <td class="border-right"><a class="sort down" column="supplier_name">Supplier</a></td>
              <td width="90" class="border-right text-center"><a class="sort"column="delivery_date">Delivery</a></td>
              <td class="border-right text-center" width="90"><a class="sort" column="completion_status">Completion</a></td>
              <td class="border-right text-center" width="110"><a class="sort" column="total_amount">Amount</a></td>
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
      "pagination":"#purchases-pagination",
      "searchable":true
		}
	
		$('#grid-purchases').grid(data);
  }) 
  </script>

<?php }
require('footer.php'); ?>