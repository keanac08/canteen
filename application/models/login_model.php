<?php

class Login_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

	public function validate_user($username,$password){

		$sql = 'SELECT ct.id user_id, ct.username, ct.firstname, ct.lastname, rt.user_type, ct.gender
				FROM cashier_tbl ct
				LEFT JOIN roles_tbl rt
				ON ct.user_type_id = rt.id
				WHERE username = ?
				and password = ?';
				
		$params = array(
						$username,
						$password
					);
		$data = $this->db->query($sql,$params);
		return $data->result();
	}
}
