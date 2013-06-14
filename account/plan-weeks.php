<?php
  /* Module: Name  */
  $capability_key = 'plan_weeks';
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
      <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
      	<input type="hidden" id="current_week" value=""/>
			<h3 class="form-title">Shipment Calendar</h3>	
      <?php
      	function getDay($m, $index) {
					return date($m, strtotime(' Thursday +'.$index.' week', strtotime(date('Y').'-01-01')));	
				}
				
				function createMonth($month_index, $month, $wk_ctr) {
					echo '<table cellspacing="0" cellpadding="0" style="width:200px; float:left; padding-right: 2px">';
	        echo '<thead><tr><td colspan="99" style="padding:1px" class="border-right text-center"><a class="item-month" rel="'.$month_index.'">'.$month.'</a></td></tr></thead>';
					echo '<tbody>';
					
					// echo '<tr>';
					// $wk_ctr1 = $wk_ctr;
					// while (getDay('n',$wk_ctr1) == $month_index) {
						// echo '<td class="text-center border-right">W'.($wk_ctr1+1).'</td>'; 
						// $wk_ctr1+=1;
					// }
					// echo '</tr>';
					
					echo '<tr>';
					$wk_ctr2 = $wk_ctr;
					while (getDay('n',$wk_ctr2) == $month_index) {
						echo '<td class="text-center border-right"  style="padding:1px;" value="'.getDay('Y-m-d',$wk_ctr2).'"><a href="#" style="display:block; padding-top:3px; height:26px; width:100%;" class="item-week" rel="'.getDay('Y-m-d',$wk_ctr2).'">'.getDay('n/j',$wk_ctr2).'</a></td>'; 
						$wk_ctr2+=1;
					}
					echo '</tr></tbody></table>';
					return $wk_ctr2;
				}
				
				$wk_ctr = 0;
				echo '<div class="grid jq-grid" style="max-width: 1320px">';
				for($m=1; $m<=6; $m++) {
					$wk_ctr = createMonth($m, getDay('F', $wk_ctr), $wk_ctr);
				}
				echo '</div>';
				echo '<br/>';
				echo '<div class="grid jq-grid" style="max-width: 1320px">';
				for($m=7; $m<=12; $m++) {
					$wk_ctr = createMonth($m, getDay('F', $wk_ctr), $wk_ctr);
				}
				echo '</div>';
				echo '<br/>';
      ?>
      <br/>
      <!-- BOF Search -->
      <div class="search-title">
      	P/O Models
      </div>
      <div class="search">
        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
      </div>
        
      <!-- BOF GridView -->
      <div id="grid-products" class="grid jq-grid" style="min-height:200px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="120"><a class="sort default active up" column="code">Code</a></td>
              <td class="border-right text-center" width="150"><a class="sort" column="po_number">P/O No.</a></td>
              <td class="border-right text-center" width="90"><a class="sort" column="series">Series</a></td>
              <td class="border-right text-center" width="90"><a class="sort" column="pack">Pack</a></td>
              <td class="border-right text-center"><a class="sort" column="remarks">Remarks</a></td>
              <td class="border-right text-center" width="90"><a class="sort" column="unit">Unit</a></td>
              <td class="border-right text-center" width="90"><a class="sort" column="qty">Qty</a></td>
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
		$(function(){
			$('.item-month').live('click', function(){
				alert($(this).attr('rel'));
			})
			$('.item-week').live('click', function(){
				var current_week = $(this).attr('rel');
				$('#current_week').val(current_week);
				$('.search-title').html('P/O Models &raquo; <span class="red">'+ current_week +'</span>');
				loadData(current_week);
			})
		})
		
		function loadData() {
			var data = { 
	    	"url":"/populate/shipment-plan-week.php?sdate="+$('#current_week').val(),
	      "limit":"15",
				"data_key":"shipment_plans",
				"row_template":"row_template_ship_plan_week",
	      "pagination":"#products-pagination",
	      "searchable":true
			}
		
			$('#grid-products').grid(data);
		}
	</script>
<?php }
require('footer.php'); ?>