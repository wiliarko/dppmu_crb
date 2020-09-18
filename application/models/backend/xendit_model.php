<?php
class Xendit_model extends CI_Model {

	public function __construct(){

		$this->load->database();

	}
	//============== GET data api==============//
	function get_xendit_api($last_transaction) {
		
		$data = array(
			'ApiKey'       	=> 'ca3affbf9c1cb3c1b7e251de31654f3037911b4a',
			'LastDate'      => $last_transaction
			);
		$url = "http://sewa-beli.net/dppmu_api/Xendit_api/getXendit";
		$ch = curl_init($url); 
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output = curl_exec($ch); 
		curl_close($ch);
		$transaction = json_decode($output, TRUE);      
		return $transaction;
	}
	function post_xendit_api($data) {
		$transaction=$this->db->insert_batch('xendit_payment', $data);
		return $transaction;
	}


	function get_last_transaction($sWhere=null, $sOrder=null, $sLimit=null)
	{
			$sql = "SELECT `transaction_timestamp` FROM `xendit_payment`  
					ORDER BY `xendit_payment`.`transaction_timestamp`  DESC";
            $query = $this->db->query($sql);
        	return $query->row();
	}
	function get_data($sWhere=null, $sOrder=null, $sLimit=null)
	{
			$sql = "SELECT * FROM xendit_payment $sWhere";
        	if($sOrder !== null && $sLimit !==null){
        		$sql.=" $sOrder $sLimit ";
        	}
                $query = $this->db->query($sql);
        	return $query;
	}
}
?>