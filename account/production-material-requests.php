<?php
  /*
   * Module: Material Requests 
  */
  $capability_key = 'material_requests';
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
				  	echo '<a href="'.$Capabilities->All['add_material_requests']['url'].'" class="nav">Create</a>';
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
      <div id="grid-requests" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td width="110" class="border-right text-center"><a class="sort" column="request_no">Request #</a></td>
              <td width="110" class="border-right text-center"><a class="sort" column="request_type">Type</a></td>
              <td width="100" class="border-right text-center"><a class="sort" column="batch_no">Batch</a></td>
              <td class="border-right"><a class="sort" column="remarks">Remarks</a></td>
              <td width="100" class="border-right text-center text-date"><a class="sort" column="requested_date">Requested</a></td>
              <td width="100" class="border-right text-center text-date"><a class="sort default active up"column="expected_date">Expected</a></td>
              <td width="100" class="border-right text-center"><a class="sort"column="expected_date">Status</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="requests-pagination"></div>
		</div>
	</div>
	
	<script>
	$(function() {
  	var data = { 
    	"url":"/populate/material-requests.php",
      "limit":"15",
			"data_key":"requests",
			"row_template":"row_template_material_requests",
      "pagination":"#requests-pagination",
      "searchable":true,
      "sort":"ASC",
      "order":"expected_date, id"
		}
	
		$('#grid-requests').grid(data);
  }) 
  </script>

<?php }
require('footer.php'); ?>