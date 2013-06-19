<?php
  /* Module: Forecast Calendar  */
  $capability_key = 'edit_forecast';
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
				  echo '<a href="'.$Capabilities->All['show_forecast']['url'].'" class="nav">'.$Capabilities->All['show_forecast']['name'].'</a>';
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
      	<a id="btn-calendar" href="#modal-calendar" rel="modal:open"></a>
				<div id="grid-forecast-calendar" class="grid jq-grid" style="min-height:400px;">
	        <table id="tbl-calendar" cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td class="border-right text-center"><a class="sort default active up" column="code">Code</a></td>
	              <td width="55" class="border-right text-center"><a class="sort" column="jan">Jan</a></td>
	              <td width="55" class="border-right text-center"><a class="sort" column="feb">Feb</a></td>
	              <td width="55" class="border-right text-center"><a class="sort" column="mar">Mar</a></td>
	              <td width="55" class="border-right text-center"><a class="sort" column="apr">Apr</a></td>
	              <td width="55" class="border-right text-center"><a class="sort" column="may">May</a></td>
	              <td width="55" class="border-right text-center"><a class="sort" column="jun">Jun</a></td>
	              <td width="55" class="border-right text-center"><a class="sort" column="jul">Jul</a></td>
	              <td width="55" class="border-right text-center"><a class="sort" column="aug">Aug</a></td>
	              <td width="55" class="border-right text-center"><a class="sort" column="sep">Sep</a></td>
	              <td width="55" class="border-right text-center"><a class="sort" column="oct">Oct</a></td>
	              <td width="55" class="border-right text-center"><a class="sort" column="nov">Nov</a></td>
	              <td width="55" class="border-right text-center"><a class="sort" column="dece">Dec</a></td>
	              <td width="80" class="border-right text-center"><a class="sort" column="total_qty">Total</a></td>
	              <td width="90" class="border-right text-center"><a class="sort" column="single_total_qty">Singles Total</a></td>
	            </tr>
	          </thead>
	          <tbody></tbody>
	        </table>
	      </div>
				<!-- BOF Pagination -->
				<div id="calendar-pagination"></div>
      </form>
		</div>
	
	<!-- BOF MODAL -->
	<div id="modal-calendar" class="modal" style="display:none;width:920px;">
		<div class="modal-title"><h3>Year Forecast</h3></div>
		<div class="modal-content">
			<form id="frm-forecast" method="POST">
				<input type="hidden" name="action" value="edit_forecast" />
				<!-- BOF GRIDVIEW -->
				<div id="grid-calendar-months" class="grid jq-grid">
					<table id="tbl-calendar-months" cellspacing="0" cellpadding="0">
						<thead>
							<tr> 
							<td class="border-right text-center" width="50"><a>Month</a></td>
							<td class="border-right text-center" width="50"><a>Ctrl #</a></td> 
							<td class="border-right text-center" width="80"><a>Delivery</a></td> 
							<td class="border-right text-center" width="80"><a>Shipment</a></td>
							<td class="border-right text-center" width="80"><a>Production</a></td>
							<td class="border-right text-center" width="90"><a>Status</a></td>
							<td class="border-right text-center"><a>Remarks</a></td> 
							<td class="border-right text-center" width="70"><a>Qty</a></td> 
<!-- 								<td class="border-right text-center" width="70"><a>Singles Qty</a></td>  -->
							</tr>
						</thead>
						<tbody id="calendar-months"></tbody>
					</table>
				</div>	
			</form>
		</div>
		<div class="modal-footer">
			<a class="btn parent-modal" rel="modal:close">Cancel</a>
			<a id="submit-forecast" href="#frm-forecast" class="btn" rel="modal:close">Save</a>
			<div class="clear"></div>
		</div>
	</div>
	
	
	<script>
	$(function() {
  	var data = { 
    	"url":"/populate/forecast-calendar.php?yr=<?php echo $active_year ?>",
      "limit":"15",
			"data_key":"forecast_calendar",
			"row_template":"row_template_forecast_read_only",
      "pagination":"#calendar-pagination",
      "searchable":true
		}
	
		$('#grid-forecast-calendar').grid(data);
		
		$('#tbl-calendar').find('tbody tr .click-month').show_calendar_modal();
		$('#submit-forecast').save_forecast();
		
  }) 
  
  $.fn.show_calendar_modal = function() {
  	$()
    this.live('click', function(e) {
    	e.preventDefault();
    	
    	$('#modal-calendar').find('.modal-title').html('<h3>Forecast &raquo; <span class="red">'+ $(this).attr('pcode') +'</span></h3>');
    	
    	var data = { 
	    	"url":"/populate/forecasts.php?pid="+$(this).attr('pid')+"&yr=<?php echo $active_year ?>",
	      "limit":"15",
				"data_key":"forecasts",
				"row_template":"row_template_forecast_months",
			}
			$('#grid-calendar-months').grid(data);
			$('#btn-calendar').click();	
    })
  }
  
  $.fn.save_forecast = function() {
    this.click(function(e) {
    	e.preventDefault();

      var form		= $(this).attr('href');
      //console.log($(form).serialize());
      
      $.post("forecasts-edit.php", $(form).serialize(), function(data) {
      	
      }).done(function(data){
      	var data = { 
		    	"url":"/populate/forecast-calendar.php?yr=<?php echo $active_year ?>",
		      "limit":"15",
					"data_key":"forecast_calendar",
					"row_template":"row_template_forecast_read_only",
		      "pagination":"#calendar-pagination",
		      "searchable":true
				}
			
				$('#grid-forecast-calendar').grid(data);
      });
    })
  }
  
  
 </script>
<?php }
require('footer.php'); ?>