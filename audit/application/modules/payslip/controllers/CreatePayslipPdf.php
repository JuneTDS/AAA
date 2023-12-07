<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// header("Content-type:application/pdf");

include('vendor/tecnickcom/tcpdf/tcpdf.php');

class CreatePayslipPdf extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
	}

	

	public function index()
	{
		// $this->load->helper('pdf_helper');
            // echo "tcpdf";
	    
	}

	public function create_document_pdf($data)
	{
		$document_id = [2];

		//echo json_encode($document_id);
		$array_link = [];
		if(count($document_id) != 0)
		{
			for($i = 0; $i < count($document_id); $i++)
			{
		        $q = $this->db->query("select * from pending_documents where id = '".$document_id[$i]."'");
				
		       	$q = $q->result_array();

		       	// $this->load->helper('pdf_helper');

	    		// create new PDF document
			    $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$obj_pdf->SetCreator(PDF_CREATOR);
				$title = "Payslip";
				$obj_pdf->SetTitle($title);
				$obj_pdf->setPrintHeader(false);
		  		
				//$obj_pdf->setPrintFooter(false);
				/*$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));*/
				$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
				$obj_pdf->SetDefaultMonospacedFont('helvetica');
				/*$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);*/
				$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				$obj_pdf->SetMargins(25.4, 35, PDF_MARGIN_RIGHT);
				$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
				$obj_pdf->SetFont('helvetica', '', 10);
				$obj_pdf->setFontSubsetting(false);
				//$obj_pdf->setPrintFooter(false);
				$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
				$obj_pdf->startPageGroup();
				$obj_pdf->setListIndentWidth(4);
				$obj_pdf->AddPage();

				//-----------------------------------API-------------------------------------
				// $ch = curl_init();
				// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// curl_setopt($ch, CURLOPT_URL, 'http://localhost/payroll/payslip/testinfo');
				// $result = curl_exec($ch);
				// curl_close($ch);

				// $employment_payslip_info = json_decode($result);

    //                     echo json_encode($employment_payslip_info);
				//------------------------------------------------------------------------

                        $employment_payslip_info = $data;

                        // echo "<br>" . json_encode($employment_payslip_info);

				$document_content = $q[0]["template"];
				
				if(strpos($document_content, '{{payslip_for}}') !== false)
            	{
            		$document_content = str_replace('{{payslip_for}}', $employment_payslip_info['payslip_for'], $document_content);
            	}
            	if(strpos($document_content, '{{employee_name}}') !== false)
            	{
            		$document_content = str_replace('{{employee_name}}', $employment_payslip_info['employee_name'], $document_content);
            	}
            	if(strpos($document_content, '{{nric}}') !== false)
            	{
            		$document_content = str_replace('{{nric}}', $employment_payslip_info['nric'], $document_content);
            	}
            	if(strpos($document_content, '{{department}}') !== false)
            	{
            		$document_content = str_replace('{{department}}', $employment_payslip_info['department'], $document_content);
            	}
            	if(strpos($document_content, '{{date}}') !== false)
            	{
            		$document_content = str_replace('{{date}}', date('d F Y', strtotime($employment_payslip_info['date'])), $document_content);
            	}
            	if(strpos($document_content, '{{pv_no}}') !== false)
            	{
            		$document_content = str_replace('{{pv_no}}', $employment_payslip_info['pv_no'], $document_content);
            	}
            	if(strpos($document_content, '{{basic_salary}}') !== false)
            	{
            		$document_content = str_replace('{{basic_salary}}', $employment_payslip_info['basic_salary'], $document_content);
            	}
            	if(strpos($document_content, '{{aws}}') !== false)
            	{
            		$document_content = str_replace('{{aws}}', $employment_payslip_info['aws'], $document_content);
            	}
            	if(strpos($document_content, '{{bonus}}') !== false)
            	{
            		$document_content = str_replace('{{bonus}}', $employment_payslip_info['bonus'], $document_content);
            	}
            	if(strpos($document_content, '{{commission}}') !== false)
            	{
            		$document_content = str_replace('{{commission}}', $employment_payslip_info['commission'], $document_content);
            	}
            	if(strpos($document_content, '{{total_basic_salary}}') !== false)
            	{
            		$document_content = str_replace('{{total_basic_salary}}', $employment_payslip_info['subtotal_salary'], $document_content);
            	}
            	if(strpos($document_content, '{{contribu_employee}}') !== false)
            	{
            		$document_content = str_replace('{{contribu_employee}}', $employment_payslip_info['cpf_employee'], $document_content);
            	}
            	if(strpos($document_content, '{{cdac}}') !== false)
            	{
            		$document_content = str_replace('{{cdac}}', $employment_payslip_info['less_cdac'], $document_content);
            	}
            	if(strpos($document_content, '{{salary_advance}}') !== false)
            	{
            		$document_content = str_replace('{{salary_advance}}', $employment_payslip_info['less_salary_advance'], $document_content);
            	}
            	if(strpos($document_content, '{{unpaid_leave}}') !== false)
            	{
            		$document_content = str_replace('{{unpaid_leave}}', $employment_payslip_info['less_unpaid_leave'], $document_content);
            	}
            	if(strpos($document_content, '{{total_less}}') !== false)
            	{
            		$document_content = str_replace('{{total_less}}', $employment_payslip_info['subtotal_less'], $document_content);
            	}
            	if(strpos($document_content, '{{health_incentive}}') !== false)
            	{
            		$document_content = str_replace('{{health_incentive}}', $employment_payslip_info['add_health_incentive'], $document_content);
            	}
            	if(strpos($document_content, '{{other_incentive}}') !== false)
            	{
            		$document_content = str_replace('{{other_incentive}}', $employment_payslip_info['add_other_incentive'], $document_content);
            	}
            	if(strpos($document_content, '{{total_add}}') !== false)
            	{
            		$document_content = str_replace('{{total_add}}', $employment_payslip_info['subtotal_add'], $document_content);
            	}
            	if(strpos($document_content, '{{total_net}}') !== false)
            	{
            		$document_content = str_replace('{{total_net}}', $employment_payslip_info['total_net_remun_pay'], $document_content);
            	}
            	if(strpos($document_content, '{{contribu_employer}}') !== false)
            	{
            		$document_content = str_replace('{{contribu_employer}}', $employment_payslip_info['cpf_employer'], $document_content);
            	}
            	if(strpos($document_content, '{{contribution_total}}') !== false)
            	{
            		$document_content = str_replace('{{contribution_total}}', $employment_payslip_info['total_cpf'], $document_content);
            	}
            	if(strpos($document_content, '{{skill_levy}}') !== false)
            	{
            		$document_content = str_replace('{{skill_levy}}', $employment_payslip_info['sd_levy'], $document_content);
            	}
            	if(strpos($document_content, '{{remaining_num}}') !== false)
            	{
            		$document_content = str_replace('{{remaining_num}}', $employment_payslip_info['remaining_days'], $document_content);
            	}

				$content = $document_content;
				$obj_pdf->writeHTML($content, true, false, false, false, '');

				$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'payroll/pdf/document/' . $q[0]["document_name"] . '(' . $employment_payslip_info['payslip_for'] . ').pdf', 'F');

                        // $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

                        $link = 'http://'. $_SERVER['SERVER_NAME'] .'/payroll/pdf/document/' . $q[0]["document_name"] . '(' . $employment_payslip_info['payslip_for'] . ').pdf';
                        // $link = 'https://'. $_SERVER['SERVER_NAME'] .'/payroll/pdf/document/' . $q[0]["document_name"] . '(' . $employment_payslip_info['payslip_for'] . ').pdf';

                        $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> '/pdf/document/' . $q[0]["document_name"] . '(' . $employment_payslip_info['payslip_for'] .').pdf');

                        return json_encode($data);
			}
		}
		
	}

 }

 class MYPDF extends TCPDF {

   public function Footer() {
        $this->SetY(-15);
        
        $this->SetFont('helvetica', '', 6);
        
        $this->Cell(0, 0, 'THIS SLIP IS COMPUTER GENERATED AND REQUIRES NO SIGNATURE ', 0, 0, 'C');
        $this->Ln();
        $this->Cell(0, 0,'SHALL THERE BE ANY DISCREPANCY PLEASE LOGIN TO EMPLOYEE PORTAL AND REPORT THE DISCREPANCY,', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Ln();
        $this->Cell(0, 0,'IF NO REPORT IS RECEIVED WITHIN 7 DAYS FROM DATE OF THIS SLIP, THIS PAYSLIP WILL BE CONSIDERED AS FINAL', 0, false, 'C', 0, '', 0, false, 'T', 'M');
   }
}
