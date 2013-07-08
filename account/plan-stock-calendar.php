<?php
  /* Module: Name  */
  $capability_key = 'plan_stock_calendar';
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
        <?php
				  //echo '<a href="'.$Capabilities->All['actual_shipment_calendar']['url'].'" class="nav">Actual Shipment</a>';
				?>      		
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
    	<a id="btn-months" href="#modal-product-months-all" rel="modal:open"></a>
    	<a id="btn-weeks" href="#modal-product-weeks-all" rel="modal:open"></a>
      <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container" style="min-width: 1140px;">
      	<input type="hidden" id="current_week" value=""/>
      	<input type="hidden" id="current_month" value=""/>
      <?php
      	function getDay($m, $index) {
					return date($m, strtotime(' Thursday +'.$index.' week', strtotime(date('Y').'-01-01')));	
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
						$current_week = '';
						if(($wk_ctr2+1) == date('W')) {
							$current_week = 'highlight-yellow';
						}
						echo '<td class="text-center border-right '.$current_week.'" style="padding:1px;" value="'.getDay('Y-m-d',$wk_ctr2).'"><a href="#" style="font-size:11px;display:block; padding-top:3px; height:26px; width:100%;" class="item-week" rel="'.getDay('Y-m-d',$wk_ctr2).'">'.getDay('n/j',$wk_ctr2).'</a></td>'; 
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
	      	P/O Model Week
	      </div>
	      <div class="search">
	        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
	      </div>
	        
	      <!-- BOF GridView -->
	      <div id="grid-products-week" class="grid jq-grid" style="min-height:140px;">
	        <table id="tbl-products-week" cellspacing="0" cellpadding="0" >
	          <thead>
	            <tr>
	              <td class="border-right"><a class="sort default active up" column="code">Model</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="series">Series</a></td>
	              <td class="border-right text-center" width="70"><a class="sort" column="pack_qty">Pack</a></td>
	              <td class="border-right text-center" width="70"><a class="sort" column="unit">Unit</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="ttl">Total</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="ttls">Singles Total</a></td>
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
	        <table id="tbl-products-month" cellspacing="0" cellpadding="0" >
	          <thead>
	            <tr>
	              <td class="border-right text-center" width="120"><a class="sort default active up" column="ship_date">Ship Date</a></td>
	              <td class="border-right" ><a class="sort" column="code">Model</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="series">Series</a></td>
	              <td class="border-right text-center" width="70"><a class="sort" column="pack_qty">Pack</a></td>
	              <td class="border-right text-center" width="70"><a class="sort" column="unit">Unit</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="ttl">Total</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="ttls">Singles Total</a></td>
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
	
	<!-- BOF MODAL -->
	<div id="modal-product-weeks-all" class="modal" style="display:none;width:920px;">
		<div class="modal-title"><h3>Week Shipment</h3></div>
		<div class="modal-content">
			<form id="frm-product-weeks-all" method="POST">
				<!-- BOF GRIDVIEW -->
				<div id="grid-product-weeks-all" class="grid jq-grid">
					<table id="tbl-product-weeks-all" cellspacing="0" cellpadding="0">
						<thead>
							<tr> 
	              <td class="border-right text-center" width="100"><a class="sort" column="ctrl_no">Ctrl No.</a></td>
	              <td class="border-right text-center" width="50"><a class="sort" column="type">Type</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="series">Series</a></td>
	              <td class="border-right text-center" width="50"><a class="sort" column="pack_qty">Pack</a></td>
	              <td class="border-right text-center"><a class="sort" column="remarks">Remarks</a></td>
	              <td class="border-right text-center" width="60"><a class="sort" column="unit">Unit</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="ttl">Total</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="ttls">Singles Total</a></td>
							</tr>
						</thead>
						<tbody id="product-weeks-all"></tbody>
					</table>
				</div>	
			</form>
		</div>
		<div class="modal-footer">
			<a class="btn parent-modal" rel="modal:close">Close</a>
			<div class="clear"></div>
		</div>
	</div>
	
	<!-- BOF MODAL -->
	<div id="modal-product-months-all" class="modal" style="display:none;width:920px;">
		<div class="modal-title"><h3>Month Shipment</h3></div>
		<div class="modal-content">
			<form id="frm-product-months-all" method="POST">
				<!-- BOF GRIDVIEW -->
				<div id="grid-product-months-all" class="grid jq-grid">
					<table id="tbl-product-months-all" cellspacing="0" cellpadding="0">
						<thead>
							<tr> 
	              <td class="border-right text-center" width="100"><a class="sort" column="ctrl_no">Ctrl No.</a></td>
	              <td class="border-right text-center" width="50"><a class="sort" column="type">Type</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="series">Series</a></td>
	              <td class="border-right text-center" width="50"><a class="sort" column="pack_qty">Pack</a></td>
	              <td class="border-right text-center"><a class="sort" column="remarks">Remarks</a></td>
	              <td class="border-right text-center" width="60"><a class="sort" column="unit">Unit</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="ttl">Total</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="ttls">Singles Total</a></td>
							</tr>
						</thead>
						<tbody id="product-months-all"></tbody>
					</table>
				</div>	
			</form>
		</div>
		<div class="modal-footer">
			<a class="btn parent-modal" rel="modal:close">Close</a>
			<div class="clear"></div>
		</div>
	</div>

	<script>
		$(function(){
			// $('.item-month').live('click', function(){
				// var current_month = $(this).attr('rel'); 
				// $('#current_month').val(current_month);
				// $('.search-title').html('Shipment Plan Month &raquo; <span class="red">'+ $(this).attr('title') +'</span>');
				// loadMonth(current_month);
				// $('#plan-weeks').fadeOut('fast', function(){
					// $('#plan-month').fadeIn('fast', function(){})
				// })
			// })
			// $('.item-week').live('click', function(){
				// var current_week = $(this).attr('rel');
				// $('#current_week').val(current_week);
				// $('.search-title').html('Shipment Plan Week &raquo; <span class="red">'+ current_week +'</span>');
				// loadWeek(current_week);
				// $('#plan-month').fadeOut('fast', function(){
					// $('#plan-weeks').fadeIn('fast', function(){})
// 					
				// })
			// })
// 			
			// $('#tbl-products-week').find('tbody tr .click-week').show_week_modal();
			// $('#tbl-products-month').find('tbody tr .click-month').show_month_modal();
		})
		
		$.fn.show_week_modal = function() {
	  	$()
	    this.live('click', function(e) {
	    	e.preventDefault();
	    	$('#modal-product-weeks-all').find('.modal-title').html('<h3>'+ $(this).attr('model') +' &raquo; <span class="red">'+ $(this).attr('ship_date') +'</span></h3>');
	    	
	    	var ship_date = $(this).attr('ship_date');
	    	
	    	var data = { 
		    	"url":"/populate/shipment-plan-week-all.php?sdate="+ship_date+"&pid="+$(this).attr('pid'),
		      "limit":"15",
					"data_key":"shipment_plan_all",
					"row_template":"row_template_ship_plan_month_all",
		      "searchable":false
				}
			
				$('#grid-product-weeks-all').grid(data);
				
				$('#btn-weeks').click();	
	    })
	  }
		
		$.fn.show_month_modal = function() {
	  	$()
	    this.live('click', function(e) {
	    	e.preventDefault();
	    	$('#modal-product-months-all').find('.modal-title').html('<h3>'+ $(this).attr('model') +' &raquo; <span class="red">'+ $(this).attr('ship_date') +'</span></h3>');
	    	
	    	var ship_date = new Date($(this).attr('ship_date'));
				var month = ship_date.getMonth()+1;
	    	
	    	var data = { 
		    	"url":"/populate/shipment-plan-month-all.php?smonth="+month+"&pid="+$(this).attr('pid'),
		      "limit":"15",
					"data_key":"shipment_plan_all",
					"row_template":"row_template_ship_plan_month_all",
		      "searchable":false
				}
			
				$('#grid-product-months-all').grid(data);
				
				$('#btn-months').click();	
	    })
	  }
		
		function loadWeek(param) {
			var data = { 
	    	"url":"/populate/shipment-plan-week.php?sdate="+param,
	      "limit":"15",
				"data_key":"shipment_plans",
				"row_template":"row_template_ship_plan_week",
	      "pagination":"#products-pagination-week",
	      "searchable":true
			}
		
			$('#grid-products-week').grid(data);
		}
		function loadMonth(param) {
			var data = { 
	    	"url":"/populate/shipment-plan-month.php?smonth="+param,
	      "limit":"15",
				"data_key":"shipment_plans",
				"row_template":"row_template_ship_plan_month",
	      "pagination":"#products-pagination-month",
	      "searchable":true
			}
		
			$('#grid-products-month').grid(data);
		}
	</script>
<?php }
require('footer.php'); ?>