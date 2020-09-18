<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Authentication {

	var $CI = null;
	var $index_redirect = 'backend/dashboard';
	/*var $index_redirect = 'backend/home';
	var $index_redirect_2 = 'backend/transaksi/dpk';*/
	var $login_redirect = 'backend/auth/login';

	function Authentication($props = array()){
		$this->CI =& get_instance();
		// Load additional libraries, helpers, etc.
		$this->CI->load->library('session');
		$this->CI->load->database();
		$this->CI->load->helper('url');
		if (count($props) > 0)
		{
			$this->initialize($props);
		}
	}
	
	
	/**
	 * initialize class preferences
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */	
	function initialize($props = array()){
		if (count($props) > 0) 
		{
			foreach ($props as $key => $val)
			{
				$this->$key = $val;
			}
		}	
	}

	function process_login($login = NULL){
		// A few safety checks
		// Our array has to be set
		if(!isset($login))
			return FALSE;
			
		//Our array has to have 2 values
		//No more, no less!
		if(count($login) != 2)
			return FALSE;
			
		$name = $login[0];
		$new_password = md5($login[1]);

    	if($name != "" && $new_password != ""){
      		$sql 	= '
				SELECT a.*, b.group_name
				FROM ws_users as a 
				INNER JOIN ws_group_user as b on a.group_id = b.group_id
				WHERE a.username = "'.$name.'"
					AND a.passwd = "'.$new_password.'"
					AND a.status = 1
			';
      		$query = $this->CI->db->query($sql);

      		if ($query->num_rows() == 1){
        		// Our user exists, set session.
        		$this->CI->load->helper('cookie');
				$row = $query->row();
       			$newdata['logged'] = array(
					'uid'		=> $row->user_id,
					'id'		=> $row->marketing_id,
					'username'	=> $row->username,
					'realname'	=> $row->first_name . ' ' .$row->last_name,
					'groupid'	=> $row->group_id,
					'groupname'	=> $row->group_name,
					'created'	=> $row->created,
					'avatar'	=> $row->avatar,
					'isprint'	=> $row->is_print,
				);
				$this->CI->session->set_userdata($newdata);
        		return TRUE;
      		}else{
        		// No existing user.
        		return FALSE;
      		}
    	}
	}
	
	/**
	 *
	 * This function redirects users after logging in
	 *
	 * @access	public
	 * @return	void
	 */		
	function redirect(){
		if ($this->CI->session->userdata('redirected_from') == FALSE)
		{
			redirect($this->index_redirect);
			/*if($this->CI->session->userdata['logged']['groupid']==1){
				redirect($this->index_redirect);
			}else{
				redirect($this->index_redirect_2);
			}*/
		} else {
			redirect($this->CI->session->userdata('redirected_from'));
		}
	}
	
	/**
	 *
	 * This function restricts users from certain pages.
	 * use restrict(TRUE) if a user can't access a page when logged in
	 *
	 * @access	public
	 * @param	boolean	wether the page is viewable when logged in
	 * @return	void
	 */	
	function restrict(){
		// If the user is logged in and he's trying to access a page
		// he's not allowed to see when logged in,
		// redirect him to the index!
		/*
		if ($logged_out && $this->logged_in())
		{
			redirect($this->index_redirect);
		}
		*/
		// If the user isn't logged in and he's trying to access a page
		// he's not allowed to see when logged out,
		// redirect him to the login page!
		if ($this->logged_in() == FALSE) {
			redirect($this->login_redirect);
		}
	}
	
	/**
	 *
	 * Checks if a user is logged in
	 *
	 * @access	public
	 * @return	boolean
	 */	
	function logged_in()
	{
		if (!isset($this->CI->session->userdata['logged']['username'])){
			return FALSE;
		}else {
			return TRUE;
		}
	}

	function logout() {
		$newdata['logged'] = '';
		$this->CI->session->set_userdata($newdata);
		return TRUE;
	}

	
}
// End of library class
// Location: mcsystem/application/libraries/Authentication.php
