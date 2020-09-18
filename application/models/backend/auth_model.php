<?php
class Auth_model extends CI_Model {

	function Auth_model(){
		parent::__construct();
		
		$this->load->database();
	}
	
}
?>