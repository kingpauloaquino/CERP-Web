<?php
function create_search_box($columns, $cur_search, $cur_filter)
{
	$search_box = '<div><input id="search" name="search" type="text" class="text w180" autocomplete="off" ';
	$search_box .= 'value="'.trim($cur_search).'"/> ';
	$search_box .= '<select id="filter" name="filter" class="text w180">';
	$search_box .= '<option value="">All</option>';
	foreach ($columns as $key => $value) {
		$search_box .= $cur_filter == $key ? $selected = '<option value="'.$key.'" selected="selected">'.$value.'</option> ' : $selected = '<option value="'.$key.'">'.$value.'</option>';
	}
	$search_box .= '</select><input type="submit" value="search" onclick="check();" /></div>';
	return $search_box;
}

function create_table_header($_page, $_cols, $_width, $_class, $_cur_page, $_cur_col, $_cur_sort, $_cur_search, $_cur_filter, $_query_string)
{
	$table_headers = ''; 
	$div = '<div style="float: left; margin: 5px 0px 0px 5px">';
	$default_page_limit = 15;
	foreach ($_cols as $key => $value) {
		//$table_headers .= '<td width="'.$_width[$key].'%" class="'.$_class[$key].'" style="font-size:small"><a><div><div style="float: left">'.$value.'</div>';
		$table_headers .= '<td width="'.$_width[$key].'%" class="'.$_class[$key].'" style="font-size:small"><a>'.$value.'<div style="float:right">';
		
		if($key==$_cur_col){
			if($_REQUEST['sort']==NULL){
				if($_REQUEST['search']==NULL){
					$table_headers .= $div.'<a href="'.$_page.'?page='.$_cur_page.'&col='.$key.'&sort=DESC&'.$_query_string.'"><img src="../images/sort_asc.png"/></a></div>';
				}else{
					$table_headers .= $div.'<a href="'.$_page.'?page='.$_cur_page.'&col='.$key.'&sort=DESC&search='.$_cur_search.'&filter='.$_cur_filter.'&'.$_query_string.'"><img src="../images/sort_asc.png"/></a></div>';
				}
			}else{
				if(strpos(substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '?')+1), "ASC")){
					if($_REQUEST['search']==NULL){
						$table_headers .= $div.'<a href="'.$_page.'?page='.$_cur_page.'&col='.$key.'&sort=DESC&'.$_query_string.'"><img src="../images/sort_asc.png"/></a></div>';
					}else{
						$table_headers .= $div.'<a href="'.$_page.'?page='.$_cur_page.'&col='.$key.'&sort=DESC&search='.$_cur_search.'&filter='.$_cur_filter.'&'.$_query_string.'"><img src="../images/sort_asc.png"/></a></div>';
					}
				}else{
					if($_REQUEST['search']==NULL){
						$table_headers .= $div.'<a href="'.$_page.'?page='.$_cur_page.'&col='.$key.'&sort=ASC&'.$_query_string.'"><img src="../images/sort_dsc.png"/></a></div>';	
					}else{
						$table_headers .= $div.'<a href="'.$_page.'?page='.$_cur_page.'&col='.$key.'&sort=ASC&search='.$_cur_search.'&filter='.$_cur_filter.'&'.$_query_string.'"><img src="../images/sort_dsc.png"/></a></div>';
					}	
				}		
			}
		}else{
			if($_REQUEST['search']==NULL){
				$table_headers .= $div.'<a href="'.$_page.'?page='.$_cur_page.'&col='.$key.'&sort=DESC&'.$_query_string.'"><img src="../images/sort.png"/></a></div>';
			}else{
				$table_headers .= $div.'<a href="'.$_page.'?page='.$_cur_page.'&col='.$key.'&sort=DESC&search='.$_cur_search.'&filter='.$_cur_filter.'&'.$_query_string.'"><img src="../images/sort.png"/></a></div>';
			}
		}
		$table_headers .= '</div></a></td>';
	}
	return $table_headers;	
}

