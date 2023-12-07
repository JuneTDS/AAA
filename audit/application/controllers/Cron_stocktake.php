<?php define( 'APPLICATION_LOADED', true );


class Cron_stocktake extends CI_Controller {

    

    function __construct()
    {
        parent::__construct();
        
        $this->load->library(array('session','parser','encryption'));
        $this->load->library(array('zip'));
        $this->load->model('stocktake/stocktake_model');
        $this->load->model('client/client_model');

    }

    public function message($to = 'World') {
        echo "Hello {$to}!" . PHP_EOL;
    }

    // // send email controller
    // public function send_stocktake_reminder()
    // {
    //     $next_month = date('d F Y', strtotime("+30 days"));
    //     // $next_month = date('d F', strtotime("2021-03-01+30 days"));
    //     // echo $next_month.'here next month';

    //     // $pic_info = $this->bank_model->get_bank_confirm_pic(date('F'), date('Y'));

    //     // get all client which FYE date is 1 month after today
    //     $get_due_client = $this->client_model->get_stocktake_client_list($next_month);
    //     // print_r($get_due_client);   

    //     // $create_bc_list = $this->bank_model->get_create_bc_list($month, $year);
    //     $bc_id_list = array();

    //     $check_no_email_counter = 0;

    //     // print_r($create_bc_list);
    //     foreach ($get_due_client as $key => $client) {
    //         // $date = DateTime::createFromFormat('d F yy', $create_bc['year_end']);
    //         $client_email = $client['email'];
    //         $fye_date = $client['year_end'];

    //         //comment this because dont send to client first

    //         // if($client_email != null)
    //         // {
                
    //         //     // $client_email = $client['email'];
    //         //     $send_date = date("d F Y");
    //         //     $client_detail = $this->client_model->getClientbyCode($client['company_code']);
    //         //     $client_name = $client_detail->company_name;
    //         //     $client_address = $this->write_address($client_detail->street_name, $client_detail->unit_no1, $client_detail->unit_no2, $client_detail->building_name, $client_detail->postal_code, 'letter');
    //         //     $client_servicing_firm = $this->client_model->getFirmbyCode($client['company_code']);

    //         //     $parse_data = array(
    //         //         'today_date'  => $send_date,
    //         //         'client_name'      => $client_name,
    //         //         'client_address'   => $client_address,
    //         //         'fye_date'         => $fye_date,
    //         //         'firm_name'        => $client_servicing_firm
    //         //     );

    //         //     $db_data = array(
    //         //         'company_code'     => $client['company_code'],
    //         //         'fye_date'         => DateTime::createFromFormat('d F Y', $fye_date )->format('Y-m-d'),
    //         //         'sent_on_date'     => date("Y-m-d")
    //         //     );


    //         //     $manager_email = "penny@aaa-global.com";
    //         //     $partner_email = "woellywilliam@acumenbizcorp.com.sg";
                
    //         //     // $cc_email = array();
    //         //     // array_push($cc_email, $manager_email, $partner_email);
    //         //     $msg = file_get_contents('./application/modules/stocktake/email_templates/stocktake_reminder.html');


    //         //     $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");
    //         //     $subject = 'Physical Inventory Count Observation - '.$client_name;
    //         //     $report_message = $this->parser->parse_string($msg, $parse_data);

    //         //     $cc_email = array();
    //         //     array_push($cc_email, array("email" => $manager_email), array("email" => $partner_email));

    //         //     $receiver_email = array();

    //         //     //this is the right query
    //         //     array_push($receiver_email, array("email" => $client_email) );

    //         //     // this is for testing
    //         //     // array_push($receiver_email, array("email" => "xinyee@aaa-global.com") );



    //         //     //SMA
    //         //     // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

    //         //     //SEND IN BLUE
    //         //     // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray

    //         //     //right query
    //         //     $this->send_email($subject, $sender_email, $receiver_email, $report_message , $cc_email, "");

    //         //     // testing query
    //         //     // $this->send_email($subject, $sender_email, $receiver_email, $report_message , "", "");

    //         // }
    //         // else
    //         // {
                
    //             $check_no_email_counter++;
    //             $client_email = $client['email'];
    //             $send_date = date("d F Y");
    //             // $send_date = date('d F Y', strtotime("+1 days"));
    //             $client_detail = $this->client_model->getClientbyCode($client['company_code']);
    //             $client_name = $client_detail->company_name;
    //             $client_address = $this->write_address($client_detail->street_name, $client_detail->unit_no1, $client_detail->unit_no2, $client_detail->building_name, $client_detail->postal_code, 'letter');
    //             $client_servicing_firm = $this->client_model->getFirmbyCode($client['company_code']);

