<?php
  /*
   * Module: Orders 
  */
  $capability_key = 'orders';
  require('header.php');
	
	if(isset($_REQUEST['search'])){
		$cur_search = trim($_REQUEST['search']);
		$cur_filter = $_REQUEST['filter'];
	}
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
					$link = $Capabilities->All['add_order'];
				  if(is_null($user)) echo '<a href="'.$link['url'].'" class="nav">'.$link['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
			
		<div id="content">
			<form class="form-container" method="post" action="<?php echo current_page_name() ?>">
				<?php
					// change columns
				  $columns = array('po_number' => 'PO Number', 'po_date' => 'PO Date', 'description' => 'Description', 
				  									'payment_terms' => 'Payment Terms', 'delivery_date' => 'Delivery'); 	
				  $column_widths = array('po_number' => '10', 'po_date' => '10', 'description' => '25', 
				  									'payment_terms' => '30', 'delivery_date' => '10'); 	// total: 85%
				  $column_class = array('po_number' => 'border-right', 'po_date' => 'border-right', 'description' => 'border-right', 
				  									'payment_terms' => 'border-right', 'delivery_date' => 'border-right');
					
					// no changes required											
					$page = current_page_name();
					$_REQUEST['page']   == NULL ? $cur_page   = 1 : $cur_page     = $_REQUEST['page'];
					$_REQUEST['col']    == NULL ? $cur_col    = 'orders.id' : $cur_col = $_REQUEST['col'];  
					$_REQUEST['sort']   == NULL ? $cur_sort   = 'ASC' : $cur_sort = $_REQUEST['sort'];
					$_REQUEST['search'] == NULL ? $cur_search = '' : $cur_search  = trim($_REQUEST['search']);
					$_REQUEST['filter'] == NULL ? $cur_filter = '' : $cur_filter  = $_REQUEST['filter'];
					
					// change table_name, query_column, query_join, quuery_condition	
					$table_name	     = 'orders';
					$query_column    = 'orders.id AS ord_id, orders.po_number AS po_number, orders.po_date AS po_date, orders.description AS description, 
															lookups.description AS payment_terms, orders.delivery_date AS delivery_date, orders.total_amount AS total_amount';
					$query_join      = 'INNER JOIN lookups ON orders.payment_terms = lookups.id';
					$query_condition = 'orders.po_number LIKE "'.$cur_search.'%" OR 
		        									orders.po_date LIKE "'.$cur_search.'%" OR
		        									orders.description LIKE "'.$cur_search.'%" OR
		        									lookups.description LIKE "'.$cur_search.'%" OR
		        									orders.payment_terms LIKE "'.$cur_search.'%" OR
		        									orders.delivery_date LIKE "'.$cur_search.'%"';
					
					// change $linked_columns, $linked_columns = NULL; if no linked columns
					$linked_columns = array('po_number' => array('column' => 'po_number', 'link_page' => 'orders-show.php', 
																								 'link_params' => array('oid' => 'ord_id'))
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
				<br/>
			</form>
		</div>
	</div>

<?php require('footer.php'); ?>