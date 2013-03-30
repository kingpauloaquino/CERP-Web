<?php
  /*
   * Module: Suppliers 
  */
  $capability_key = 'products';  
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
				  echo '<a href="'.$Capabilities->All['add_product']['url'].'" class="nav">'.$Capabilities->All['add_product']['name'].'</a>';
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
              <td class="border-right text-center" width="150"><a class="sort default active up" column="code">Code</a></td>
              <td class="border-right text-center" width="100"><a class="sort down" column="brand">Brand</a></td>
              <td class="border-right text-center" width="90"><a class="sort down" column="pack">Pack</a></td>
              <td class="border-right text-center" width="90"><a class="sort" column="color">Color</a></td>
              <td class="border-right text-center"><a class="sort" column="description">Description</a></td>
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
    	"url":"/populate/products.php",
      "limit":"15",
			"data_key":"products",
			"row_template":"row_template_products",
      "pagination":"#products-pagination"
		}
	
		$('#grid-products').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>