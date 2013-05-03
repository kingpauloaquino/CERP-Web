<?php
  /*
   * Module: Deliveries 
  */
  $capability_key = 'deliveries';  
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
				  //echo '<a href="'.$Capabilities->All['supplier_delivery']['url'].'" class="nav">'.$Capabilities->All['supplier_delivery']['title'].'</a>';
				  echo '<a href="'.$Capabilities->All['add_deliveries']['url'].'" class="nav">'.$Capabilities->All['add_deliveries']['title'].'</a>';
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
      <div id="grid-deliveries" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
							<td class="border-right text-center" width="130"><a class="sort default active up" column="purchase_number">P/O Number</a></td> 
              <td class="border-right text-center"><a class="sort down" column="supplier_name">Supplier</a></td>
              <td class="border-right text-center" width="90"><a class="sort" column="status">Status</a></td>  
              <td class="border-right text-center" width="100"><a class="sort" column="delivery_date">Delivery Date</a></td>  
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="deliveries-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/deliveries.php",
      "limit":"15",
			"data_key":"deliveries",
			"row_template":"row_template_deliveries",
      "pagination":"#deliveries-pagination",
      "searchable":true
		}
	
		$('#grid-deliveries').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>