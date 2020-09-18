<?php
class Laporan_model extends CI_Model {

	function Transaksi_model(){
		parent::__construct();
		
		$this->load->database();
	}
	

	/*function get_laporan_lama($params, $type) {
		switch ($type) {
			case 'bayar': case 'lunas': $selec = "X.`kode_bayar` = '99'"; break;
			case 'blm_bayar': $selec = " (X.`kode_bayar` != '99' OR X.`kode_bayar` IS NULL)"; break;
			case 'janji_bayar': $selec = " X.`kode_bayar` = '01'"; break;
			case 'percepatan': $selec = " X.`kode_bayar` = '02'"; break;
			case 'sisa_titipan': $selec = " X.`kode_bayar` = '03'"; break;
			case 'tarik_barang': $selec = " X.`kode_bayar` = '04'"; break;
			case 'tidak_ada_orang': $selec = " X.`kode_bayar` = '05'"; break;
			case 'alasan_kesehatan': $selec = " X.`kode_bayar` = '06'"; break;
			case 'alasan_ekonomi': $selec = " X.`kode_bayar` = '07'"; break;
			case 'komplain_produk': $selec = " X.`kode_bayar` = '08'"; break;
			case 'rencana_tarik_Barang': $selec = " X.`kode_bayar` = '09'"; break;
			case 'pindah_alamat': $selec = " X.`kode_bayar` = '10'"; break;
			case 'tidak_ada_barang': $selec = " X.`kode_bayar` = '11'"; break;
			case 'ada_barang': $selec = " X.`kode_bayar` = '12'"; break;
			case 'bangkrut': $selec = " X.`kode_bayar` = '13'"; break;
			case 'kabur': $selec = " X.`kode_bayar` = '14'"; break;
			case 'force_mejeur': $selec = " X.`kode_bayar` = '15'"; break;
			default: $selec = "1"; break;
		}

		$sql = "SELECT X.* FROM (SELECT  max(id) AS id, id_trx, nofakt, due_date, dddate, mydate, kdcust, namakons, collector_id, collector, tlpn, angsuran, tenor, angke, no_dpk, kode_bayar, bayar, bayar_sebagian, bayar_sisa, nextDate, keterangan, cutOff_id, cutOff, update_via, update_by_id, update_by, published, publish_by_id, publish_by, published_date, created_date FROM `transaksi_log` GROUP by nofakt ) AS X WHERE $selec";

		if($params['nofakt'] !='' OR $params['kdcust'] !='' OR $params['collector'] !=''){
			if($params['nofakt'] != '')
				$sql .= ' AND X.nofakt = "'. $params['nofakt'] .'"';
			if($params['kdcust'] != '')
				$sql .= ' AND X.kdcust = "'. $params['kdcust'] .'"';
			if($params['collector'] != '')
				$sql .= ' AND X.collector_id = "'. $params['collector'] .'"';
		}

		if($params['start_date'] != '' && $params['end_date'] != ''){
			if(in_array($type, array("janji_bayar", "tarik_barang"))) $date = 'X.`nextDate`';
			else $date = 'X.`due_date`';

			$sql .= ' AND ('.$date.' >= "'.$params['start_date'].'" AND '.$date.' <= "'.$params['end_date'].'") ';
		}

		if($params['bulan'] != '' && $params['tahun'] != '')
		{
			$sql .= ' AND MONTH(X.`created_date`)="'.$params['bulan'].'" AND YEAR(X.`created_date`)="'.$params['tahun'].'"';
		}

		$sql .= ' ORDER BY X.due_date ASC';
		$result = $this->db->query($sql);
		return $result;
	}*/

