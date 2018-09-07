<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . '/vendor/autoload.php';

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class Sales extends CI_Controller {
	
	public function __construct(){
		parent::__construct();

		//~ $this->load->model('sales_model');
		//~ session_check();
	}
	
	public function index(){
		
		//~ print_r($_SESSION);
		
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
	
	public function report(){
		
		$this->load->model('sales_model');
		
		$from_date = $this->input->post('from_date') == NULL ? date('Y-m-d') : date('Y-m-d', strtotime($this->input->post('from_date')));
		$to_date = $this->input->post('to_date') == NULL ? date('Y-m-d') : date('Y-m-d', strtotime($this->input->post('to_date')));
			
		$data['content'] = 'sales_report_view';
		$data['title'] = 'IPC Canteen POS';
		$data['head_title'] = 'Canteen | POS';
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['data'] = $this->sales_model->get_sales_by_date_range($from_date, $to_date);
		$this->load->view('include/template',$data);
	}
	
	public function sales_by_cashier(){
		
		$this->load->model('sales_model');
		
		$from_date = $this->input->post('from_date') == NULL ? date('Y-m-d') : date('Y-m-d', strtotime($this->input->post('from_date')));
		$to_date = $this->input->post('to_date') == NULL ? date('Y-m-d') : date('Y-m-d', strtotime($this->input->post('to_date')));
		$cashier_id = $this->input->post('cashier_id');
		
		$data['content'] = 'sales_report_view';
		$data['title'] = 'IPC Canteen POS';
		$data['head_title'] = 'Canteen | POS';
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['data'] = $this->sales_model->get_sales_by_date_range($from_date, $to_date, $cashier_id);
		$this->load->view('include/template',$data);
	}
	
	public function cashier(){
		
		$this->load->model('sales_model');
		
		$data['content'] = 'daily_sales_view';
		$data['title'] = 'IPC Canteen POS';
		$data['head_title'] = 'Canteen | POS';
		$data['data'] = $this->sales_model->get_daily_sales_by_cashier($this->session->ctn_user_id);
		$this->load->view('include/template',$data);
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
	
	public function ajax_transaction_items(){
		
		$this->load->model('sales_model');
		$data['data'] = $this->sales_model->get_transaction_items($this->input->post('id'));
		$data['transaction_id'] = $this->input->post('id');
		
		$this->load->view('modal/transaction_items_modal',$data);
	}
	
	public function print_receipt(){
		
		$data = json_decode(file_get_contents("php://input"), true);
		
		//~ print_r($data);
		
		$items = $data['cart'];
		$employee_name = $data['employee_name'];
		$meal_allowance = $data['meal_allowance'];
		$total_purchase = $data['total_purchase'];
		$transaction_id = $data['transaction_id'];
		
		$hname = explode('.', gethostbyaddr($_SERVER['REMOTE_ADDR']));

		$connector = new WindowsPrintConnector('smb://' . $hname[0] . '/EPSON TM-T82II Receipt');
		$printer = new Printer($connector);
		
		try {
			$printer->initialize();
			
			$printer->setJustification(Printer::JUSTIFY_CENTER);
			$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer->text("IPC Canteen\n");
			$printer->feed(2);
			$printer->setJustification();
			
			$printer -> selectPrintMode();
			$printer->text(str_pad('Qty', 8));
			$printer->text(str_pad('Description', 32));
			$printer->text(str_pad('Subtotal', 5) . "\n");
			$printer->text(str_pad('', 48, '-'));
			$printer->feed(1);

			foreach ($items as $item) 
			{
				$printer->text(str_pad('', 1));
				$printer->text(str_pad($item['quantity'], 7));
				
				$printer->text(str_pad($item['name'], 24));
				$printer->setFont(Printer::FONT_B);
				
				if ($item['quantity'] > 1){
					$printer->text('@' . str_pad($item['price'], 9));
				}
				else{
					$printer->text(str_pad('', 10));
				}
				$printer->setFont();
				$printer->text(str_pad($item['total'], 8, ' ', STR_PAD_LEFT) . "\n");
			}

			$printer->text(str_pad('', 48, '-'));
			$printer->feed(1);
			
			$printer -> setEmphasis(true);
			$printer->text(str_pad('Total', 36, ' ', STR_PAD_LEFT));
			$printer->text(str_pad($total_purchase, 11, ' ', STR_PAD_LEFT) . "\n");
			$printer -> setEmphasis(false);
			$printer->feed(2);
			
			$printer->text(str_pad('Meal Allowance', 36, ' ', STR_PAD_LEFT));
			$printer->text(str_pad($meal_allowance, 11, ' ', STR_PAD_LEFT) . "\n");
			$printer->text(str_pad('Purchase Amount', 36, ' ', STR_PAD_LEFT));
			$printer->text(str_pad($total_purchase, 11, ' ', STR_PAD_LEFT) . "\n");
			$printer->text(str_pad('', 40));
			$printer->text(str_pad('', 8, '-'));
			$printer -> setEmphasis(true);
			$printer->text(str_pad('Remaining Meal Allowance', 36, ' ', STR_PAD_LEFT));
			$printer->text(str_pad(number_format($meal_allowance - $total_purchase, 2), 11, ' ', STR_PAD_LEFT) . "\n");
			$printer -> setEmphasis(false);
			$printer->text(str_pad('', 48, '-'));
			
			$printer->feed(2);
			$printer->text("Transaction Number: " . $transaction_id . "\n");
			$printer->text("Customer: " . ucwords(strtolower($employee_name)) . "\n");
			$printer->text("Purchase Date: " . date('D, M d, Y h:i A') . "\n");
			$printer->feed(1);
			$printer->text("Cashier: " . ucwords(strtolower($this->session->ctn_fullname)) . "\n");
			
			$printer->feed(2);
			$printer->setJustification(Printer::JUSTIFY_CENTER);
			$printer->text("Enyoy your meal!");
			$printer->feed();
			$printer->cut();
			
		} catch(Exception $e) {
		    echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
		}
		finally {
			$printer -> close();
		}
	}
}
