<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	function Auth(){
		parent::__construct();

		$this->load->library(array('authentication'));
    	$this->load->model(array('backend/auth_model', 'backend/log_model'));
    	$this->load->helper('cookie');              
	}

	function index(){
		redirect('/auth/login');
	}

	function login() {
		$this->load->helper(array('form', 'url','directory', 'cookie'));
		  
		$login = array($this->input->post('uname'), $this->input->post('upass'));
		$data['error'] = '';
		
		// 
		if(!empty($login[0]) && !empty($login[1])){
			if($this->authentication->process_login($login)){
		    	// Login successful, let's redirect.
		    	
		    	// LOG LOGIN FOR USER
		    	$data = array(
					'user_name' => $this->session->userdata['logged']['username'],
					'updated' 	=> date("Y-m-d H:i:s"),
					'content' 	=> 'Login Aplikasi.'
				);
				$this->log_model->insert($data);
				
		    	$this->authentication->redirect();
		  	}else{
		  		$data['error'] = 'Username / Password Failed.'; 
		  	}
		}

		if(isset($this->session->userdata['logged']['uid'])){
			redirect( base_url() ."backend/home");
		}else{
			$this->load->view('backend/login',$data);
		}
	}	

	function logout(){
		// LOG LOGOUT FOR USER
    	/*$data = array(
			'IP' => $_SERVER['REMOTE_ADDR'],
			'content' 	=> 'Logout Aplikasi.',
			'created_time' => date("Y-m-d H:i:s")
		);
		
		$this->log_model->insert($data);*/
		
		if($this->authentication->logout()){
			redirect('/backend/auth/login');
		}
	}

        
}