	function get_laporan($params, $type) {
		switch ($type) {
			case 'bayar': case 'lunas': $selec = "b.`kode_bayar` = '99'"; break;
			case 'blm_bayar': $selec = " (b.`kode_bayar` != '99' OR b.`kode_bayar` IS NULL)"; break;
			case 'janji_bayar': $selec = " b.`kode_bayar` = '01'"; break;
			case 'percepatan': $selec = " b.`kode_bayar` = '02'"; break;
			case 'sisa_titipan': $selec = " b.`kode_bayar` = '03'"; break;
			case 'tarik_barang': $selec = " b.`kode_bayar` = '04'"; break;
			case 'tidak_ada_orang': $selec = " b.`kode_bayar` = '05'"; break;
			case 'alasan_kesehatan': $selec = " b.`kode_bayar` = '06'"; break;
			case 'alasan_ekonomi': $selec = " b.`kode_bayar` = '07'"; break;
			case 'komplain_produk': $selec = " b.`kode_bayar` = '08'"; break;
			case 'rencana_tarik_Barang': $selec = " b.`kode_bayar` = '09'"; break;
			case 'pindah_alamat': $selec = " b.`kode_bayar` = '10'"; break;
			case 'tidak_ada_barang': $selec = " b.`kode_bayar` = '11'"; break;
			case 'ada_barang': $selec = " b.`kode_bayar` = '12'"; break;
			case 'bangkrut': $selec = " b.`kode_bayar` = '13'"; break;
			case 'kabur': $selec = " b.`kode_bayar` = '14'"; break;
			case 'force_mejeur': $selec = " b.`kode_bayar` = '15'"; break;
			default: $selec = "1"; break;
		}

		$sql = "SELECT a.*, b.id, b.kode_bayar, b.bayar, b.collector_id, b.collector, b.kdcust, b.namakons, b.due_date, b.angke, b.no_dpk, b.keterangan
				FROM (SELECT nofakt, MAX(created_date) AS created_date, update_by_id, id_trx, dddate, mydate, tlpn, angsuran, tenor, bayar_sebagian, bayar_sisa, nextDate, cutOff_id, cutOff, update_via, update_by, published, publish_by_id, publish_by, published_date FROM transaksi_log GROUP BY nofakt) AS a
				JOIN transaksi_log b ON b.nofakt=a.nofakt AND b.created_date=a.created_date WHERE $selec";

		if($params['nofakt'] !='' OR $params['kdcust'] !='' OR $params['collector'] !=''){
			if($params['nofakt'] != '')
				$sql .= ' AND a.nofakt = "'. $params['nofakt'] .'"';
			if($params['kdcust'] != '')
				$sql .= ' AND b.kdcust = "'. $params['kdcust'] .'"';
			if($params['collector'] != '')
				$sql .= ' AND b.collector_id IN ('. $params['collector'] .')';
		}

		if($params['start_date'] != '' && $params['end_date'] != ''){
			if(in_array($type, array("janji_bayar", "tarik_barang"))) $date = 'a.`nextDate`';
			else $date = 'b.`due_date`';

			$sql .= ' AND ('.$date.' >= "'.$params['start_date'].'" AND '.$date.' <= "'.$params['end_date'].'") ';
		}

		if($params['bulan'] != '' && $params['tahun'] != '')
		{
			$sql .= ' AND MONTH(b.`created_date`)="'.$params['bulan'].'" AND YEAR(b.`created_date`)="'.$params['tahun'].'"';
		}

		$sql .= ' ORDER BY b.due_date ASC';
		$result = $this->db->query($sql);
		return $result;
	}

	function get_dpk_trx($id) {
	
		$q = $this->db->query("SELECT * FROM `transaksi_log` WHERE `id` = '$id'");
		return $q;
	
	}

	function get_cetak_piutang($nofakt, $page){
		
		$limit = ($page==1)  ? "LIMIT 0, 14" : "LIMIT 14, 25";
		$sql = "SELECT * FROM `transaksi` WHERE `nofakt`='$nofakt' AND `post`='0' AND `published_date` IS NOT NULL ORDER BY `id` ASC $limit";
		$result = $this->db->query($sql);
		return $result;

	}

	function get_total_pinjaman($nofakt){

		$sql = "SELECT (angsuran * angke) as total FROM `transaksi` WHERE `nofakt`='$nofakt' ORDER BY `id` DESC LIMIT 1 ";
		$result = $this->db->query($sql)->row();
		return $result;

	}

