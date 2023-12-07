<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once('assets/vendor/tcpdf/tcpdf.php');


class Report extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
        
        $this->load->library(array('session','parser','encryption'));
        $this->load->library(array('zip'));
        $this->load->model('report_model');
        $this->load->model('client/client_model');
        $this->load->model('caf/caf_model');
        $this->load->model('fs_model');
        $this->load->model('fs_account_category_model');

        // $this->load->model('leave/leave_model');
    }

    public function index()
    {   
        // $this->meta['page_name'] = 'Bank Confirmation';
        if(isset($_GET['tab_active']))
        {
            $this->session->set_userdata("tab_active", $_GET['tab_active']);
        }

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $this->data['ml_client']  = $this->report_model->get_ml_client();


        $bc = array(array('link' => '#', 'page' => 'Report'));
        $meta = array('page_title' => 'Report', 'bc' => $bc, 'page_name' => 'Report');

        // $this->page_construct('index.php', $this->meta, $this->data);
        $this->page_construct('index.php', $meta, $this->data);
    }

    public function get_caf_report_list()
    {
        $form_data = $this->input->post();

        $company_code = $form_data['company_code'];

        // $result   = $this->client_model->bank_confirm_filter($month, $status);

        $caf_list = $this->client_model->get_caf_list($company_code);

        echo json_encode($caf_list);
    }

    public function generate_ml_report()
    {   
        $form_data = $this->input->post();

        $assignment_id = $form_data['assignment_id'];

        $show_data_content = $this->fs_model->is_saved_fs_categorized_account($assignment_id);

        // $data = array('status'=>show_data_content, 'pdf_link'=>$link, 'path'=> '/pdf/document/Bank Authorization ('.DATE("Y-m-d",now()).').pdf');
        if($show_data_content)
        {
            $obj_pdf = new NOFOOTER_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $obj_pdf->SetCreator(PDF_CREATOR);
            $title = "ML Report";
            $obj_pdf->SetTitle($title);
            $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='<table style="width:100%; height: auto;"><tr style="text-align: center;"><td style="width:80%; height: auto;" align="center"></td></tr></table>', $tc=array(0,0,0), $lc=array(0,0,0));


            $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $obj_pdf->SetDefaultMonospacedFont('times');
            $obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            // $obj_pdf->SetFooterMargin(10);
            $obj_pdf->SetMargins(28, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $obj_pdf->SetAutoPageBreak(TRUE, 40);
            $obj_pdf->SetFont('times', '', 12);
            $obj_pdf->setFontSubsetting(false);
            $obj_pdf->setY(33);

            $obj_pdf->startPageGroup();

            $obj_pdf->AddPage();

            $do_template_query = $this->db->query("select * from audit_doc_master_template where id = 9");

            $do_template_query = $do_template_query->result_array();

            /** THIS IS FOR ACCOUNT CALCULATION **/
            $required_account_names = $this->report_model->get_audit_ml_report_json();

            $required_account_names = $required_account_names['report_account'];

            $fca_id = $this->report_model->get_fca_id_with_name($assignment_id, $required_account_names);            

            $data = $this->fs_account_category_model->get_account_with_sub_ids($assignment_id, $fca_id);

            $calculated_data = $this->calculate_ml_report_accounts($data);

            // print_r($calculated_data);
            /** END OF CALCULATIONS **/

            // $this->load->helper('pdf_helper');

            $pattern = "/{{[^}}]*}}/";
            $subject = $do_template_query[0]["document_content"];
            preg_match_all($pattern, $subject, $matches);

            $contents_info = $do_template_query[0]["document_content"];
            $assignment_detail = $this->caf_model->getAssignmentDetail($assignment_id);

            $toggle_array = $matches[0];

            if(count($toggle_array) != 0)
            {
                for($r = 0; $r < count($toggle_array); $r++)
                {
                    $string1 = (str_replace('{{', '',$toggle_array[$r]));
                    $string2 = (str_replace('}}', '',$string1));

                    
                    if($string2 == "company name")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $assignment_detail->client_name;
                                  
                    }
                    elseif($string2 == "firm name")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $assignment_detail->firm_name;
                                  
                    }
                    elseif($string2 == "total assets")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['total assets']);
                                  
                    }
                    elseif($string2 == "current assets")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['current assets']);
                                  
                    }
                    elseif($string2 == "assets1")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['assets1']);
                                  
                    }
                    elseif($string2 == "assets2")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['assets2']);
                                  
                    }
                    elseif($string2 == "assets3")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['assets3']);
                                  
                    }
                    elseif($string2 == "non-current assets")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['non-current assets']);
                                  
                    }
                    elseif($string2 == "ncassets1")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['ncassets1']);
                                  
                    }
                    elseif($string2 == "ncassets2")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['ncassets2']);
                                  
                    }
                    elseif($string2 == "total liabilities")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['total liabilities']);
                                  
                    }
                    elseif($string2 == "current liabilities")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['current liabilities']);
                                  
                    }
                    elseif($string2 == "cliabilities1")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['cliabilities1']);
                                  
                    }
                    elseif($string2 == "cliabilities2")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['cliabilities2']);
                                  
                    }
                    elseif($string2 == "non-current liabilities")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['non-current liabilities']);
                                  
                    }
                    elseif($string2 == "ncliabilities1")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['ncliabilities1']);
                                  
                    }
                    elseif($string2 == "ncliabilities2")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['ncliabilities2']);
                                  
                    }
                    elseif($string2 == "net assets")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['net assets']);
                                  
                    }
                    elseif($string2 == "revenue")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['revenue']);
                                  
                    }
                    elseif($string2 == "revenue1")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['revenue1']);
                                  
                    }
                    elseif($string2 == "revenue2")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['revenue2']);
                                  
                    }
                    elseif($string2 == "total assets")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['total assets']);
                                  
                    }
                    elseif($string2 == "costs and expenses")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['costs and expenses']);
                                  
                    }
                    elseif($string2 == "expenses1")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['expenses1']);
                                  
                    }
                    elseif($string2 == "expenses2")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['expenses2']);
                                  
                    }
                    elseif($string2 == "expenses3")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['expenses3']);
                                  
                    }
                    elseif($string2 == "expenses4")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['expenses4']);
                                  
                    }
                    elseif($string2 == "expenses5")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['expenses5']);
                                  
                    }
                    elseif($string2 == "expenses6")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['expenses6']);
                                  
                    }
                    elseif($string2 == "p/l before tax")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['p/l before tax']);
                                  
                    }
                    elseif($string2 == "p/l after tax")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['p/l after tax']);
                                  
                    }
                    elseif($string2 == "cash beginning")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['cash beginning']);
                                  
                    }
                    elseif($string2 == "cash end")
                    {
                        $replace_string = $toggle_array[$r];

                        $content = $this->negative_bracket($calculated_data['cash end']);
                                  
                    }
                    


                    $contents_info = str_replace($replace_string, $content, $contents_info);

                }
            }

            $new_content = $contents_info;



            $obj_pdf->writeHTML($new_content, true, false, false, false, '');

        
            $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/ML_REPORT('.DATE("Y-m-d",now()).').pdf', 'F');
            // $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'test_audit/pdf/document/Bank Authorization ('.DATE("Y-m-d",now()).').pdf', 'F');
            chmod($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/ML_REPORT('.DATE("Y-m-d",now()).').pdf',0644);                
            
            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

            $link = 'http://'. $_SERVER['SERVER_NAME'] .'/audit/pdf/document/ML_REPORT('.DATE("Y-m-d",now()).').pdf';
            // $link = 'http://'. $_SERVER['SERVER_NAME'] .'/test_audit/pdf/document/Bank Authorization ('.DATE("Y-m-d",now()).').pdf';

            $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> '/pdf/document/ML_REPORT('.DATE("Y-m-d",now()).').pdf');

            echo json_encode($data);
        }
        else
        {
            $data = array('status'=>$show_data_content, 'pdf_link'=>'', 'path'=> '');
            echo json_encode($data);
        }


        


    }

    public function calculate_ml_report_accounts($data)
    {
        $temp_arr = array();

        foreach ($data as $key => $value) {
            // print_r($value['parent_array'][0]);
            if(strcasecmp($value['parent_array'][0]['description'], 'Assets') == 0) 
            {
                $temp_arr['Assets'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Current assets') == 0) 
            {
                $temp_arr['Current assets'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Trade receivables') == 0) 
            {
                $temp_arr['Trade receivables'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Cash and short-term deposits') == 0) 
            {
                $temp_arr['Cash and short-term deposits'] = $value['parent_array'][0]['total_c_adjusted'];
                $temp_arr['Cash and short-term deposits (lye)'] = $value['parent_array'][0]['total_c_lye'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Non-current assets') == 0) 
            {
                $temp_arr['Non-current assets'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Plant and equipment') == 0) 
            {
                $temp_arr['Plant and equipment'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Liabilities') == 0) 
            {
                $temp_arr['Liabilities'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Current liabilities') == 0) 
            {
                $temp_arr['Current liabilities'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Loan and borrowings') == 0) 
            {
                if($value['parent_array'][0]['account_code'] == "L1010000")
                {
                    $temp_arr['Loan and borrowings (cl)'] = $value['parent_array'][0]['total_c_adjusted'];
                }
                elseif($value['parent_array'][0]['account_code'] == "L2010000")
                {
                    $temp_arr['Loan and borrowings (ncl)'] = $value['parent_array'][0]['total_c_adjusted'];
                }
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Revenue') == 0) 
            {
                $temp_arr['Revenue'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Other Income') == 0) 
            {
                $temp_arr['Other Income'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Interest Income') == 0) 
            {
                $temp_arr['Interest Income'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Cost of sales') == 0) 
            {
                $temp_arr['Cost of sales'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Expenses') == 0) 
            {
                $temp_arr['Expenses'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Advertising and marketing expenses') == 0) 
            {
                $temp_arr['Advertising and marketing expenses'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Bad debts written off - trade') == 0) 
            {
                $temp_arr['Bad debts written off - trade'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Allowance for credit losses - trade') == 0) 
            {
                $temp_arr['Allowance for credit losses - trade'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Debt collection fee') == 0) 
            {
                $temp_arr['Debt collection fee'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Employee benefits expense') == 0) 
            {
                $temp_arr['Employee benefits expense'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Rental of office') == 0) 
            {
                $temp_arr['Rental of office'] = $value['parent_array'][0]['total_c_adjusted'];
            }

            if(strcasecmp($value['parent_array'][0]['description'], 'Tax') == 0) 
            {
                $temp_arr['Tax'] = $value['parent_array'][0]['total_c_adjusted'];
            }            

        }

        $calculated_arr = array();

        $calculated_arr['total assets']             = isset($temp_arr['Assets'])?$temp_arr['Assets']:0;
        $calculated_arr['current assets']           = isset($temp_arr['Current assets'])?$temp_arr['Current assets']:0;
        $calculated_arr['assets1']                  = isset($temp_arr['Trade receivables'])?$temp_arr['Trade receivables']:0;
        $calculated_arr['assets2']                  = isset($temp_arr['Cash and short-term deposits'])?$temp_arr['Cash and short-term deposits']:0;
        $calculated_arr['assets3']                  = isset($temp_arr['Current assets'])?($temp_arr['Current assets'] - $calculated_arr['assets1'] - $calculated_arr['assets2']):0;
        $calculated_arr['non-current assets']       = isset($temp_arr['Non-current assets'])?$temp_arr['Non-current assets']:0;
        $calculated_arr['ncassets1']                = isset($temp_arr['Plant and equipment'])?$temp_arr['Plant and equipment']:0;
        $calculated_arr['ncassets2']                = isset($temp_arr['Non-current assets'])?($temp_arr['Non-current assets'] - $calculated_arr['ncassets1']):0;
        $calculated_arr['total liabilities']        = isset($temp_arr['Liabilities'])?($temp_arr['Liabilities'] * -1):0;
        $calculated_arr['current liabilities']      = isset($temp_arr['Current liabilities'])?($temp_arr['Current liabilities'] * -1):0;
        $calculated_arr['cliabilities1']            = isset($temp_arr['Loan and borrowings (cl)'])?($temp_arr['Loan and borrowings (cl)'] * -1):0;
        $calculated_arr['cliabilities2']            = $calculated_arr['current liabilities'] - $calculated_arr['cliabilities1'];
        $calculated_arr['non-current liabilities']  = isset($temp_arr['Non-current liabilities'])?($temp_arr['Non-current liabilities'] * -1):0;
        $calculated_arr['ncliabilities1']           = isset($temp_arr['Loan and borrowings (ncl)'])?($temp_arr['Loan and borrowings (ncl)'] * -1):0;
        $calculated_arr['ncliabilities2']           = $calculated_arr['non-current liabilities'] - $calculated_arr['ncliabilities1'];
        $calculated_arr['net assets']               = $calculated_arr['total assets'] - $calculated_arr['total liabilities'];
        $calculated_arr['revenue']                  = 0;
        if(isset($temp_arr['Revenue']) || isset($temp_arr['Other Income']))
        {
            if(isset($temp_arr['Revenue']))
            {
                $calculated_arr['revenue'] += $temp_arr['Revenue'];
            }

            if(isset($temp_arr['Other Income']))
            {
                $calculated_arr['revenue'] += $temp_arr['Other Income'];
            }

            $calculated_arr['revenue'] *= -1;
        }
        $calculated_arr['revenue1']                 = isset($temp_arr['Interest Income'])?($temp_arr['Interest Income'] * -1):0;
        $calculated_arr['revenue2']                 = $calculated_arr['revenue'] - $calculated_arr['revenue1'];
        $calculated_arr['costs and expenses']       = 0;
        if(isset($temp_arr['Cost of sales']) || isset($temp_arr['Operating expenses']))
        {
            if(isset($temp_arr['Cost of sales']))
            {
                $calculated_arr['costs and expenses'] += $temp_arr['Cost of sales'];
            }

            if(isset($temp_arr['Expenses']))
            {
                $calculated_arr['costs and expenses'] += $temp_arr['Expenses'];
            }
        }
        $calculated_arr['expenses1']                = isset($temp_arr['Advertising and marketing expenses'])?$temp_arr['Advertising and marketing expenses']:0;
        // $calculated_arr['expenses1'] = 0;
        // if(isset($temp_arr['Advertising and marketing expenses']) || isset($temp_arr['Allowance for credit losses - trade']))
        // {
        //     if(isset($temp_arr['Advertising and marketing expenses']))
        //     {
        //         $calculated_arr['expenses1'] += $temp_arr['Advertising and marketing expenses'];
        //     }

        //     if(isset($temp_arr['Allowance for credit losses - trade']))
        //     {
        //         $calculated_arr['expenses1'] += $temp_arr['Allowance for credit losses - trade'];
        //     }
        // }
        $calculated_arr['expenses2'] = 0;
        if(isset($temp_arr['Bad debts written off - trade']) || isset($temp_arr['Allowance for credit losses - trade']))
        {
            if(isset($temp_arr['Bad debts written off - trade']))
            {
                $calculated_arr['expenses2'] += $temp_arr['Bad debts written off - trade'];
            }

            if(isset($temp_arr['Allowance for credit losses - trade']))
            {
                $calculated_arr['expenses2'] += $temp_arr['Allowance for credit losses - trade'];
            }
        }
        // $calculated_arr['expenses2']                = isset($temp_arr['Bade debts written off - trade'])?$temp_arr['Bade debts written off - trade'] : 0;
        $calculated_arr['expenses3']                = isset($temp_arr['Debt collection fee'])?$temp_arr['Debt collection fee']:0;
        $calculated_arr['expenses4']                = isset($temp_arr['Employee benefits expense'])?$temp_arr['Employee benefits expense']:0;
        $calculated_arr['expenses5']                = isset($temp_arr['Rental of office'])?$temp_arr['Rental of office']:0;
        $calculated_arr['expenses6']                = $calculated_arr['costs and expenses'] - $calculated_arr['expenses1'] - $calculated_arr['expenses2'] - $calculated_arr['expenses3'] - $calculated_arr['expenses4'] - $calculated_arr['expenses5'];
        $calculated_arr['p/l before tax']           = $calculated_arr['revenue'] - $calculated_arr['costs and expenses'];

        if(isset($temp_arr['Tax']))
        {
            $calculated_arr['p/l after tax']            = $calculated_arr['p/l before tax'] - $temp_arr['Tax'];
        }
        else
        {
            $calculated_arr['p/l after tax']            = $calculated_arr['p/l before tax'];
        }
        
        $calculated_arr['cash beginning']           = isset($temp_arr['Cash and short-term deposits (lye)'])?$temp_arr['Cash and short-term deposits (lye)']:0;
        $calculated_arr['cash end']                 = $calculated_arr['assets2'];


        return $calculated_arr;
    }

    function negative_bracket($number)
    {
      
        if($number < 0)
        {
            return "(" . number_format(abs($number),2) . ")";
        }
        else
        {
            return number_format($number,2);
        }
    }

    public function write_address($street_name, $unit_no1, $unit_no2, $building_name, $postal_code, $type)
    {
        $unit = '';
        $unit_building_name = '';

        $comma = '';

        if($type == "normal")
        {
            $br = '';
        }
        elseif($type == "letter")
        {
            $br = ' <br/>';
        }
        elseif($type == "letter with comma")
        {
            $br = ' <br/>';
            $comma = ',';
        }
        elseif($type == "comma")
        {
            $br = '';
        }

        $street_name = strtoupper($street_name);
        $building_name = strtoupper($building_name);
        $unit_no1 = strtoupper($unit_no1);
        $unit_no2 = strtoupper($unit_no2);
        $postal_code = strtoupper($postal_code);

        // Add unit
        if(!empty($unit_no1) && !empty($unit_no2))
        {
            $unit = '#' . $unit_no1 . '-' . $unit_no2 . $comma;
        }

        // Add building
        if(!empty($building_name) && !empty($unit))
        {
            $unit_building_name = $unit . ' ' . $building_name . $comma;
        }
        elseif(!empty($unit))
        {
            $unit_building_name = $unit;
        }
        elseif(!empty($building_name))
        {
            $unit_building_name = $building_name . $comma;
        }
        //print_r($street_name . $br . $unit_building_name . $br . 'Singapore ' . $postal_code);
        if(!empty($unit))
        {
            $address = $street_name . $comma . $br . $unit_building_name . $br . 'SINGAPORE ' . $postal_code;
        }
        elseif(!empty($building_name))
        {
            $address = $street_name . $comma . $br . $building_name . $comma . $br . 'SINGAPORE ' . $postal_code;
        }
        else
        {
            $address = $street_name . $comma . $br . 'SINGAPORE' . $postal_code;
        }
        return $address;
    }

    public function write_header($firm_id, $use_own_header)
    {
        $query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
                                                JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
                                                JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
                                                JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
                                                where firm.id = '".$firm_id."'");
        $query = $query->result_array();

        // Calling getimagesize() function 
        list($width, $height, $type, $attr) = getimagesize("uploads/logo/" . $query[0]["file_name"]); 

        $different_w_h = (float)$width - (float)$height;

        if((float)$width > (float)$height && $different_w_h > 100)
        {
            //before width is 25, height is 73.75
            $td_width = 25;
            $td_height = 73.75;
        }
        else
        {
            $td_width = 15;
            $td_height = 83.75;
        }

        if(!$use_own_header){
            if(!empty($query[0]["file_name"]))
            {
                $img = '<img src="uploads/logo/'. $query[0]["file_name"] .'" height="55" />';
            }
            else
            {
                $img = '';
            }
        }

        if(!$use_own_header)
        {
            $header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
                    <tbody>
                    <tr style="height: 60px;">
                        <td style="text-align: left; height: 60px; padding: 5%;" width="'.$td_width.'%" align="center">
                            <table style="border-collapse: collapse; width: 100%;" border="0">
                            <tbody>
                            <tr valign="center">
                            <td style="text-align: left; height: 60px;" align="center" valign="center"><p>'. $img .'  </p></td>
                            </tr>
                            </tbody>
                            </table>
                        </td>
                        <td style="text-align: left;" width="1.25%">&nbsp;</td>
                        <td style="height: 60px;" width="'.$td_height.'%"><span style="font-size: 18pt;">'.$query[0]["name"].'</span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />Address: '. $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"] .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $query[0]["fax"] .'&nbsp;</span></td>
                    </tr>
                    </tbody>
                    </table>';
        }
        else
        {
            $header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
                                <tbody>
                                <tr style="height: 80px;"><td style="height: 60px;"></td></tr>
                                </tbody>
                               </table>';
        }

        return $header_content;
    }



}

class MYPDF extends TCPDF {
    public function Header() {
        $headerData = $this->getHeaderData();
        $this->writeHTMLCell(0, 0, 20, 7, $headerData['string'], 0, 0, false, "C", true);
   }

   public function Footer() {
        // Position at 25 mm from bottom
        $this->SetY(-18);
        // $this->SetX(10);


        // Set font
        $this->SetFont('helvetica', 'B', 8);

        $logoX = 160; // The logo will be displayed on the left side close to the border of the page
        $logoFileName = base_url()."img/footer_img.png";
        $logoWidth = 40; // 15mm
        $logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);

        $this->Cell(0,0, $logo, 0, 0, 'R');
        
        // $this->Cell(0, 0, '53 Ubi Ave 3 #01-01 Travelite Building, Singapore 408863', 0, 0, 'C');
        // $this->Ln();
        // $this->Cell(0, 0,'Tel:(65) 6785 8000      Fax:(65) 6785 7000      Email: jonfresh@singnet.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // // Page number
        // if (empty($this->pagegroups)) {
        //     $pagenumtxt = 'Page '.' '.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
        // } else {
        //     $pagenumtxt = 'Page '.' '.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
        // }
        // $this->SetFont('helvetica', 'B', 10);
        // $this->Cell(0, 5, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // $this->Ln(1);
        // $this->SetFont('helvetica', 'B', 8);
        // $this->Cell(170, 15, 'Powered by ', 0, false, 'C', 0, '', 0, false, 'T', 'M');


  //       $logoX = 110; // 186mm. The logo will be displayed on the right side close to the border of the page
        // $logoFileName = base_url()."/assets/uploads/logos/Audit_logo.png";
        // $logoWidth = 10; // 15mm
        // // $logo = $this->Image($logoFileName, $logoX, $this->GetY() + 5, $logoWidth);

        // $this->Ln(4);
  //       $this->SetFont('helvetica', 'B', 8);
  //       $this->Cell(0, 15, 'enquiry@acumenbizcorp.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //$this->GetY()
   }
   
}

class NOFOOTER_PDF extends TCPDF {
    public function Header() {
        $headerData = $this->getHeaderData();
        $this->writeHTMLCell(0, 0, 20, 7, $headerData['string'], 0, 0, false, "C", true);
   }

   public function Footer() {
        // Position at 25 mm from bottom
        // $this->SetY(-16);
        // $this->SetX(10);


        // // Set font
        // $this->SetFont('helvetica', 'B', 8);

        // $logoX = 20; // The logo will be displayed on the left side close to the border of the page
        // $logoFileName = base_url()."img/footer_img.png";
        // $logoWidth = 50; // 15mm
        // $logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);

        // $this->Cell(0,0, $logo, 0, 0, 'R');
        
        // $this->Cell(0, 0, '53 Ubi Ave 3 #01-01 Travelite Building, Singapore 408863', 0, 0, 'C');
        // $this->Ln();
        // $this->Cell(0, 0,'Tel:(65) 6785 8000      Fax:(65) 6785 7000      Email: jonfresh@singnet.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // // Page number
        // if (empty($this->pagegroups)) {
        //     $pagenumtxt = 'Page '.' '.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
        // } else {
        //     $pagenumtxt = 'Page '.' '.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
        // }
        // $this->SetFont('helvetica', 'B', 10);
        // $this->Cell(0, 5, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // $this->Ln(1);
        // $this->SetFont('helvetica', 'B', 8);
        // $this->Cell(170, 15, 'Powered by ', 0, false, 'C', 0, '', 0, false, 'T', 'M');


  //       $logoX = 110; // 186mm. The logo will be displayed on the right side close to the border of the page
        // $logoFileName = base_url()."/assets/uploads/logos/Audit_logo.png";
        // $logoWidth = 10; // 15mm
        // // $logo = $this->Image($logoFileName, $logoX, $this->GetY() + 5, $logoWidth);

        // $this->Ln(4);
  //       $this->SetFont('helvetica', 'B', 8);
  //       $this->Cell(0, 15, 'enquiry@acumenbizcorp.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //$this->GetY()
   }
   
}


