<?php
  /*
   * Module: Suppliers 
  */
  $capability_key = 'suppliers';  
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
				  echo '<a href="'.$Capabilities->All['add_supplier']['url'].'" class="nav">'.$Capabilities->All['add_supplier']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" placeholder="Search" />
      </div>
        
      <!-- BOF GridView -->
      <div id="grid-suppliers" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="110"><a class="sort default active up" column="code">Code</a></td>
              <td class="border-right text-center" width="410"><a class="sort" column="name">Name</a></td>
              <td class="border-right text-center"><a class="sort" column="type">Type</a></td>
              <td class="border-right text-center"><a class="sort" column="prodserv">Product/Service</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="suppliers-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/suppliers.php",
      "limit":"15",
			"data_key":"suppliers",
			"row_template":"row_template_suppliers",
      "pagination":"#suppliers-pagination",
      "searchable":true
		}
	
		$('#grid-suppliers').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>