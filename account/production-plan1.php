<?php
  /*
   * Module: Manufacturing 
  */
  $capability_key = 'production_plan';
  require('header.php');
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  //echo '<a href="'.$Capabilities->All['add_material']['url'].'" class="nav">'.$Capabilities->All['add_material']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form method="post" action="<?php echo current_page_name() ?>">
				<?php
					// change columns
			  	$columns	= array('po_no' => 'P/O No.', 
			  										'po_date' => 'Expected Delivery Date', 
			  										'target_date' => 'Production Target Date',
			  										'status' => 'Status'); 
					$column_widths	= array('po_no' => '25', 
														'po_date' => '20', 
														'shipment_date' => '20',
			  										'status' => '10'); 
					$column_class	= array('po_no' => 'border-right text-center', 
														'po_date' => 'border-right text-center', 
														'target_date' => 'border-right text-center',
			  										'status' => 'border-right text-center'); 
													 
					// no changes required											
					$page = current_page_name();
					$_REQUEST['page']   == NULL ? $cur_page   = 1 : $cur_page     = $_REQUEST['page'];
					$_REQUEST['col']    == NULL ? $cur_col    = array_shift(array_keys($columns)) : $cur_col = $_REQUEST['col']; // if null, gets first column name
					$_REQUEST['sort']   == NULL ? $cur_sort   = 'ASC' : $cur_sort = $_REQUEST['sort'];
					$_REQUEST['search'] == NULL ? $cur_search = '' : $cur_search  = trim($_REQUEST['search']);
					$_REQUEST['filter'] == NULL ? $cur_filter = '' : $cur_filter  = $_REQUEST['filter'];
					
					// change table_name, query_column, query_join, quuery_condition	
					$table_name	     = 'production_purchase_orders';
					$query_column    = 'production_purchase_orders.id AS ppoid, production_purchase_orders.order_id AS oid, orders.po_number AS po_no, 
															orders.po_date AS po_date, production_purchase_orders.target_date AS target_date, lookups.description AS status';	
					$query_join      = 'INNER JOIN orders ON orders.id = production_purchase_orders.order_id
															INNER JOIN lookups ON lookups.id = production_purchase_orders.status';	
					$query_condition = 'orders.po_number LIKE "'.$cur_search.'%"';
					
					// change $linked_columns, $linked_columns = NULL; if no linked columns
					$linked_columns = array('po_no' => array('column' => 'po_no', 'link_page' => 'production-plan-show.php', 
																								 'link_params' => array('ppoid' => 'ppoid', 'oid' => 'oid'))
																								 );
																								 
					// change $link_details, $link_details = NULL; if no VIEW DETAILS						
					$link_details = NULL;
					
					// no changes required
					$table_args = array('page' => $page, 'columns' => $columns, 'column_widths' => $column_widths, 'column_class' => $column_class, 'current_page' => $cur_page, 'current_column' => $cur_col, 'current_sort' => $cur_sort, 
												'current_search' => $cur_search, 'current_filter' => $cur_filter, 'table_name' => $table_name, 
												'query_column' => $query_column,  'query_condition' => $query_condition, 'query_join' => $query_join,
												'linked_columns' => $linked_columns, 'link_details' => $link_details);
                ?>
                
                <div class="search"><?php echo create_search_box($columns, $cur_search, $cur_filter); ?></div>
                
				<?php echo create_table($table_args);  ?> 
  		</form>
		</div>
	</div>

<?php require('footer.php'); ?>