<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class sales_report_excel extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		//~ session_check();
		$this->load->model('sales_model');
		$this->load->helper('date');
	}
	
	public function index(){

		$from_date = $this->uri->segment(4);
		$to_date =  $this->uri->segment(5);
		
		$rows = $this->sales_model->get_sales_by_date_range($from_date,$to_date);
		
		$this->load->library('excel');
			
		$writer = new XLSXWriter();
		
		$header = array(
						'Reference_Number' => 'integer',
						'Customer_Name' => 'string',
						'Cashier_Name' => 'string',
						'Purchase_Date' => 'MM/DD/YYYY',
						'Amount' => '#,##0.00'
					);
		$writer->writeSheetHeader('Sheet1', $header );
		
		foreach($rows as $row){
			
			$array = array(
						$row->id,
						$row->customer_name,
						$row->cashier_name,
						$row->purchase_date,
						$row->total_purchase
						);
			
			$writer->writeSheetRow('Sheet1', $array);
		}
		
		$filename = "sales_report.xlsx";
		header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		$writer->writeToStdOut();
	}
}
