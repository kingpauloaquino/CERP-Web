<?php
  /*
   * Module: Materials Inventory
  */
  $capability_key = 'product_inventory';  
  require('header.php');
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  //echo '<a href="'.$Capabilities->All['add_product_inventory']['url'].'" class="nav">'.$Capabilities->All['add_product_inventory']['name'].'</a>';
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
      <div id="grid-products" class="grid jq-grid">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td width="150" class="border-right text-center"><a class="sort default active up" column="code">Code</a></td>
              <td width="100" class="border-right text-center"><a class="sort" column="brand">Brand</a></td>
              <td width="90" class="border-right text-center"><a class="sort" column="pack">Pack</a></td>
              <td width="90" class="border-right text-center"><a class="sort" column="color">Color</a></td>
              <td class="border-right"><a class="sort" column="description">Description</a></td>
              <td width="100" class="border-right text-center" ><a class="sort" column="uom">UOM</a></td>
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
      "pagination":"#products-pagination"
		}
	
		$('#grid-products').grid(data);
  }) 
 </script>
<?php require('footer.php'); ?>