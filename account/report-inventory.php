<?php
  /* Module: Inventory reports  */
  $capability_key = 'show_inventory_reports';
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
      	
				<h3 class="form-title">Month End Inventory Reports</h3>
      	<ul>
      		<li><a href="#">Raw Materials</a></li>
      		<li><a href="#">Finished Goods</a></li>
      		<li><a href="#">WIP</a></li>
      	</ul>
      	<br/>
				<h3 class="form-title">Export</h3>
      	<ul>
      		<li><a href="#">Shipping Quantity and Price</a></li>
      	</ul>
      </form>
		</div>
	</div>

<?php }
require('footer.php'); ?>