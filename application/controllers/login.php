<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('login_model');
		$this->load->helper('string_helper');
		//~ session_check();
	}
	
	public function index(){
		
		$this->load->view('login_view');
	}
	
	
	public function ajax_validate(){
		
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		
		//~ echo $current_datetime;die();
		
		$data = $this->login_model->validate_user($username, $password);

		if(count($data) > 0){
			
			$user_data = array(
					'ctn_user_id' => $data[0]->user_id,
					'ctn_firstname' => $data[0]->firstname,
					'ctn_lastname' => $data[0]->lastname,
					'ctn_fullname' => CAMELCASE($data[0]->firstname . ' ' .  $data[0]->lastname),
					'ctn_usertype' => $data[0]->user_type,
					'ctn_gender' => $data[0]->gender
				);
			$this->session->set_userdata($user_data);
			
			if($data[0]->user_type == 'administrator'){
				echo 'admin';
			}
			else{ 
				echo 'cashier';
			}
		}
		else{
			echo 'error';
		}
	}
	
	public function logout(){
		
		$current_datetime = date('d-M-y H:i:s');
		
		$user_data = $this->session->get_userdata();
		foreach($user_data as $key => $value){
			$this->session->unset_userdata($key);
		}
		redirect('login');
	}
}