    //             $parse_data = array(
    //                 'today_date'       => $send_date,
    //                 'client_name'      => $client_name,
    //                 'client_address'   => $client_address,
    //                 'fye_date'         => $fye_date,
    //                 'firm_name'        => $client_servicing_firm->firm_name,
    //                 'firm_id'          => $client_servicing_firm->servicing_firm
    //             );

    //             $db_data = array(
    //                 'company_code'     => $client['company_code'],
    //                 'fye_date'         => DateTime::createFromFormat('d F Y', $fye_date )->format('Y-m-d'),
    //                 'sent_on_date'     => date("Y-m-d")
    //             );


    //             // $manager_email = "penny@aaa-global.com";
    //             // $partner_email = "woellywilliam@acumenbizcorp.com.sg";
                
    //             // $cc_email = array();
    //             // array_push($cc_email, $manager_email, $partner_email);
    //             // $msg = file_get_contents('./application/modules/client/email_templates/stocktake_reminder.html');
    //             // $message = $this->parser->parse_string($msg, $parse_data);

    //             // $subject = 'Physical Inventory Count Observation - '.$client_name;
    //             // $this->sma->send_email($client_email, $subject, $message,"" ,"" ,"", $cc_email);
    //             $data = $this->generate_reminder_document($key+1, $parse_data);
    //             $this->zip->read_file($data["path"]);

    //         // }

    //         $result = $this->stocktake_model->submit_reminder_record($db_data);
           

    //     }

    //     // foreach ($bc_id_list as $key => $bc_id) {
    //     //     $data = $this->generate_confirm_document($key+1, $bc_id);
    //     //     $this->zip->read_file($data["path"]);
    //     // }
    //     if ($check_no_email_counter > 0)
    //     {
    //         $this->zip->archive($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/stocktake_reminder('.substr($next_month, 3).').zip');

    //         $email_parse_data = array(
    //                         // 'pic_name'       => "",
    //                         'month'          => substr($next_month, 3)
    //                     );

            
    //         // $pic_email = $pic_info[0]['email'];
    //         // if($pic_email == '"james@acumenbizcorp.com.sg"'){
    //         //     $pic_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$pic_email);
    //         // }
            
    //         $manager_email = "penny@aaa-global.com";
    //         $msg = file_get_contents('./application/modules/stocktake/email_templates/stocktake_no_email.html');
    //         $message = $this->parser->parse_string($msg, $email_parse_data);

    //         $subject = 'Physical Inventory Count Observation  - '.substr($next_month, 3);
    //         $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");

    //         $cc_email = array();
    //         array_push($cc_email, array("email" => "xinyee@aaa-global.com"));

    //         $receiver_email = array();
            

    //         //this the right query
    //         array_push($receiver_email, array("email" => $manager_email));

    //         //this is for testing purpose
    //         // array_push($receiver_email, array("email" => "xinyee@aaa-global.com"));
            

    //         $item = array();
    //         $item['content'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/stocktake_reminder('.substr($next_month, 3).').zip'));
    //         $item['name'] = 'stocktake_reminder('.substr($next_month, 3).').zip';
    //         $attachment = array(); 
    //         array_push($attachment, $item);



    //         //SMA
    //         // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

    //         //SEND IN BLUE
    //         // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray

    //         //this the right query
    //         $this->send_email($subject, $sender_email, $receiver_email, $message, "", $attachment);

    //         //this is for testing purpose
    //         // $this->send_email($subject, $sender_email, $receiver_email, $message, "" , $attachment);
    //     }
        

    //     // print_r($bc_id_list);
    // }

