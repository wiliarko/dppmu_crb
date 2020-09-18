<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class _adminsite extends CI_Controller {

	function _admin(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('encrypt', 'form_validation', 'session'));
    	$this->load->library('authentication');
	}
	
	public function index(){
		redirect('/backend/auth/login');
	}

	public function skin_config() {
		$this->load->view('backend/skin-config');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */