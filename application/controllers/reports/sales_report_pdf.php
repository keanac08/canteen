<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class sales_report_pdf extends CI_Controller {
	
	var $pdf = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->load->model('sales_model');
		//~ session_check();
	}
	
	public function index(){
		
		$from_date = $this->uri->segment(4);
		$to_date =  $this->uri->segment(5);
		
		$orientation = 'P';
		
		$this->pdf($orientation);
		$this->load->helper('number_helper');
		$this->load->helper('date_helper');
		$this->load->helper('string_helper');
		
		$rows = $this->sales_model->get_sales_by_date_range($from_date,$to_date);
		
		$this->pdf->AddPage($orientation);
		
		//~ HEADER ---------------------------------------------------------------------------------------------------
		$html = '<table border="0" style="padding: 2px">
					<tr>
						<td colspan="2" align="left" style="font-size: 17px;"><strong>Canteen Billing Report</strong></td>
					</tr>
					<tr>
						<td align="left" style="font-size: 12px;">'.date('m/d/Y', strtotime($from_date)).' - '.date('m/d/Y', strtotime($to_date)).'</td>
					</tr>
				</table>';
		$this->pdf->writeHTML($html, true, false, true, false, '');
		
		$ctr = 0;
		$header = 0;
		$total = 0;
		
		foreach($rows as $row){
			if($ctr == 0){
				$html = '<table border="0" style="font-size: 11px;padding: 2px 4px;">';
			}
			if($header == 0){
				$html .= '<tr style="background-color: #cccccc;border: 1px solid #ccc;">
							<td width="100px">Reference No</td>
							<td width="170px">Customer Name</td>
							<td width="170px">Cashier Name</td>
							<td width="150px">Purchase Date</td>
							<td width="80px" align="right">Amount</td>
						</tr>';
				$header++;
			}
			//~ else if($first_row == 0){
				
			//~ }
			if($row->id % 2 == 0){
				$style = 'style="background-color: #f1f1f1;"';
			}
			else{
				$style = '';
			}
			$html .= '<tr '.$style.'>
						<td width="100px">'.$row->id.'</td>
						<td width="170px">'.camelcase($row->customer_name).'</td>
						<td width="170px">'.camelcase($row->cashier_name).'</td>
						<td width="150px">'.long_date($row->purchase_date).'</td>
						<td width="80px"align="right">'.amount($row->total_purchase).'</td>
					  </tr>';
					  
			$total += $row->total_purchase;
			$ctr++;
			if($ctr == 100){
				$html .= '</table>';
				$this->pdf->writeHTML($html, true, false, true, false, '');
				$ctr = 0;
			}
		}
		
		$html = '<table border="0" style="padding: 2px">
					<tr>
						<td align="right"><strong>Total : '.amount($total).'</strong></td>
					</tr>
				</table>';
		$this->pdf->writeHTML($html, true, false, true, false, '');
		
		$this->pdf->Output('collection_receipt.pdf', 'I');
		
	}
	
	public function pdf($orientation){
		
		if($orientation == 'P'){
			// generate pdf content
			$this->load->library('Pdf_P');
			// create new PDF document
			$this->pdf = new PDF_P(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		}
		else{
			// generate pdf content
			$this->load->library('Pdf_L');
			// create new PDF document
			$this->pdf = new PDF_L(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		}
		// set document information
		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor('Isuzu');
		$this->pdf->SetTitle('IPC Treasury Portal');
		$this->pdf->SetSubject('IPC Treasury Portal');
		$this->pdf->SetKeywords('IPC Treasury Portal');
		// set default header data
		$this->pdf->SetheaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		$this->pdf->setFooterData(array(0,0,0), array(0,0,0));
		// set header and footer fonts
		$this->pdf->setheaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set default monospaced font
		$this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// set margins
		$this->pdf->SetMargins(PDF_MARGIN_LEFT - 5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT - 5);
		$this->pdf->SetheaderMargin(PDF_MARGIN_HEADER);
		$this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		// set auto page breaks
		$this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		// set image scale factor
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$this->pdf->setLanguageArray($l);
		}
		// set default font subsetting mode
		$this->pdf->setFontSubsetting(true);
	}
}
