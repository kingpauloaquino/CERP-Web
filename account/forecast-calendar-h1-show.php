<?php
  /* Module: Forecasts Calendar Show */
  $capability_key = 'show_forecast_calendar_h1';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
		$active_year = date('Y');
?>
	<!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['forecast_calendar']['url'].'" class="nav">'.$Capabilities->All['forecast_calendar']['name'].'</a>';
				  echo '<a href="'.$Capabilities->All['show_forecast_calendar_h2']['url'].'" class="nav">'.$Capabilities->All['show_forecast_calendar_h2']['name'].'</a>';
				  echo '<a href="'.$Capabilities->All['edit_forecast_calendar_h1']['url'].'" class="nav">'.$Capabilities->All['edit_forecast_calendar_h1']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
      </div>
      
      <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
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
      </form>
		</div>
	</div>
	<script>
	$(function() {
  	var data = { 
    	"url":"/populate/forecast-calendar.php?yr=<?php echo $active_year ?>",
      "limit":"15",
			"data_key":"forecast_calendar",
			"row_template":"row_template_forecast_h1_read_only",
      "pagination":"#products-pagination",
      "searchable":true
		}
	
		$('#grid-products').grid(data);
  }) 
 </script>
<?php }
require('footer.php'); ?>