function create_table($table_args)
{
	$DB				= new MySQL;  
	$default_page_limit = 15;
	$startpoint = ($table_args['current_page'] * $default_page_limit) - $default_page_limit;
	$table = '';
	$table = '<div class="grid jq-grid"><table cellspacing="0" cellpadding="0"><thead><tr>';
	$table .= '<td width="6%" class="border-right text-right"></td>'; //counter column
	$table .= create_table_header($table_args['page'], $table_args['columns'], $table_args['column_widths'], $table_args['column_class'], $table_args['current_page'], $table_args['current_column'],
								$table_args['current_sort'], trim($table_args['current_search']), $table_args['current_filter'], $table_args['query_string'].$table_args['extra_param']);
	
	$table .= ($table_args['link_details']==NULL) ? '' : '<td width="9%"></td>';
	$table .= '</tr></thead>';
	$table .= '<tbody>';

	$query_result = $DB->Get($table_args['table_name'], array(
		        				'columns' 		=> 'COUNT('.$table_args['table_name'].'.id) AS cnt',
		        				'joins' 			=> $table_args['query_join'],
		        				'conditions' 	=> $table_args['current_filter']=='' ? $table_args['query_condition'] : $table_args['current_filter'].' LIKE "'.trim($table_args['current_search']).'%"'
    							 ));
	$total = $query_result[0]['cnt']; 
	$lastpage = ceil($total/$default_page_limit);
	$lastpage==0 ? $lastpage = 1 : $lastpage=$lastpage;

	
	$query_result = $DB->Get($table_args['table_name'], array(
		        				'columns' 		=> $table_args['query_column'],
		        				'joins' 			=> $table_args['query_join'],
		        				'conditions' 	=> $table_args['current_filter']=='' ? $table_args['query_condition'] : $table_args['current_filter'].' LIKE "'.trim($table_args['current_search']).'%"',
		        				'sort_column' => $table_args['current_column'],
		        				'sort_order' 	=> $table_args['current_sort'],
		        				'startpoint' 	=> (string) $startpoint,
		        				'limit' 			=> (string) $default_page_limit
    							 ));	
	$ctr=1;
 	$table_args['current_page'] == 1 ? $ctr=1 : $ctr = (($table_args['current_page'] * $default_page_limit) - $default_page_limit) + 1;
	$columns = array();
	$columns = $table_args['columns'];
	$linked_columns = $table_args['linked_columns'];
	foreach($query_result as $result) {
		$table .= '<tr>';
		$table .= '<td class="border-right text-center">'.$ctr.'</td>';	
		foreach ($columns as $key => $value) {
			$key = (strpos($key, '.')) ? substr($key, strpos($key, '.')+1) : $key;
			if(isset($table_args['linked_columns']) && array_key_exists($key , $linked_columns)){
				foreach ($linked_columns as $linked_column) {
					if($linked_column['column'] == $key) { // linked
						$params = $linked_column['link_params'];
						$param = '';
						foreach ($params as $pkey => $pvalue) {
							$param .= $pkey.'='.$result[$pvalue].'&';
						}
						$param = rtrim($param, '&');
						$field = (strpos($key, '.')) ? $result[$key] : $result[$key];
						$table .= (isset($field)) ? '<td class="'.$table_args['column_class'][$key].'"><a href="'.$linked_column['link_page'].'?'.$param.'">'.$field.'</a></td>' : '<td class="border-right text-center"> - </td>';
					} 
				}		
			}else{
				$field = (strpos($key, '.')) ? $result[$key] : $result[$key];
				//$table .= (isset($field)) ? '<td class="'.$table_args['column_class'][$key].'">'.$field.'</td>' : '<td class="border-right text-center"> - </td>';
				//$table_args['current_search']
				//$table .= (isset($field)) ? '<td class="'.$table_args['column_class'][$key].'">'.($field = ($key=='qty') ? trim_decimal($field) : $field).'</td>' : '<td class="border-right text-center"> - </td>';
				$table .= (isset($field)) ? '<td class="'.$table_args['column_class'][$key].'">'.($field = ($key=='qty') 
																		? trim_decimal($field) 
																		: $sfield = (strpos($field, $table_args['current_search'])!==false) 
																			? str_ireplace($table_args['current_search'], '<b>'.$table_args['current_search'].'</b>', $field) 
																			: $field ).'</td>' 
																	: '<td class="border-right text-center"> - </td>';
			}
		}
		
		if(isset($table_args['link_details'])){
			$params = $table_args['link_details']['link_params'];
			$param = '';
			foreach ($params as $pkey => $pvalue) {
				$param .= $pkey.'='.$result[$pvalue].'&';
			}
			$param = rtrim($param, '&');
			$table .= '<td class="text-center"><a href="'.$table_args['link_details']['link_page'].'?'.$param.'">view details</a></td>';	
		}
		$table .= '</tr>';	
		$ctr+=1;
	} 
	$table .= '</tbody></table></div>';
	
	//$table .= '</tbody><tfoot><tr><td colspan="7">';
		if($total>0){
			$param ='';
			if($table_args['current_column']!=NULL){ $param = '&col='.$table_args['current_column'].'&sort='.$table_args['current_sort']; }
			if($table_args['current_search']!=NULL) { $param.= '&search='. trim($table_args['current_search']) . '&filter='. $table_args['current_filter'];}  
			$table .= create_pagination($total,$default_page_limit,$table_args['current_page'], $table_args['page'], $param.'&'.$table_args['query_string'].$table_args['extra_param']);
			$table .= '<div style="margin-top: 5px; float: right; padding-right: 10px">Total Records: <b>'.$total.'</b></div>'; 
		} else{
			$table .= '<div style="font-style: italic">No Records</div>';
		}
	//$table .= '<div class="clear"></div>';	
	//$table .= '</td></tr></tfoot></table></div>';	

	return $table;
}

