<?php
  /* Module: Name  */
  $capability_key = 'show_production';
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
				  //echo '<a href="'.$Capabilities->All['actual_production_calendar']['url'].'" class="nav">Actual Production</a>';
				?>      		
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
      	<input type="hidden" id="current_week" value=""/>
      	<input type="hidden" id="current_pid" value=""/>
      	<input type="hidden" id="current_model" value=""/>
      	<a id="toggle-calendar" href="#">toggle calendar</a><br/>
      	<div id="div-calendar" style="min-width: 1140px;">
      		
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
							$current_week = 'no-highlight';
							if(($wk_ctr2+1) == date('W')) {
								$current_week = 'highlight-yellow';
							}
							echo '<td class="text-center border-right '.$current_week.'" style="padding:1px; " value="'.getDay('Y-m-d',$wk_ctr2).'"><a href="#" style="font-size:11px;display:block; padding-top:3px; height:26px; width:100%;" class="item-week" rel="'.getDay('Y-m-d',$wk_ctr2).'">'.getDay('n/j',$wk_ctr2).'</a></td>'; 
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
      	</div>
      
      <br/>
      
      <div id="div-details" class="form-container" style="display: none">
		      <!-- BOF Search -->
		      <div class="search-title">
		      	
		      </div>
		      <div class="search">
		        &nbsp;
		      </div>
	      <div id="prod-weeks" style="display: none;">  
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
      
				<div id="model-all" style="display: none;">
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
      	</div>
      	
      	<div id="model-materials" style="display: none;">
		      <table>
             <tr>
                <td width="170">Daily Production Capacity:</td><td width="120"><input type="text" class="text-field-number text-right numbers prd-prod-cp" disabled/></td>
                <td width="130">Production Plan Qty:</td><td width="120"><input type="text" class="text-field-number text-right numbers prd-plan-qty" disabled/></td>
                <td width="170">Estimated Production Days:</td><td width="120"><input type="text" class="text-field-number text-right numbers prd-est-days" disabled/></td>
                <td><button id="btn-request" class="btn">SEND REQUEST</button></td>
             </tr>
             <tr><td height="5" colspan="99"></td></tr>
          </table>
      		<!-- BOF GRIDVIEW -->
					<div id="grid-product-materials" class="grid jq-grid">
						<table id="tbl-product-materials" cellspacing="0" cellpadding="0">
							<thead>
								<tr> 
		              <td class="border-right text-center" width="30"><a></a></td>
		              <td class="border-right text-center"><a class="sort" column="code">Material</a></td>
		              <td class="border-right text-center" width="100"><a class="sort" column="type">Type</a></td>
		              <td class="border-right text-center" width="70"><a class="sort" column="unit">Unit</a></td>
		              <td class="border-right text-center" width="70"><a class="sort" column="qty">Qty</a></td>
		              <td class="border-right text-center" width="100"><a class="sort" column="total">Total</a></td>
		              <td class="border-right text-center" width="100"><a class="sort" column="wh_stock">W/H Stock</a></td>
		              <td class="border-right text-center" width="100"><a class="sort" column="total">WIP Stock</a></td>
								</tr>
							</thead>
							<tbody id="product-materials"></tbody>
						</table>
					</div>	
      	</div>
      	
      <form id="form-request" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" >
      	<input type="hidden" name="action" value="add_material_request"/>
      	<input type="hidden" name="request_type" value="171"/>
      	<input type="hidden" name="batch_no" value=""/>
      	<input type="hidden" name="status" value="11"/>
      	<div id="model-materials-request" style="display: none;">
		      <table>
             <tr>
                <td width="170">Daily Production Capacity:</td><td width="130"><input id="prod-cp" type="text" class="text-field-medium text-right numbers prd-prod-cp" disabled/></td>
                <td width="130">Production Plan Qty:</td><td width="130"><input type="text" class="text-field-medium text-right numbers prd-plan-qty" disabled/></td>
                <td width="170">Estimated Production Days:</td><td width="130"><input type="text" class="text-field-medium text-right numbers prd-est-days" disabled/></td>
                
             </tr>
             <tr>
                <td>Set total by days:</td><td><input id="prod-days" type="text" class="text-field-medium text-right numeric" /></td>
                <td>Set total by qty:</td><td><input id="prod-qty" type="text" class="text-field-medium text-right numeric" /></td>
                <td>Expected Date:</td><td><input id="expected-date" name="expected_date" type="text" class="text-field date-pick-week" required/></td>
             </tr>
             <tr><td height="5" colspan="99"></td></tr>
          </table>
      		<!-- BOF GRIDVIEW -->
					<div id="grid-product-materials-request" class="grid jq-grid">
						<table id="tbl-product-materials" cellspacing="0" cellpadding="0">
							<thead>
								<tr> 
		              <td class="border-right text-center" width="30"><a></a></td>
		              <td class="border-right text-center"><a class="sort" column="code">Material</a></td>
		              <td class="border-right text-center" width="100"><a class="sort" column="type">Type</a></td>
		              <td class="border-right text-center" width="70"><a class="sort" column="unit">Unit</a></td>
		              <td class="border-right text-center" width="70"><a class="sort" column="qty">Qty</a></td>
		              <td class="border-right text-center" width="70"><a class="sort" column="total">Total</a></td>
								</tr>
							</thead>
							<tbody id="product-materials-request"></tbody>
						</table>
					</div>	
	      	<div class="field-command">
						<div class="text-post-status"></div>
						<input id="submit-btn" href="#form-request" type="submit" value="REQUEST" class="btn" />
					</div>
      	</div>
      	
      	</div>
      </form>
		</div>
	</div>
	

	

	<script>
		$(function(){
			$('#toggle-calendar').click(function(){
				$('#div-calendar').toggle('slow');
			})
			
			$('.item-week').live('click', function(event){
				event.preventDefault();
				
				// highlight selected date
				// $(this).closest('td').attr('class', 'text-center border-right highlight-blue');
				$('#div-details').show();
				
				var current_week = $(this).attr('rel');
				$('#current_week').val(current_week);
				loadWeek(current_week);
				
				$('#model-materials-request').fadeOut('fast', function(){
					$('.search-title').html('Production Week &raquo; <span class="red">'+ current_week +'</span>');
					$('#model-all').fadeOut('fast', function(){
						$('#model-materials').fadeOut('fast', function(){
							$('#prod-weeks').fadeIn('fast', function(){})
						})
					})
				})
			})
			
			$('#tbl-products-week').find('tbody tr .click-week').show_all_models();
			$('#tbl-product-weeks-all').find('tbody tr .click-model').show_product_materials();
			$('#btn-request').show_material_requests();
			$('#prod-days').set_prod_days();
			$('#prod-qty').set_prod_qty();
			$('#submit-btn').submit_request();
		})
		
		$.fn.show_all_models = function() {
	  	$()
	    this.live('click', function(event) {
	    	event.preventDefault(); 
	    	
	    	var prod_date = $(this).attr('prod_date');
	    	var model = $(this).attr('model');
	    	$('#current_model').val(model);
	    	$('#current_pid').val($(this).attr('pid'));
	    	
	    	var data = { 
		    	"url":"/populate/production-plan-week-all.php?pdate="+prod_date+"&pid="+$(this).attr('pid'),
		      "limit":"15",
					"data_key":"production_plan_all",
					"row_template":"row_template_prod_plan_month_all",
		      "searchable":false
				}
			
				$('#grid-product-weeks-all').grid(data);
				
				
				$('#prod-weeks').fadeOut('fast', function(){
					$('.search-title').html('Production Week &raquo; '+ $('#current_week').val() +' &raquo; <span class="red">'+model+'</span>');
					$('#model-materials').fadeOut('fast', function(){
						$('#model-all').fadeIn('fast', function(){})	
					})
				})
	    })
	  }
	  
	  $.fn.show_product_materials = function() {
	  	$()
	    this.live('click', function(event) {
	    	event.preventDefault(); 
	    	var model = $(this).attr('model');
	    	
	    	var qty_buffer = 0.05;
	    	var prod_qty = parseFloat($(this).attr('prod_qty'));
	    	var prod_cp = parseFloat($(this).attr('prod_cp')); 
	    	var est_days = Math.round(parseFloat(prod_qty / prod_cp));
	    	$('.prd-prod-cp').val(prod_cp).digits();
	    	$('.prd-plan-qty').val(prod_qty).digits();
	    	$('.prd-est-days').val(est_days).digits();
	    	$('#current_model').val(model);
	    	$('#current_pid').val($(this).attr('pid'));
	    	
	    	prod_qty = (prod_qty * qty_buffer) + prod_qty;
	    	var data = { 
		    	"url":"/populate/product_parts.php?pid="+$(this).attr('pid')+"&qty="+prod_qty,
		      "limit":"15",
					"data_key":"product_parts",
					"row_template":"row_template_product_parts",
		      "searchable":false
				}
			
				$('#grid-product-materials').grid(data);
				
				$('#model-all').fadeOut('fast', function(){
					$('.search-title').html('Production Week &raquo; '+ $('#current_week').val() +' &raquo; '+model+' &raquo; <span class="red">Parts</span>');
					$('#prod-weeks').fadeOut('fast', function(){
						$('#model-materials').fadeIn('fast', function(){})	
					})
				})
	    })
	  }
	  
	  $.fn.show_material_requests = function() {
	  	$()
	    this.live('click', function(event) {
	    	event.preventDefault(); 
	    	
	    	var qty_buffer = 0.05;
	    	var prod_qty = parseFloat($('.prd-plan-qty').val().replace(/,/g, ''), 10);
	    	prod_qty = (prod_qty * qty_buffer) + prod_qty;
	    	
	    	var data = { 
		    	"url":"/populate/product_parts.php?pid="+$('#current_pid').val()+"&qty="+prod_qty, 
		      "limit":"15",
					"data_key":"product_parts",
					"row_template":"row_template_product_parts_request",
		      "searchable":false
				}
			
				$('#grid-product-materials-request').grid(data);
				
				$('#model-materials').fadeOut('fast', function(){
					$('.search-title').html('Production Week &raquo; '+ $('#current_week').val() +' &raquo; '+$('#current_model').val()+' &raquo; <span class="red">Parts Request</span>');
					$('#model-materials-request').fadeIn('fast', function(){})	
				})
	    })
	  }
	  
	  $.fn.set_prod_days = function() {
	  	$()
	    this.keyup(function(){
				$('#prod-qty').val('');
	    	var tb = $(this);
	    	var prod_days = parseInt($(tb).val()); 
	    	$('#product-materials-request').find('.request-quantity').each(function(){
	    		
	    		var qty_buffer = 0.05;
	    		
	    		if(prod_days > 0 && !isNaN(prod_days)) {
	    			var total = prod_days * parseFloat($(this).val()) * parseInt($('#prod-cp').val().replace(/,/g, ''), 10);
	    	  	$(this).closest('tr').find($('.request-total')).val((total*qty_buffer)+total);
	    		} else {
	    			prod_days = 0;
	    			var total = prod_days * parseFloat($(this).val()) * parseInt($('#prod-cp').val().replace(/,/g, ''), 10);
	    	  	$(this).closest('tr').find($('.request-total')).val((total*qty_buffer)+total);
	    		}
	    	})
	    })
	  }
	  
	  $.fn.set_prod_qty = function() {
	  	$()
	    this.keyup(function(){
	    	$('#prod-days').val('');
	    	var tb = $(this);
	    	var prod_qty = parseInt($(tb).val()); 
	    	var qty_buffer = 0.05;

	    	$('#product-materials-request').find('.request-quantity').each(function(){
	    		if(prod_qty <= parseInt($('#prod-cp').val().replace(/,/g, ''), 10)) {
		    		if(prod_qty > 0 && !isNaN(prod_qty)) {
		    			var total = prod_qty * parseFloat($(this).val());
		    	  	$(this).closest('tr').find($('.request-total')).val((total*qty_buffer)+total);
		    		}	
	    		}
	    	})
	    })
	  }
	  
	  $.fn.submit_request = function() {
	  	$()
	    this.live('click', function(event) {
	    	event.preventDefault(); 
	    	
	    	var form = $(this).attr('href');
	    	
	    	if($('#expected-date').val() != '') {
		    	$.post(document.URL, $(form).serialize(), function(data) {
		      }).done(function(data){
		      	
						loadWeek($('#current_week').val());
						$('#model-materials-request').fadeOut('fast', function(){
							$('.search-title').html('Production Week &raquo; <span class="red">'+ $('#current_week').val() +'</span>');
							$('#model-all').fadeOut('fast', function(){
								$('#model-materials').fadeOut('fast', function(){
									$('#prod-weeks').fadeIn('fast', function(){})
								})
							})
						})
		      });		
	    	}
	    })
	  }
	  
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
	</script>
<?php }
require('footer.php'); ?>