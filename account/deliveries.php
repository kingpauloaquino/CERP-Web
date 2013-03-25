<?php
  /*
   * Module: Deliveries 
  */
  $capability_key = 'deliveries';  
  require('header.php');
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  //echo '<a href="'.$Capabilities->All['add_delivery']['url'].'" class="nav">'.$Capabilities->All['add_delivery']['name'].'</a>';
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
      <div id="grid-deliveries" class="grid jq-grid">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
							<td class="border-right text-center" width="110"><a class="sort default active up" column="delivery_receipt">Receipt</a></td>
              <td class="border-right text-center"><a class="sort down" column="supplier_name">Supplier</a></td>
              <td class="border-right text-center" width="80"><a class="sort" column="status">Status</a></td>   
              <td class="border-right text-center" width="100"><a class="sort" column="date_received">Date Received</a></td>  
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
      "pagination":"#deliveries-pagination"
		}
	
		$('#grid-deliveries').grid(data);
  }) 
 </script>
<?php require('footer.php'); ?>