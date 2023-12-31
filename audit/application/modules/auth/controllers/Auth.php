<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->lang->load('auth');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model('auth/auth_model');
        $this->load->library('ion_auth');
        $this->load->library('session');
    }

    function index()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    function check_session(){

        $ci = & get_instance();
        $SessTimeLeft    = 0;
        $SessExpTime     = $ci->config->config["sess_expiration"];
        $CurrTime        = time();
        $lastActivity = $this->session->userdata['last_activity'];
        $SessTimeLeft = ($SessExpTime - ($CurrTime - $lastActivity))*1000;
        echo ($SessTimeLeft);
    }

    function change_session(){
        $this->session->set_userdata(array(
                'last_activity' => time()
        ));
    }

    function client()
    {
        if (!$this->loggedIn) {
            redirect('login');
        }
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => "Access Client Users"));
        $meta = array('page_title' => "Access Client Users", 'bc' => $bc, 'page_name' => "Access Client Users");

        if (isset($_POST['search'])) 
        {
            $term = $_POST['search'];
            
        }

        if($term != null)
        {
            $this->db->select($this->db->dbprefix('users').".id as id, first_name, last_name,".$this->db->dbprefix('users').".email," . $this->db->dbprefix('groups') . ".description, active");
            $this->db->from("users");
            $this->db->join('groups', 'users.group_id = groups.id', 'inner');
            //->join('firm', 'firm.user_id = "'.$this->session->userdata("user_id").'"', 'left')
            $this->db->join('user_client as a', 'a.user_admin_code_id = "'.$this->session->userdata("user_admin_code_id").'"', 'inner');

            $this->db->group_start();
                $this->db->or_like('first_name',$term);
                $this->db->or_like('last_name',$term);
                $this->db->or_like('email',$term);
            $this->db->group_end();
            $this->db->where('users.user_deleted = 0');
            $this->db->where('users.group_id = 4');
            $this->db->group_by('users.id');
        }
        else
        {
            $this->db->select($this->db->dbprefix('users').".id as id, first_name, last_name,".$this->db->dbprefix('users').".email," . $this->db->dbprefix('groups') . ".description, active")
            ->from("users")
            ->join('groups', 'users.group_id = groups.id', 'inner')
            //->join('firm', 'firm.user_id = "'.$this->session->userdata("user_id").'"', 'left')
            ->join('user_client as a', 'a.user_admin_code_id = "'.$this->session->userdata("user_admin_code_id").'"', 'inner')
            ->where('users.user_deleted = 0')
            ->where('users.group_id = 4')
            ->group_by('users.id');
        }
        

        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            $this->data["user"] = $data;
            //echo $this->data;
        }

        $this->db->select("*")
            ->from("users")
            ->where('id = "'.$this->session->userdata("user_id").'"');

        $user = $this->db->get();
        $user = $user->result_array();

        $this->data["no_of_user"] = $user[0]["no_of_user"];
        $this->data["total_no_of_user"] = $user[0]["total_no_of_user"];

        $this->page_construct('auth/client_index', $meta, $this->data);
    }

    function get_manager_name()
    {
        //$ci =& get_instance();

        $query = 'SELECT users.id, users.last_name, users.first_name, users.group_id FROM users left join user_firm as a on a.user_id = "'.$this->session->userdata("user_id").'" left join user_firm as b on b.firm_id = a.firm_id where b.user_id = users.id AND users.id != 1 AND  users.user_deleted = 0 AND users.active = 1 AND users.group_id = 5 GROUP BY users.id';

        $result = $this->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("Users not found.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['first_name'] != null)
                {
                    $res[$row['id']] = $row['last_name']." ".$row['first_name'];
                }
              
            }
            //$res = json_decode($res);
            // if($_SESSION['group_id'] != 2 && $_SESSION['group_id'] != 6)
            // {
            //     $selected_user_name = $this->session->userdata("user_id");
            // }
            // else
            // {
            //     $ci =& get_instance();
            //     $selected_user_name = $ci->session->userdata('claim_user_id');
            //     $ci->session->unset_userdata('claim_user_id');
            // }
            //, 'selected_user_name'=>$selected_user_name
            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"User fetched successfully.", 'result'=>$res);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_vendor_name'=>'');

            echo json_encode($data);
        }
    }

    function users()
    {
        if (!$this->loggedIn) {
            redirect('login');
        }
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('users')));
        $meta = array('page_title' => lang('users'), 'bc' => $bc, 'page_name' => lang('users'));

        if (isset($_POST['search'])) 
        {
            $term = $_POST['search'];
            
        }
       
        // $this->db->select('posts.post_id,GROUP_CONCAT(posts.tag) as all_tags');
        // $this->db->from('posts');
        // $this->db->join('posts_tags', 'posts.post_id = post_tags.post_id', 'inner');
        // $this->db->join('tags', 'posts_tags.tag_id = tags.tag_id', 'inner');

        if($term != null)
        {
            $this->db->select($this->db->dbprefix('users').".id as id, first_name, last_name,".$this->db->dbprefix('users').".email," . $this->db->dbprefix('groups') . ".description, active");
            $this->db->from("users");
            $this->db->join('groups', 'users.group_id = groups.id', 'inner');
            //->join('firm', 'firm.user_id = "'.$this->session->userdata("user_id").'"', 'left')
            $this->db->join('user_firm as a', 'a.user_id = "'.$this->session->userdata("user_id").'"', 'inner');
            $this->db->join('user_firm as b', 'a.firm_id=b.firm_id', 'inner');

            $this->db->group_start();
                $this->db->or_like('first_name',$term);
                $this->db->or_like('last_name',$term);
                $this->db->or_like('email',$term);
            $this->db->group_end();
            $this->db->where('users.user_deleted = 0');
            $this->db->where('b.user_admin_code_id = a.user_admin_code_id');
            $this->db->where('b.user_id = users.id');
            $this->db->where('b.user_id != "'.$this->session->userdata("user_id").'"');
            $this->db->where('users.group_id != 4');
            $this->db->group_by('users.id');
        }
        else
        {
            $this->db->select($this->db->dbprefix('users').".id as id, first_name, last_name,".$this->db->dbprefix('users').".email," . $this->db->dbprefix('groups') . ".description, active")
            ->from("users")
            ->join('groups', 'users.group_id = groups.id', 'inner')
            //->join('firm', 'firm.user_id = "'.$this->session->userdata("user_id").'"', 'left')
            ->join('user_firm as a', 'a.user_id = "'.$this->session->userdata("user_id").'"', 'inner')
            ->join('user_firm as b', 'a.firm_id=b.firm_id', 'inner')
            ->where('users.user_deleted = 0')
            ->where('b.user_admin_code_id = a.user_admin_code_id')
            ->where('b.user_id = users.id')
            ->where('b.user_id != "'.$this->session->userdata("user_id").'"')
            ->where('users.group_id != 4')
            ->group_by('users.id');
        }
        

        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            $this->data["user"] = $data;
            //echo json_encode($this->data);
        }

        $this->db->select("*")
            ->from("users")
            ->where('id = "'.$this->session->userdata("user_id").'"');

        $user = $this->db->get();
        $user = $user->result_array();

        $this->data["no_of_user"] = $user[0]["no_of_user"];
        $this->data["total_no_of_user"] = $user[0]["total_no_of_user"];

        $this->data['rules_info'] = $this->auth_model->get_rules_info();
        //print_r($this->session->userdata("user_id"));
        $this->page_construct('auth/index', $meta, $this->data);
    }

    public function check_status()
    {
        $checked = $_POST["checked"];
        $user_id = $_POST["user_id"];

        if($checked == "false")
        {
            $data_checked["active"] = 0;
            $this->db->update("users",$data_checked,array("id" => $user_id));
        }
        else
        {
            $data_checked["active"] = 1;
            $this->db->update("users",$data_checked,array("id" => $user_id));
        }
        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }
    /*function getUsers()
    {
        $this->sma->checkPermissions('users', TRUE);*/

            // ->where('company_id', 1)
        /*$this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('users').".id as id, first_name, last_name, email," . $this->db->dbprefix('groups') . ".name, active")
            ->from("users")
            ->join('groups', 'users.group_id=groups.id', 'left')
            ->where('admin_id', $this->session->userdata("user_id"))
            ->where('users.id != users.admin_id')
            ->group_by('users.id')
            ->edit_column('active', '$1__$2', 'active, id')
            ->add_column("Actions", "<center><a href='" . site_url('auth/profile/$1') . "' class='tip' title='" . lang("edit_user") . "'><i class=\"fa fa-edit\"></i></a></center>", "id");*/

        /*if (!$this->Owner) {
            $this->datatables->unset_column('id');
        }*/
        //echo $this->datatables->generate();

        /*$q = $this->db->select($this->db->dbprefix('users').".id as id, first_name, last_name, email," . $this->db->dbprefix('groups') . ".name, active")
            ->from("users")
            ->join('groups', 'users.group_id=groups.id', 'left')
            ->where('admin_id', $this->session->userdata("user_id"))
            ->where('users.id != users.admin_id')
            ->group_by('users.id');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            $this->data["user"] = $data;
            echo $this->data;
        }*/
        //return FALSE;
    //}

    function getUserLogins($id = NULL)
    {
        if (!$this->ion_auth->in_group(array('super-admin', 'admin'))) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect('welcome');
        }
        $this->load->library('datatables');
        $this->datatables
            ->select("login, ip_address, time")
            ->from("user_logins")
            ->where('user_id', $id);

        echo $this->datatables->generate();
    }

    function delete_avatar($id = NULL, $avatar = NULL)
    {

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group('owner') && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . $_SERVER["HTTP_REFERER"] . "'; }, 0);</script>");
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            unlink('assets/uploads/avatars/' . $avatar);
            unlink('assets/uploads/avatars/thumbs/' . $avatar);
            if ($id == $this->session->userdata('user_id')) {
                $this->session->unset_userdata('avatar');
            }
            $this->db->update('users', array('avatar' => NULL), array('id' => $id));
            $this->session->set_flashdata('message', lang("avatar_deleted"));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . $_SERVER["HTTP_REFERER"] . "'; }, 0);</script>");
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function profile($id = NULL, $part = NULL)
    {
        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group('admin') && $this->ion_auth->in_group('admin') != null) || (!$this->ion_auth->in_group('manager') && $this->ion_auth->in_group('manager') != null) && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$id || empty($id)) {
            redirect('auth');
        }
        
        $this->data['title'] = lang('profile');

        $user = $this->ion_auth->user($id, $part)->row();
        $groups = $this->ion_auth->groups()->result_array();
        $this->data['csrf'] = $this->_get_csrf_nonce();
        $this->data['user'] = $user;
        $this->data['groups'] = $groups;
        $this->data['billers'] = $this->Site->getAllCompanies('biller');
        // $this->data['warehouses'] = $this->site->getAllWarehouses();
        //$this->data['product_groups'] = $this->auth_model->getAllUserProductGroups($id);

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form-control',
            'type' => 'password',
            'value' => ''
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'class' => 'form-control',
            'type' => 'password',
            'value' => ''
        );
        $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
        $this->data['old_password'] = array(
            'name' => 'old',
            'id' => 'old',
            'class' => 'form-control',
            'type' => 'password',
        );
        $this->data['new_password'] = array(
            'name' => 'new',
            'id' => 'new',
            'type' => 'password',
            'class' => 'form-control',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
        );
        $this->data['new_password_confirm'] = array(
            'name' => 'new_confirm',
            'id' => 'new_confirm',
            'type' => 'password',
            'class' => 'form-control',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
        );
        $this->data['user_id'] = array(
            'name' => 'user_id',
            'id' => 'user_id',
            'type' => 'hidden',
            'value' => $user->id,
        );

        $this->data['id'] = $id;
        $this->data['part'] = $part;

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('auth/users'), 'page' => lang('users')), array('link' => '#', 'page' => "User Profile"));
        $meta = array('page_title' => lang('profile'),  'page_name' => "User Profile", 'bc' => $bc);

        if($id != $this->session->userdata('user_id'))
        {
            $this->load->library('mybreadcrumb');
            if($part != "access_client")
            {
                $this->mybreadcrumb->add('User', base_url('/auth/users/'));
            }
            else
            {
                $this->mybreadcrumb->add('Access Client User', base_url('/auth/client/'));
            }
            $this->mybreadcrumb->add('Edit User - '.$user->first_name. ' '.$user->last_name, base_url());
            $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        }

        $this->page_construct('auth/profile', $meta, $this->data);
    }

    public function captcha_check($cap)
    {
        $expiration = time() - 300; // 5 minutes limit
        $this->db->delete('captcha', array('captcha_time <' => $expiration));

        $this->db->select('COUNT(*) AS count')
            ->where('word', $cap)
            ->where('ip_address', $this->input->ip_address())
            ->where('captcha_time >', $expiration);

        if ($this->db->count_all_results('captcha')) {
            return true;
        } else {
            $this->form_validation->set_message('captcha_check', lang('captcha_wrong'));
            return FALSE;
        }
    }

    public function get_user_type()
    {
        //$position = $_POST['position'];

        $result = $this->db->query("select * from user_type");

        $result = $result->result_array();

        if(!$result) {
            throw new exception("User Type not found.");
        }
        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['type_of_user'];
        }
        

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"User Type fetched successfully.", 'result'=>$res);

        echo json_encode($data);
    }

    function login($m = NULL)
    {
        //validate form input
        $this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

        if ($this->ion_auth->logged_in()) {
            // redirect('welcome');

            if($this->session->userdata("work_pass_expired_denial"))
            {
                redirect('auth/logout');
            }
            else
            {
                redirect('welcome');
            }
        }
        
        if($m == "register")
        {
            $this->session->set_flashdata('title', "Register");
            //$this->load->view($this->theme . 'auth/login#register', $this->data);
            redirect("auth/login#register");
        }
        else if ($m == "forgot_password")
        {
            $this->session->set_flashdata('title', "forgot_password");
            //$this->load->view($this->theme . 'auth/login#register', $this->data);
            redirect("auth/login#forgot_password");
        }
        else
        {
            $this->data['title'] = lang('login');
        }
        // $produk = $this->products_model->getAllProducts();
        // $bahan = $this->products_model->getAllBahan();
        // $kaos = $this->products_model->getAllKaos();
        // $pola = $this->products_model->getAllpola();
        // if ($this->Settings->captcha) {
        //     $this->form_validation->set_rules('captcha', lang('captcha'), 'required|callback_captcha_check');
        // }

        if ($this->form_validation->run() == true) {

            $remember = (bool)$this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                /*if ($this->Settings->mmode) {
                    if (!$this->ion_auth->in_group('owner')) {
                        $this->session->set_flashdata('error', lang('site_is_offline_plz_try_later'));
                        redirect('auth/logout');
                    }
                }
                if ($this->ion_auth->in_group('customer') || $this->ion_auth->in_group('supplier')) {
                    redirect('auth/logout');
                }*/
                if($this->session->userdata("work_pass_expired_denial"))
                {
                    redirect('auth/logout');
                }
                
                $this->session->set_flashdata('message', $this->ion_auth->messages());

                if($this->ion_auth->in_group('owner'))
                {
                    $referrer = 'welcome';
                }
                else
                {
                    $referrer = $this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'welcome';
                }
                
        // $this->sma->print_arrays($_POST);    
                $email = $this->input->post('identity');

                $q = $this->db->query('select users.id, users.first_name, users.email, users.group_id, users.user_admin_code_id, user_admin_code.admin_code from users LEFT JOIN user_admin_code on user_admin_code.id = users.user_admin_code_id where email = "'.$email.'" and user_deleted = 0');

                $q = $q->result_array();

                $this->session->set_userdata('user_id', $q[0]["id"]);
                $this->session->set_userdata('first_name', $q[0]["first_name"]);
                $this->session->set_userdata('email_for_user', $q[0]["email"]);
                $this->session->set_userdata('user_admin_code_id', $q[0]["user_admin_code_id"]);
                
                //$this->session->set_userdata('admin_id', $q[0]["admin_id"]);

                $check_user_firm = $this->db->get_where("user_firm", array("user_id" => $this->session->userdata('user_id'), "in_use = " => 1));

                if ($check_user_firm->num_rows())
                {
                    $check_user_firm = $check_user_firm->result_array();

                    $this->session->set_userdata('firm_id', $check_user_firm[0]["firm_id"]);

                    //$this->session->set_userdata('admin_code', $check_user_firm[0]["admin_code"]);

                    $query = $this->db->query('select id, firm_id from billing_template where firm_id = "'.$check_user_firm[0]["firm_id"].'"');

                    if (!$query->num_rows())
                    {
                        $master_billing_query = $this->db->query('select * from billing_master_template');

                        $master_billing_query = $master_billing_query->result_array();

                        for($y = 0; $y < count($master_billing_query); $y++)
                        {
                            $billing_template["firm_id"] = $check_user_firm[0]["firm_id"];
                            $billing_template["service"] = $master_billing_query[$y]["service"];
                            $billing_template["invoice_description"] = $master_billing_query[$y]["invoice_description"];
                            $billing_template["amount"] = $master_billing_query[$y]["amount"];
                            $billing_template["frequency"] = $master_billing_query[$y]["frequency"];

                            $this->db->insert("billing_template",$billing_template);
                        }
                    }

                    $document_master_query = $this->db->query('select id, firm_id from document_master where firm_id = "'.$check_user_firm[0]["firm_id"].'"');

                    if (!$document_master_query->num_rows())
                    {
                        $document_master_template_query = $this->db->query('select * from document_master_template');

                        $document_master_template_query = $document_master_template_query->result_array();

                        for($y = 0; $y < count($document_master_template_query); $y++)
                        {
                            $document_master["firm_id"] = $check_user_firm[0]["firm_id"];
                            $document_master["document_name"] = $document_master_template_query[$y]["document_name"];
                            $document_master["triggered_by"] = $document_master_template_query[$y]["triggered_by"];
                            $document_master["document_content"] = $document_master_template_query[$y]["document_content"];

                            $this->db->insert("document_master",$document_master);
                        }
                    }
                }


                redirect($referrer);
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                $this->session->set_flashdata('regEmailAddress', $this->input->post('identity'));
                redirect('auth/login');
            }
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => lang('email'),
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => lang('password'),
            );

            if ($m == 'db') {
                $this->data['message'] = lang('db_restored');
            } elseif ($m) {
                $this->data['error'] = lang('we_are_sorry_as_this_sction_is_still_under_development.');
            }

            $this->data['work_pass_expire_flag'] = false;
            if(strpos(json_encode($this->data['error']), 'Work Pass Expiring') !== false)
            {
                $this->data['work_pass_expire_flag'] = true;
                $this->session->set_userdata('work_pass_expired_denial', true);
            }

            $this->load->view('auth/login', $this->data);
        }
    }

    function reload_captcha()
    {
        $this->load->helper('captcha');
        $vals = array(
            'img_path' => './assets/captcha/',
            'img_url' => site_url() . 'assets/captcha/',
            'img_width' => 150,
            'img_height' => 34,
            'word_length' => 5,
            'colors' => array('background' => array(255, 255, 255), 'border' => array(204, 204, 204), 'text' => array(102, 102, 102), 'grid' => array(204, 204, 204))
        );
        $cap = create_captcha($vals);
        $capdata = array(
            'captcha_time' => $cap['time'],
            'ip_address' => $this->input->ip_address(),
            'word' => $cap['word']
        );
        $query = $this->db->insert_string('captcha', $capdata);
        $this->db->query($query);
        //$this->data['image'] = $cap['image'];

        echo $cap['image'];
    }

    function logout($m = NULL)
    {

        $logout = $this->ion_auth->logout();
        $this->session->set_flashdata('message', $this->ion_auth->messages());


        redirect('auth/login' . $m);
    
        
    }

    function close_browser()
    {
        $this->session->sess_destroy();
    }

    function change_password()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('login');
        }
        $this->form_validation->set_rules('old_password', lang('old_password'), 'required');
        $this->form_validation->set_rules('new_password', lang('new_password'), 'required|min_length[8]|max_length[25]');
        $this->form_validation->set_rules('new_password_confirm', lang('confirm_password'), 'required|matches[new_password]');

        $id = $this->input->post('user_id');

        //echo json_encode($user);

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            //redirect('auth/profile/' . $user->id . '/#cpassword');
        } else {
            /*if (DEMO) {
                $this->session->set_flashdata('warning', lang('disabled_in_demo'));
                redirect($_SERVER["HTTP_REFERER"]);
            }*/

            
            if($id != $this->session->userdata('user_id'))
            {
                $user = $this->ion_auth->user($id)->row();

                $change = $this->ion_auth->change_password($user->email, $this->input->post('old_password'), $this->input->post('new_password'));            
            }
            else
            {
                $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

                //echo json_encode($identity);

                $change = $this->ion_auth->change_password($identity, $this->input->post('old_password'), $this->input->post('new_password'));
            }



            if ($change) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                if($id != $this->session->userdata('user_id'))
                {
                    redirect('auth/users/');
                }
                else
                {
                    $this->logout();
                }
                
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                if($id != $this->session->userdata('user_id'))
                {
                    redirect('auth/users/');
                }
                else
                {
                    redirect('auth/profile/' . $user->id . '/#cpassword');
                }
            }
        }
    }

    function profile_change_password($part = NULL)
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('login');
        }
        $this->form_validation->set_rules('old_password', lang('old_password'), 'required');
        $this->form_validation->set_rules('new_password', lang('new_password'), 'required|min_length[8]|max_length[25]');
        $this->form_validation->set_rules('new_password_confirm', lang('confirm_password'), 'required|matches[new_password]');

        //$id = $this->input->post('user_id');
        $id = $_POST["user_id"];
        //echo json_encode($id);

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            //redirect('auth/profile/' . $user->id . '/#cpassword');
        } else {
            /*if (DEMO) {
                $this->session->set_flashdata('warning', lang('disabled_in_demo'));
                redirect($_SERVER["HTTP_REFERER"]);
            }*/

            
            if($id != $this->session->userdata('user_id'))
            {
                $user = $this->ion_auth->user($id)->row();

                $change = $this->ion_auth->change_password($user->email, $_POST["old_password"], $_POST["new_password"]);            
            }
            else
            {
                $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

                //echo json_encode($identity);

                $change = $this->ion_auth->change_password($identity, $_POST["old_password"], $_POST["new_password"]);
            }



            if ($change) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                if($id != $this->session->userdata('user_id') && $part != "access_client")
                {
                    echo json_encode(array('Status' => 1, 'message' => 'User profile has been updated', 'title' => 'Updated'));
                    //redirect('auth/users/');
                }
                // elseif($part != "access_client")
                // {
                //     echo json_encode(array('Status' => 2, 'message' => 'User profile has been updated', 'title' => 'Updated', 'user_id' => $id));
                // }
                else if($id != $this->session->userdata('user_id') && $part == "access_client")
                {
                    echo json_encode(array('Status' => 4, 'message' => 'User profile has been updated', 'title' => 'Updated'));
                    //redirect('auth/users/');
                }
                // elseif($part == "access_client")
                // {
                //     echo json_encode(array('Status' => 4, 'message' => 'User profile has been updated', 'title' => 'Updated', 'user_id' => $id));
                // }
                else
                {
                    
                    echo json_encode(array('Status' => 2, 'message' => 'User profile has been updated', 'title' => 'Updated', 'user_id' => $id));
                    /*redirect('auth/profile/' . $user->id . '/#cpassword');*/
                    //$this->logout();
                }
                
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                if($id != $this->session->userdata('user_id'))
                {
                    echo json_encode(array('Status' => 1, 'message' => 'User profile has been updated', 'title' => 'Updated'));
                    //redirect('auth/users/');
                }
                else
                {
                    echo json_encode(array('Status' => 3, 'message' => 'Your password is incorrect.', 'title' => 'Error'));
                    /*redirect('auth/profile/' . $user->id . '/#cpassword');*/
                }
            }
        }
    }

    function forgot_password()
    {
        $this->form_validation->set_rules('forgot_email', lang('email_address'), 'required|valid_email');

        if ($this->form_validation->run() == false) {
            $error = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->session->set_flashdata('error', $error);
            redirect("login#forgot_password");
        } else {

            $identity = $this->ion_auth->where('email', strtolower($this->input->post('forgot_email')))->users()->row();
            if (empty($identity)) {
                $this->ion_auth->set_message('Email address is not registered. Click <a href="login/register" style="font-weight:bold;">here</a> to register for new account');
                $this->session->set_flashdata('error', $this->ion_auth->messages());
                redirect("login#forgot_password");
            }

            $forgotten = $this->ion_auth->forgotten_password($identity->email);

            if ($forgotten) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth/login#after_forgot_password");
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect("auth/login#forgot_password");
            }
        }
    }

    public function reset_password($code = NULL)
    {
        if ($this->loggedIn) {
            redirect('welcome');
        }

        if (!$code) {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {

            $this->form_validation->set_rules('new_password', lang('new_password'), 'required|min_length[8]|max_length[25]|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', lang('confirm_new_password'), 'required');

            if ($this->form_validation->run() == false) {

                $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['title'] = lang('reset_password');
                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'class' => 'form-control',
                    'pattern' => '^.{8}.*$',
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'class' => 'form-control',
                    'pattern' => '^.{8}.*$',
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;
                $this->data['identity_label'] = $user->email;
                //render
                $this->load->view('auth/reset_password', $this->data);
            } else {
                // do we have a valid request?
                /*if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

                    //something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);
                    show_error(lang('error_csrf'));

                } else {*/
                    // finally change the password
                    $identity = $user->email;

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new_password'));

                    if ($change) {
                        //if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        //$this->logout();
                        redirect('auth/login');
                    } else {
                        $this->session->set_flashdata('error', $this->ion_auth->errors());
                        redirect('auth/reset_password/' . $code);
                    }
                //}
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('error', $this->ion_auth->errors());
            redirect("auth/login#forgot_password");
        }
    }

    function activate($id, $code = false)
    {

        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->Owner) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            if ($this->Owner) {
                redirect($_SERVER["HTTP_REFERER"]);
            } else {
                redirect("login");
            }
        } else {
            $this->session->set_flashdata('error', $this->ion_auth->errors());
            redirect("forgot_password");
        }
    }

    function deactivate($id = NULL)
    {
        $this->sma->checkPermissions('users', TRUE);
        $id = $this->config->item('use_mongodb', 'ion_auth') ? (string)$id : (int)$id;
        $this->form_validation->set_rules('confirm', lang("confirm"), 'required');

        if ($this->form_validation->run() == FALSE) {
            if ($this->input->post('deactivate')) {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER["HTTP_REFERER"]);
            } else {
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['user'] = $this->ion_auth->user($id)->row();
                $this->data['modal_js'] = $this->Site->modal_js();
                $this->load->view($this->theme . 'auth/deactivate_user', $this->data);
            }
        } else {

            if ($this->input->post('confirm') == 'yes') {
                if ($id != $this->input->post('id')) {
                    show_error(lang('error_csrf'));
                }

                if ($this->ion_auth->logged_in() && $this->Owner) {
                    $this->ion_auth->deactivate($id);
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                }
            }

            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function get_client()
    {
        $this->db->select('client.*');
        $this->db->from('client');
        $this->db->where('client.firm_id', $this->session->userdata('firm_id'));
        $this->db->where('client.deleted', 0);
        $this->db->order_by('client.id', 'desc');
        //$q = $this->db->get();
        $q = $this->db->get();
        $result = $q->result_array();
        //echo json_encode($result);
        if(!$q) {
            throw new exception("Client not found.");
        }
        $res = array();

        for($j = 0; $j < count($result); $j++)
        {
            $res[$result[$j]['id']] = $result[$j]['company_name'];
        }    

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client fetched successfully.", 'result'=>$res);

        echo json_encode($data);
    }

    function get_firm()
    {

        //$currency = $_POST['currency'];
        $this->db->select('firm.*')
                ->from('firm')
                ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'));

        //$get_all_firm = $this->db->get_where('firm',array('user_id'=>$this->session->userdata('user_id')));
        $get_all_firm = $this->db->get();
        $result = $get_all_firm->result_array();
        //echo json_encode($result);
        if(!$get_all_firm) {
            throw new exception("Firm not found.");
        }
        $res = array();

        for($j = 0; $j < count($result); $j++)
        {
            $res[$result[$j]['id']] = $result[$j]['name'];
        }    

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Firm fetched successfully.", 'result'=>$res);

        echo json_encode($data);
    }

    function get_group()
    {

        //$currency = $_POST['currency'];
        $this->db->select('groups.*')
                ->from('groups')
                ->order_by('id', 'DESC' )
                ->where('id != 1')
                ->where('id != 4');
                //->limit(2);

        //$get_all_firm = $this->db->get_where('firm',array('user_id'=>$this->session->userdata('user_id')));
        $get_all_group = $this->db->get();
        $result = $get_all_group->result_array();
        //echo json_encode($result);
        if(!$get_all_group) {
            throw new exception("Group not found.");
        }
        $res = array();

        for($j = 0; $j < count($result); $j++)
        {
            $res[$result[$j]['id']] = $result[$j]['description'];
        }    

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Group fetched successfully.", 'result'=>$res);

        echo json_encode($data);

    }

    function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    function create_access_client_user()
    {
        if ((!$this->Admin && $this->Admin != null) || (!$this->Manager && $this->Manager != null)) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->data['title'] = "Create Access Client User";
        //$this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[users.username]');
        $this->form_validation->set_rules('email', lang("email"), 'trim');

        if ($this->form_validation->run() == true) {

            //$username = strtolower($this->input->post('username'));
            list($username, $domain) = explode("@", $this->input->post('email'));
            $user_type_id = 1;
            $email = strtolower($this->input->post('email'));
            //$password = $this->input->post('password');
            $password = $this->randomPassword();
            $notify = $this->input->post('notify');
            $selected_client = $this->input->post("selected_client");
            $term_and_condition = $this->input->post("terms");
            //$admin_id = $this->input->post('user_id');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                /*'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),*/
                //'gender' => $this->input->post('gender'),
                'group_id' => $this->input->post('role'),
                //'admin_id' => $this->input->post('admin_id'),
/*                'biller_id' => $this->input->post('biller'),
                'warehouse_id' => $this->input->post('warehouse'),*/
            );
            $active = "1";
            //$groupData = $this->ion_auth->in_group('super-admin') ? array($this->input->post('group')) : NULL;
            //$this->sma->print_arrays($data);
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $user_type_id, $password, $email, $additional_data, $term_and_condition, $active, $notify, $selected_client)) {

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth/client");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));

            $this->data['groups'] = $this->ion_auth->groups()->result_array();
            //$this->data['product_groups'] = $this->auth_model->getAllProductGroups();
            $this->data['billers'] = $this->Site->getAllCompanies('biller');
            // $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['user_id'] = $this->session->userdata("user_id");
            //$this->_render_page('auth/create_user', $this->data);
            $bc = array(array('link' => site_url('home'), 'page' => lang('home')), array('link' => site_url('auth/client'), 'page' => lang('users')), array('link' => '#', 'page' => "Create Access Client Users"));
            $meta = array('page_title' => "Create Access Client Users", 'bc' => $bc, 'page_name' => "Access Client User Profile");

            $this->load->library('mybreadcrumb');
            $this->mybreadcrumb->add('Access Client User', base_url('/auth/client/'));
            $this->mybreadcrumb->add('Create Access Client User', base_url());
            $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

            $this->page_construct('auth/create_access_client_user', $meta, $this->data);
        }
    }

    function create_user()
    {
        if ((!$this->Admin && $this->Admin != null) || (!$this->Manager && $this->Manager != null)) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->data['title'] = "Create User";
        //$this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[users.username]');
        $this->form_validation->set_rules('email', lang("email"), 'trim');

        if ($this->form_validation->run() == true) {

            //$username = strtolower($this->input->post('username'));
            list($username, $domain) = explode("@", $this->input->post('email'));
            $user_type_id = 1;
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $notify = $this->input->post('notify');
            //$manager_in_charge = $this->input->post("manager_in_charge");
            $selected_firm = $this->input->post("selected_firm");
            $term_and_condition = $this->input->post("terms");
            //$admin_id = $this->input->post('user_id');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                /*'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),*/
                //'gender' => $this->input->post('gender'),
                'group_id' => $this->input->post('group') ? $this->input->post('group') : $this->input->post('role'),
                'manager_in_charge' => $this->input->post("manager_in_charge") ? $this->input->post("manager_in_charge") : 0,
                'department_id' => $this->input->post('department'),
                //'admin_id' => $this->input->post('admin_id'),
/*                'biller_id' => $this->input->post('biller'),
                'warehouse_id' => $this->input->post('warehouse'),*/
            );
            $active = "1";
            //$groupData = $this->ion_auth->in_group('super-admin') ? array($this->input->post('group')) : NULL;
            //$this->sma->print_arrays($data);
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $user_type_id, $password, $email, $additional_data, $term_and_condition, $active, $notify, $selected_firm)) {

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("employee");
        } else {

            //$this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));
            $this->data['error'] = 'This email register under this system already.';
            //echo json_encode($this->session->flashdata('error'));
            $this->data['users_group_dropdown'] = $this->auth_model->get_users_group_dropdown();

            $this->session->set_flashdata('employee_id', $this->input->post('employee_id'));

            $this->data['user_id'] = $this->session->userdata("user_id");
            //$this->_render_page('auth/create_user', $this->data);
            $bc = array(array('link' => site_url('home'), 'page' => lang('home')), array('link' => site_url('auth/users'), 'page' => lang('users')), array('link' => '#', 'page' => lang('create_user')));
            $meta = array('page_title' => lang('users'), 'bc' => $bc, 'page_name' => "Create User");

            $this->load->library('mybreadcrumb');
            $this->mybreadcrumb->add('Employee', base_url('employee'));
            $this->mybreadcrumb->add('Create User', base_url());
            $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

            $this->page_construct('auth/create_user_account', $meta, $this->data);
        }
    }

    function delete_client_user()
    {
        $id = $_POST["user_id"];

        if ($this->auth_model->delete_user($id)) {

            //$this->db->delete("user_firm",array('user_id'=>$id));
            $this->db->where('user_id', $id);
            $this->db->update('user_firm', array('user_deleted' => 1));
        }
        //$this->recalculate();
        echo json_encode(array('message' => 'Information Updated', 'title' => 'Updated'));
    }

    function delete_user()
    {
        $id = $_POST["user_id"];

        if ($this->auth_model->delete_user($id)) {

            //$this->db->delete("user_firm",array('user_id'=>$id));
            $this->db->where('user_id', $id);
            $this->db->update('user_firm', array('user_deleted' => 1));
        }
        $this->recalculate();
        echo json_encode(array('message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function recalculate()
    {
        // $this->db->select("users.id")
        //         ->from("users")
        //         ->join('groups', 'users.group_id = groups.id', 'inner')
        //         ->join('user_firm as a', 'a.user_id = "'.$this->session->userdata("user_id").'"', 'inner')
        //         ->join('user_firm as b', 'a.firm_id=b.firm_id', 'inner')
        //         ->where('b.user_id = users.id')
        //         ->where('b.user_id != "'.$this->session->userdata("user_id").'"')
        //         ->group_by('users.id');

        $this->db->select("users.id")
                ->from("users")
                ->join('groups', 'users.group_id = groups.id', 'inner')
                ->where('users.user_admin_code_id = "'.$this->session->userdata("user_admin_code_id").'"')
                ->where('users.group_id = 3')
                ->where('users.user_deleted = 0')
                ->group_by('users.id');

        $test = $this->db->get();
        $test = $test->result_array();

        $data_user_id = array();

        foreach ($test as $rr) {
            array_push($data_user_id, $rr["id"]);
        }

        $this->db->select("firm_id")
        ->from("user_firm")
        ->where('user_admin_code_id = "'.$this->session->userdata("user_admin_code_id").'"')
        ->group_by('firm_id');

        $firm_id = $this->db->get();
        $firm_id = $firm_id->result_array();

        $data_firm_id = array();

        foreach ($firm_id as $rows) {
            array_push($data_firm_id, $rows["firm_id"]);
        }

        $user["no_of_user"] = count($data_user_id);
        $user["no_of_firm"] = count($data_firm_id);

        $this->db->where('user_admin_code_id', $this->session->userdata("user_admin_code_id"));
        $this->db->where('group_id = 2');
        $this->db->update('users',$user);

        if(count($data_firm_id) != 0)
        {
            
            $this->db->select('id');
            $this->db->from('client');
            $this->db->where_in('firm_id', $data_firm_id);

            $num_client = $this->db->get();
            $num_client = $num_client->result_array();

            if(count($num_client) != 0)
            {   
                //echo json_encode($row["id"]);
                $users["no_of_client"] = count($num_client);

            }
            else
            {
                $users["no_of_client"] = 0;
            }

            $this->db->where('user_admin_code_id', $this->session->userdata("user_admin_code_id"));
            $this->db->update('users',$users);

            // $data_user_id = array();

            // foreach ($test as $r) {
            //     array_push($data_user_id, $r["id"]);
            // }

            // if(count($data_user_id) != 0)
            // {  
            //     $this->db->where_in('id', $data_user_id);
            //     $this->db->update('users',$users);
            // }
        }
    }

    function edit_user($id = NULL, $part = NULL)
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
        $this->data['title'] = lang("edit_user");

        //|| !$this->Owner && $id != $this->session->userdata('user_id')
        if (!$this->loggedIn ) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $user = $this->ion_auth->user($id, $part)->row();
        
        //echo json_encode($user);
        //$groups = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();
        if ($user->username != $this->input->post('username')) {
            $this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[users.username]');
        }
        if ($user->email != $this->input->post('email')) {
            $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        }
        //echo json_encode($this->input->post('role'));
        if ($this->form_validation->run() === TRUE) {

            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('id = '.$this->session->userdata('user_id'));
            $q = $this->db->get();

            $user_info = $q->result_array();

            if (($this->Admin && $this->Admin != null) || ($this->Manager && $this->Manager != null)) {
                if ($id == $this->session->userdata('user_id')) {
                    $data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        //'group_id' => $this->input->post('role'),
                        /*'company' => $this->input->post('company'),
                        'phone' => $this->input->post('phone'),*/
                        //'website' => $this->input->post('website'),
                        //'gender' => $this->input->post('gender'),
                    );
                } elseif ($this->ion_auth->in_group('user', $id) || $this->ion_auth->in_group('admin', $id) || $this->ion_auth->in_group('manager', $id) || $this->ion_auth->in_group('bookkeeper', $id)) {
                    if($this->input->post('role') == 3)
                    {
                        $data = array(
                            'first_name' => $this->input->post('first_name'),
                            'last_name' => $this->input->post('last_name'),
                            'group_id' => $this->input->post('role'),
                            'manager_in_charge' => $this->input->post('manager_in_charge'),
                            'department_id' => $this->input->post('department'),
                            'total_no_of_user' => 0,
                        );
                    }
                    else
                    {
                        $data = array(
                            'first_name' => $this->input->post('first_name'),
                            'last_name' => $this->input->post('last_name'),
                            'group_id' => $this->input->post('role'),
                            'manager_in_charge' => $this->input->post('manager_in_charge'),
                            'department_id' => $this->input->post('department'),
                            'total_no_of_user' => $user_info[0]['total_no_of_user'],
                        );
                    }
                } else {
                    $data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        // 'company' => $this->input->post('company'),
                        // 'username' => $this->input->post('username'),
                        // 'email' => $this->input->post('email'),
                        // 'phone' => $this->input->post('phone'),
                        // //'website' => $this->input->post('website'),
                        // 'gender' => $this->input->post('gender'),
                        // 'active' => $this->input->post('status'),
                        // 'group_id' => $this->input->post('group'),
                        // 'biller_id' => $this->input->post('biller') ? $this->input->post('biller') : NULL,
                        // 'warehouse_id' => $this->input->post('warehouse') ? $this->input->post('warehouse') : NULL,
                        // 'award_points' => $this->input->post('award_points'),
                    );
                }
                //$pgs = $this->auth_model->getAllUserProductGroups($id);
                //foreach($pgs as $pg) {
                //    $upg[] = array('user_id' => $id, 'product_group_id' => $this->input->post('group'.$pg->id), 'percent' => $this->input->post('percent'.$pg->id));
                //}
                //print_r($upg); die();
            } elseif (($this->Admin && $this->Admin != null) || ($this->Manager && $this->Manager != null)) {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    // 'company' => $this->input->post('company'),
                    // 'phone' => $this->input->post('phone'),
                    // //'website' => $this->input->post('website'),
                    // 'gender' => $this->input->post('gender'),
                    // 'active' => $this->input->post('status'),
                    // 'award_points' => $this->input->post('award_points'),
                );
            } else {
                $data = array(
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    //'group_id' => $_POST['role'],
                    //'company' => $this->input->post('company'),
                    //'phone' => $_POST['phone'],
                    //'website' => $this->input->post('website'),
                    //'gender' => $_POST['gender'],
                    /*'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    //'company' => $this->input->post('company'),
                    'phone' => $this->input->post('phone'),
                    //'website' => $this->input->post('website'),
                    'gender' => $this->input->post('gender'),*/
                );
            }

            if (($this->Admin && $this->Admin != null) || ($this->Manager && $this->Manager != null)) {
                if ($this->input->post('password')) {
                    if (DEMO) {
                        $this->session->set_flashdata('warning', lang('disabled_in_demo'));
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                    $this->form_validation->set_rules('password', lang('edit_user_validation_password_label'), 'required|min_length[8]|max_length[25]|matches[password_confirm]');
                    $this->form_validation->set_rules('password_confirm', lang('edit_user_validation_password_confirm_label'), 'required');

                    $data['password'] = $this->input->post('password');
                }
            }
            //$this->sma->print_arrays($data);

        }
        if ($this->form_validation->run() === TRUE && $this->ion_auth->update($user->id, $data)) {
            if($id == $this->session->userdata('user_id'))
            {
                $this->session->set_userdata('first_name', $this->input->post('first_name'));
            }
            $this->session->set_flashdata('message', lang('user_updated'));

            

            if(isset($_POST['selected_firm']) && $part != "access_client")
            {
                $selected_firm = $_POST['selected_firm'];

                $this->db->where('user_id', $user->id);
                $this->db->delete('user_firm'); 

                $user_firm['user_admin_code_id'] = $this->session->userdata('user_admin_code_id');
                $user_firm["user_id"] = $user->id;
                $user_firm["client_module"] = "full";
                $user_firm["person_module"] = "full";
                $user_firm["document_module"] = "full";
                $user_firm["report_module"] = "full";
                $user_firm["billing_module"] = "full";

                for($r = 0; $r < count($selected_firm); $r++)
                {
                    $user_firm["firm_id"] = $selected_firm[$r];

                    if($r == 0)
                    {
                        $user_firm["default_company"] = 1;
                        $user_firm["in_use"] = 1;
                    }
                    else
                    {
                        $user_firm["default_company"] = 0;
                        $user_firm["in_use"] = 0;
                    }
                    $this->db->insert('user_firm', $user_firm);
                }
            }
            elseif(isset($_POST['selected_client']))
            {
                $selected_client = $_POST['selected_client'];

                $this->db->where('user_id', $user->id);
                $this->db->delete('user_client');

                $user_client['user_admin_code_id'] = $this->session->userdata('user_admin_code_id');
                $user_client["user_id"] = $id;

                for($r = 0; $r < count($selected_client); $r++)
                {
                    $user_client["client_id"] = $selected_client[$r];

                    $this->db->insert('user_client', $user_client);
                }

                $this->db->where('user_id', $user->id);
                $this->db->delete('user_firm');

                $query_client = $this->db->query("select * from client where id = '".$selected_client[0]."'");

                $query_client = $query_client->result_array();

                $user_firm['user_admin_code_id'] = $this->session->userdata('user_admin_code_id');
                $user_firm["user_id"] = $id;
                $user_firm["client_module"] = "full";
                $user_firm["person_module"] = "full";
                $user_firm["document_module"] = "full";
                $user_firm["report_module"] = "none";
                $user_firm["billing_module"] = "none";
                $user_firm["firm_id"] = $query_client[0]["firm_id"];
                $user_firm["default_company"] = 1;
                $user_firm["in_use"] = 1;

                $this->db->insert('user_firm', $user_firm);
            }

            if($id != $this->session->userdata('user_id') && $part != "access_client")
            {
                $redirect = "user_page";
            }
            elseif($part == "access_client")
            {
                $redirect = "user_client_page";
            }
            else
            {
                $redirect = "homepage";
            }
            echo json_encode(array('message' => 'User profile has been updated', 'title' => 'Updated', 'direct' => $redirect));
            //redirect("auth/profile/" . $id);
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }


    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')
        ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function _render_page($view, $data = null, $render = false)
    {

        $this->viewdata = (empty($data)) ? $this->data : $data;
        $view_html = $this->load->view('header', $this->viewdata, $render);
        $view_html .= $this->load->view($view, $this->viewdata, $render);
        $view_html = $this->load->view('footer', $this->viewdata, $render);

        if (!$render)
            return $view_html;
    }

    /**
     * @param null $id
     */
    function update_avatar($id = NULL)
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }

        if (!$this->ion_auth->logged_in() || !$this->Owner && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        //validate form input
        $this->form_validation->set_rules('avatar', lang("avatar"), 'trim');

        if ($this->form_validation->run() == true) {

            if ($_FILES['avatar']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/avatars';
                $config['allowed_types'] = 'gif|jpg|png';
                //$config['max_size'] = '500';
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['max_filename'] = 25;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('avatar')) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                $photo = $this->upload->file_name;

                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/uploads/avatars/' . $photo;
                $config['new_image'] = 'assets/uploads/avatars/thumbs/' . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 150;
                $config['height'] = 150;;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                $user = $this->ion_auth->user($id)->row();
            } else {
                $this->form_validation->set_rules('avatar', lang("avatar"), 'required');
            }
        }

        if ($this->form_validation->run() == true && $this->auth_model->updateAvatar($id, $photo)) {
            unlink('assets/uploads/avatars/' . $user->avatar);
            unlink('assets/uploads/avatars/thumbs/' . $user->avatar);
            $this->session->set_userdata('avatar', $photo);
            $this->session->set_flashdata('message', lang("avatar_updated"));
            redirect("auth/profile/" . $id);
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect("auth/profile/" . $id);
        }
    }

    /*function register_account()
    {
        $this->data['title'] = "Register";
        if ($this->Settings->allow_reg) {
             $this->load->view('auth/login', $this->data);
        }
    }*/
    function term_and_condition()
    {
        //echo json_encode("test");
        //redirect("register");
       /* $data['main_menu'] = "tr" ;
        $this->load->view('auth/term_and_condition', $data);*/
        $this->data['pola']="term";
        $this->load->view($this->theme . 'term_and_condition', $this->data); 
    }

    function privacy_policy()
    {
        //echo json_encode("test");
        //redirect("register");
       /* $data['main_menu'] = "tr" ;
        $this->load->view('auth/term_and_condition', $data);*/
        $this->data['pola']="term";
        $this->load->view($this->theme . 'privacy_policy', $this->data); 
    }

    function register()
    {
        $this->data['title'] = "Register";

        if (!$this->Settings->allow_reg) {
            $this->session->set_flashdata('error', lang('registration_is_disabled'));
            redirect("login");
        }
        //validate form input
        //$this->form_validation->set_message('is_unique', 'An account already registered with this email');
        $this->form_validation->set_rules('first_name', lang('first_name'), 'required');
        $this->form_validation->set_rules('last_name', lang('last_name'), 'required');
        $this->form_validation->set_rules('email', lang('email_address'), 'required|valid_email|is_unique[users.email]');
        /*$this->form_validation->set_rules('phone', lang('phone'), 'required');
        $this->form_validation->set_rules('company', lang('company'), 'required');*/
        $this->form_validation->set_rules('password', lang('password'), 'required|min_length[8]|max_length[25]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', lang('confirm_password'), 'required');
        /*$this->form_validation->set_rules('captcha', 'captcha', 'required|callback_captcha_check');*/

        if ($this->form_validation->run() == true) {
            list($username, $domain) = explode("@", $this->input->post('email'));
            $user_type_id = $this->input->post('user_type');
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $term_and_condition = $this->input->post('terms');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                /*'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),*/
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $user_type_id, $password, $email, $additional_data, $term_and_condition)) {

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("login#after_register");
        } else {

            $error = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->session->set_flashdata('error', $error);

            //$first_name = $_POST["first_name"];
            $this->session->set_flashdata('regFirstName', $this->input->post('first_name'));
            $this->session->set_flashdata('regLastName', $this->input->post('last_name'));
            /*$this->session->set_flashdata('regCompany', $this->input->post('company'));
            $this->session->set_flashdata('regPhone', $this->input->post('phone'));*/
            $this->session->set_flashdata('regEmail', $this->input->post('email'));
            //echo json_encode($this->input->post('first_name'));

            redirect("login#register");



            //$this->page_construct("login");
            //$this->load->view($this->theme . 'auth/login', $this->data);
            /*$this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));
            $this->data['groups'] = $this->ion_auth->groups()->result_array();*/

            /*$this->load->helper('captcha');
            $vals = array(
                'img_path' => './assets/captcha/',
                'img_url' => site_url() . 'assets/captcha/',
                'img_width' => 150,
                'img_height' => 34,
            );
            $cap = create_captcha($vals);
            $capdata = array(
                'captcha_time' => $cap['time'],
                'ip_address' => $this->input->ip_address(),
                'word' => $cap['word']
            );

            $query = $this->db->insert_string('captcha', $capdata);
            $this->db->query($query);*/
            /*$this->data['image'] = $cap['image'];
            $this->data['captcha'] = array('name' => 'captcha',
                'id' => 'captcha',
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => lang('type_captcha')
            );*/

            /*$this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array(
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone'] = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            $this->load->view('auth/register', $this->data);*/
        }
    }

    function user_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->auth_model->delete_user($id);
                    }
                    $this->session->set_flashdata('message', lang("users_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sales'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('first_name'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('last_name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('email'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('group'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('status'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $user = $this->Site->getUser($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $user->first_name);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $user->last_name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $user->email);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $user->company);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $user->group);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $user->status);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'users_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_user_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function delete($id = NULL)
    {
        $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->auth_model->delete_user($id)) {
            //echo lang("user_deleted");
            $this->session->set_flashdata('message', 'user_deleted');
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    public function add_rules_info()
    {
        $this->form_validation->set_rules('department[0]', 'Department', 'required');
        $this->form_validation->set_rules('type[0]', 'Type', 'required');
        $this->form_validation->set_rules('description[0]', 'Description', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $error = array(
                'department' => strip_tags(form_error('department[0]')),
                'type' => strip_tags(form_error('type[0]')),
                'description' => strip_tags(form_error('description[0]')),
            );

            echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field.', 'title' => 'Error', "error" => $error));
        }
        else
        {
            $data['department'] = json_encode($_POST['department']);
            $data['type']=$_POST['type'][0];
            $data['description']=$_POST['description'][0];
            $data['select_all_department_checkbox']=(isset($_POST['select_all_department_checkbox'])) ? 1 : 0;

            $q = $this->db->get_where("user_rules", array("id" => $_POST['rules_info_id'][0]));

            if (!$q->num_rows())
            {   
                $this->db->insert("user_rules",$data);
                $insert_rules_info_id = $this->db->insert_id();

                echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_rules_info_id" => $insert_rules_info_id));
            }
            else
            {
                $this->db->update("user_rules",$data,array("id" => $_POST['rules_info_id'][0]));

                echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
            }
        }
    }

    public function delete_rules_info ()
    {
        $id = $_POST["rules_info_id"];
        $data['deleted'] = 1;
        //$this->db->delete("user_rules",array('id'=>$id));
        $this->db->update("user_rules",$data,array("id" => $_POST['rules_info_id'][0]));

        echo json_encode(array("Status" => 1));
                
    }

    public function get_rules()
    {
        $department_id = isset($_POST['department_id'])?$_POST['department_id']:"";
        //echo json_encode($_POST);

        $this->db->select('*');
        $this->db->from('user_rules');
        $this->db->order_by("type", "asc");
        $q = $this->db->get();
        //echo json_encode( $q->result_array() );
        $data = [];

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                //echo json_encode(!in_array(strval($department_id), $row->department));
                if(in_array(strval($department_id), json_decode($row->department))){
                    $data[] = $row;
                }
            }
            echo json_encode( $data );
        }
        //echo json_encode( FALSE );
    }

    public function get_department()
    {
        $department = isset($_POST['department'])?$_POST['department']:"";

        $result_department = $this->db->query("select * from department");

        $result = $result_department->result_array();

        if(!$result_department) {
            throw new exception("Department not found.");
        }
        $res = array();

        for($j = 0; $j < count($result); $j++)
        {
            $res[$result[$j]['id']] = $result[$j]['department_name'];
        }
        
        if ($department != "")
        {
            $selected_department = $department;
        }
        else
        {
            $selected_department = null;
        }
        
        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Department fetched successfully.", 'result'=>$res, 'selected_department'=>$selected_department);

        echo json_encode($data);
    }

}
