<?php
class Role
{
	private $DB;
  private $permissions;
	private $user_id;
	private $roles;
	
	function Role($db, $uid) {
  	$this->DB = $db;
    $this->permissions = array();
		$this->user_id = $uid;
  }

  function getRolePermissions($role_id) {
		$result = $this->DB->Get('role_permissions', array(
						  			'columns' 		=> 'permissions.description',
						  			'joins'				=> 'INNER JOIN permissions ON permissions.id = role_permissions.permission_id',
						  	    'conditions' 	=> 'role_permissions.role_id='.$role_id));
		foreach ($result as $perm) {
			$this->permissions[$perm['description']] = TRUE;
		}
    return $this->permissions;
  }

  function hasPerm($permission) {
    return isset($this->permissions[$permission]);
  }
	
	function set_roles($r) {
		$this->roles = $r;
	}
	
	public static function get_roles() {
		return $this->roles;
	}
}

