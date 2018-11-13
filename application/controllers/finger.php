<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finger extends CI_Controller {
	
	public function __construct(){
		parent::__construct();

		//~ $this->load->model('sales_model');
		session_check();
	}
	
	public function register(){
		
		//~ print_r($_SESSION);
		
		$data['content'] = 'finger_register_view';
		$data['title'] = 'IPC Canteen Fingerprint Registration';
		$data['head_title'] = 'Canteen | Biometrics';
		$this->load->view('include/template',$data);
	}
}
