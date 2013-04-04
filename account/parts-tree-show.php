<?php
  /* Module: Parts Tree - Show  */
  $capability_key = 'show_parts_tree';
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
				  echo '<a href="'.$Capabilities->All['edit_parts_tree']['url'].'?pid='.$_REQUEST['pid'].'&code='.$_REQUEST['code'].'" class="nav">'.$Capabilities->All['edit_parts_tree']['name'].'</a>'; 
					echo '<a href="'.$Capabilities->All['show_product']['url'].'?pid='.$_REQUEST['pid'].'" class="nav">'.$Capabilities->All['show_product']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" placeholder="Search" value="<?php echo $_GET['pid']; ?>" style="display: none" />
      </div>
      <form id="purchase-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
         
         <!-- BOF GRIDVIEW -->
         <div id="grid-parts-materials" class="grid jq-grid" style="min-height:146px;">
           <table cellspacing="0" cellpadding="0">
             <thead>
               <tr>
                 <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all"/></td>
                 <td width="20" class="border-right text-center">No.</td>
                 <td width="140" class="border-right">Item Code</td>
                 <td width="55" class="border-right text-center">Qty</td>
                 <td width="30" class="border-right text-center">Unit</td>
                 <td width="100" class="border-right text-center">Unit Price</td>
                 <td width="100" class="border-right text-center">Amount</td>
                 <td class="text-center">Remarks</td>
               </tr>
             </thead>
             <tbody id="parts-materials"></tbody>
           </table>
         </div>
         
         <!-- BOF REMARKS -->
         <div>
         	<table width="100%">
               <tr><td height="5" colspan="99"></td></tr>
               <tr>
                  <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="purchase_amount" type="text" value="" class="text-right text-currency" style="width:95px;" disabled/></td>
               </tr>
            </table>
         </div>
         
         <div class="field-command">
           <input type="button" value="Edit" class="btn redirect-to" rel="<?php echo host('parts-tree-edit.php?pid='. $_GET['pid'] .'&code='. $_GET['code']); ?>"/>
           <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('products.php'); ?>"/>
             </div>
      </form>
   </div>
	</div>
      
	 <script>
	$(function() {
		var data = { 
	  	"url":"/populate/parts-tree.php",
	    "limit":"50",
			"data_key":"parts_tree",
			"row_template":"row_template_parts_tree_read_only",
		}
	
		$('#grid-parts-materials').grid(data);
	  })         
	      
	 </script>

<?php }
require('footer.php'); ?>