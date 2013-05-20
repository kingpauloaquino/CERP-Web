<?php
  /*
   * Module: Invoices 
  */
  $capability_key = 'invoices';
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
          <div class="clear"></div>
        </h2>
      </div>
				
		<div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
      </div>
        
      <!-- BOF GridView -->
      <div id="grid-invoices" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td width="110" class="border-right text-center"><a class="sort default active up" column="invoice">Invoice #</a></td>
              <td class="border-right"><a class="sort down" column="supplier">Supplier</a></td>
              <td width="100" class="border-right text-center"><a class="sort" column="terms">Trade Terms</a></td>
              <td width="90" class="border-right text-center text-date"><a class="sort" column="receive_date">Date</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="invoices-pagination"></div>
		</div>
	</div>
	
	<script>
	$(function() {
  	var data = { 
    	"url":"/populate/invoices.php",
      "limit":"15",
			"data_key":"invoices",
			"row_template":"row_template_invoices",
      "pagination":"#invoices-pagination",
      "searchable":true
		}
	
		$('#grid-invoices').grid(data);
  }) 
  </script>

<?php }
require('footer.php'); ?>