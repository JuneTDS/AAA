<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MX_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
    }

	public function index()
	{
		$bc = array(array('link' => '#', 'page' => 'Dashboard'));
        $meta = array('page_title' => 'Dashboard', 'bc' => $bc, 'page_name' => 'Dashboard');

        $this->page_construct('welcome_message', $meta, $this->data);
		// $this->page_construct('welcome_message', $this->meta, $this->data);
	}
}