    public function generate_reminder_document($file_counter = 1, $parse_data)
    {

        // $po_list = $this->products_model->getPdfData(); // retrieve all po from database
        // if(!empty($this->input->post('confirm_id')))
        // {
        //     $confirm_id = $this->input->post('confirm_id');
        // }
        
        // print_r($confirm_id);

        // $document_info_query = $this->db->query("SELECT ba.*, bc.*, c.company_name, bank.bank_name, bank.add_line1, bank.add_line2, f.id as firm_id, f.name as firm_name from audit_bank_confirm bc, audit_bank_auth ba
        //                                         LEFT JOIN client c on ba.company_code = c.company_code 
        //                                         LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
        //                                         LEFT JOIN firm f on ba.firm_id = f.id
        //                                         where ba.id = bc.bank_auth_id and bc.id = ".$confirm_id);
        // $document_info_query = $document_info_query->result_array();

        // foreach ($document_info_query as $key => $value) {
        //     $document_info_query[$key]['company_name'] = $this->encryption->decrypt($document_info_query[$key]['company_name']);
        // }



        
        $header_company_info = $this->write_header($parse_data['firm_id'], false);
        $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title = "Stocktake Reminder";
        $obj_pdf->SetTitle($title);
        // $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='<table style="width:100%; height: auto;"><tr style="text-align: center;"><td style="width:80%; height: auto;" align="center"></td></tr></table>', $tc=array(0,0,0), $lc=array(0,0,0));
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


        $do_template_query = $this->db->query("select * from audit_doc_master_template where id = 4");

        $do_template_query = $do_template_query->result_array();

        // $this->load->helper('pdf_helper');

        $pattern = "/{{[^}}]*}}/";
        $subject = $do_template_query[0]["document_content"];
        preg_match_all($pattern, $subject, $matches);

        $contents_info = $do_template_query[0]["document_content"];
                
        
        // $detail = $document_info_query[0];

        // $company_add = $this->write_address($detail['street_name'], $detail["unit_no1"], $detail["unit_no2"], $detail['building_name'], $detail["postal_code"], 'letter');

        // $firm_add = $this->write_address($detail['firm_street_name'], $detail["firm_unit_no1"], $detail["firm_unit_no2"], $detail['firm_building_name'], $detail["firm_postal_code"], 'letter');

        // $bank_add = $detail['add_line1'].', <br/>'.$detail['add_line2'].'.';
                  
        $toggle_array = $matches[0];

        if(count($toggle_array) != 0)
        {
            for($r = 0; $r < count($toggle_array); $r++)
            {
                $string1 = (str_replace('{{', '',$toggle_array[$r]));
                $string2 = (str_replace('}}', '',$string1));

 
                
                if($string2 == "today_date")
                {
                    $replace_string = $toggle_array[$r];

                    // $date = DateTime::createFromFormat('Y-m-d', $detail["sent_on_date"]);
                    // $new_date = $date->format('d F Y');   
                   
                    $content = $parse_data['today_date'];        
                }
                elseif($string2 == "client_name")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["do_number"];
                    $content = $parse_data['client_name'];
                }
                elseif($string2 == "client_address")
                {
                    $replace_string = $toggle_array[$r];

                    // $date = DateTime::createFromFormat('Y-m-d', $detail["auth_date"]);
                    // $auth_date = $date->format('d F Y');

                    $content = $parse_data['client_address'];
                }
                elseif($string2 == "firm_name")
                {
                    $replace_string = $toggle_array[$r];

                    $content = $parse_data["firm_name"];
                }
                elseif($string2 == "fye_date")
                {
                    $replace_string = $toggle_array[$r];

                    // $date = DateTime::createFromFormat('Y-m-d', $detail["auth_date"]);
                    // $auth_date = $date->format('d F Y');

                    // $content = $document_info_query[0]["order_code"];
                    $content = date('d F Y', strtotime("+30 days"));
                    // $content = date('d F Y', strtotime("2021-03-01+30 days"));
                }

                $contents_info = str_replace($replace_string, $content, $contents_info);


            }
        }

            $new_content_info = $contents_info;
       

            $obj_pdf->writeHTML($new_content_info, true, false, false, false, '');

        
            $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Stocktake Reminder ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf', 'F');

            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

            // $link = 'https://'. $_SERVER['SERVER_NAME'] .'/generate_do/pdf/document/Delivery Order ('.DATE("Y-m-d",now()).').pdf';
            $link = 'http://'. $_SERVER['SERVER_NAME'] .'/audit/pdf/document/Stocktake Reminder ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf';

            $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> $_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Stocktake Reminder ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf');

            // kill session for doc_cus_id array
            $this->session->unset_userdata('doc_cus_id');
            $this->session->set_userdata(array('doc_cus_id' =>array())); // create new session back for session 'doc_cus_id'

