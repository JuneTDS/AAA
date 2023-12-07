<?php define( 'APPLICATION_LOADED', true );


class Cron_bank extends CI_Controller {

    

    function __construct()
    {
        parent::__construct();
        
        $this->load->library(array('session','parser','encryption'));
        $this->load->library(array('zip'));
        $this->load->model('bank/bank_model');

    }

    public function message($to = 'World') {
        echo "Hello {$to}!" . PHP_EOL;
    }

    // send email controller
    public function set_pic_reminder()
    {
        $day = date('d');
        $month = date('F');
        $year = date('Y');

        $next_month = date('F Y', strtotime('+1 month'));

        // $pic_info = $this->bank_model->get_bank_confirm_pic($month, $year);

        // $create_bc_list = $this->bank_model->get_create_bc_list($month, $year);

        $set_pic_flag       = $this->bank_model->get_set_pic_flag($next_month);
        $bank_auth_exist    = $this->bank_model->check_bank_auth_exist($next_month);

        $bc_id_list = array();

        // print_r($create_bc_list);
        if ($day >= 10 && $bank_auth_exist && $set_pic_flag)
        {
            $data = array(
                'confirm_month' => $next_month,
                'pic_id' => 68,  //set to RAYCE as default
            );

            $default_pic_info = $this->db->query('SELECT * from payroll_employee WHERE id ='.$data['pic_id']);

            $default_pic_info =  $default_pic_info->result();

            // print_r($data);

            $result = $this->bank_model->submit_bank_confirm_setting($data, null);

            $parse_data = array(
                'month'       => $next_month,
                'default_pic' => $default_pic_info[0]->name
            );

            $manager_email = "penny@aaa-global.com";
            $msg = file_get_contents('./application/modules/bank/email_templates/set_pic_reminder.html');
            
            $message = $this->parser->parse_string($msg, $parse_data);
            $subject = 'Set PIC Reminder - '.$next_month;
            $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");

            $receiver_email = array();
            // this is right query
            array_push($receiver_email, array("email" => $manager_email) );

            // array_push($receiver_email, array("email" => "xinyee@aaa-global.com") );

            //this is for testing purpose
            // array_push($receiver_email, array("email" => "xinyee@aaa-global.com") );

            // print_r($message);

            //SMA
            // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

            //SEND IN BLUE
            // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray
        
            $this->send_email($subject, $sender_email, $receiver_email, $message, "", "");      
        }

        // $parse_data = array(
        //     'pic_name'  => $pic_info[0]['first_name']." ".$pic_info[0]['last_name'],
        //     'month'      => $month
        // );

        // $pic_email = $pic_info[0]['email'];
        // if($pic_email == '"james@acumenbizcorp.com.sg"'){
        //     $pic_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$pic_email);
        // }
        
        // $manager_email = "penny@aaa-global.com";
        // $msg = file_get_contents('./application/modules/bank/email_templates/pic_bank_confirmation.html');
        // $message = $this->parser->parse_string($msg, $parse_data);

        // $subject = 'Bank Confirmation - '.$month." ".$year;
        // $this->sma->send_email($pic_email, $subject, $message,"" ,"" ,$_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/bank_confirmation('.$month.$year.').zip' ,$manager_email, "");
        // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

        // print_r($bc_id_list);
    }

