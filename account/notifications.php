<?php
  /*
   * Module: Notifications 
  */
  $capability_key = 'notifications';  
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
      <div id="grid-notifications" class="grid jq-grid">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="120"><a class="sort default active up" column="created_at">Date</a></td>
              <td class="border-right text-center" width="110"><a class="sort" column="type">Type</a></td>
              <td class="border-right text-center" width="130"><a class="sort" column="title">Title</a></td>
              <td class="border-right text-center" width="120"><a class="sort" column="value">Parameter</a></td>
              <td class="border-right text-center"><a class="sort" column="remarks">Remarks</a></td>
              <td class="border-right text-center" width="110"><a class="sort" column="value">Status</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="notifications-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/notifications.php",
      "limit":"15",
			"data_key":"notifications",
			"row_template":"row_template_notifications",
      "pagination":"#notifications-pagination",
      "searchable":true
		}
	
		$('#grid-notifications').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>