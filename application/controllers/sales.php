<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {

	public function __construct(){
		parent::__construct();

		//~ $this->load->model('sales_model');
		//~ session_check();
	}
	
	public function index(){
		
		$data['content'] = 'sales_view';
		$data['title'] = 'IPC Canteen POS';
		$data['head_title'] = 'Canteen | POS';
		$this->load->view('include/template',$data);
	}
	
	public function customer(){
		
		$data['title'] = 'Customer Order';
		$data['head_title'] = 'Canteen | POS';
		$this->load->view('customer_view',$data);
	}
	
	public function ajax_employee_details(){
		
		$this->load->model('user_model');
		
		$data = $this->user_model->get_employee_details($this->input->get('employee_number'));
		
		if(count($data) > 0){
			echo json_encode($data);
		}
		else{
			echo json_encode(false);
		}
	}
	
	public function ajax_check_out(){
		
		$this->load->model('sales_model');
		
		$data = json_decode(file_get_contents("php://input"), true);
		
		//~ print_r($data);
		
		$items = $data['cart'];
		$employee_id = $data['employee_id'];
		$total_purchase = $data['total_purchase'];
		
		//~ print_r($items);	
		//~ echo $employee_id;
		//~ echo $total_purchase;
		//~ echo $this->session->ctn_user_id;
		
		$header = array(
					'user_id' => $employee_id,
					'credit_used' => $total_purchase,
					'cash' => 0,
					'total_purchase' => $total_purchase,
					'change' => 0,
					'cashier_id' => $this->session->ctn_user_id
				);
		
		//~ print_r($header);

		$transaction_id = $this->sales_model->insert_transaction_header($header);
		
		$lines = array();
		foreach($items as $item){
			
			$item = (object)$item;
			
			$lines[] = array(
							'item_id' => $item->id,
							'trans_id' => $transaction_id,
							'price' => $item->price,
							'quantity' => $item->quantity,
							'total' => $item->total
						);
		}
		
		$this->sales_model->insert_transaction_lines($lines);
		
		if($this->sales_model->update_meal_alowance($employee_id, $total_purchase) > 0){
		
			echo json_encode($transaction_id);
		}
		else{
			echo json_encode(false);
		}
	
	}
}
