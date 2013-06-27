<?php
  /* Module: Name  */
  $capability_key = 'show_receiving_date_report';
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
				  	echo '<a href="'.$Capabilities->All['show_receiving_supplier_report']['url'].'" class="nav">By Supplier</a>';
				  	echo '<a href="'.$Capabilities->All['show_receiving_all_supplier_report']['url'].'" class="nav">All Suppliers</a>';
					?>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
      <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST">
				<input type="hidden" id="mydate" value="<?php echo date('Y-m-d')?>" />
				
	      <div class="search-title">
	      	Current Month: <input type="text" id="month" class="text-field-auto month_year_pick" value="<?php echo date('F Y') ?>" readonly/>
	      	
	      	<input type="button" id="btn-view" value="VIEW" class="btn" />
	      	<input type="button" id="btn-export" value="TO EXCEL" class="btn btn-download"/>
	      </div>
      	
				<!-- BOF Search -->
	      <div class="search">
	        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
	      </div>
	        
	      <!-- BOF GridView -->
	      <div id="grid-receive" class="grid jq-grid" style="min-height:400px;">
	        <table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td class="border-right text-center" width="30"><a></a></td>
	              <td class="border-right text-center" width="100"><a class="sort default active up" column="receive_date">Date</a></td>
	              <td class="border-right"><a class="sort" column="supplier_name">Supplier</a></td>
	              <td class="border-right text-center" width="130"><a class="sort" column="invoice">Invoice No.</a></td>
	              <td class="border-right text-center" width="130"><a class="sort" column="receipt">Receipt No.</a></td>
	            </tr>
	          </thead>
	          <tbody></tbody>
	        </table>
	      </div>
	      
	      <!-- BOF Pagination -->
	      <div id="receive-pagination"></div>
      </form>
		</div>
	</div>
<script>
	$(function() {
		
		var cur_date = $.datepicker.formatDate('yy-mm-dd', new Date($('#month').val()));
		$('#btn-export').attr('rel', '<?php echo export_file("?type=xls&cat=receive_date_report&mydate="); ?>' + cur_date);
		loadData();
		
		$('#btn-view').click(function(){
			cur_date = $.datepicker.formatDate('yy-mm-dd', new Date($('#month').val()));
			$('#btn-export').attr('rel', '<?php echo export_file("?type=xls&cat=receive_date_report&mydate="); ?>' + cur_date);
			loadData();
		})
		
		$('#btn-export').click(function(){
			$('#btn-export').attr('rel', '<?php echo export_file("?type=xls&cat=receive_date_report&mydate="); ?>' + $.datepicker.formatDate('yy-mm-dd', new Date($('#month').val())));
		})

		function loadData() {
	  	var data = { 
	    	"url":"/populate/receive-date-report.php?mydate="+cur_date,
	      "limit":"15",
				"data_key":"receive_date_report",
				"row_template":"row_template_receive_date_report",
	      "pagination":"#receive-pagination",
	      "searchable":true
			}
		
			$('#grid-receive').grid(data);
		}
  }) 
 </script>

<?php }
require('footer.php'); ?>