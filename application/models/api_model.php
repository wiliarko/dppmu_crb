<?php
class Api_model extends CI_Model {

	function __construct(){
		parent::__construct();		
		$this->load->database();

		$this->_user		= 'ws_users';
		$this->_groupuser	= 'ws_group_user';
		$this->_trx	= 'transaksi';
	}


	function get_login($params)
	{
		$sql 	= '
				SELECT a.*, b.group_name
				FROM '. $this->_user .' as a 
				INNER JOIN '. $this->_groupuser .' as b on a.group_id = b.group_id
				WHERE a.username = "'.$params['uname'].'"
					AND a.passwd = "'.md5($params['pass']).'"
					AND a.status = 1
			';
		$query 	= $this->db->query($sql);
		return $query;
	}

	function check_device($uname, $deviceid)
	{
		
		$sql = 'SELECT * FROM  '.$this->_user.' WHERE username = "'.$uname.'"';
		$res = $this->db->query($sql);
		if($res->num_rows()){
			$rec = $res->row();
			if($rec->device_id == ''){
				$sql = 'UPDATE '.$this->_user.' SET device_id="'.$deviceid.'" WHERE username = "'.$uname.'"';
				$this->db->query($sql);
			}
			$sql1 = 'SELECT * FROM  '.$this->_user.' WHERE device_id="'.$deviceid.'" AND username = "'.$uname.'"';
			$result = $this->db->query($sql1);
		}
		return $result;

	}

	function checkfaktur_blmbayar($notrx = null)
	{
		$sql = 'SELECT * FROM  '.$this->_trx.' WHERE `id` = "'.$notrx.'" AND `kode_bayar` = "99"';
		$result = $this->db->query($sql);
		return $result;
	}

	function get_deviceid($collector_id)
	{
		
		$sql = 'SELECT * FROM  '.$this->_user.' WHERE marketing_id = "'.$collector_id.'"';
		$result = $this->db->query($sql);
		return $result;

	}

}
?>