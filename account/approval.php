<?php
	$approval = $DB->Find($approval_table, 
												array('columns' => ''.$approval_table.'.id,
																					CONCAT(users1.first_name, " ", users1.last_name) AS created_by_name, '.$approval_table.'.created_by, '.$approval_table.'.created_at, roles1.name AS created_by_role,
																					CONCAT(users2.first_name, " ", users2.last_name) AS checked_by_name, '.$approval_table.'.checked_by, '.$approval_table.'.checked_at, roles2.name AS checked_by_role,
																					CONCAT(users3.first_name, " ", users3.last_name) AS approved_by_name, '.$approval_table.'.approved_by, '.$approval_table.'.approved_at, roles3.name AS approved_by_role',
															'joins' => 'LEFT OUTER JOIN users AS users1 ON users1.id = '.$approval_table.'.created_by
																					LEFT OUTER JOIN users AS users2 ON users2.id = '.$approval_table.'.checked_by
																					LEFT OUTER JOIN users AS users3 ON users3.id = '.$approval_table.'.approved_by
																					LEFT OUTER JOIN user_roles AS user_roles1 ON user_roles1.user_id = users1.id
																					LEFT OUTER JOIN user_roles AS user_roles2 ON user_roles2.user_id = users2.id
																					LEFT OUTER JOIN user_roles AS user_roles3 ON user_roles3.user_id = users3.id
																					LEFT OUTER JOIN roles AS roles1 ON roles1.id = user_roles1.role_id
																					LEFT OUTER JOIN roles AS roles2 ON roles2.id = user_roles2.role_id
																					LEFT OUTER JOIN roles AS roles3 ON roles3.id = user_roles3.role_id',
															'conditions' => ''.$approval_table.'.id='.$approval_item_id));

	$prepared = array ('by' => $approval['created_by_name'],
											'id' => $approval['created_by'],
											'pos' => $approval['created_by_role'],
											'at' => (isset($approval['created_at']) ? date("F d, Y", strtotime($approval['created_at'])) : ''));
	
	$checked = array ('by' => $approval['checked_by_name'],
											'id' => $approval['checked_by'],
											'pos' => $approval['checked_by_role'],
											'at' => (isset($approval['checked_at']) ? date("F d, Y", strtotime($approval['checked_at'])) : ''));
	
	$approved = array ('by' => $approval['approved_by_name'],
											'id' => $approval['approved_by'],
											'pos' => $approval['approved_by_role'],
											'at' => (isset($approval['approved_at']) ? date("F d, Y", strtotime($approval['approved_at'])) : ''));
?>

<div class="field-approve">
	<table width="100%">
		<tr><td height="5" colspan="99"></td></tr>
		<tr>
			<td width="25%">Prepared By:</td>
			<td width="25%">Checked By:</td>
			<td width="25%">Approved By:</td>
			<td></td>
		</tr>
		<tr>
			<td><b><?php echo $prepared['by'] ?></b></td>
			<td><b><?php echo $checked['by'] ?></b></td>
			<td><b><?php echo $approved['by'] ?></b></td>
			<td></td>
		</tr>
		<tr>
			<td><?php echo $prepared['pos'] ?></td>
			<td><?php echo $checked['pos'] ?></td>
			<td><?php echo $approved['pos'] ?></td>
			<td></td>
		</tr>
		<tr>
			<td><?php echo $prepared['at'] ?></td>
			<td><?php echo $checked['at'] ?></td>
			<td><?php echo $approved['at'] ?></td>
			<td></td>
		</tr>
	</table>
</div>