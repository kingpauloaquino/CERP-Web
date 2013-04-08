<?php
  /*
   * Module: Production Plan 
  */
  $capability_key = 'production_plan';  
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
      <div id="grid-production-plan" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td width="110" class="border-right text-center"><a class="sort default active up" column="po_number">Production Plan ID</a></td>
              <td width="110" class="border-right text-center"><a class="sort default active up" column="po_number">PO Number</a></td>
              <td width="100" class="border-right text-center"><a class="sort" column="po_date">Delivery Date</a></td>
              <td width="100" class="border-right text-center"><a class="sort" column="target_date">Target Date</a></td>
              <td width="100" class="border-right text-center"><a class="sort" column="status">Status</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="production-plan-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/production-plan.php",
      "limit":"15",
			"data_key":"production_purchase_orders",
			"row_template":"row_template_production_plans",
      "pagination":"#production-plan-pagination"
		}
	
		$('#grid-production-plan').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>