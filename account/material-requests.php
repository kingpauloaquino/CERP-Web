<?php
  /*
   * Module: Material Requests 
  */
  $capability_key = 'material_requests';
  
  require('header.php');
	
  if(isset($_REQUEST['search'])){
    $cur_search = trim($_REQUEST['search']);
    $cur_filter = $_REQUEST['filter'];
  }
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['add_material_request']['url'].'" class="nav">'.$Capabilities->All['add_material_request']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" name="keyword" placeholder="Search"/>
        <button>Go</button>
      </div>
        
      <!-- BOF GridView -->
      <div id="grid-material-requests" class="grid jq-grid">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="110"><a class="sort default active up" column="po_number">P/O #</a></td>
              <td class="border-right text-center" width="110"><a class="sort" column="lot_no">Lot #</a></td>
              <td class="border-right text-center" width="100"><a class="sort" column="brand">Brand</a></td>
              <td class="border-right text-center"><a class="sort" column="product_code">Product</a></td>
              <td class="border-right text-center" width="100"><a class="sort" column="request_date">Date</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="material-requests-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/material-requests.php",
      "limit":"15",
			"data_key":"material_requests",
			"row_template":"row_template_material_requests",
      "pagination":"#material-requests-pagination"
		}
	
		$('#grid-material-requests').grid(data);
  }) 
 </script>
<?php require('footer.php'); ?>