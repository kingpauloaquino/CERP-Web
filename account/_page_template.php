<?php
  /* Module: Name  */
  $capability_key = 'key';
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
      
      <a id="btn-id" href="#modal-id" rel="modal:open"></a>	
      </form>
		</div>
	</div>

	<div id="modal-id" class="modal" style="display:none;width:920px;">
		<div class="modal-title"><h3>Title</h3></div>
		<div class="modal-content">
			<div id="grid-id" class="grid jq-grid">
				<table cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td width="20" class="border-right text-center"><input type="checkbox" class="chk-all"/></td>
							<td width="40" class="border-right text-center"><a>col1</a></td>
							<td class="border-right text-center"><a>col2</a></td>
							<td width="40" class="border-right text-center"><a>col3</a></td>
							<td width="40" class="border-right text-center"><a>col4</a></td>
							<td width="40" class="border-right text-center"><a>col5</a></td>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>	
			<div id="tbody-id"></div>
		</div>     
		<div class="modal-footer">
			<a class="btn modal-close" rel="modal:close">Close</a>
			<div class="clear"></div>
		</div>
	</div>
<?php }
require('footer.php'); ?>