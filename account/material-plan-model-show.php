<?php
  /* Module: Material Plan Model */
  $capability_key = 'show_material_plan_model';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		if(isset($_GET['mid'])) {
	  	$materials = $DB->Find('materials', array(
					  			'columns' 		=> 'materials.id AS mid, materials.base, materials.parent, materials.material_code, materials.description, brand_models.brand_model, materials.bar_code,
																  	item_classifications.classification, users.id AS user_id, CONCAT(users.first_name, " ", users.last_name) AS pic, materials.defect_rate,
																  	lookups3.description AS material_type, lookups4.description AS status, terminals.id AS tid, CONCAT(terminals.terminal_code," - ", terminals.terminal_name) AS terminal', 
					  	    'conditions' 	=> 'materials.id = '.$_GET['mid'], 
					  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON materials.brand_model = brand_models.id 
																		LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id 
																		LEFT OUTER JOIN users ON materials.person_in_charge = users.id
																		LEFT OUTER JOIN lookups AS lookups3 ON materials.material_type = lookups3.id
																		LEFT OUTER JOIN lookups AS lookups4 ON materials.status = lookups4.id
																		LEFT OUTER JOIN terminals ON terminals.id=materials.production_entry_terminal_id' ));
	  }
  	
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
          <form id="purchase-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
             <!-- BOF TEXTFIELDS -->
             <div>
			        <h3 class="form-title">Details</h3>
			        <a class="toggler" href="#" rel="div_details">Hide</a>
			        <div id="div_details">
			        	<table>
				           <tr>
				              <td width="150">Material Code:</td><td width="310"><input type="text" value="<?php echo $materials['material_code'] ?>" class="text-field" disabled/></td>
				              <td width="150">Base Material Code:</td><td><input type="text" value="<?php echo $base_code ?>" class="text-field" disabled/>
				              	<?php echo $linkto = (isset($base_id)) ? link_to('materials-show.php?mid='.$base_id) : '' ?>
				              </td>
				           </tr>
				           <tr>
				              <td>Barcode:</td><td><input type="text" value="<?php echo $materials['bar_code'] ?>" class="text-field" disabled/></td>
				              <td>Model:</td><td><input type="text" value="<?php echo $materials['brand_model'] ?>" class="text-field" disabled/></td>
				           </tr>
				           <tr>
				              <td>Classification:</td><td><input type="text" value="<?php echo $materials['classification'] ?>" class="text-field" disabled/></td>
				              <td>Status:</td><td><input type="text" value="<?php echo $materials['status'] ?>" class="text-field" disabled/></td>
				           </tr>    
				           <tr>
				              <td>Person-in-charge:</td><td><input type="text" value="<?php echo $materials['pic'] ?>" class="text-field" disabled/>
				              	<?php echo $linkto = ($materials['pic']!='') ? link_to('users-show.php?uid='.$materials['user_id']) : '' ?>
				              </td>
				              <td>WIP Line Entry:</td><td><input type="text" value="<?php echo $materials['terminal'] ?>" class="text-field" disabled />
				              	<?php echo $linkto = ($materials['terminal_code']!='') ? link_to('terminals-show.php?tid='.$materials['tid']) : '' ?>
				              </td>
				           </tr>      
				           <tr>
				              <td>Address:</td><td><input type="text" value="<?php echo $address['address'] ?>" class="text-field" disabled/>
				              	<?php echo $linkto = ($address['address']!='') ? link_to('locations-show.php?lid='.$address['add_id']) : '' ?>
				              </td>
				              <td>Defect Rate %:</td><td><input type="text" value="<?php echo ($materials['defect_rate'] * 100) ?>" class="text-field text-right" disabled/></td>
				           </tr>             
				           <tr>
				              <td>Description:</td>
				              <td colspan="99">
				                <input type="text" value="<?php echo $materials['description'] ?>" class="text-field" style="width:645px" disabled/>
				              </td>
				           </tr>  
				          	<?php
				          		if($revisions!=NULL) {
				          			echo '<tr><td>Revisions:</td><td colspan="99">';
				          			foreach ($revisions as $rev) {
													echo '<a href="materials-show.php?mid='.$rev['id'].'">'.$rev['material_code'].'</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
												}
												echo '</td></tr>';
				          		} 
				          	?>
				           <tr><td height="5" colspan="99"></td></tr>
				        </table>	
			        </div>
			        <br/>
			        <h3 class="form-title">Purchase Information</h3>
			        <a class="toggler" href="#" rel="div_purchase">Hide</a>
			        <div id="div_purchase">
				        <table>
				        	<?php
				        		$costs = $DB->Get('materials', array('columns' => 'suppliers.id AS sid, suppliers.name AS supplier, item_costs.id AS cost_id, item_costs.cost, item_costs.moq,
																																			item_costs.transportation_rate, lookups1.description AS unit, lookups2.code AS currency', 
						 																				'joins' => 'INNER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type = "MAT"
																																INNER JOIN suppliers ON suppliers.id = item_costs.supplier
																																INNER JOIN lookups AS lookups1 ON lookups1.id = item_costs.unit
																																INNER JOIN lookups AS lookups2 ON lookups2.id = item_costs.currency',
							 																			'conditions' => 'materials.id = '.$_GET['mid']));
										foreach($costs as $cost) {
										?>
											<tr>
					              <td width="150">Supplier:</td>
					              <td colspan="99">
					              	<input type="text" value="<?php echo $cost['supplier'] ?>" class="text-field" style="width:645px" disabled/>
					              	<?php echo $linkto = ($cost['supplier']!='') ? link_to('suppliers-show.php?sid='.$cost['sid']) : '' ?>
					              </td>
					           </tr>
					           <tr>
					              <td width="150">Currency:</td><td width="310"><input type="text" value="<?php echo $cost['currency'] ?>" class="text-field" disabled/></td>
					              <td width="150">Cost:</td><td><input type="text" value="<?php echo $cost['cost'] ?>" class="text-field  text-right" disabled/></td>
					           </tr>
					           <tr>
					              <td width="150">Unit:</td><td width="310"><input type="text" value="<?php echo $cost['unit'] ?>" class="text-field" disabled/></td>
					              <td>MOQ:</td><td><input type="text" value="<?php echo $cost['moq'] ?>" class="text-field text-right" disabled/></td>
					           </tr>   
					           <tr>
					              <td width="150">Transportation Rate:</td><td width="310"><input type="text" value="<?php echo $cost['transportation_rate'] ?>" class="text-field text-right" disabled/></td>
					              <td></td><td></td>
					           </tr>    
					           <tr><td height="5" colspan="99"></td></tr>
										<?php
										}
				        	?>           
				        </table>	
			        </div>
			        
        
             </div>
             
             <!-- BOF GRIDVIEW -->
             <div id="grid-material-plan" class="grid jq-grid" style="min-height:146px;">
               <table cellspacing="0" cellpadding="0">
                 <thead>
                   <tr>
                     <td width="160" class="border-right text-center">Item Code</td>
                     <td class="border-right text-center">Model</td>
                     <td width="50" class="border-right text-center">DR</td>
                     <td width="100" class="border-right text-center">Prod. Plan</td>
                     <td width="100" class="border-right text-center">Inventory</td>
                     <td width="100" class="border-right text-center">Open P/O</td>
                     <td width="100" class="border-right text-center">Balance</td>
                     <td width="90" class="border-right text-center">MOQ</td>
                     <td width="90" class="border-right text-center">Unit Price</td>
                     <td width="90" class="text-center">P/O Qty</td>
                   </tr>
                 </thead>
                 <tbody id="material-plan"></tbody>
               </table>
             </div>
             
           
             
             <!-- BOF REMARKS -->
             <!-- <div>
             	<table width="100%">
                   <tr><td height="5" colspan="99"></td></tr>
                   <tr>
                      <td></td>
                      <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="purchase_amount" type="text" class="text-right" style="width:95px;" disabled/></td>
                   </tr>
                </table>
             </div> -->
             
             <div class="field-command">
           	   <div class="text-post-status">
           	     <strong>Saved As:</strong>&nbsp;&nbsp;<?php echo $purchase['status']; ?>
               </div>
<!--            	   <input type="button" value="Download" class="btn btn-download" rel="<?php echo excel_file('?category=purchase&id='. $purchase['id']); ?>"/> -->
               <?php if($purchase['status'] != "Publish") { ?>
<!--                <input type="button" value="Edit" class="btn redirect-to" rel="<?php echo host('material-plan-edit.php?sid='. $_GET['sid']); ?>"/> -->
           	   <?php } ?>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('material-plan.php'); ?>"/>
             </div>
          </form>
       </div>
       
       <script>
				$(".toggler").click(function () {
					$("#"+$(this).attr("rel")).toggle("fast");
					$(this).text(($(this).text() == "Show") ? "Hide" : "Show");
				});	
       
       	$(function() {
       		
       		
			  	var data = { 
			    	"url":"/populate/material-plan.php?sid=<?php echo $_GET['sid']; ?>",
			      "limit":"50",
						"data_key":"material_plan",
						"row_template":"row_template_material_plan"
					}
				
					$('#grid-material-plan').grid(data);
					//$('#purchase_amount').currency_format(<?php echo $purchase['total_amount']; ?>);
			  }) 
      </script>

<?php }
require('footer.php'); ?>