<?php
  /*
   * Module: Terminal Items - Show 
  */
  $capability_key = 'terminal_wh_items';
  require('header.php');
  
  if(isset($_REQUEST['search'])){
		$cur_search = trim($_REQUEST['search']);
		$cur_filter = $_REQUEST['filter'];
	}
	
	if(isset($_REQUEST['tid'])) {
  	$terminal = $DB->Find('terminals', array(
  		'columns' => 'terminals.*, locations.location_code AS bldg', 
  		'joins' => 'INNER JOIN locations ON terminals.location_id=locations.id',
  	  'conditions' => 'terminals.id = '.$_REQUEST['tid']
  	  ));
	}
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
	        <?php
				  	echo '<a href="'.$Capabilities->All['terminals']['url'].'" class="nav">'.$Capabilities->All['terminals']['name'].'</a>'; 
					  echo '<a href="'.$Capabilities->All['show_terminal']['url'].'?tid='.$_REQUEST['tid'].'" class="nav">'.$Capabilities->All['show_terminal']['name'].'</a>'; 
					?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form method="post" action="<?php echo current_page_name() ?>" class="form-container">
				<h3 class="form-title"><?php echo $terminal['terminal_name'] .' : '. $terminal['terminal_code'] .' (' . $terminal['type']. ')' ?></h3>
				<input type="hidden" name="tid" value="<?php echo $_REQUEST['tid'] ?>" />
				<input type="hidden" name="typ" value="<?php echo $_REQUEST['typ'] ?>" />
				<?php
					$tid = (!isset($_REQUEST['tid'])) ? $_POST['tid'] : $_REQUEST['tid'];
					$typ = (!isset($_REQUEST['typ'])) ? $_POST['typ'] : $_REQUEST['typ'];
					// change columns
				  $columns = array('item_code' => 'Item Code', 'description' => 'Description', 'qty' => 'Total Quantity'); 	
				  $column_widths = array('item_code' => '25', 'description' => '40', 'qty' => '20'); 	// total: 85%					  
				  $column_class = array('item_code' => 'border-right text-center', 'description' => 'border-right ', 'qty' => 'border-right text-right'); 

					// no changes required											
					$page = current_page_name();
					$_REQUEST['page']   == NULL ? $cur_page   = 1 : $cur_page     = $_REQUEST['page'];
					$_REQUEST['col']    == NULL ? $cur_col    = array_shift(array_keys($columns)) : $cur_col = $_REQUEST['col']; // if null, gets first column name
					$_REQUEST['sort']   == NULL ? $cur_sort   = 'ASC' : $cur_sort = $_REQUEST['sort'];
					$_REQUEST['search'] == NULL ? $cur_search = '' : $cur_search  = trim($_REQUEST['search']);
					$_REQUEST['filter'] == NULL ? $cur_filter = '' : $cur_filter  = $_REQUEST['filter'];
					
					// change table_name, query_column, query_join, quuery_condition	
					if($typ=='MAT') {
						$table_name	     = 'warehouse_inventories';
						$query_column    = 'warehouse_inventories.item_id AS item_id, materials.material_code AS item_code, SUM(warehouse_inventories.input) AS qty,
																materials.description AS description';
						$query_join      = 'INNER JOIN materials ON materials.id = warehouse_inventories.item_id 
																AND warehouse_inventories.item_type="MAT" AND warehouse_inventories.terminal_id='.$tid;
						$query_condition = 'materials.material_code LIKE "'.$cur_search.'%" OR
																materials.description LIKE "'.$cur_search.'%"
																GROUP BY warehouse_inventories.item_id';	
						$url = 'minventory-show.php';
						} else {						
						$table_name	     = 'warehouse_inventories';
						$query_column    = 'warehouse_inventories.item_id AS item_id, products.product_code AS item_code, SUM(warehouse_inventories.input) AS qty,
																products.description AS description';
						$query_join      = 'INNER JOIN products ON products.id = warehouse_inventories.item_id 
																AND warehouse_inventories.item_type="PRD" AND warehouse_inventories.terminal_id='.$tid;
						$query_condition = 'products.product_code LIKE "'.$cur_search.'%" OR
																products.description LIKE "'.$cur_search.'%"
																GROUP BY warehouse_inventories.item_id';	
						$url = 'pinventory-show.php';
					}

					// change $linked_columns, $linked_columns = NULL; if no linked columns
					$linked_columns = NULL;
																								 
					// change $link_details, $link_details = NULL; if no VIEW DETAILS	
					$link_details = array('link_page' => $url, 
															  'link_params' => array('id' => 'item_id'));

					// no changes required
					$table_args = array('page' => $page, 'columns' => $columns, 'column_widths' => $column_widths, 'column_class' => $column_class,'current_page' => $cur_page, 'current_column' => $cur_col, 'current_sort' => $cur_sort, 
												'current_search' => $cur_search, 'current_filter' => $cur_filter, 'table_name' => $table_name, 
												'query_column' => $query_column,  'query_condition' => $query_condition, 'query_join' => $query_join,
												'linked_columns' => $linked_columns, 'link_details' => $link_details, 'extra_param' => 'tid='.$tid);
								?>				
				
								<div class="search"><?php echo create_search_box($columns, $cur_search, $cur_filter); ?></div>
                
				<?php echo create_table($table_args);  ?>  
			<br/> 		
			</form> 
		</div>		
	</div>

<?php require('footer.php'); ?>