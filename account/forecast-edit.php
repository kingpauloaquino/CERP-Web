<?php
  /* Module: Forecasts Edit */
  $capability_key = 'edit_forecast';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
		$product = $Query->product_by_id($_GET['pid']);
		
		$active_year = date('Y');
		
		$calendar = $Query->forecast_calendar_by_product_id_year($_GET['pid'], $active_year);
?>
	<!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
      	<?php
				  echo '<a href="'.$Capabilities->All['show_forecast']['url'].'?pid='.$_GET['pid'].'" class="nav">'.$Capabilities->All['show_forecast']['name'].'</a>';
      	?>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
      <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
      	<input type="hidden" name="action" value="edit_forecast_days" />
      	<input type="hidden" id="forecast_cal_id" name="forecast_cal_id" />
      	<input type="hidden" id="current_year" name="current_year" />
      	<input type="hidden" id="current_month" name="current_month" />
      	<input type="hidden" id="current_month_id" name="current_month_id" />
      	<input type="hidden" id="month_total" />
      	<div>
					<table>
						<tr>
							<td width="150">Product Code:</td><td width="310"><input type="text" value="<?php echo $product['product_code'] ?>" class="text-field" disabled/></td>
							<td width="150">Brand:</td><td><input type="text" value="<?php echo $product['brand'] ?>" class="text-field" disabled/>
							</td>
						</tr>
						<tr>
							<td>Barcode:</td><td><input type="text" value="<?php echo $product['bar_code'] ?>" class="text-field" disabled/></td>
							<td>Color:</td><td><input type="text" value="<?php echo $product['color'] ?>" class="text-field" disabled/></td>
						</tr>    
						<tr>
							<td>Pack:</td><td><input type="text" value="<?php echo $product['classification'] ?>" class="text-field" disabled/></td>
							<td>Status:</td><td><input type="text" value="<?php echo $product['status'] ?>" class="text-field" disabled/></td>
						</tr>    
						<tr>
							<td>Production CP:</td><td><input type="text" value="<?php echo $product['prod_cp'] ?>" class="text-field text-right" disabled/></td>
							<td></td>
						</tr>             
						<tr>
							<td>Description:</td>
							<td colspan="99">
								<input type="text" value="<?php echo $product['description'] ?>" class="text-field" style="width:645px" disabled/>
							</td>
						</tr>
						<tr><td height="5" colspan="99"></td></tr>
					</table>
				</div>
	      <br/>
	      
				<h3 class="form-title">Year Forecast</h3>
				<div id="notice-fade">
					<span class="notice">
	          <p class="info"><strong>Notice!</strong> Select and click quantity under month to view its week forecasts</p>
	        </span>
				</div>
				<div id="grid-calendar" class="grid jq-grid" style="min-height:60px;">
	        <table id="tbl-calendar" cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td class="border-right text-center"><a>Year</a></td>
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
	      
	      <br/>
				<h3 class="form-title" id="forecast-map">Details</h3>

	      <div id="tabs-left">
				  <ul class="ui-tabs-nav">
				    <li><a href="#tabs-left-1" id="1" class="week-link">Week 1</a></li>
				    <li><a href="#tabs-left-2" id="2" class="week-link">Week 2</a></li>
				    <li><a href="#tabs-left-3" id="3" class="week-link">Week 3</a></li>
				    <li><a href="#tabs-left-4" id="4" class="week-link">Week 4</a></li>
				  </ul>
				  
				  <?php
				  	for($i=1; $i<=4; $i++) {
		  		?>
		  		<div id="tabs-left-<?php echo $i?>">
		  			<div class="week-header">
		  				<div class="week-num"><b>Week <?php echo $i ?></b></div>
		  				<div class="month-total">&nbsp;</div>
		  			</div>
		  			
				    <div id="grid-week-<?php echo $i?>" class="grid jq-grid" style="min-height:140px;">
			        <table id="tbl-week-<?php echo $i?>" cellspacing="0" cellpadding="0">
			          <thead>
			            <tr>
			              <td class="border-right text-center"><a>Line</a></td>
			              <td width="105" class="border-right text-center"><a>Day 1</a></td>
			              <td width="105" class="border-right text-center"><a>Day 2</a></td>
			              <td width="105" class="border-right text-center"><a>Day 3</a></td>
			              <td width="105" class="border-right text-center"><a>Day 4</a></td>
			              <td width="105" class="border-right text-center"><a>Day 5</a></td>
			              <td width="105" class="border-right text-center"><a>Week Total</a></td>
			            </tr>
			          </thead>
			          <tbody id="week-<?php echo $i?>" class="week-rows"></tbody>
			        </table>
			      </div>
				  </div>
		  		<?php
				  	}
				  ?>
				</div>
				<br/>
				<div class="field-command">
					<div class="text-post-status">
						<strong>Save As:</strong>&nbsp;&nbsp;<select name="forecast[status]" ><?php echo build_select_post_status_by_level(getConditionByLevel($_SESSION['user']['level'])); ?></select>
					</div>
					<input type="submit" value="Save" class="btn"/>
					<input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('forecast-show.php?pid='.$_GET['pid']); ?>"/>
				</div>
      </form>
		</div>
	</div>
	
	
	<script>
		$(function() {
			$( "#tabs-left" ).tabs({
	    });
	    
	    var data = { 
	    	"url":"/populate/forecast-months.php?pid=<?php echo $_GET['pid']; ?>",
	      "limit":"10",
				"data_key":"forecast_months",
				"row_template":"row_template_forecast_months",
			}	
			$('#grid-calendar').grid(data);	
			
			// Set initial
			//getWeekdays($('#forecast_cal_id').val(), $('#current_month_id').val(), $(this).attr('id'));
			
			// Month Select
			$('#tbl-calendar').on('click', '.month', function () {
				resetTables();
				var forecast_cal_id = $(this).closest('tr').attr('forecast_cal_id');
				var current_year = $(this).attr('year');
				var current_month = $(this).attr('month');
				var month_id = $(this).attr('id');
				
				$('#forecast_cal_id').val(forecast_cal_id);
				$('#current_year').val(current_year);
				$('#current_month').val(current_month);
				$('#current_month_id').val(month_id);
				$('#month_total').val($(this).html());
				$('.month-total').html('Month Total: '+$(this).html());
				
		    $('#forecast-map').html(current_year+' &raquo; '+current_month+' &raquo; <span class="red">Week 1</span>');
		    getWeekdays(forecast_cal_id, month_id, 1);
			});
			
	    // Week Select
	    $('.ui-tabs-nav').find('.week-link').click(function(){
				$('#forecast-map').html($('#current_year').val()+' &raquo; '+$('#current_month').val()+' &raquo; <span class="red">Week '+$(this).attr('id')+'</span>');
				getWeekdays($('#forecast_cal_id').val(), $('#current_month_id').val(), $(this).attr('id'));
			})
			
			function getWeekdays(forecast_week_id, mo, wk) {
				var data = { 
		    	"url":"/populate/forecast-week-days.php?fwid="+forecast_week_id+"&mo="+mo+"&wk="+wk,
		      "limit":"10",
					"data_key":"forecast_week_days",
					"row_template":"row_template_forecast_week_days",
				}	
				$('#grid-week-'+wk).grid(data);	
			}
			
			function resetTables() {
				$('.week-rows').empty();	
				$('#tabs-left').tabs({ active: 0}); 
			}
			
			setTimeout(function(){
				$("#notice-fade").fadeOut("slow", function () {
			  	//$("#notice-fade").remove();
		    });
			}, 6000);
		})
		
		$.fn.show_daily_modal = function() {
  	$()
    	this.live('click', function(e) {
	    	$('#btn-daily-forecast').click();	
	    	//clear
	    	$('#daily-day1').val('');
	  		$('#daily-day2').val('');
	  		$('#daily-day3').val('');
	  		$('#daily-day4').val('');
	  		$('#daily-day5').val('');
	    	
	    	$('#week_id').val($(this).attr('id'));
	    	
	    	$.ajax({
				  dataType: "json",
				  url: "/cerp/populate/forecast-week-days.php?wid="+$(this).attr('id'),
				  success: function(data) {
				  	if(data['forecast_week_days'].length > 0) {
				  		$('#daily-day1').val(data['forecast_week_days'][0].day1);
				  		$('#daily-day2').val(data['forecast_week_days'][0].day2);
				  		$('#daily-day3').val(data['forecast_week_days'][0].day3);
				  		$('#daily-day4').val(data['forecast_week_days'][0].day4);
				  		$('#daily-day5').val(data['forecast_week_days'][0].day5);
				  	}
				  }
				});
				  
    })
   }
	</script>
<?php }
require('footer.php'); ?>