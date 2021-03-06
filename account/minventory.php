<?php
  /*
   * Module: Materials Inventory
  */
  $capability_key = 'material_inventory';  
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
				  //echo '<a href="'.$Capabilities->All['show_materials_inventory_report']['url'].'" class="nav">'.$Capabilities->All['show_materials_inventory_report']['name'].' Report</a>';
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
	      <div id="grid-materials" class="grid jq-grid" style="min-height:400px;">
	        <table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td class="border-right text-center" width="160"><a class="sort default active up" column="code">Code</a></td>
	              <td class="border-right text-center" width="140"><a class="sort" column="classification">Classification</a></td>
	              <td class="border-right text-center"><a class="sort" column="description">Description</a></td>
	              <td class="border-right text-center" width="60"><a class="sort" column="uom">UOM</a></td>
	              <td class="border-right text-center" width="100"><a class="sort" column="qty">Current Qty</a></td>
	            </tr>
	          </thead>
	          <tbody></tbody>
	        </table>
	      </div>
	      
	      <!-- BOF Pagination -->
	      <div id="materials-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/minventory.php",
      "limit":"15",
			"data_key":"material_inventory",
			"row_template":"row_template_materials_inventory",
      "pagination":"#materials-pagination",
      "searchable":true
		}
	
		$('#grid-materials').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>