    // send email controller
    public function auto_generate_bank_confirm()
    {
        $month = date('F');
        $year = date('Y');

        //for sql condition to retrieve bank confirmation report
        $year_month = date('Y-m');

        $pic_info = $this->bank_model->get_bank_confirm_pic($month, $year);

        $create_bc_list = $this->bank_model->get_create_bc_list($month, $year);
        $bc_id_list = array();


        foreach ($create_bc_list as $key => $create_bc) {
            $date = DateTime::createFromFormat('d F Y', $create_bc['year_end']);
            $fye_date = $date->format('Y-m-d');

            $send_date = date("Y-m-d");

           // echo $form_data['auth_date'];

            $data = array(
                'company_code' => $create_bc['company_code'],
                'bank_id' => $create_bc['bank_id'],
                'fye_date' => $fye_date,
                'confirm_status' => 1,
                'sent_on_date' => $send_date
            );

            $result = $this->bank_model->submit_bank_confirm($data);
            array_push($bc_id_list, $result);

        }

        foreach ($bc_id_list as $key => $bc_id) {
            $data = $this->generate_confirm_document($key+1, $bc_id);
            $this->zip->read_file($data["path"]);
        }

        if($create_bc_list)
        {
            $this->zip->archive($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/bank_confirmation('.$month.$year.').zip');
            // $this->zip->archive($_SERVER['DOCUMENT_ROOT'].'test_audit/pdf/document/bank_confirmation('.$month.$year.').zip');

            $parse_data = array(
                'pic_name'  => $pic_info[0]['first_name']." ".$pic_info[0]['last_name'],
                'month'      => $month
            );

            $pic_email = $pic_info[0]['email'];
            if($pic_email == '"james@acumenbizcorp.com.sg"'){
                $pic_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$pic_email);
            }
            
            $manager_email = "penny@aaa-global.com";
            $partner_email = "woellywilliam@acumenbizcorp.com.sg";

            $msg = file_get_contents('./application/modules/bank/email_templates/pic_bank_confirmation.html');
            $message = $this->parser->parse_string($msg, $parse_data);

            $subject = 'Bank Confirmation - '.$month." ".$year;

            $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");

            $receiver_email = array();

            // this the right query
            array_push($receiver_email, array("email" => $pic_email) );

            //this is for testing purpose
            // array_push($receiver_email, array("email" => "xinyee@aaa-global.com") );

            $item = array();
            $item['content'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/bank_confirmation('.$month.$year.').zip'));
            // $item['content'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'test_audit/pdf/document/bank_confirmation('.$month.$year.').zip'));
            $item['name'] = 'bank_confirmation('.$month.$year.').zip';
            $attachment = array(); 
            array_push($attachment, $item);

            // echo(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/audit/pdf/document/bank_confirmation(July2020).zip'));
            //SMA
            // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

            //SEND IN BLUE
            // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray

            //this the right query
            $this->send_email($subject, $sender_email, $receiver_email, $message, "", $attachment);

            // $this->sma->send_email($pic_email, $subject, $message,"" ,"" ,$_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/bank_confirmation('.$month.$year.').zip' , "", "");
            // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

            //get bank confirmation summary list-----------------------------------------------------------------------------------------------------------
            $report_summary = $this->bank_model->get_bank_confirm_report($year_month);

            if($report_summary != null)
            {
                $report_content = "";

                //loop summary of every bank
                foreach ($report_summary as $key => $each_bank) {
                    $report_content .= '<p>Bank: '.$each_bank['bank_name'].'<br/>Sent: '.$each_bank['bc_total'].'</p>';
                }

                $parse_report_data = array(
                    'pic_name'  => $pic_info[0]['first_name']." ".$pic_info[0]['last_name'],
                    'month'      => $month,
                    'report_content' => $report_content
                );

                //get report message template
                $report_msg = file_get_contents('./application/modules/bank/email_templates/bank_confirmation_report.html');
                

                $report_message = $this->parser->parse_string($report_msg, $parse_report_data);
               
                $cc_email = array();
                array_push($cc_email, array("email" => $manager_email), array("email" => $partner_email));

                $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");

                $receiver_email = array();
                // this is right query
                array_push($receiver_email, array("email" => $pic_email) );

                // array_push($receiver_email, array("email" => "xinyee@aaa-global.com") );

                //SMA
                // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

                //SEND IN BLUE
                // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray
            
                $this->send_email($subject, $sender_email, $receiver_email, $report_message, $cc_email, ""); 
                // $this->send_email($subject, $sender_email, $receiver_email, $report_message, "", ""); 

                // $this->sma->send_email($pic_email, $subject, $report_message,"" ,"" ,"" , $cc_email, "");
            }

        
        }
        
        // print_r($bc_id_list);
    }

    public function generate_confirm_document($file_counter = 1, $confirm_id="")
    {

        // $po_list = $this->products_model->getPdfData(); // retrieve all po from database
        if(!empty($this->input->post('confirm_id')))
        {
            $confirm_id = $this->input->post('confirm_id');
        }
        
        // print_r($confirm_id);

        $document_info_query = $this->db->query("SELECT ba.*, bc.*, c.company_name, bank.bank_name, bank.add_line1, bank.add_line2, bank.add_line3, f.id as firm_id, f.name as firm_name from audit_bank_confirm bc, audit_bank_auth ba
                                                LEFT JOIN client c on ba.company_code = c.company_code 
                                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
                                                LEFT JOIN firm f on ba.firm_id = f.id
                                                where ba.id = bc.bank_auth_id and bc.id = ".$confirm_id);
        $document_info_query = $document_info_query->result_array();

        foreach ($document_info_query as $key => $value) {
            $document_info_query[$key]['company_name'] = $this->encryption->decrypt($document_info_query[$key]['company_name']);
        }



        
        $header_company_info = $this->write_header($document_info_query[0]['firm_id'], false);
        $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title = "Bank Confirmation";
        $obj_pdf->SetTitle($title);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info, $tc=array(0,0,0), $lc=array(0,0,0));


        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('times');
        $obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        // $obj_pdf->SetFooterMargin(10);
        $obj_pdf->SetMargins(28, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $obj_pdf->SetAutoPageBreak(FALSE, 18);
        $obj_pdf->SetFont('times', '', 9);
        $obj_pdf->setFontSubsetting(false);
        $obj_pdf->setY(33);

        $obj_pdf->startPageGroup();

        $obj_pdf->AddPage();

        $counter = 0;


        $do_template_query = $this->db->query("select * from audit_doc_master_template where id = 2");

        $do_template_query = $do_template_query->result_array();

        // $this->load->helper('pdf_helper');

        $pattern = "/{{[^}}]*}}/";
        $subject = $do_template_query[0]["document_content"];
        preg_match_all($pattern, $subject, $matches);

        $contents_info = $do_template_query[0]["document_content"];
                
        
        $detail = $document_info_query[0];

        // $company_add = $this->write_address($detail['street_name'], $detail["unit_no1"], $detail["unit_no2"], $detail['building_name'], $detail["postal_code"], 'letter');

        // $firm_add = $this->write_address($detail['firm_street_name'], $detail["firm_unit_no1"], $detail["firm_unit_no2"], $detail['firm_building_name'], $detail["firm_postal_code"], 'letter');

        $bank_add = $detail['add_line1'].', <br/>'.$detail['add_line2'].', <br/>'.$detail['add_line3'].'.';
                  
        $toggle_array = $matches[0];

        if(count($toggle_array) != 0)
        {
            for($r = 0; $r < count($toggle_array); $r++)
            {
                $string1 = (str_replace('{{', '',$toggle_array[$r]));
                $string2 = (str_replace('}}', '',$string1));

 
                
                if($string2 == "Send date")
                {
                    $replace_string = $toggle_array[$r];

                    $date = DateTime::createFromFormat('Y-m-d', $detail["sent_on_date"]);
                    $new_date = $date->format('d F Y');   
                   
                    $content = $new_date;        
                }
                elseif($string2 == "Bank name")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["do_number"];
                    $content = $detail['bank_name'];
                }
                elseif($string2 == "Bank address")
                {
                    $replace_string = $toggle_array[$r];

                    // $date = DateTime::createFromFormat('Y-m-d', $detail["auth_date"]);
                    // $auth_date = $date->format('d F Y');

                    $content = $bank_add;
                }
                elseif($string2 == "Client name")
                {
                    $replace_string = $toggle_array[$r];

                    $content = $detail["company_name"];
                }
                elseif($string2 == "Bank auth date")
                {
                    $replace_string = $toggle_array[$r];

                    $date = DateTime::createFromFormat('Y-m-d', $detail["auth_date"]);
                    $auth_date = $date->format('d F Y');

                    // $content = $document_info_query[0]["order_code"];
                    $content = $auth_date;
                }
                // elseif($string2 == "Company name")
                // {
                //     $replace_string = $toggle_array[$r];

                //     if($detail['former_name'] == "") 
                //     {
                //         $content = $detail['company_name'];
                //     }
                //     else
                //     {
                //         $content = $detail['company_name'].'('.$detail['former_name'].')';
                //     }    
                // }
                elseif($string2 == "Year end date")
                {
                    $replace_string = $toggle_array[$r];
           
                    $date = DateTime::createFromFormat('Y-m-d', $detail["fye_date"]);
                    $fye_date = $date->format('d F Y');

                    $content = $fye_date;
                }
                elseif($string2 == "Firm name")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["method"];
                    $content = $detail['firm_name'];
                }

                $contents_info = str_replace($replace_string, $content, $contents_info);


            }
        }

            $new_content_info = $contents_info;
       

            $obj_pdf->writeHTML($new_content_info, true, false, false, false, '');

        
            $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Bank Confirmation ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf', 'F');
            // $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'test_audit/pdf/document/Bank Confirmation ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf', 'F');


            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

            // $link = 'https://'. $_SERVER['SERVER_NAME'] .'/generate_do/pdf/document/Delivery Order ('.DATE("Y-m-d",now()).').pdf';
            $link = 'http://'. $_SERVER['SERVER_NAME'] .'/audit/pdf/document/Bank Confirmation ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf';
            // $link = 'http://'. $_SERVER['SERVER_NAME'] .'/test_audit/pdf/document/Bank Confirmation ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf';


            $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> $_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Bank Confirmation ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf');
            // $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> $_SERVER['DOCUMENT_ROOT'].'test_audit/pdf/document/Bank Confirmation ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf');
            // kill session for doc_cus_id array
            $this->session->unset_userdata('doc_cus_id');
            $this->session->set_userdata(array('doc_cus_id' =>array())); // create new session back for session 'doc_cus_id'

            echo json_encode($data);
            return $data;
            
    }

