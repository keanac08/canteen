<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('category_model');
		//~ session_check();
	}
	
	public function index(){
		
		$data['content'] = 'category_view';
		$data['title'] = 'Category <small>Item Setup</small>';
		$data['head_title'] = 'Canteen | POS';
		//~ $data['items'] = $this->category_model->get_category_items();
		$this->load->view('include/template',$data);
	}
	
	public function ajax_category_items(){
		
		echo json_encode($this->category_model->get_category_items($this->input->get('id')));
	}
	
	public function ajax_categories(){
		
		echo json_encode($this->category_model->get_categories());
	}
	
	public function ajax_active_items(){
		
		echo json_encode($this->category_model->get_active_items());
	}
	
	public function ajax_change_item_status(){
		
		echo json_encode($this->category_model->update_item_active_status($this->input->get('id')));
	}
	
	public function ajax_category_active_items(){
		
		echo json_encode($this->category_model->get_category_active_items($this->input->get('category_id')));
	}
	
	public function ajax_new_item(){
		
		$item_name = $this->input->get('item_name');
		$item_price = $this->input->get('item_price');
		$item_category = $this->input->get('item_category');
		
		if(!$this->category_model->check_item_duplicate($item_name)){
			
			$new_item_id = $this->category_model->save_new_item($item_name, $item_price);
			echo json_encode($this->category_model->save_new_item_category($new_item_id, $item_category));
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
		
		if(!$this->category_model->check_item_duplicate_on_update($item_id, $item_name)){
			//update item details and category
			$this->category_model->update_item($item_id, $item_name, $item_price);
			$this->category_model->update_item_category($item_id, $item_category);
			
			//add update logs
			$this->category_model->insert_update_logs($item_id, $old_name, $old_price, $old_category, $updated_by);
			
			echo json_encode(true);
		}	
		else{
			echo json_encode(false);
		}
	}
}
