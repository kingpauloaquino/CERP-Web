<?php
  /*
   * Module: Terminal Items
  */
  $capability_key = 'terminal_prod_items';  
  require('header.php');
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php 
      		$trml = '';
      		switch($_GET['tid']){
						case 3: $trml = 'Pre-production'; break;
						case 5: $trml = 'Ink-filling'; break;
						case 7: $trml = 'LSP'; break;
						case 9: $trml = 'Packing'; break;
      		}
      		echo $trml.' '.$Capabilities->GetTitle(); ?></span>
        <?php
				  //echo '<a href="'.$Capabilities->All['add_material_inventory']['url'].'" class="nav">'.$Capabilities->All['add_material_inventory']['name'].'</a>';
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
      <div id="grid-materials" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="100"><a class="sort default active up" column="ppoid">Prod. Plan ID</a></td>
              <td class="border-right text-center" width="110"><a class="sort" column="product">Product</a></td>
              <td class="border-right text-center" width="100"><a class="sort" column="prod_lot_no">Prod. Lot No</a></td>
              <td class="border-right text-center" width="100"><a class="sort" column="tracking_no">Tracking No</a></td>
              <td class="border-right text-center" width="100"><a class="sort" column="mat_lot_no">Mat. Lot No</a></td>
              <td class="border-right text-center" width="120"><a class="sort" column="material_code">Mat. Code</a></td>
              <td class="border-right text-center"><a class="sort" column="description">Description</a></td>
              <td class="border-right text-center" width="100"><a class="sort" column="status">Status</a></td>
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
    	"url":"/populate/terminal-prod-items.php?tid=<?php echo $_GET['tid']; ?>",
      "limit":"15",
			"data_key":"terminal_prod_items",
			"row_template":"row_template_terminal_prod_items",
      "pagination":"#materials-pagination"
		}
	
		$('#grid-materials').grid(data);
  }) 
 </script>
<?php require('footer.php'); ?>