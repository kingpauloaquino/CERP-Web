<?php
  /* Module: Purchase reports  */
  $capability_key = 'show_purchase_reports';
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
      	
				<h3 class="form-title">Received</h3>
      	<ul>
      		<li><a href="#">by Supplier</a></li>
      		<li><a href="#">by Date</a></li>
      	</ul>
      </form>
		</div>
	</div>

<?php }
require('footer.php'); ?>