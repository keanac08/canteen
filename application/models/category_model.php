<?php

class Category_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

	public function get_categories(){

		$sql = "SELECT id, name
				FROM category_tbl
				ORDER by id";
				
		//~ $sql = "SELECT c.name,
					//~ c.id,
					//~ COUNT(i.id) item_count,
					//~ COUNT(CASE WHEN i.active = 1 THEN 1 ELSE NULL END) active_count
				//~ FROM items_tbl i
				//~ LEFT JOIN item_category_tbl ic
					//~ ON ic.item_id = i.id
				//~ LEFT JOIN category_tbl c
					//~ ON ic.category_id = c.id
				//~ WHERE 1 = 1
					//~ GROUP BY c.id, c.name
				//~ ORDER BY ic.category_id, i.name";

		$data = $this->db->query($sql);
		return $data->result_array();
	}
	
	public function get_active_items(){

		$sql = "SELECT ic.category_id,
						i.id,
						i.name,
						i.price
				FROM items_tbl i
				LEFT JOIN item_category_tbl ic
				ON ic.item_id = i.id
				WHERE 1 = 1
				AND i.active = 1
				ORDER BY ic.category_id, i.name";

		$data = $this->db->query($sql);
		return $data->result_array();
	}
	
	public function get_category_items($category_id = NULL){

		$sql = "SELECT i.id, i.name, i.price, i.active, ic.category_id
				FROM items_tbl i
				LEFT JOIN item_category_tbl ic
				ON i.id = ic.item_id
				WHERE 1 = 1
				AND ic.category_id = IFNULL(?, (select min(id) FROM category_tbl))
				ORDER BY i.name";

		$data = $this->db->query($sql, $category_id);
		return $data->result_array();
	}
	
	public function get_category_active_items($category_id = NULL){

		$sql = "SELECT i.id, i.name, i.price, i.active, ic.category_id
				FROM items_tbl i
				LEFT JOIN item_category_tbl ic
				ON i.id = ic.item_id
				WHERE 1 = 1
				AND i.active = 1
				AND ic.category_id = IFNULL(?, (select min(id) FROM category_tbl))
				ORDER BY CASE WHEN i.name like '%rice%' then 1 else 2 END, i.name";

		$data = $this->db->query($sql, $category_id);
		return $data->result_array();
	}
	
	public function update_item_active_status($item_id){

		$sql = "UPDATE items_tbl 
				SET active = (CASE WHEN active = 1 THEN 0 ELSE 1 END) 
				WHERE id = ?";

		return $this->db->query($sql, $item_id);

	}
	
	public function check_item_duplicate($item_name)
	{
		$query = $this->db->get_where('items_tbl', array('name' => $item_name));

		return $query->num_rows();
	}
	
	public function check_item_duplicate_on_update($item_id, $item_name)
	{
		$query = $this->db->get_where('items_tbl', array('name' => $item_name, 'id !=' => $item_id));

		return $query->num_rows();
	}
	
	public function save_new_item($item_name, $item_price){

		$sql = "INSERT INTO items_tbl (name, price, active, datetime) VALUES (?, ?, ?, ?)";
		
		$params = array($item_name, $item_price, 1, date('Y-m-d H:i:s'));

		$this->db->query($sql, $params);
		
		return $this->db->insert_id();

	}
	
	public function save_new_item_category($item_id, $category_id){

		$sql = "INSERT INTO item_category_tbl (item_id, category_id) VALUES (?, ?)";
		
		$params = array($item_id, $category_id);

		return $this->db->query($sql, $params);
		
	}
	
	public function update_item($item_id, $item_name, $item_price){

		$sql = "UPDATE items_tbl 
				SET name = ?,
				price = ?
				WHERE id = ?";
		$params = array($item_name, $item_price, $item_id);

		return $this->db->query($sql, $params);

	}
	
	public function update_item_category($item_id, $category_id){

		$sql = "UPDATE item_category_tbl 
				SET category_id = ?
				WHERE item_id = ?";
		$params = array($category_id, $item_id);

		return $this->db->query($sql, $params);

	}
	
	public function insert_update_logs($item_id, $old_name, $old_price, $old_category, $updated_by){

		$sql = "INSERT INTO items_update_tbl (
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
