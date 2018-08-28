<?php

class Sales_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

	public function get_items($category){

		$sql = "SELECT i.id, i.name, i.price 
				FROM items_tbl i
				LEFT JOIN item_category_tbl ic
				ON i.id = ic.item_id
				LEFT JOIN category_tbl c
				ON ic.category_id = c.id
				WHERE c.name = ?
				ORDER BY CASE WHEN i.name like '%rice%' THEN 1 ELSE 2 END, i.name";

		$data = $this->db->query($sql, $category);
		return $data->result_array();
	}
	
	public function insert_transaction_header($params){

		$this->db->insert('transaction_tbl', $params);
		
		return $this->db->insert_id();
	}
	
	public function insert_transaction_lines($params){

		$this->db->insert_batch('transaction_item_tbl', $params);
	}
	
	public function update_meal_alowance($employee_id, $total_purchase){

		$sql = "UPDATE users_meal_allowance_tbl SET meal_allowance = (meal_allowance - ?) WHERE user_id = ?";

		$this->db->query($sql, array($total_purchase, $employee_id));
		
		return $this->db->affected_rows();
	}

	
}
