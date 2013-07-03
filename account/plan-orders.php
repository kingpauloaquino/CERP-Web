<?php
  /*
   * Module: Plan Orders 
  */
  $capability_key = 'plan_orders';  
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
	        	echo '<a href="'.$Capabilities->All['plan_models']['url'].'" class="nav">Browse by Model</a>';
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
      <div id="grid-plan-orders" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="140"><a class="sort default active up" column="po_number">Order No.</a></td>
              <td class="border-right text-center" width="120"><a class="sort" column="po_date">Order Date</a></td>
              <td class="border-right text-center" width="120"><a class="sort" column="ship_date">Ship Date</a></td>
              <td class="border-right text-center"><a class="sort" column="remarks">Remarks</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="plan-orders-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/plan-orders.php",
      "limit":"15",
			"data_key":"plan_orders",
			"row_template":"row_template_plan_orders",
      "pagination":"#plan-orders-pagination",
      "searchable":true
		}
	
		$('#grid-plan-orders').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>