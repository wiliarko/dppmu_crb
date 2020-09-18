<?php
class Master_model extends CI_Model {

	function Transaksi_model(){
		parent::__construct();
		
		$this->load->database();
	}

	function get_list_collector($id=null) {
		
		$isWhere = !empty($id) ? "WHERE `a`.`id`='$id'" : "";
		$sql = "SELECT `b`.*, `a`.`id`, `a`.`update_at` as created, `a`.`nama`, `c`.`group_name`
				FROM `data_collector` as a
				LEFT JOIN `ws_users` as b ON `b`.marketing_id = a.id
				LEFT JOIN `ws_group_user` as c ON `b`.`group_id` = `c`.`group_id`
				$isWhere
				ORDER BY a.id DESC ";
		$query = $this->db->query($sql);
		
		return $query;

	}

	function get_list_users($id=null) {
		
		$isWhere = !empty($id) ? " AND a.`user_id`='$id'" : "";
		$sql = "SELECT a.user_id, a.first_name, a.username, a.group_id, b.group_name, a.status, a.created, a.updated
				FROM `ws_users` a
				JOIN `ws_group_user` as b ON `a`.`group_id` = `b`.`group_id`
				WHERE a.group_id NOT IN ('1','2') $isWhere
				ORDER BY a.user_id DESC ";
		$query = $this->db->query($sql);
		
		return $query;

	}

	function get_usergroup($id=null){

		$isWhere = !empty($id) ? "WHERE `group_id`='$id'" : "";
		$sql = "SELECT * FROM `ws_group_user` $isWhere ORDER BY `group_id` ASC";
		$rec = $this->db->query($sql);
		return $rec;

	}

	function check_users($id){

		$sql = 'SELECT * FROM `ws_users` WHERE marketing_id='.$id;
		$rec = $this->db->query($sql);
		return $rec;

	}

	function check_username($username, $id=null){

		$isWhere = isset($id) ? ' AND `marketing_id` != '. $id : "";
		$sql = 'SELECT * FROM `ws_users` WHERE `username`="'.$username.'"'. $isWhere;
		$rec = $this->db->query($sql);
		return $rec;

	}

	function update_collector($data, $id) {

		$res = $this->db->update('ws_users', $data, "marketing_id = '$id'");
		return $res;

	}

	function get_nofak_by_collector() {

		$sql = "SELECT * FROM `transaksi` GROUP BY nofakt ORDER BY nofakt DESC";
		$result = $this->db->query($sql);
		return $result;

	}

	function get_nofak_by_collector_mutasi($aColumns=null, $sWhere=null, $sLimit=null) {

		$sql = "SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM `transaksi` $sWhere";
		$sql.= " GROUP BY nofakt ";
		if($sLimit !== null)
		{
			$sql.= ' '.$sLimit;
		}
		$result = $this->db->query($sql);
		return $result;

	}

	function get_nofak_by_consumen($idkus) {

		$sql = "SELECT * FROM `transaksi` WHERE `kdcust`='$idkus' AND `kode_bayar` IS NULL GROUP BY nofakt ORDER BY nofakt DESC";
		$result = $this->db->query($sql);
		return $result;

	}

	function get_sisa_tagihan($nofakt, $type=null){

		$sel = !empty($type) ? "SUM(angsuran) as sisa" : "*";
		$sql = "SELECT $sel FROM `transaksi` WHERE `nofakt`='$nofakt' AND `kode_bayar` IS NULL";
		$result = $this->db->query($sql);
		return $result;

	}

	function get_collector_by_trx($nofakt){

		$sql = "SELECT collector_id, collector, kode_bayar FROM `transaksi` WHERE `nofakt`='$nofakt' ORDER BY kode_bayar ASC LIMIT 1 ";
		$result = $this->db->query($sql);
		return $result;

	}

	function mutasi_process($params, $collector_id, $nofakt) {
	
		$this->db->where("`nofakt` = '$nofakt' AND `collector_id` = '$collector_id'", NULL);
		$res = $this->db->update('transaksi', $params);
		return $res;
	
	}

	function mutasi_process_log($params) {
	
		$res = $this->db->insert('mutasi_log', $params);
		return $res;
	
	}

