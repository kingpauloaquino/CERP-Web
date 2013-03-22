<?php
  /*
   * Module: Product Inventory History
  */
  $capability_key = 'product_inventory_history';
  require('header.php');
	
	$query_string = extract_querystring($_SERVER["QUERY_STRING"], 'iid,pid');	
	$iid = ($_REQUEST['iid']==NULL)? $_POST['iid'] : $_REQUEST['iid'];
	$pid = ($_REQUEST['pid']==NULL)? $_POST['pid'] : $_REQUEST['pid'];
	
	if(isset($_REQUEST['search'])){
		$cur_search = trim($_REQUEST['search']);
		$cur_filter = $_REQUEST['filter'];
		$query_string = 'iid='.$iid.'&pid='.$pid;
	}	
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['show_product_inventory']['url'].'?iid='.$_REQUEST['iid'].'&pid='.$_REQUEST['pid'].'" class="nav">'.$Capabilities->All['show_product_inventory']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
				<form method="post" action="<?php echo current_page_name() ?>">
					<input type="hidden" name="iid" id="iid" value="<?php echo $iid; ?>"/>
					<input type="hidden" name="pid" id="pid" value="<?php echo $pid; ?>"/>
					<?php
						// change columns
					  $columns = array('created_at' => 'Date', 'terminal_code' => 'Terminal', 'address' => 'Address', 
				  									 'status' => 'Status', 'quantity' => 'Qty', 'remarks' => 'Remarks');
					  $column_widths = array('created_at' => '15', 'terminal_code' => '10', 'address' => '10', 
				  									 'status' => '15', 'quantity' => '10', 'remarks' => '25'); // total: 85%
					  $columns_class = array('created_at' => 'border-right', 'terminal_code' => 'border-right text-center', 'address' => 'border-right', 
				  									 'status' => 'border-right', 'quantity' => 'border-right', 'remarks' => 'border-right'); 
						// no changes required												
						$page = current_page_name();
						$_REQUEST['page']   == NULL ? $cur_page   = 1 : $cur_page     = $_REQUEST['page'];
						$_REQUEST['col']    == NULL ? $cur_col    = array_shift(array_keys($columns)) : $cur_col = $_REQUEST['col']; // if null, gets first column name
						$_REQUEST['sort']   == NULL ? $cur_sort   = 'DESC' : $cur_sort = $_REQUEST['sort'];
						$_REQUEST['search'] == NULL ? $cur_search = '' : $cur_search  = trim($_REQUEST['search']);
						$_REQUEST['filter'] == NULL ? $cur_filter = '' : $cur_filter  = $_REQUEST['filter'];
						
						// change table_name, query_column, query_join, quuery_condition	
						$table_name	     = 'item_inventory_location_history';
						$query_column    = 'terminals.id AS trml_id, terminals.terminal, terminals.terminal_code, item_inventory_location_history.inventory_id, location_addresses.address, 
																item_inventory_location_history.location_address_id, item_inventory_location_history.terminal_id, item_inventory_location_history.terminal_device_id, 
																lookups.description AS status, item_inventory_location_history.quantity, item_inventory_location_history.remarks, item_inventory_location_history.created_at';	
						$query_join      = 'LEFT OUTER JOIN location_addresses ON location_addresses.id = item_inventory_location_history.location_address_id
																LEFT OUTER JOIN lookups ON lookups.id = item_inventory_location_history.status
																RIGHT OUTER JOIN terminals ON terminals.id = item_inventory_location_history.terminal_id';
						
						$query_condition = 'item_inventory_location_history.inventory_id = '.$_REQUEST['iid'];
						
						// change $linked_columns, $linked_columns = NULL; if no linked columns
						$linked_columns = NULL;
																									 
						// change $link_details, $link_details = NULL; if no VIEW DETAILS						
						$link_details = NULL;
						
						// no changes required				
						$table_args = array('page' => $page, 'columns' => $columns, 'column_widths' => $column_widths, 'column_class' => $column_class, 'current_page' => $cur_page, 'current_column' => $cur_col, 'current_sort' => $cur_sort, 
													'current_search' => $cur_search, 'current_filter' => $cur_filter, 'table_name' => $table_name, 
													'query_column' => $query_column,  'query_condition' => $query_condition, 'query_join' => $query_join,
													'linked_columns' => $linked_columns, 'link_details' => $link_details, 'query_string' => $query_string);
                ?>
                
                <div class="search"><?php echo create_search_box($columns, $cur_search, $cur_filter); ?></div>
                
				<?php echo create_table($table_args);  ?> 
				</form>
		</div>
	</div>

<?php require('footer.php'); ?>