function create_pagination($total, $per_page = 10,$page = 1, $url, $param){
    $adjacents = "2"; 

	$page = ($page == 0 ? 1 : $page);  
	$start = ($page - 1) * $per_page;								
	
	$prev = $page - 1;							
	$next = $page + 1;
    $lastpage = ceil($total/$per_page);
	$lpm1 = $lastpage - 1;
	$pagination = "";+
	$url .= '?page=';
	
	if($lastpage > 1)
	{	
		$pagination .= "<div class='pagination'>";
		if ($lastpage < 6 + ($adjacents * 2))
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<a class='number current'>$counter</a>";
				else
					$pagination.= "<a class='number' href='{$url}$counter{$param}'>$counter</a>";					
			}
		}
		elseif($lastpage > 4 + ($adjacents * 2))
		{
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<a class='number current'>$counter</a>";
					else
						$pagination.= "<a class='number' href='{$url}$counter{$param}'>$counter</a>";					
				}
				$pagination.= "<span>...</span>";
				$pagination.= "<a class='number' href='{$url}$lpm1{$param}'>$lpm1</a>";
				$pagination.= "<a class='number' href='{$url}$lastpage{$param}'>$lastpage</a>";		
			}
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a class='number' href='{$url}1{$param}'>1</a>";
				$pagination.= "<a class='number' href='{$url}2{$param}'>2</a>";
				$pagination.= "<span>...</span>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<a class='number current'>$counter</a>";
					else
						$pagination.= "<a class='number' href='{$url}$counter{$param}'>$counter</a>";					
				}
				$pagination.= "<span>...</span>";	
				$pagination.= "<a class='number' href='{$url}$lpm1{$param}'>$lpm1</a>";
				$pagination.= "<a class='number' href='{$url}$lastpage{$param}'>$lastpage</a>";
			}
			else
			{
				$pagination.= "<a class='number' href='{$url}1{$param}'>1</a>";
				$pagination.= "<a class='number' href='{$url}2{$param}'>2</a>";
				$pagination.= "<span>...</span>";	
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<a class='number current'>$counter</a>";
					else
						$pagination.= "<a class='number' href='{$url}$counter{$param}'>$counter</a>";					
				}
			}
		}
		if ($page < $counter - 1){
            $pagination.= "<a href='{$url}$next{$param}'>Next</a>";
            $pagination.= "<a href='{$url}$lastpage{$param}'>Last</a>";
		}else{
			$pagination.= "<a class='current'>Next</a>";
            $pagination.= "<a class='current'>Last</a>";
        }
		$pagination.= "</div>\n";		
	} 
    return $pagination;
} 