<?php
require '../include/general.class.php';
$DB = new MySQL; 
if($_POST)
{
	$search			= $_POST['searchword'];
	$resultcount= $_POST['resultcount'];
	$table			= $_POST['table'];
	$columns		= $_POST['columns'];
	$joins			= $_POST['joins'];
	$conditions	= $_POST['conditions'];
	
	$query_result = $DB->Get($table, array(
	        				'columns' 		=> $columns,
	        				'joins'				=> $joins,
	        				'conditions' 	=> $conditions,
	        				'startpoint' 	=> '0',
	        				'limit' 			=> $resultcount
  							 ));						 
	foreach($query_result as $res) {	
		$result=$res['item_code'];  
		$fresult = str_ireplace($search, '<b>'.$search.'</b>', $result); 
	
		echo '<div class="display_box" align="left">';
		echo '<p id="'.$fresult.'" class="mcode">'.$fresult;
		if($_POST['searchtype']=='revision'){
			echo '<input type="hidden" class="mat_id" value="'.$res['id'].'">';
			echo '<input type="hidden" class="mat_type_id" value="'.$res['material_type'].'">';   
			echo '<input type="hidden" class="mat_class_id" value="'.$res['material_classification'].'">';
			echo '<input type="hidden" class="model_id" value="'.$res['brand_model_id'].'">';
			echo '<input type="hidden" class="mat_type" value="'.$res['mat_typ'].'">';
			echo '<input type="hidden" class="mat_class" value="'.$res['mat_class'].'">';
			echo '<input type="hidden" class="model" value="'.$res['brand_model'].'">';
			echo '<input type="hidden" class="parent" value="'.$res['parent'].'">';
		}
		if($_POST['searchtype']=='location'){
			echo '<input type="hidden" class="mat_id" value="'.$res['mat_id'].'">';   
		}
		if($_POST['searchtype']=='producttree'){
			echo '<input type="hidden" class="mat_id" value="'.$res['mat_id'].'">';
			echo '<input type="hidden" class="desc" value="'.$res['description'].'">';
			echo '<input type="hidden" class="unit" value="'.$res['unit'].'">';
			echo '<input type="hidden" class="cost" value="'.$res['cost'].'">';	
			echo '<input type="hidden" class="supplier" value="'.$res['supplier'].'">';	
		}		
		if($_POST['searchtype']=='minventory'){
			echo '<input type="hidden" class="mat_id" value="'.$res['id'].'">';
			echo '<input type="hidden" class="mat_type" value="'.$res['mat_typ'].'">';
			echo '<input type="hidden" class="mat_class" value="'.$res['mat_class'].'">';
			echo '<input type="hidden" class="model" value="'.$res['brand_model'].'">';
			echo '<input type="hidden" class="pic" value="'.$res['pic'].'">';
			echo '<input type="hidden" class="status" value="'.$res['status'].'">';
			echo '<input type="hidden" class="desc" value="'.$res['description'].'">';
		}
		if($_POST['searchtype']=='pinventory'){
			echo '<input type="hidden" class="pro_id" value="'.$res['id'].'">';
			echo '<input type="hidden" class="brand" value="'.$res['brand_model'].'">';
			echo '<input type="hidden" class="desc" value="'.$res['description'].'">';
		}
		if($_POST['searchtype']=='order'){
			echo '<input type="hidden" class="item_id" value="'.$res['id'].'">';
			echo '<input type="hidden" class="item_type" value="'.$res['item_type'].'">';
			echo '<input type="hidden" class="class" value="'.$res['class'].'">';
			echo '<input type="hidden" class="unit" value="'.$res['unit'].'">';
			echo '<input type="hidden" class="currency" value="'.$res['currency'].'">';
			echo '<input type="hidden" class="cost" value="'.$res['cost'].'">';
		}
		if($_POST['searchtype']=='mat_req_items'){
			echo '<input type="hidden" class="item_id" value="'.$res['id'].'">';
			echo '<input type="hidden" class="unit" value="'.$res['unit'].'">';
		}
		if($_POST['searchtype']=='mat_req_po'){
			echo '<input type="hidden" id="ppoid" value="'.$res['ppoid'].'">';
		}
		if($_POST['searchtype']=='mat_req_lot'){
			echo '<input type="hidden" id="lot_no" value="'.$res['lot_no'].'">';
		}
		if($_POST['searchtype']=='mat_req_prd'){
			echo '<input type="hidden" id="product_id" value="'.$res['pid'].'">';
		}
		echo '</p></div>';
	}
	
	echo '<script type="text/javascript">';
	echo '$(document).ready(function(){';	
	echo '$(".mcode").click(function() {';
	if($_POST['searchtype']=='revision'){
		echo '$(\'[name*="material[base_code]"]\').val($(this).text());';
		echo '$(\'[name*="material[item_id]"]\').val($(this).find(".mat_id").val());';
		echo '$(\'[name*="material[material_type]"]\').val($(this).find(".mat_type_id").val());';
		echo '$(\'#material_type\').val($(this).find(".mat_type").val());';
		echo '$(\'[name*="material[material_classification]"]\').val($(this).find(".mat_class_id").val());';
		echo '$(\'#material_classification\').val($(this).find(".mat_class").val());';
		echo '$(\'[name*="material[brand_model]"]\').val($(this).find(".model_id").val());';
		echo '$(\'#brand_model\').val($(this).find(".model").val());';
		echo '$(\'[name*="material[parent]"]\').val($(this).find(".parent").val());';
	}
	if($_POST['searchtype']=='location'){
		echo '$(\'#item_code\').val($(this).text());';
		echo '$(\'#mat_id\').val($(this).find(".mat_id").val());';
	}
	if($_POST['searchtype']=='producttree'){
		echo '$(\'#material_id\').val($(this).find(".mat_id").val());';
		echo '$(\'#material_code\').val($(this).text());';
		echo '$(\'#description\').val($(this).find(".desc").val());';
		echo '$(\'#unit\').val($(this).find(".unit").val());';
		echo '$(\'#cost\').val($(this).find(".cost").val());';
		echo '$(\'#supplier\').val($(this).find(".supplier").val());';
	}	
	if($_POST['searchtype']=='minventory'){
		echo '$(\'[name*="inventory[item_id]"]\').val($(this).find(".mat_id").val());';
		echo '$(\'[name*="material[material_code]"]\').val($(this).text());';
		echo '$(\'[name*="material[material_type]"]\').val($(this).find(".mat_type").val());';
		echo '$(\'[name*="material[classification]"]\').val($(this).find(".mat_class").val());';
		echo '$(\'[name*="material[brand_model]"]\').val($(this).find(".model").val());';
		echo '$(\'[name*="material[pic]"]\').val($(this).find(".pic").val());';
		echo '$(\'[name*="material[status]"]\').val($(this).find(".status").val());';
		echo '$(\'[name*="material[m_description]"]\').val($(this).find(".desc").val());';
	}
	if($_POST['searchtype']=='pinventory'){
		echo '$(\'#item_id\').val($(this).find(".pro_id").val());';
		echo '$(\'[name*="product[product_code]"]\').val($(this).text());';
		echo '$(\'[name*="product[brand]"]\').val($(this).find(".brand").val());';
		echo '$(\'[name*="product[p_description]"]\').val($(this).find(".desc").val());';
	}
	if($_POST['searchtype']=='order'){
		echo '$(\'#item_code\').val($(this).text());';
		echo '$(\'#item_name\').val($(this).text());';
		echo '$(\'#item_unit\').val($(this).find(".unit").val());';
		echo '$(\'#item_unit_price\').val($(this).find(".cost").val());';
		echo '$(\'#item_id\').val($(this).find(".item_id").val());';
		echo '$(\'#item_type\').val($(this).find(".item_type").val());';
	}
	if($_POST['searchtype']=='mat_req_items'){
		echo '$(\'#item_code\').val($(this).text());';
		echo '$(\'#item_unit\').val($(this).find(".unit").val());';
		echo '$(\'#item_id\').val($(this).find(".item_id").val());';
	}
	if($_POST['searchtype']=='mat_req_po'){
		echo '$(\'#po_number\').val($(this).text());';
		echo '$(\'#production_purchase_order_id\').val($(this).find("#ppoid").val());';
	}
	if($_POST['searchtype']=='mat_req_lot'){
		echo '$(\'#lot_no\').val($(this).text());';
	}
	if($_POST['searchtype']=='mat_req_prd'){
		echo '$(\'#product_name\').val($(this).text());';
		echo '$(\'#product_id\').val($(this).find("#product_id").val());';
	}
	echo '$(\'.live_search_display\').hide();';
	echo '$(\'#live_search_display_modal\').hide();';
	echo '});});</script>';
} 
?> 