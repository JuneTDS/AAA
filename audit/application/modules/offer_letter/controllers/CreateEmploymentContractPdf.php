<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// header("Content-type:application/pdf");

include('vendor/tecnickcom/tcpdf/tcpdf.php');

class CreateEmploymentContractPdf extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
	}

	

	public function index()
	{
		// $this->load->helper('pdf_helper');

	    
	}

	public function create_document_pdf($data)
	{
		$document_id = [1];

		//echo json_encode($document_id);
		$array_link = [];
		if(count($document_id) != 0)
		{
			for($i = 0; $i < count($document_id); $i++)
			{
		        $q = $this->db->query("select * from payroll_pending_documents where id = '".$document_id[$i]."'");
				
		       	$q = $q->result_array();

		       	$query = $this->db->query("SELECT * from firm 
											LEFT JOIN firm_telephone ON firm.id = firm_telephone.firm_id AND firm_telephone.primary_telephone = 1 
											LEFT JOIN firm_fax ON firm.id = firm_fax.firm_id AND firm_fax.primary_fax = 1 
											LEFT JOIN firm_email ON firm.id = firm_email.firm_id AND firm_email.primary_email = 1 
											WHERE firm.id ='".$data['firm_id']."'");

		       	$query = $query->result_array();

		       	// $this->load->helper('pdf_helper');

	    		// create new PDF document
			    $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$obj_pdf->SetCreator(PDF_CREATOR);
				$title = "Employment Letter";
				$obj_pdf->SetTitle($title);
				$obj_pdf->setPrintHeader(true);
		  		
				//$obj_pdf->setPrintFooter(false);
				/*$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));*/
				$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='
					<table style="width: 100%; border-collapse: collapse; height: 95px; font-family: arial, helvetica, sans-serif; font-size: 10pt" border="0">
						<tbody>
							<tr style="height: 95px;">
								<td style="width: 18%; height: 95px;" align="center"><img src="uploads/logo/'. $query[0]["file_name"] .'" height="60" /></td>
								<td style="width: 80%; height: 95px;">
								<span style="font-size: 14pt;"><strong>'.$query[0]["name"].'</strong></span><br />
								<span style="font-size: 8pt; text-align:left;">'. $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"] .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $query[0]["fax"] .'<br />Email: <span style="font-size:7pt;">'. $query[0]["email"] .'<br />Website:'. $query[0]["url"] .'</span>&nbsp;</span></td>
							</tr>
						</tbody>
					</table>', 
					$tc=array(0,0,0), $lc=array(0,0,0));

				$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
				$obj_pdf->SetDefaultMonospacedFont('helvetica');
				$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				// $obj_pdf->SetMargins(43, 44, PDF_MARGIN_RIGHT);
				$obj_pdf->SetMargins(43, 40, PDF_MARGIN_RIGHT);
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
				// curl_setopt($ch, CURLOPT_URL, 'http://192.168.1.100/payroll/offer_letter/info');
				// $result = curl_exec($ch);
				// curl_close($ch);

				$employment_contract_info = $data;

				// echo json_encode($data);
				//------------------------------------------------------------------------

				$document_content = $q[0]["template"];
				if(strpos($document_content, '{{company_name}}') !== false)
            	{
            		$document_content = str_replace('{{company_name}}', $employment_contract_info['firm'], $document_content);
            	}
            	if(strpos($document_content, '{{employee_name}}') !== false)
            	{
            		$document_content = str_replace('{{employee_name}}', $employment_contract_info['name'], $document_content);
            	}
            	if(strpos($document_content, '{{identification_no}}') !== false)
            	{
            		$document_content = str_replace('{{identification_no}}', $employment_contract_info['ic_passport_no'], $document_content);
            	}
            	if(strpos($document_content, '{{effective_date}}') !== false)
            	{
            		$document_content = str_replace('{{effective_date}}', date('d F Y', strtotime($employment_contract_info['effective_from'])), $document_content);
            	}
            	if(strpos($document_content, '<span class="is_singaporean">') !== false)
            	{
            		if($employment_contract_info['is_pr_singaporean'] == 1)
            		{
            			$document_content = str_replace('<span class="is_singaporean">', '.<span class="is_singaporean" style="display: none;">', $document_content);
            		}
            	}
            	if($employment_contract_info['is_employee'] == false)
            	{
            		
            		$document_content = str_replace('<li class="old_employee">', '<li class="old_employee" style="display: none;">', $document_content);
            	}
            	elseif($employment_contract_info['is_employee'] == true)
            	{
            		$document_content = str_replace('<li class="new_employee">', '<li class="new_employee" style="display: none;">', $document_content);
            	}

            	$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            	if(strpos($document_content, '{{probation_period}}') !== false)
            	{
            		$document_content = str_replace('{{probation_period}}', $f->format($employment_contract_info['probationary_period']).' ('.$employment_contract_info['probationary_period'].')', $document_content);
            	}
            	if(strpos($document_content, '{{working_start_time}}') !== false)
            	{
            		$document_content = str_replace('{{working_start_time}}', $employment_contract_info['working_hour_time_start'], $document_content);
            	}
            	if(strpos($document_content, '{{working_end_time}}') !== false)
            	{
            		$document_content = str_replace('{{working_end_time}}', $employment_contract_info['working_hour_time_end'], $document_content);
            	}
            	if(strpos($document_content, '{{working_start_day}}') !== false)
            	{
            		$document_content = str_replace('{{working_start_day}}', $employment_contract_info['working_hour_day_start'], $document_content);
            	}
            	if(strpos($document_content, '{{working_end_day}}') !== false)
            	{
            		$document_content = str_replace('{{working_end_day}}', $employment_contract_info['working_hour_day_end'], $document_content);
            	}
            	if(strpos($document_content, '{{salary}}') !== false)
            	{
            		$document_content = str_replace('{{salary}}', $employment_contract_info['given_salary'], $document_content);
            	}
            	if(strpos($document_content, '{{terminar_notice}}') !== false)
            	{
            		$document_content = str_replace('{{terminar_notice}}', $f->format($employment_contract_info['termination_notice']).' ('.$employment_contract_info['termination_notice'].')', $document_content);
            	}
            	if(strpos($document_content, '{{employer_name}}') !== false)
            	{
            		$document_content = str_replace('{{employer_name}}', $employment_contract_info['employer'], $document_content);
            	}

				$content = $document_content;
				$obj_pdf->writeHTML($content, true, false, false, false, '');

				// $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'payroll/pdf/employement_letter/'.$q[0]["document_name"].'.pdf', 'I');

				$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'payroll/pdf/employment_letter/' . $q[0]["document_name"] . '(' . $employment_contract_info['name'] . ').pdf', 'F');

                // $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

                $link = 'http://'. $_SERVER['SERVER_NAME'] .'/payroll/pdf/employment_letter/' . $q[0]["document_name"] . '(' . $employment_contract_info['name'] . ').pdf';
                // $link = 'https://'. $_SERVER['SERVER_NAME'] .'/payroll/pdf/employment_letter/' . $q[0]["document_name"] . '(' . $employment_contract_info['name'] . ').pdf';

                $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> '/pdf/employment_letter/' . $q[0]["document_name"] . '(' . $employment_contract_info['name'] .').pdf');

                return json_encode($data);
			}
		}
		
	}

 }

 class MYPDF extends TCPDF {
 	//Page header
    public function Header() {
		$headerData = $this->getHeaderData();
        $this->SetFont('helvetica', 'B', 23);
        $this->SetXY(10, 10);
        // $this->writeHTML($headerData['string'], true, false, false, false, '');
        $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);
   	}

   public function Footer() {
        $this->SetY(-23);
        
        // Page number
        if($this->page != "4")
       	{
	        if (empty($this->pagegroups)) {
	            $pagenumtxt = $this->getAliasNumPage();
	        } else {
	            $pagenumtxt = $this->getPageNumGroupAlias();
	        }
	        $this->SetFont('helvetica', 'B', 10);
	        $this->Cell(140, 0, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
	    }
	    else
	    {
	    	if (empty($this->pagegroups)) {
	            $pagenumtxt = $this->getAliasNumPage();
	        } else {
	            $pagenumtxt = $this->getPageNumGroupAlias();
	        }
	        $this->SetFont('helvetica', 'B', 10);
	        $this->Cell(0, 0, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
	    }
       
       	if($this->page != "4")
       	{
	        $this->SetFont('helvetica', '', 11);
	        $this->MultiCell(20, 5, 'Employer', 1, 'C', 0, 0, '155', '', true);
			$this->MultiCell(20, 5, 'Employee', 1, 'C', 0, 1, '', '', true);
			$this->MultiCell(20, 5, '', 1, 'C', 0, 0, '155', '', true);
			$this->MultiCell(20, 5, '', 1, 'C', 0, 2, '175' ,'', true);

			$this->Ln(4);
		}
   }
}
