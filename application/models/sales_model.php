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
	
	public function get_sales_by_date_range($from, $to, $cashier_id = NULL){
		
		if($cashier_id == NULL){
			$and = '';
		}
		else{
			$and = 'AND c.cashier_id = ' . $cashier_id;
		}
		
		//change datetime to cutoff date
		$sql = "SELECT t.id, 
					 CONCAT(pit.first_name, ' ', pit.last_name) customer_name, 
					 t.total_purchase, 
					 t.datetime purchase_date,
					 CASE WHEN DATE(t.datetime) > '2018-09-07' 
							THEN CONCAT(c.firstname, ' ', c.lastname) 
							ELSE CONCAT(pit2.first_name, ' ', pit2.last_name) 
					 END cashier_name
				 FROM transaction_tbl t
				 LEFT JOIN ipc_central.personal_information_tab pit
					ON t.user_id = pit.employee_id
				 LEFT JOIN cashier_tbl c
					ON t.cashier_id = c.id
				 LEFT JOIN ipc_central.personal_information_tab pit2
					ON t.cashier_id = pit2.employee_id
				 WHERE DATE(t.datetime) between ? AND ?
				 ".$and."
				 ORDER BY t.id DESC";

		$data = $this->db->query($sql, array($from, $to));
		return $data->result();
	}
	
	public function get_daily_sales_by_cashier($cashier_id){
		
		//change datetime to cutoff date
		$sql = "SELECT t.id, 
					 CONCAT(pit.first_name, ' ', pit.last_name) customer_name, 
					 t.total_purchase, 
					 t.datetime purchase_date,
					 CASE WHEN DATE(t.datetime) > '2018-07-31' 
						THEN CONCAT(c.firstname, ' ', c.lastname) 
						ELSE CONCAT(pit2.first_name, ' ', pit2.last_name) 
					 END cashier_name
				 FROM transaction_tbl t
				 LEFT JOIN ipc_central.personal_information_tab pit
					ON t.user_id = pit.employee_id
				 LEFT JOIN cashier_tbl c
					ON t.cashier_id = c.id
				 LEFT JOIN ipc_central.personal_information_tab pit2
					ON t.cashier_id = pit2.employee_id
				 WHERE c.id = ?
				 AND DATE(t.datetime) = CURDATE()
				 ORDER BY t.id DESC";

		$data = $this->db->query($sql, $cashier_id);
		return $data->result();
	}
	
	public function get_transaction_items($transaction_id){

		$sql = "SELECT it.id, it.name, ti.price, ti.quantity, ti.total
				FROM transaction_item_tbl ti
				LEFT JOIN items_tbl it
				ON ti.item_id = it.id
				WHERE 1 = 1
				AND ti.trans_id = ?";

		$data = $this->db->query($sql, $transaction_id);
		return $data->result();
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