    public function follow_up_bank_auth()
    {
        $next_month = date('F Y', strtotime("+1 month"));
        // echo $next_month;
        $check_month  = date('m');

        if($check_month == "02")
        {
            $five_days_before = "23";
        }
        else if($check_month == "04" || $check_month == "06" || $check_month == "09" || $check_month == "11")
        {
            $five_days_before = "25";
        }
        else
        {
            $five_days_before = "26";
        }

        //check if today is five days before next month
        if(date('d') == $five_days_before)
        {
            $follow_up_ba_list = $this->bank_model->get_follow_up_ba_list($next_month);
            // print_r($follow_up_ba_list);

             

            if(count($follow_up_ba_list) > 0)
            {
                $msg = file_get_contents('./application/modules/bank/email_templates/no_attachment_ba_report.html');
                
                $table = '<table><tr><th>No. </th><th>Client</th><th>Bank name</th><th>Status</th><th>FYE</th></tr>';

                for($c=0 ; $c<count($follow_up_ba_list) ; $c++)
                {
                    $table .= '<tr>';

                    $table .= '<td>';
                    $table .= $c + 1;
                    $table .= '</td>';

                    $table .= '<td>';
                    $table .= $follow_up_ba_list[$c]->company_name;
                    $table .= '</td>';

                    $table .= '<td>';
                    $table .= $follow_up_ba_list[$c]->bank_name;
                    $table .= '</td>';

                    $table .= '<td>';
                    $table .= $follow_up_ba_list[$c]->status;
                    $table .= '</td>';

                    $table .= '<td>';
                    $table .= $follow_up_ba_list[$c]->year_end;
                    $table .= '</td>';

                    $table .= '</tr>';
                }

                $table .= '</table>';

                $parse_data = array(
                    'report_content'  => $table
                );

                $message = $this->parser->parse_string($msg, $parse_data);

                $subject = 'Bank Authorization Follow Up - '.$next_month;

                $manager_email = "penny@aaa-global.com";
                $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");
                $receiver_email = array();

                // this is right query
                array_push($receiver_email, array("email" => $manager_email) );

                //this is for testing purpose
                // array_push($receiver_email, array("email" => "xinyee@aaa-global.com") );

                //SMA
                // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

                //SEND IN BLUE
                // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray
            
                $this->send_email($subject, $sender_email, $receiver_email, $message, "", "");      

            }
        }
        else 
        {
            echo "Not today!";
        }

        
    }

