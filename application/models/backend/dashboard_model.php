<?php
class Dashboard_model extends CI_Model {

	function __construct(){
		parent::__construct();
		
		$this->load->database();

		$this->groupid = $this->session->userdata['logged']['groupid'];
		$this->uid = $this->session->userdata['logged']['uid'];
		$this->id = $this->session->userdata['logged']['id'];
	}

	function get_jml_jb() {

		$isWhere = (!in_array($this->groupid, array(1,4,6,7,8,9))) ? "AND `update_by_id` = '$this->uid'" : "";
		$thisYear = date('Y'); 

		$sql = "SELECT * FROM `transaksi` WHERE `kode_bayar` = '01' AND YEAR(`nextDate`) = '$thisYear' $isWhere";
		$result = $this->db->query($sql);
		return $result;

	}

	function get_jml_bayar() {

		$isWhere = (!in_array($this->groupid, array(1,4,6,7,8,9))) ? "AND `publish_by_id` = '$this->uid'" : "";
		$thisYM = date('Y-m');

		$sql = "SELECT * FROM `transaksi` WHERE `kode_bayar` = '99' AND published = '1' AND DATE_FORMAT(`created_date`, '%Y-%m') = '$thisYM' $isWhere";
		$result = $this->db->query($sql);
		return $result;

	}

	function get_jml_blm_bayar() {

		$isWhere = (!in_array($this->groupid, array(1,4,6,7,8,9))) ? "AND `update_by_id` = '$this->uid'" : "";
		$thisYM = date('Y-m');

		$sql = "SELECT * FROM `transaksi` WHERE `kode_bayar` IS NULL AND DATE_FORMAT(`due_date`, '%Y-%m') < '$thisYM' $isWhere";
		$result = $this->db->query($sql);
		return $result;

	}

	function get_revenue() {

		$isWhere = (!in_array($this->groupid, array(1,4,6,7,8,9))) ? "AND `publish_by_id` = '$this->uid'" : "";
		$thisYM = date('Y-m');

		$sql = "SELECT SUM(x.revenue) AS revenue FROM
				(
			     	SELECT IF(kode_bayar='00', (angsuran-bayar_sisa), angsuran) AS revenue
			      	FROM `transaksi`
			      	WHERE `kode_bayar` IN ('00', '99') AND `published` = '1' AND DATE_FORMAT(`created_date`, '%Y-%m') = '$thisYM' $isWhere
				) AS x ";
		$result = $this->db->query($sql);
		return $result;

	}

	function get_chart_revenue($yearMonth) {

		$isWhere = (!in_array($this->groupid, array(1,4,6,7,8,9))) ? "AND `update_by_id` = '$this->uid'" : "";
		$sql = "SELECT SUM(x.revenue) AS revenue FROM
				(
			     	SELECT IF(kode_bayar='00', (angsuran-bayar_sisa), angsuran) AS revenue
			      	FROM `transaksi`
			      	WHERE `kode_bayar` IN ('00', '99') AND `published` = '1' AND DATE_FORMAT(`created_date`, '%Y-%m') = '$yearMonth' $isWhere
				) AS x ";
		$result = $this->db->query($sql);
		return $result;

	}

	function get_kasir_event() {

		$isWhere = (!in_array($this->groupid, array(1,4,6,7,8,9))) ? "AND `update_by_id` = '$this->uid'" : "";
		$sql = "SELECT id, nofakt, namakons, collector, angsuran, bayar_sisa, angke, kode_bayar, publish_by AS kasir, created_date
		      	FROM `transaksi`
		      	WHERE (`update_via`='kasir' OR `published`='1') AND `kode_bayar` IS NOT NULL $isWhere 
		      	ORDER BY created_date DESC LIMIT 0,100";
		$result = $this->db->query($sql);
		return $result;

	}

	function get_status_bayar($collector=null, $bulan=null, $tahun=null, $status_bayar=null) {
			
		$isWhere = (!in_array($this->groupid, array(1,2,3,4,6,7,8,9))) ? " AND `b.update_by_id` = '$this->uid' " : "";
		
		if($bulan !== '' && $tahun !== '')
		{
			$isWhere .= " AND MONTH(b.`created_date`)='$bulan' AND YEAR(b.`created_date`)='$tahun' ";
		}

		if($collector !== ''){
			if(is_array($collector)){
				$isCollector='';
				for($i=0;$i<count($collector);$i++){
					$isCollector .= $collector[$i];
					if($i < count($collector) - 1) $isCollector.=',';
				}
				$isWhere .= " AND b.collector_id IN ($isCollector) ";
			}else{
				$isWhere .= " AND b.collector_id='$collector' ";
			}
		}else{
			if($this->groupid == 2){
				$isWhere .= " AND b.collector_id='$this->id' ";
			}
		}

		if($status_bayar !== null){
			$isWhere .= "AND b.kode_bayar='$status_bayar' ";
		}

		$sql = "SELECT b.nofakt, b.kode_bayar, b.created_date, b.update_by_id, b.collector_id
				FROM (SELECT nofakt, MAX(created_date) AS created_date, collector_id, update_by_id FROM transaksi_log GROUP BY nofakt) AS a
				JOIN transaksi_log b ON b.nofakt=a.nofakt AND b.created_date=a.created_date WHERE b.kode_bayar != '99' $isWhere ";
		
		$result = $this->db->query($sql);
		return $result;
	}
}
?>