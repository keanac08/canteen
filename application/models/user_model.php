<?php

class User_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

	public function get_employee_details($employee_number){

		$sql = 'SELECT 	emt.id, 
						emt.employee_no, 
						pit.first_name, 
						pit.middle_name, 
						pit.last_name, 
						st.section, 
						uma.meal_allowance,
						CASE WHEN tf.fld_ref_id is null THEN "Not Yet Registered" ELSE "Registered" END fingerprint 
				FROM ipc_central.employee_masterfile_tab emt
				LEFT JOIN ipc_central.personal_information_tab pit
					ON emt.id = pit.employee_id
				LEFT JOIN ipc_central.section_tab st
					ON emt.section_id = st.id
				LEFT JOIN users_meal_allowance_tbl uma
					ON emt.id = uma.user_id
				LEFT JOIN db_fingerprint.tbl_fingerprint tf
					ON emt.employee_no = tf.fld_ref_id 
				WHERE emt.employee_no = ?';
				
		$data = $this->db->query($sql,$employee_number);
		return $data->result();
	}
	
	public function get_cashiers(){

		$sql = "SELECT id, CONCAT(firstname, ' ', lastname) name
				FROM cashier_tbl
				WHERE user_type_id = 2
				AND id != 1";
				
		$data = $this->db->query($sql);
		return $data->result();
	}
}
