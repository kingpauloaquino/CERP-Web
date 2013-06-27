<?php
  /* Module: Name  */
  $capability_key = 'manage_purchasing';
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
				<h3 class="form-title">Reports</h3>
        <table> 
           <tr>
              <td width="300">Receiving Report By Date:</td><td><input id="by-date" type="button" value="VIEW" class="btn strd redirect-to" rel="<?php echo host('report-received-date.php'); ?>" /></td>
           </tr>  
           <tr>
              <td>Receiving Report By Supplier:</td><td><input id="by-supplier" type="button" value="VIEW" class="btn strd redirect-to" rel="<?php echo host('report-received-supplier.php'); ?>" /></td>
           </tr>  
           <tr>
              <td>Receiving Report By All Suppliers:</td><td><input id="by-all-supplier" type="button" value="VIEW" class="btn strd redirect-to" rel="<?php echo host('report-received-supplier-all.php'); ?>" /></td>
           </tr>  
           <tr><td height="5" colspan="99"></td></tr>
        </table>
      </form>
		</div>
	</div>
<?php }
require('footer.php'); ?>