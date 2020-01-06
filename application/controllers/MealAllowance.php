<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MealAllowance extends CI_Controller {
	
	public function __construct(){
		parent::__construct();

		//~ $this->load->model('sales_model');
		session_check();
	}
	
	public function upload_meal_allowance(){
		
		//~ print_r($_SESSION);
		
		$data['content'] = 'upload_meal_allowance_view';
		$data['title'] = 'IPC Canteen Meal Allowance Upload';
		$data['head_title'] = 'Canteen | Meal Allowance';
		$this->load->view('include/template',$data);
	}

	public function transfer_meal_allowance(){
		$attachment_path = $_FILES['excel_file']['name'];
		// $ext             = pathinfo($attachment_path, PATHINFO_EXTENSION);
		// $attachment_file = 'upload/' . time() . '.' . $ext;

		if($attachment_path == 'MEAL ALLOWANCE.xlsx'){
			$attachment_file = '//ecommerce5/c$/wamp/www/canteen_barcode/public/excel/upload_meal_allowance/' . $attachment_path;
       		move_uploaded_file($_FILES['excel_file']['tmp_name'],  $attachment_file);

       		$this->session->set_flashdata('message', 
									'<div class="alert alert-success alert-dismissible" role="alert">
									    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									    <strong>File was successfully uploaded. </strong>                  
									</div>');

       		redirect(base_url('MealAllowance/upload_meal_allowance'));
		}else{
			$this->session->set_flashdata('message', 
									'<div class="alert alert-danger alert-dismissible" role="alert">
									    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									    <strong>Incorrect file. </strong>                  
									</div>');
			
			redirect(base_url('MealAllowance/upload_meal_allowance'));
		}
	}
}