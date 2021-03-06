<?php
  /*
   * Module: Materials Inventory Report
  */
  $capability_key = 'show_materials_inventory_report';  
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{

?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['manage_warehouse']['url'].'" class="nav">Manage</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form>
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
	      <div id="grid-materials" class="grid jq-grid" style="min-height:400px;">
	        <table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td class="border-right text-center" width="160"><a class="sort default active up" column="code">Code</a></td>
	              <td class="border-right text-center" width="140"><a class="sort" column="classification">Classification</a></td>
	              <td class="border-right text-center"><a class="sort" column="description">Description</a></td>
	              <td class="border-right text-center" width="60"><a class="sort" column="uom">UOM</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="qty">System Qty</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="physical_qty">Physical Qty</a></td>
	            </tr>
	          </thead>
	          <tbody></tbody>
	        </table>
	      </div>
	      
	      <!-- BOF Pagination -->
	      <div id="materials-pagination"></div>
			</form>
		</div>
	</div>
<script>
	$(function() {
		
		var cur_date = $.datepicker.formatDate('yy-mm-dd', new Date($('#month').val()));
		$('#btn-export').attr('rel', '<?php echo export_file("?type=xls&cat=minventory_report&mydate="); ?>' + cur_date);
		loadData();
		
		$('#btn-view').click(function(){
			cur_date = $.datepicker.formatDate('yy-mm-dd', new Date($('#month').val()));
			$('#btn-export').attr('rel', '<?php echo export_file("?type=xls&cat=minventory_report&mydate="); ?>' + cur_date);
			loadData();
		})
		
		$('#btn-export').click(function(){
			$('#btn-export').attr('rel', '<?php echo export_file("?type=xls&cat=minventory_report&mydate="); ?>' + $.datepicker.formatDate('yy-mm-dd', new Date($('#month').val())));
		})
		
		function loadData() {
	  	var data = { 
	    	"url":"/populate/minventory-report.php?mydate="+cur_date,
	      "limit":"15",
				"data_key":"material_inventory_report",
				"row_template":"row_template_materials_inventory_report",
	      "pagination":"#materials-pagination",
	      "searchable":true
			}
		
			$('#grid-materials').grid(data);
		}
  }) 
 </script>

<?php }
require('footer.php'); ?>