<?php
class Role
{
	private $DB;
	private $user_id;
	
	function Role($db, $uid) {
  	$this->DB = $db;
		$this->user_id = $uid;
  }
	
	function isCapableByID($cap_id) {
		foreach ($this->getUserRoles() as $user_role) {
			$result = $this->DB->Find('role_capabilities', array(
						  			'columns' 		=> 'id',
						  	    'conditions' 	=> 'capability_id='. $cap_id .' AND role_id='. $user_role['role_id']));
			if(isset($result)) {
				return TRUE;
			}
		}
	}
	
	function isCapableByName($cap_name) {
		foreach ($this->getUserRoles() as $user_role) {
			$result = $this->DB->Find('role_capabilities', array(
						  			'columns' 		=> 'role_capabilities.*',
						  			'joins' => 'INNER JOIN capabilities ON capabilities.id = role_capabilities.capability_id',
						  	    'conditions' 	=> 'capabilities.capability="'. $cap_name .'" AND role_id='. $user_role['role_id']));
			if(isset($result)) {
				return TRUE;
			}
		}
	}

	function getUserRoles() {
		$result = $this->DB->Get('user_roles', array(
						  			'columns' 		=> 'user_roles.role_id, roles.name',
						  			'joins' => 'INNER JOIN roles ON roles.id = user_roles.role_id',
						  	    'conditions' 	=> 'user_roles.user_id='. $this->user_id));
		if(isset($result)) {
			return $result;
		}
	}
	
	function getUserCapabilities($role_id) {
		$result = $this->DB->Get('role_capabilities', array(
						  			'columns' 		=> 'role_capabilities.id, capabilities.id AS cap_id, capabilities.name',
						  			'joins' => 'INNER JOIN capabilities ON capabilities.id = role_capabilities.capability_id',
						  	    'conditions' 	=> 'role_capabilities.role_id='. $role_id));
		if(isset($result)) {
			return $result;
		}
	}
	
	function getRoleCapabilityIDs($role_id) {
		$result = $this->DB->Get('role_capabilities', array(
						  			'columns' 		=> 'capability_id',
						  	    'conditions' 	=> 'role_id='. $role_id));
		if(isset($result)) {
			return $result;
		}
	}
	
	function getAllCapabilities() {
		$result = $this->DB->Get('capabilities', array(
						  			'columns' 		=> 'capabilities.*'));
		if(isset($result)) {
			return $result;
		}
	}
}

