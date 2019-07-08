<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		//~ if(date('m/d/y') => '')
		
		
		//~ $target_date = strtotime('2019-01-19');

		//~ $current_date = strtotime(date('Y-m-d'));

		//~ if ($current_date >= $target_date)
		//~ {
			//~ $this->load->model('category_model_011919', 'category');
		//~ }
		//~ else{
			//~ $this->load->model('category_model', 'category');
		//~ }
		
		$this->load->model('category_model', 'category');
		session_check();
	}
	
	public function index(){
		
		$data['content'] = 'category_view';
		$data['title'] = 'Category Setup';
		$data['head_title'] = 'Canteen | POS';
		//~ $data['items'] = $this->category->get_category_items();
		$this->load->view('include/template',$data);
	}
	
	public function item(){
		
		$data['content'] = 'category_item_view';
		$data['title'] = 'Item Setup</small>';
		$data['head_title'] = 'Canteen | POS';
		//~ $data['items'] = $this->category->get_category_items();
		$this->load->view('include/template',$data);
	}
	
	public function ajax_category_items(){
		
		echo json_encode($this->category->get_category_items($this->input->get('id')));
	}
	
	public function ajax_categories(){
		
		echo json_encode($this->category->get_categories());
	}
	
	public function ajax_active_items(){
		
		echo json_encode($this->category->get_active_items());
	}
	
	public function ajax_change_item_status(){
		
		echo json_encode($this->category->update_item_active_status($this->input->get('id')));
	}
	
	public function ajax_change_category_status(){
		
		echo json_encode($this->category->update_category_active_status($this->input->get('id')));
	}
	
	public function ajax_category_active_items(){
		
		echo json_encode($this->category->get_category_active_items($this->input->get('category_id')));
	}
	
	public function ajax_new_item(){
		
		$item_name = $this->input->get('item_name');
		$item_price = $this->input->get('item_price');
		$item_category = $this->input->get('item_category');
		
		if(!$this->category->check_item_duplicate($item_name)){
			
			$new_item_id = $this->category->save_new_item($item_name, $item_price);
			echo json_encode($this->category->save_new_item_category($new_item_id, $item_category));
		}
		else{
			echo json_encode(false);
		}
	}
	
	public function ajax_new_category(){
		
		$category_name = $this->input->get('category_name');
		
		if(!$this->category->check_category_duplicate($category_name)){
			
			$new_category_id = $this->category->save_new_category($category_name);
			echo json_encode($new_category_id);
		}
		else{
			echo json_encode(false);
		}
	}
	
	public function ajax_update_item(){
		
		$updated_by = $this->session->ctn_user_id;
		
		$item_id = $this->input->get('item_id');
		$item_name = $this->input->get('item_name');
		$item_price = $this->input->get('item_price');
		$item_category = $this->input->get('item_category');
		
		$old_name = $this->input->get('old_name');
		$old_price = $this->input->get('old_price');
		$old_category = $this->input->get('old_category');
		
		if(!$this->category->check_item_duplicate_on_update($item_id, $item_name)){
			//update item details and category
			$this->category->update_item($item_id, $item_name, $item_price);
			$this->category->update_item_category($item_id, $item_category);
			
			//add update logs
			$this->category->insert_update_logs($item_id, $old_name, $old_price, $old_category, $updated_by);
			
			echo json_encode(true);
		}	
		else{
			echo json_encode(false);
		}
	}
	
	public function ajax_update_category(){
		
		$updated_by = $this->session->ctn_user_id;
		
		$category_id = $this->input->get('category_id');
		$category_name = $this->input->get('category_name');
		
		if(!$this->category->check_category_duplicate_on_update($category_id, $category_name)){
			//update category name
			$this->category->update_category($category_id, $category_name);
			echo json_encode(true);
		}	
		else{
			echo json_encode(false);
		}
	}
}