            echo json_encode($data);
            return $data;
            
    }

     // send email controller
    public function send_stocktake_reminder()
    {
        // $next_month = date('d F Y', strtotime("+30 days"));
        $next_month = date('d F', strtotime("+30 days"));
        // echo $next_month.'here next month';

        // $pic_info = $this->bank_model->get_bank_confirm_pic(date('F'), date('Y'));

        // get all client which FYE date is 1 month after today
        $get_due_client = $this->client_model->get_stocktake_client_list($next_month);
        // print_r($get_due_client);   

        $check_no_email_counter = 0;
        $table = '<table><tr><th>No. </th><th>Client</th></tr>';

        // print_r($create_bc_list);
        foreach ($get_due_client as $key => $client) {
            // $date = DateTime::createFromFormat('d F yy', $create_bc['year_end']);
            // $client_email = $client['email'];
            // $fye_date = $client['year_end'];       
            
            // $check_no_email_counter++;
            // $client_email = $client['email'];
            // $send_date = date("d F Y");
            // $send_date = date('d F Y', strtotime("+1 days"));
            $client_detail = $this->client_model->getClientbyCode($client['company_code']);
            $client_name = $client_detail->company_name;
            // $client_address = $this->write_address($client_detail->street_name, $client_detail->unit_no1, $client_detail->unit_no2, $client_detail->building_name, $client_detail->postal_code, 'letter');
            // $client_servicing_firm = $this->client_model->getFirmbyCode($client['company_code']);

            // $parse_data = array(
            //     'today_date'       => $send_date,
            //     'client_name'      => $client_name,
            //     'client_address'   => $client_address,
            //     'fye_date'         => $fye_date,
            //     'firm_name'        => $client_servicing_firm->firm_name,
            //     'firm_id'          => $client_servicing_firm->servicing_firm
            // );

            $db_data = array(
                'company_code'     => $client['company_code'],
                'fye_date'         => date('Y-m-d', strtotime("+30 days")),
                'sent_on_date'     => date("Y-m-d")
            );

            // $data = $this->generate_reminder_document($key+1, $parse_data);
            // $this->zip->read_file($data["path"]);


            $result = $this->stocktake_model->submit_reminder_record($db_data);
            // $this->db->insert('audit_stocktake_arrangement', $data); 

            $sa_data = array(
                'reminder_id'      => $result
            );
            $result = $this->stocktake_model->add_stocktake_arrangement($sa_data);

            $sa_info_data = array(
                'stocktake_arrangement_id'      => $result
            );
            $result = $this->stocktake_model->insert_stocktake_arrangement_info($sa_info_data);

            $table .= '<tr>';

            $table .= '<td>';
            $table .= $key + 1;
            $table .= '</td>';

            $table .= '<td>';
            $table .= $client_name;
            $table .= '</td>';

            $table .= '</tr>';
           

        }

        // foreach ($bc_id_list as $key => $bc_id) {
        //     $data = $this->generate_confirm_document($key+1, $bc_id);
        //     $this->zip->read_file($data["path"]);
        // }
        // if ($check_no_email_counter > 0)
        // {

        $table .= '</table>';

        $email_parse_data = array(
                        // 'pic_name'       => "",
                        'month'          => substr($next_month, 3),
                        'report_content'  => $table
                    );


        
        // $pic_email = $pic_info[0]['email'];
        // if($pic_email == '"james@acumenbizcorp.com.sg"'){
        //     $pic_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$pic_email);
        // }
        
        $manager_email = "penny@aaa-global.com";
        $msg = file_get_contents('./application/modules/stocktake/email_templates/stocktake_pic_reminder.html');
        $message = $this->parser->parse_string($msg, $email_parse_data);

        $subject = 'Physical Inventory Count Observation  - '.substr($next_month, 3);
        $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");

        $cc_email = array();
        array_push($cc_email, array("email" => "xinyee@aaa-global.com"));

        $receiver_email = array();
        

        //this the right query
        array_push($receiver_email, array("email" => $manager_email));

        //this is for testing purpose
        // array_push($receiver_email, array("email" => "xinyee@aaa-global.com"));
        

        // $item = array();
        // $item['content'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/stocktake_reminder('.substr($next_month, 3).').zip'));
        // $item['name'] = 'stocktake_reminder('.substr($next_month, 3).').zip';
        // $attachment = array(); 
        // array_push($attachment, $item);



        //SMA
        // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

        //SEND IN BLUE
        // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray

        //this the right query
        if(count($get_due_client) > 0)
        {
            $this->send_email($subject, $sender_email, $receiver_email, $message, "", "");

            //this is for testing purpose
            // $this->send_email($subject, $sender_email, $receiver_email, $message, "" , $attachment);
        }
        

        //this is for testing purpose
        // $this->send_email($subject, $sender_email, $receiver_email, $message, "" , $attachment);
        // }
        
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
            $address = $street_name . $comma . $br . 'Singapore ' . $postal_code;
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

}

class NOFOOTER_PDF extends TCPDF {
    public function Header() {
        $headerData = $this->getHeaderData();
        $this->writeHTMLCell(0, 0, 20, 12, $headerData['string'], 0, 0, false, "C", true);
   }

   public function Footer() {
      
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

   }
   
}

