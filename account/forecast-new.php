<?php
  /* Module: Forecasts New */
  $capability_key = 'add_forecast';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	// if(!$allowed) {
		// require('inaccessible.php');	
	// }else{
		
		$active_year = date('Y');
		
		$data = $DB->Find('forecasts', array('columns' => 'created_at', 'conditions' => 'product_id='.$_GET['pid'].' ORDER BY created_at DESC LIMIT 1'));
		if(!empty($data)) {
			if(date('Y', strtotime($data['created_at'])) == date('Y')) { //already has entry for current year
				
			} else {
				
			}
		}
?>
	<!-- BOF PAGE -->
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
      
      <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
      	<input type="hidden" name="action" value="add_forecast_1st_half" />
      	<input type="hidden" name="forecast_year" value="<?php echo $active_year ?>" />
				<div id="grid-products" class="grid jq-grid" style="min-height:400px;">
	        <table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td width="110" class="border-right text-center"><a class="sort default active up" column="code">Code</a></td>
              	<td class="border-right text-center"><a class="sort" column="description">Description</a></td>
	              <td width="110" class="border-right text-center"><a>Jan</a></td>
	              <td width="110" class="border-right text-center"><a>Feb</a></td>
	              <td width="110" class="border-right text-center"><a>Mar</a></td>
	              <td width="110" class="border-right text-center"><a>Apr</a></td>
	              <td width="110" class="border-right text-center"><a>May</a></td>
	              <td width="110" class="border-right text-center"><a>Jun</a></td>
	            </tr>
	          </thead>
	          <tbody></tbody>
	        </table>
	      </div>
				<!-- BOF Pagination -->
				<div id="products-pagination"></div>
				<div class="field-command">
					<div class="text-post-status">
						<strong>Save As:</strong>&nbsp;&nbsp;<select name="forecast[status]"><?php echo build_select_post_status(); ?></select>
					</div>
					<input type="submit" value="Save" class="btn"/>
					<input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('forecasts.php'); ?>"/>
				</div>
      </form>
		</div>
	</div>
	<script>
	$(function() {
  	var data = { 
    	"url":"/populate/forecast-calendar.php?yr=<?php echo $active_year ?>",
      "limit":"15",
			"data_key":"forecast_calendar",
			"row_template":"row_template_forecast_1st_half",
      "pagination":"#products-pagination",
      "searchable":true
		}
	
		$('#grid-products').grid(data);
  }) 
 </script>
<?php //}
require('footer.php'); ?>