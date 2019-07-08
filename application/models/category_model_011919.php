<?php

class Category_model_011919 extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

	public function get_categories(){

		$sql = "SELECT id, name, active
				FROM category_tbl_copy
				ORDER by (CASE WHEN id = 15 then 1 else 2 end), id";

		$data = $this->db->query($sql);
		return $data->result_array();
	}
	
	public function get_active_items(){

		$sql = "SELECT ic.category_id,
						i.id,
						i.name,
						i.price
				FROM items_tbl_copy i
				LEFT JOIN item_category_tbl_copy ic
				ON ic.item_id = i.id
				WHERE 1 = 1
				AND i.active = 1
				ORDER BY ic.category_id, i.name";

		$data = $this->db->query($sql);
		return $data->result_array();
	}
	
	public function get_category_items($category_id = NULL){

		$sql = "SELECT i.id, i.name, i.price, i.active, ic.category_id
				FROM items_tbl_copy i
				LEFT JOIN item_category_tbl_copy ic
				ON i.id = ic.item_id
				WHERE 1 = 1
				AND ic.category_id = IFNULL(?, (select min(id) FROM category_tbl))
				AND i.datetime >= '2019-01-16 10:00:00'
				ORDER BY i.active DESC, i.name";

		$data = $this->db->query($sql, $category_id);
		return $data->result_array();
	}
	
	public function get_category_active_items($category_id = NULL){

		if($category_id == NULL OR $category_id == 15){
			$sql = "SELECT it.id, it.name, it.price, it.active, '15' category_id, COUNT(it.id) cnt
					FROM transaction_tbl t
					LEFT JOIN transaction_item_tbl ti
					ON t.id = ti.trans_id
					LEFT JOIN items_tbl_copy it
					ON ti.item_id = it.id
					WHERE t.cashier_id = ".$this->session->ctn_user_id."
					AND DATE(t.datetime) = '".date("Y-m-d")."'
					AND it.active = 1
					-- AND it.name not like '%rice%'
					GROUP BY it.id
					ORDER BY CASE WHEN it.name like '%rice%' then 1 else 2 END, cnt DESC";
					//~ ".date("Y-m-d")." ".$this->session->ctn_user_id."
			$data = $this->db->query($sql);
		}
		else{
			$sql = "SELECT i.id, i.name, i.price, i.active, ic.category_id
					FROM items_tbl_copy i
					LEFT JOIN item_category_tbl_copy ic
					ON i.id = ic.item_id
					WHERE 1 = 1
					AND i.active = 1
					AND ic.category_id = ?
					ORDER BY CASE WHEN i.name like '%rice%' then 1 else 2 END, i.name";
			$data = $this->db->query($sql, $category_id);		
		}
		
		return $data->result_array();
	}
	
	public function update_item_active_status($item_id){

		$sql = "UPDATE items_tbl_copy 
				SET active = (CASE WHEN active = 1 THEN 0 ELSE 1 END) 
				WHERE id = ?";

		return $this->db->query($sql, $item_id);

	}
	
	public function update_category_active_status($category_id){

		$sql = "UPDATE category_tbl_copy 
				SET active = (CASE WHEN active = 1 THEN 0 ELSE 1 END) 
				WHERE id = ?";

		return $this->db->query($sql, $category_id);

	}
	
	public function check_item_duplicate($item_name)
	{
		$query = $this->db->get_where('items_tbl_copy', array('name' => $item_name, 'datetime >=' => '2019-01-16 10:00:00'));

		return $query->num_rows();
	}
	
	public function check_category_duplicate($category_name)
	{
		$query = $this->db->get_where('category_tbl_copy', array('name' => $category_name));

		return $query->num_rows();
	}
	
	public function check_item_duplicate_on_update($item_id, $item_name)
	{
		$query = $this->db->get_where('items_tbl_copy', array('name' => $item_name, 'id !=' => $item_id));

		return $query->num_rows();
	}
	
	public function check_category_duplicate_on_update($category_id, $category_name)
	{
		$query = $this->db->get_where('category_tbl_copy', array('name' => $category_name, 'id !=' => $category_id));

		return $query->num_rows();
	}
	
	public function save_new_item($item_name, $item_price){

		$sql = "INSERT INTO items_tbl_copy (name, price, active, datetime) VALUES (?, ?, ?, ?)";
		
		$params = array($item_name, $item_price, 1, date('Y-m-d H:i:s'));

		$this->db->query($sql, $params);
		
		return $this->db->insert_id();

	}
	
	public function save_new_category($category_name){

		$sql = "INSERT INTO category_tbl_copy (name) VALUES (?)";

		$this->db->query($sql, $category_name);
		
		return $this->db->insert_id();

	}
	
	public function save_new_item_category($item_id, $category_id){

		$sql = "INSERT INTO item_category_tbl_copy (item_id, category_id) VALUES (?, ?)";
		
		$params = array($item_id, $category_id);

		return $this->db->query($sql, $params);
		
	}
	
	public function update_item($item_id, $item_name, $item_price){

		$sql = "UPDATE items_tbl_copy
				SET name = ?,
				price = ?
				WHERE id = ?";
		$params = array($item_name, $item_price, $item_id);

		return $this->db->query($sql, $params);

	}
	
	public function update_category($category_id, $category_name){

		$sql = "UPDATE category_tbl_copy 
				SET name = ?
				WHERE id = ?";
		$params = array($category_name, $category_id);

		return $this->db->query($sql, $params);

	}
	
	public function update_item_category($item_id, $category_id){

		$sql = "UPDATE item_category_tbl_copy 
				SET category_id = ?
				WHERE item_id = ?";
		$params = array($category_id, $item_id);

		return $this->db->query($sql, $params);

	}
	
	public function insert_update_logs($item_id, $old_name, $old_price, $old_category, $updated_by){

		$sql = "INSERT INTO items_update_tbl_copy (
					item_id,
					old_name,
					old_price,
					old_category,
					updated_by)
				VALUES (?,?,?,?,?)";
		$params = array($item_id, $old_name, $old_price, $old_category, $updated_by);

		return $this->db->query($sql, $params);

	}
	

}