    public function send_client_bank_auth_summary()
    {
        $check_month  = date('F Y');

        // $check_month = "March 2021";

        echo $check_month;

        $no_bank_auth_list  = $this->bank_model->get_no_bank_auth_list($check_month);
        $existing_bank_auth = $this->bank_model->get_this_month_bank_auth($check_month);

        // $no_bank_auth_list  = $this->bank_model->get_no_bank_auth_list("March 2021");
        // $existing_bank_auth = $this->bank_model->get_this_month_bank_auth("March 2021");

        usort($no_bank_auth_list, function($a, $b) {return strcmp($a->company_name, $b->company_name);});
        usort($existing_bank_auth, function($a, $b) {return strcmp($a->company_name, $b->company_name);});
        // $existing_bank_auth = array();

        if(count($no_bank_auth_list) > 0 || count($existing_bank_auth) > 0)
        {
            $msg = file_get_contents('./application/modules/bank/email_templates/client_bank_auth_summary_report.html');
            
            $no_bank_auth_table = '<table>
                        <tr>
                            <th>No. </th>
                            <th>Client</th>
                        </tr>';

            if(count($no_bank_auth_list) > 0)
            {
                for($c=0 ; $c<count($no_bank_auth_list) ; $c++)
                {
                    $no_bank_auth_table .= '<tr>';

                    $no_bank_auth_table .= '<td>';
                    $no_bank_auth_table .= $c + 1;
                    $no_bank_auth_table .= '</td>';

                    $no_bank_auth_table .= '<td>';
                    $no_bank_auth_table .= $no_bank_auth_list[$c]->company_name;
                    $no_bank_auth_table .= '</td>';

                    $no_bank_auth_table .= '</tr>';
                }
            }
            else
            {
                $no_bank_auth_table .= '<tr><td colspan="2">No record(s)</td></tr>';
            }

            

            $no_bank_auth_table .= '</table>';

            $existing_bank_auth_table = '<table>
                        <tr>
                            <th>No. </th>
                            <th>Client</th>
                            <th>Bank(s)</th>
                        </tr>';

            if(count($existing_bank_auth) > 0)
            {

                for($c=0 ; $c<count($existing_bank_auth) ; $c++)
                {
                    $existing_bank_auth_table .= '<tr>';

                    $existing_bank_auth_table .= '<td>';
                    $existing_bank_auth_table .= $c + 1;
                    $existing_bank_auth_table .= '</td>';

                    $existing_bank_auth_table .= '<td>';
                    $existing_bank_auth_table .= $existing_bank_auth[$c]->company_name;
                    $existing_bank_auth_table .= '</td>';

                    $existing_bank_auth_table .= '<td>';
                    $existing_bank_auth_table .= $existing_bank_auth[$c]->bank_name;
                    $existing_bank_auth_table .= '</td>';

                    $existing_bank_auth_table .= '</tr>';
                }
            }
             else
            {
                $existing_bank_auth_table .= '<tr><td colspan="3">No record(s)</td></tr>';
            }

            $existing_bank_auth_table .= '</table>';


            $parse_data = array(
                'no_bank_auth_report_content'        => $no_bank_auth_table,
                'existing_bank_auth_report_content'  => $existing_bank_auth_table
            );

            $message = $this->parser->parse_string($msg, $parse_data);

            $subject = 'Bank Authorization For The Month - '.$check_month;

            $manager_email = "penny@aaa-global.com";
            $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");
            $receiver_email = array();

            // this is right query
            array_push($receiver_email, array("email" => $manager_email) );

            //this is for testing purpose
            // array_push($receiver_email, array("email" => "xinyee@aaa-global.com") );

            //SMA
            // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

            //SEND IN BLUE
            // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray
        
            $this->send_email($subject, $sender_email, $receiver_email, $message, "", "");      

        }
    }



