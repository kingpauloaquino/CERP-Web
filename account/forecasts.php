<?php
  /* Module: Forecasts  */
  $capability_key = 'forecasts';
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
				  echo '<a href="'.$Capabilities->All['edit_forecast_calendar_h1']['url'].'" class="nav">'.$Capabilities->All['edit_forecast_calendar_h1']['name'].'</a>';
				  echo '<a href="'.$Capabilities->All['edit_forecast_calendar_h2']['url'].'" class="nav">'.$Capabilities->All['edit_forecast_calendar_h2']['name'].'</a>';
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
	              <td class="border-right text-center"><a class="sort default active up" column="code">Code</a></td>
	              <td width="60" class="border-right text-center"><a>Jan</a></td>
	              <td width="60" class="border-right text-center"><a>Feb</a></td>
	              <td width="60" class="border-right text-center"><a>Mar</a></td>
	              <td width="60" class="border-right text-center"><a>Apr</a></td>
	              <td width="60" class="border-right text-center"><a>May</a></td>
	              <td width="60" class="border-right text-center"><a>Jun</a></td>
	              <td width="60" class="border-right text-center"><a>Jul</a></td>
	              <td width="60" class="border-right text-center"><a>Aug</a></td>
	              <td width="60" class="border-right text-center"><a>Sep</a></td>
	              <td width="60" class="border-right text-center"><a>Oct</a></td>
	              <td width="60" class="border-right text-center"><a>Nov</a></td>
	              <td width="60" class="border-right text-center"><a>Dec</a></td>
	              <td width="60" class="border-right text-center"><a>Total</a></td>
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
			"row_template":"row_template_forecast_read_only",
      "pagination":"#products-pagination",
      "searchable":true
		}
	
		$('#grid-products').grid(data);
  }) 
 </script>
<?php }
require('footer.php'); ?>