	function update_users($data, $id) {

		$res = $this->db->update('ws_users', $data, "user_id = '$id'");
		return $res;

	}

	function get_customer($id=null, $params=null, $orderlist=false) {

		$isWhere = !empty($id) ? " AND a.`id`='$id'" : "";
		$isJoin="";
		if(!@$params['status_bayar'] != '-'){
			switch (@$params['status_bayar']) {
				case 'bayar':
					if($params['start_date'] != '' && $params['end_date'] != ''){
						$xDueDate = " AND c.due_date BETWEEN '".$params['start_date']."' AND '".$params['end_date']."'";
					}else{
						$xDueDate = " AND c.due_date < NOW() ";
					}

					$isWhere .= $xDueDate." AND c.`kode_bayar`='99'";
					break;
				
				case 'belum_bayar':
					if($params['start_date'] != '' && $params['end_date'] != ''){
						$xDueDate = " AND c.due_date BETWEEN '".$params['start_date']."' AND '".$params['end_date']."'";
					}else{
						$xDueDate = " AND NOW() > c.due_date ";
					}

					$isWhere .= $xDueDate." AND c.`kode_bayar` IS NULL";
					break;

				case 'janji_bayar':
					$xDueDate="";
					if($params['start_date'] != '' && $params['end_date'] != ''){
						$xDueDate = " AND c.due_date BETWEEN '".$params['start_date']."' AND '".$params['end_date']."'";
					}
					
					$isWhere .= $xDueDate." AND c.kode_bayar = '01'";
					break;

				default:
					if($params['start_date'] != '' && $params['end_date'] != ''){
						$isWhere .= " AND c.due_date BETWEEN '".$params['start_date']."' AND '".$params['end_date']."'";
					}else{
						$isWhere .= "";
					}
					break;
			}
			
			$isJoin .= "JOIN `transaksi` c ON b.NOFAKT=c.nofakt";
		}else{
			if($params['start_date'] != '' && $params['end_date'] != ''){
				$isWhere .= " AND c.due_date BETWEEN '".$params['start_date']."' AND '".$params['end_date']."'";
			}else{
				$isWhere .= "";
			}
		}

		if($params['nofakt'] != '' && $params['nofakt'] != '-'){
            $isWhere .= ' AND b.nofakt = "'. $params['nofakt'] .'"';
        }
        if($params['kdcust'] != '' && $params['kdcust'] != '-'){
			$isWhere .= ' AND a.kdcust = "'. $params['kdcust'] .'"';
		}

		$sql = "SELECT a.*, b.nofakt, b.alamatof, b.NAMAKOF, b.ALAMATOF
				FROM `data_customer` a
				JOIN `master` b ON a.kdcust=b.kdcust
				$isJoin
				WHERE 1 $isWhere GROUP BY b.nofakt";

		if($orderlist==true){
			$sql.=  " ORDER BY ". $params['columns'][$params['request']['order'][0]['column']]."   ".$params['request']['order'][0]['dir']."  LIMIT ".$params['request']['start']." ,".$params['request']['length']." ";
		}
		$result = $this->db->query($sql);
		return $result;

	}

	function get_collector($id=null) {

		$isWhere = !empty($id) ? " AND `id`='$id'" : "";
		$sql = "SELECT * FROM `data_collector` WHERE 1 $isWhere";
		$result = $this->db->query($sql);
		return $result;

	}

	function get_deskcall($id=null, $params=null, $orderlist=false) {

		$isWhere = !empty($id) ? " AND `id`='$id'" : "";
		if($params['via'] != ''){
            $isWhere .= ' AND via = "'. $params['via'] .'"';
        }
		$sql = "SELECT * FROM `deskcall` WHERE 1 $isWhere";

		if($orderlist==true){
			$sql.=  " ORDER BY ". $params['columns'][$params['request']['order'][0]['column']]."   ".$params['request']['order'][0]['dir']."  LIMIT ".$params['request']['start']." ,".$params['request']['length']." ";
		}

		$result = $this->db->query($sql);
		return $result;

	}

	function update_to_lunas($data, $nofakt) {

		$res = $this->db->update('master', $data, "NOFAKT = '$nofakt'");
		return $res;

	}

}
?>