    public function send_email($subject, $sender_email, $receiver_email, $content, $cc = null, $attachment = null)
    {
        // $subject-string, $sender_email-object, $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray

        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

        $apiInstance = new SendinBlue\Client\Api\SMTPApi(
          // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
          // This is optional, `GuzzleHttp\Client` will be used as default.
          new GuzzleHttp\Client(),
          $config
        );

        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
        $sendSmtpEmail['subject'] = $subject;
        // $sender_email = $from_email;
        $sendSmtpEmail['sender'] = $sender_email;
        $sendSmtpEmail['to'] = $receiver_email;
        $sendSmtpEmail['htmlContent'] = $content;
        if($cc != null)
        {
            $sendSmtpEmail['cc'] = $cc;
        }
        // $attachment['content'] = base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/AA-20200013.pdf'));
        // $attachment['name'] = "AA-20200013.pdf";
        //array_push($pdfDocPath, json_decode($email_queue_info[$i]['attachment'], true));
        if($attachment != null)
        {
             $sendSmtpEmail['attachment'] = $attachment;
        }
       
        //print_r($sendSmtpEmail);

        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            //print_r($result);
            if ($result) 
            {
            // $email_queue['sended'] = 1;
            // $email_queue['sendInBlueResult'] = $result;
            // $this->db->update("email_queue",$email_queue,array("id" => $email_queue_info[$i]['id']));
                echo 'Your Email has successfully been sent.';
            }
        } catch (Exception $e) {
            echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }
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