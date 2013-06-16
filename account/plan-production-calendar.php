<?php
  /* Module: Name  */
  $capability_key = 'plan_production_calendar';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);		
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
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
      <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container" style="min-width: 1140px;">
      	<input type="hidden" id="current_week" value=""/>
      	<input type="hidden" id="current_month" value=""/>
      <?php
      	function getDay($m, $index) {
					return date($m, strtotime(' Friday +'.$index.' week', strtotime(date('Y').'-01-01')));	
				}
				
				function createMonth($month_index, $month, $wk_ctr) {
					echo '<table cellspacing="0" cellpadding="0" style="width:190px; float:left; padding-right: 3px">';
	        echo '<thead><tr><td colspan="99" style="padding:1px" class="border-right text-center"><a class="item-month" title="'.$month.'" rel="'.$month_index.'">'.$month.'</a></td></tr></thead>';
					echo '<tbody>';
					
					echo '<tr>';
					$wk_ctr1 = $wk_ctr;
					while (getDay('n',$wk_ctr1) == $month_index) {
						echo '<td class="text-center border-right" style="font-size:11px;">W'.($wk_ctr1+1).'</td>'; 
						$wk_ctr1+=1;
					}
					echo '</tr>';
					
					echo '<tr>';
					$wk_ctr2 = $wk_ctr;
					while (getDay('n',$wk_ctr2) == $month_index) {
						echo '<td class="text-center border-right" style="padding:1px;" value="'.getDay('Y-m-d',$wk_ctr2).'"><a href="#" style="font-size:11px;display:block; padding-top:3px; height:26px; width:100%;" class="item-week" rel="'.getDay('Y-m-d',$wk_ctr2).'">'.getDay('n/j',$wk_ctr2).'</a></td>'; 
						$wk_ctr2+=1;
					}
					echo '</tr></tbody></table>';
					return $wk_ctr2;
				}
				
				$wk_ctr = 0;
				echo '<div class="grid jq-grid" style="max-width: 1140px">';
				for($m=1; $m<=6; $m++) {
					$wk_ctr = createMonth($m, getDay('F', $wk_ctr), $wk_ctr);
				}
				echo '</div>';
				echo '<br/>';
				echo '<div class="grid jq-grid" style="max-width: 1140px">';
				for($m=7; $m<=12; $m++) {
					$wk_ctr = createMonth($m, getDay('F', $wk_ctr), $wk_ctr);
				}
				echo '</div>';
				echo '<br/>';
      ?>
      <br/>
      <div id="plan-weeks" style="display: none;">
	      <!-- BOF Search -->
	      <div class="search-title">
	      	Production Plan Week
	      </div>
	      <div class="search">
	        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
	      </div>
	        
	      <!-- BOF GridView -->
	      <div id="grid-products-week" class="grid jq-grid" style="min-height:140px;">
	        <table cellspacing="0" cellpadding="0" >
	          <thead>
	            <tr>
	              <td class="border-right text-center" width="130"><a class="sort default active up" column="code">Code</a></td>
	              <td class="border-right text-center" width="70"><a class="sort" column="series">Series</a></td>
	              <td class="border-right text-center" width="70"><a class="sort" column="pack_qty">Pack Qty</a></td>
	              <td class="border-right text-center"><a class="sort" column="description">Description</a></td>
	              <td class="border-right text-center" width="70"><a class="sort" column="unit">Unit</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="total_qty">Qty</a></td>
	            </tr>
	          </thead>
	          <tbody></tbody>
	        </table>
	      </div>
	      <!-- BOF Pagination -->
	      <div id="products-pagination-week"></div>
      </div>
      
      <div id="plan-month" style="display: none;">
	      <!-- BOF Search -->
	      <div class="search-title">
	      	P/O Model Month
	      </div>
	      <div class="search">
	        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
	      </div>
	        
	      <!-- BOF GridView -->
	      <div id="grid-products-month" class="grid jq-grid" style="min-height:140px;">
	        <table cellspacing="0" cellpadding="0" >
	          <thead>
	            <tr>
	              <td class="border-right text-center" width="120"><a class="sort default active up" column="prod_date">Prod Date</a></td>
	              <td class="border-right text-center" width="130"><a class="sort" column="code">Code</a></td>
	              <td class="border-right text-center" width="70"><a class="sort" column="series">Series</a></td>
	              <td class="border-right text-center" width="70"><a class="sort" column="pack_qty">Pack Qty</a></td>
	              <td class="border-right text-center"><a class="sort" column="description">Description</a></td>
	              <td class="border-right text-center" width="70"><a class="sort" column="unit">Unit</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="total_qty">Qty</a></td>
	            </tr>
	          </thead>
	          <tbody></tbody>
	        </table>
	      </div>
	      
	      <!-- BOF Pagination -->
	      <div id="products-pagination-month"></div>
      </div>
      
      </form>
		</div>
	</div>

	<script>
		$(function(){
			$('.item-month').live('click', function(){
				var current_month = $(this).attr('rel'); 
				$('#current_month').val(current_month);
				$('.search-title').html('Production Plan Month &raquo; <span class="red">'+ $(this).attr('title') +'</span>');
				loadMonth(current_month);
				$('#plan-weeks').fadeOut('fast', function(){
					$('#plan-month').fadeIn('fast', function(){})
				})
			})
			$('.item-week').live('click', function(){
				var current_week = $(this).attr('rel');
				$('#current_week').val(current_week);
				$('.search-title').html('Production Plan Week &raquo; <span class="red">'+ current_week +'</span>');
				loadWeek(current_week);
				$('#plan-month').fadeOut('fast', function(){
					$('#plan-weeks').fadeIn('fast', function(){})
					
				})
			})
		})
		
		function loadWeek(param) {
			var data = { 
	    	"url":"/populate/production-plan-week.php?pdate="+param,
	      "limit":"15",
				"data_key":"production_plans",
				"row_template":"row_template_prod_plan_week",
	      "pagination":"#products-pagination-week",
	      "searchable":true
			}
		
			$('#grid-products-week').grid(data);
		}
		function loadMonth(param) {
			var data = { 
	    	"url":"/populate/production-plan-month.php?pmonth="+param,
	      "limit":"15",
				"data_key":"production_plans",
				"row_template":"row_template_prod_plan_month",
	      "pagination":"#products-pagination-month",
	      "searchable":true
			}
		
			$('#grid-products-month').grid(data);
		}
	</script>
<?php }
require('footer.php'); ?>