	function get_lunas_bayar($params){

		$sql = "SELECT a.id, b.nama as collector, a.NOFAKT as nofakt, a.DDDATE as dddate, a.MYDATE as mydate, a.NAMAKONS as namakons, '-' as due_date, a.TELEPHONE as tlpn, a.ANGSURAN as angsuran, a.TENOR as tenor, a.ANGKE as angke, '04' as kode_bayar, NULL as no_dpk, NULL as bayar, NULL as keterangan
				FROM `master` a
				JOIN data_collector b ON a.COLLECTOR=b.nama
				WHERE a.`ANGKE` = '0' AND a.`STATUS` = 'LUNAS'";

		if($params['nofakt'] !='' OR $params['kdcust'] !='' OR $params['collector'] !=''){
			if($params['nofakt'] != '')
				$sql .= ' AND NOFAKT = "'. $params['nofakt'] .'"';
			if($params['kdcust'] != '')
				$sql .= ' AND KDCUST = "'. $params['kdcust'] .'"';
			if($params['collector'] != '')
				$sql .= ' AND collector_id = "'. $params['collector'] .'"';
		}

		$sql .= ' ORDER BY nofakt ASC';
		$result = $this->db->query($sql);
		return $result;

	}

	function get_saldo($params) {
		
		$sql = "SELECT * FROM `saldo` WHERE 1";

		if($params['start_date'] != '' && $params['end_date'] != ''){
			$sql .= ' AND (`date_cutoff` >= "'.$params['start_date'].'" AND `date_cutoff` <= "'.$params['end_date'].'") ';
		}

		if($params['userkasir'] != ''){
			$sql .= ' AND `update_by_id` = "'. $params['userkasir'] .'" ';
		}

		$sql .= ' ORDER BY `date_cutoff` ASC';
		$result = $this->db->query($sql);
		return $result;
	}

	function get_master($nofakt){
		
		$sql = "SELECT * FROM `master` WHERE NOFAKT='$nofakt' ";
		$q = $this->db->query($sql);
		return $q;

	}

	function get_nofakt_lunas(){
		
		$sql = "SELECT NOFAKT as nofakt FROM `master` WHERE `ANGKE` = '0' AND `STATUS` = 'LUNAS' ";
		$q = $this->db->query($sql);
		return $q;

	}

	function get_customer_lunas(){
		
		$sql = "SELECT KDCUST as kdcust, NAMAKONS as nama FROM `master` WHERE `ANGKE` = '0' AND `STATUS` = 'LUNAS' ";
		$q = $this->db->query($sql);
		return $q;

	}

	function get_collector_lunas(){

		$sql = "SELECT b.id, b.nama
				FROM `master` a 
				JOIN data_collector b ON a.COLLECTOR=b.nama
				WHERE a.`ANGKE` = '0' AND a.`STATUS` = 'LUNAS'
				GROUP BY b.id";
		$q = $this->db->query($sql);
		return $q;

	}

	function get_customer($id=null, $params=null, $orderlist=false) {

		$isWhere = !empty($id) ? " AND a.`id`='$id'" : "";
		$isJoin="";
		if($params['start_date'] != '' && $params['end_date'] != ''){
			$isJoin .= "JOIN `transaksi` c ON b.NOFAKT=c.nofakt";
			$isWhere .= " AND DATE_FORMAT(c.created_date,'%Y-%m-%d') BETWEEN '".$params['start_date']."' AND '".$params['end_date']."'";
		}else{
			$isWhere .= "";
		}

		$sql = "SELECT a.*, b.nofakt, b.alamatof, b.NAMAKOF, b.ALAMATOF, c.created_date
				FROM `data_customer` a
				JOIN `master` b ON a.kdcust=b.kdcust
				$isJoin
				WHERE 1 $isWhere GROUP BY b.nofakt";
				
		$result = $this->db->query($sql);
		return $result;

	}

}
?>