<?php
  /*
   * Module: Materials Inventory
  */
  $capability_key = 'product_inventory';  
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
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
      </div>
        
      <!-- BOF GridView -->
      <div id="grid-products" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td width="150" class="border-right text-center"><a class="sort default active up" column="code">Code</a></td>
              <td width="100" class="border-right text-center"><a class="sort" column="brand">Brand</a></td>
              <td width="70" class="border-right text-center"><a class="sort" column="series">Series</a></td>
              <td width="70" class="border-right text-center"><a class="sort" column="pack_qty">Pack Qty</a></td>
              <td width="90" class="border-right text-center"><a class="sort" column="color">Color</a></td>
              <td class="border-right"><a class="sort" column="description">Description</a></td>
              <td width="100" class="border-right text-center" ><a class="sort" column="qty">Current Qty</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="products-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/pinventory.php",
      "limit":"15",
			"data_key":"warehouse2_inventories",
			"row_template":"row_template_products_inventory",
      "pagination":"#products-pagination",
      "searchable":true
		}
	
		$('#grid-products').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>