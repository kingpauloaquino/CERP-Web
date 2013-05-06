<?php
  /*
   * Module: Work Orders 
  */
  $capability_key = 'work_orders';  
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
				  echo '<a href="'.$Capabilities->All['add_work_order']['url'].'" class="nav">'.$Capabilities->All['add_work_order']['name'].'</a>';
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
      <div id="grid-work-orders" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="140"><a class="sort default active up" column="wo_number">W/O Number</a></td>
              <td class="border-right text-center" width="120"><a class="sort default active up" column="wo_date">W/O Date</a></td>
              <td class="border-right text-center"><a class="sort" column="description">Remarks</a></td>
              <td class="border-right text-center" width="120"><a class="sort" column="ship_date">Ship Date</a></td>
              <td class="border-right text-center" width="120"><a class="sort" column="status">Approval</a></td>
              <td class="text-center" width="120"><a class="sort" column="completion_status">Completion</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="work-orders-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/work-orders.php",
      "limit":"15",
			"data_key":"work_orders",
			"row_template":"row_template_work_orders",
      "pagination":"#work-orders-pagination",
      "searchable":true
		}
	
		$('#grid-work-orders').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>