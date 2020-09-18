<?php
class Log_model extends CI_Model {

	function Log_model(){
		parent::__construct();
		
		$this->load->database();
	}
	
	function insert($data){
		$res = $this->db->insert('ws_log_activity', $data);
		return $res;
	}
	
}
?>