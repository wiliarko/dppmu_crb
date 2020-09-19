<?php
class Transaksi_model extends CI_Model {

	function Transaksi_model(){
		parent::__construct();
		
		$this->load->database();
	}
	

	function get_dpk($params, $api=null, $orderlist=true) {
	
		$isWhere="";
		// OR @$params['request'] !=''
		if(@$params['export'] !='' OR $params['nofakt'] !='' OR $params['kdcust'] !='' OR $params['collector'] !='' OR @$params['status_bayar'] !='' OR (@$params['start_date'] != '' && @$params['end_date'] != '') OR (@$params['start_pay_date'] != '' && @$params['end_pay_date'] != '')){
		
			if($params['nofakt'] != '' && $params['nofakt'] != '-'){
				$isWhere .= ' AND a.nofakt = "'. $params['nofakt'] .'"';
			}
			if($params['kdcust'] != '' && $params['kdcust'] != '-'){
				$isWhere .= ' AND a.kdcust = "'. $params['kdcust'] .'"';
			}
			if($params['collector'] != '' && $params['collector'] != '-'){
				$isWhere .= ' AND a.collector_id = "'. $params['collector'] .'"';
			}
			if(@$params['start_date'] != '' && @$params['end_date'] != ''){
				$isWhere .= ' AND (a.`due_date` >= "'.$params['start_date'].'" AND a.`due_date` <= "'.$params['end_date'].'") ';
			}
			if(@$params['start_pay_date'] != '' && @$params['end_pay_date'] != ''){
				$isWhere .= ' AND (date(a.`created_date`) >= "'.$params['start_pay_date'].'" AND date(a.`created_date`) <= "'.$params['end_pay_date'].'") AND a.kode_bayar IN ("99", "00") ';
			}
			if(@$params['status_bayar'] != '-' && @$params['status_bayar'] != ''){
				switch ($params['status_bayar']) {
					case 'bayar': $isWhere .= ' AND a.kode_bayar IN ("99", "00")'; break;
					case 'belum_bayar': $isWhere .= ' AND (a.kode_bayar NOT IN ("99", "00") OR a.kode_bayar IS NULL)'; break;
					case 'janji_bayar': $isWhere .= ' AND a.kode_bayar="01"'; break;
					default: $isWhere .= ''; break;
				}
			}

			if(@$params['outlet'] != '-' && @$params['outlet'] != ''){
				switch ($params['outlet']) {
					case 'ALFAMART': $isWhere .= ' AND c.retail_outlet_name = "ALFAMART"'; break;
					case 'INDOMARET': $isWhere .= ' AND c.retail_outlet_name = "INDOMARET"'; break;
					default: $isWhere .= ''; break;
				}
			}

			if( !empty($params['request']['search']['value']) ) {   
				$isWhere .=" AND (";
				$isWhere .=" a.nofakt LIKE '".$params['request']['search']['value']."%'";
				$isWhere .=" OR a.namakons LIKE '".$params['request']['search']['value']."%'";
				$isWhere .=" OR a.collector LIKE '".$params['request']['search']['value']."%'";
				$isWhere .=" OR a.no_dpk LIKE '".$params['request']['search']['value']."%' )";
			}

			$cLunas = ($this->session->userdata['logged']['groupid'] != '1') ? "b.`ANGKE` > 0" : "1";

			if(empty($api)){
				$sql = "SELECT X.* FROM ((SELECT a.id, a.nofakt, a.collector, a.namakons, a.due_date, a.angsuran, a.angke, a.no_dpk, a.kode_bayar, a.bayar, a.keterangan, a.published, a.created_date, c.retail_outlet_name 
					FROM `transaksi` a
                    JOIN `master` b ON a.nofakt=b.NOFAKT
                    LEFT JOIN `xendit_request` c ON a.id=c.transaksi_id
					WHERE $cLunas $isWhere
					ORDER BY a.`due_date` ASC)

					UNION

					(SELECT 'total' AS id, NULL AS nofakt, NULL AS collector, NULL AS namakons, '_' AS due_date, SUM(a.angsuran), NULL AS angke, NULL AS no_dpk, NULL AS kode_bayar, NULL AS bayar, NULL AS keterangan, NULL AS published, NULL AS created_date, c.retail_outlet_name
					FROM `transaksi` a
					JOIN `master` b ON a.nofakt=b.NOFAKT
					LEFT JOIN `xendit_request` c ON a.id=c.transaksi_id
					WHERE $cLunas $isWhere LIMIT 1)) AS X WHERE X.angsuran <> 0";

					if($orderlist==true){
						$ordename = ($params['columns'][$params['request']['order'][0]['column']]!=='id') ? $params['columns'][$params['request']['order'][0]['column']] : "due_date"; 
						$sql.=  " ORDER BY ". $ordename ."   ".$params['request']['order'][0]['dir']."  LIMIT ".$params['request']['start']." ,".$params['request']['length']." ";
					}else{
						$sql.= " ORDER BY due_date ASC";
					}
			} else{
				$sql = "SELECT id, nofakt, collector, namakons, due_date, angsuran, angke, no_dpk, kode_bayar, bayar, keterangan, published, created_date 
					FROM `transaksi` 
					ORDER BY due_date ASC";
			}

			$sql .= '';
			$result = $this->db->query($sql);
			return $result;
		} else {
			return false;
		}
	}

	function get_cutOff_id()
	{

		$sql = "SELECT cutOff_id FROM `saldo` ORDER BY cutOff_id DESC LIMIT 1";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0){
			$cutOff_id = $query->row()->cutOff_id;
			return $cutOff_id+1;
		}else{
			return '1';
		}

	}

	function get_total_kas()
	{

		$uid = $this->session->userdata['logged']['uid'];
		$isWhere = (in_array($this->session->userdata['logged']['groupid'], array('1', '6'))) ? (($uid!='1') ? " AND `publish_by_id` = '$uid'" : "") : "";
		$today = date("Y-m-d H:i:s");
		$where = $isWhere. " AND published_date <= '$today'";

		$sql = "SELECT SUM(X.total) as total FROM (
					SELECT SUM(angsuran) as total
					FROM `transaksi_log`
					WHERE kode_bayar='99' AND published = '1' AND cutOff='0' AND cutOff_id <> '' $where

					UNION ALL

					SELECT SUM(bayar_sebagian) as total
					FROM `transaksi_log`
					WHERE kode_bayar='00' AND published = '1' AND cutOff='0' AND cutOff_id <> '' $where
				) AS X LIMIT 1";
		$result = $this->db->query($sql);
		return $result;
	}

	function angsuran_last_check($nofakt, $angke) {

		$q = $this->db->query("
				SELECT * FROM `transaksi`
				WHERE `nofakt` = '$nofakt' AND `angke` < '$angke'
				ORDER BY `transaksi`.`angke` DESC
				LIMIT 1");
		return $q;

	}

	function angsuran_next_check($nofakt, $angke) {

		$q = $this->db->query("
				SELECT * FROM `transaksi`
				WHERE `nofakt` = '$nofakt' AND `angke` > '$angke'
				ORDER BY `transaksi`.`angke` ASC
				LIMIT 1");
		return $q;

	}

	function get_no_faktur($id=null,$kdcust=null,$keyword=null) {

		$isWhere = ($this->session->userdata['logged']['groupid'] != '1') ? "b.`ANGKE` > 0" : "1";
		$sql = "SELECT
				 a.`nofakt`
				FROM `transaksi` a
				JOIN `master` b ON a.nofakt=b.NOFAKT
				WHERE $isWhere";
		
		$sql.= !empty($id) ? " AND a.collector_id='$id'" : "";
		$sql.= !empty($kdcust) ? " AND a.kdcust='$kdcust'" : "";
		$sql.= !empty($keyword) ? " AND a.nofakt LIKE '%$keyword%'" : "";
		$sql.= " GROUP BY a.`nofakt` ORDER BY `nofakt`";

		$q = $this->db->query($sql);
		return $q;

	}

	function get_customer($collector_id=null, $keyword=null, $kdcust=null) {

		// JOIN transaksi b ON a.kdcust=b.kdcust
		$sql = "SELECT a.kdcust, a.nama
				FROM `data_customer` a
				
				WHERE 1
				";
		
		// $sql.= !empty($collector_id) ? " AND b.collector_id='$collector_id'" : "";
		$sql.= !empty($kdcust) ? " AND a.kdcust='$kdcust'" : "";
		$sql.= !empty($keyword) ? " AND a.nama LIKE '%$keyword%'" : "";
		$sql.= " GROUP BY a.`kdcust`, a.`nama` ORDER BY a.`nama` ASC";
		// die($sql);
		$q = $this->db->query($sql);
		return $q;

	}

	function get_collector($id_collector=null, $with=null) {

		$isWhere = !empty($id_collector) ? (empty($with) ? "WHERE id != '$id_collector'" : "WHERE id = '$id_collector'") : "";
		$q = $this->db->query("SELECT * FROM `data_collector` $isWhere");
		return $q;

	}

	function get_range($nofakt=null) {

		$q = $this->db->query("SELECT * FROM `transaksi` WHERE nofakt='$nofakt' ORDER BY angke ASC");
		return $q;

	}

	function get_userkasir($user_id=null) {

		$isWhere = !empty($user_id) ? " AND user_id = '$user_id'" : "";
		$q = $this->db->query("SELECT user_id, first_name FROM `ws_users` WHERE group_id IN (1,3) $isWhere");
		return $q;

	}

	function get_dpk_trx($id) {
	
		$where = "(SELECT telephone FROM `data_customer_histori` WHERE kdcust=a.kdcust
		           ORDER BY created_date DESC LIMIT 1) AS notlpn1,";
		$where .= "(SELECT TELEPHONE FROM `master` WHERE `KDCUST` = a.kdcust LIMIT 1) AS notlpn2";         
		$q = $this->db->query("SELECT a.*, $where FROM `transaksi` a WHERE a.`id` = '$id'");
		return $q;
	
	}

	function dpk_process($params, $id) {
	
		$this->db->where('id', $id);
		$res = $this->db->update('transaksi', $params);
		return $res;
	
	}

	function dpk_process_log($params) {
	
		$res = $this->db->insert('transaksi_log', $params);
		return $res;
	
	}

	function dpkProcessLogUpdate($params, $id) {
	
		$this->db->where('id_trx', $id);
		$res = $this->db->update('transaksi_log', $params);
		return $res;
	
	}


	function get_pembayaran($params, $api=null, $orderlist=true) {
	
		$isWhere="";
		// OR @$params['request'] !=''
		if(@$params['export'] !='' OR $params['nofakt'] !='' OR $params['kdcust'] !='' OR $params['collector'] !='' OR @$params['status_bayar'] !='' OR (@$params['start_date'] != '' && @$params['end_date'] != '') OR (@$params['start_pay_date'] != '' && @$params['end_pay_date'] != '')){
		
			if($params['nofakt'] != '' && $params['nofakt'] != '-'){
				$isWhere .= ' AND a.nofakt = "'. $params['nofakt'] .'"';
			}
			if($params['kdcust'] != '' && $params['kdcust'] != '-'){
				$isWhere .= ' AND a.kdcust = "'. $params['kdcust'] .'"';
			}
			if($params['collector'] != '' && $params['collector'] != '-'){
				$isWhere .= ' AND a.collector_id = "'. $params['collector'] .'"';
			}
			if(@$params['start_date'] != '' && @$params['end_date'] != ''){
				$isWhere .= ' AND (a.`due_date` >= "'.$params['start_date'].'" AND a.`due_date` <= "'.$params['end_date'].'") ';
			}
			if(@$params['start_pay_date'] != '' && @$params['end_pay_date'] != ''){
				$isWhere .= ' AND (date(a.`created_date`) >= "'.$params['start_pay_date'].'" AND date(a.`created_date`) <= "'.$params['end_pay_date'].'") AND a.kode_bayar IN ("99", "00") ';
			}
			if(@$params['status_bayar'] != '-' && @$params['status_bayar'] != ''){
				switch ($params['status_bayar']) {
					case 'bayar': $isWhere .= ' AND a.kode_bayar IN ("99", "00")'; break;
					case 'belum_bayar': $isWhere .= ' AND (a.kode_bayar NOT IN ("99", "00") OR a.kode_bayar IS NULL)'; break;
					case 'janji_bayar': $isWhere .= ' AND a.kode_bayar="01"'; break;
					default: $isWhere .= ''; break;
				}
			}

			if(@$params['outlet'] != '-' && @$params['outlet'] != ''){
				switch ($params['outlet']) {
					case 'ALFAMART': $isWhere .= ' AND c.retail_outlet_name = "ALFAMART"'; break;
					case 'INDOMARET': $isWhere .= ' AND c.retail_outlet_name = "INDOMARET"'; break;
					default: $isWhere .= ''; break;
				}
			}

			if( !empty($params['request']['search']['value']) ) {   
				$isWhere .=" AND (";
				$isWhere .=" a.nofakt LIKE '".$params['request']['search']['value']."%'";
				$isWhere .=" OR a.namakons LIKE '".$params['request']['search']['value']."%'";
				$isWhere .=" OR a.collector LIKE '".$params['request']['search']['value']."%'";
				$isWhere .=" OR a.no_dpk LIKE '".$params['request']['search']['value']."%' )";
			}

			$cLunas = ($this->session->userdata['logged']['groupid'] != '1') ? "b.`ANGKE` > 0" : "1";

			if(empty($api)){
				$sql = "SELECT X.* FROM ((SELECT a.id, a.nofakt, a.collector, a.namakons, a.due_date, a.angsuran, a.angke, a.no_dpk, a.kode_bayar, a.bayar, a.keterangan, a.published, a.created_date, c.id as xendit_request_id, d.id as xendit_notify_id, c.payment_code, ifnull(c.fixed_payment_code_id, d.fixed_payment_code_id) as fixed_payment_code_id, c.status as request_status, d.status as notify_status, ifnull(c.retail_outlet_name, d.retail_outlet_name) as retail_outlet_name, a.id_nasabah
					FROM `transaksi` a
                    -- JOIN `master` b ON a.nofakt=b.NOFAKT
                    LEFT JOIN `xendit_request` c ON a.id=c.transaksi_id
                    LEFT JOIN `xendit_notify` d ON a.id=d.external_id
					WHERE $cLunas $isWhere
					ORDER BY a.`due_date` ASC)

					UNION

					(SELECT 'total' AS id, NULL AS nofakt, NULL AS collector, NULL AS namakons, '_' AS due_date, SUM(a.angsuran), NULL AS angke, NULL AS no_dpk, NULL AS kode_bayar, NULL AS bayar, NULL AS keterangan, NULL AS published, NULL AS created_date, c.id as xendit_request_id, d.id as xendit_notify_id, c.payment_code, ifnull(c.fixed_payment_code_id, d.fixed_payment_code_id) as fixed_payment_code_id, c.status as request_status, d.status as notify_status, ifnull(c.retail_outlet_name, d.retail_outlet_name) as retail_outlet_name, a.id_nasabah
					FROM `transaksi` a
					-- JOIN `master` b ON a.nofakt=b.NOFAKT
					LEFT JOIN `xendit_request` c ON a.id=c.transaksi_id
                    LEFT JOIN `xendit_notify` d ON a.id=d.external_id
					WHERE $cLunas $isWhere LIMIT 1)) AS X WHERE X.angsuran <> 0";

					if($orderlist==true){
						$ordename = ($params['columns'][$params['request']['order'][0]['column']]!=='id') ? $params['columns'][$params['request']['order'][0]['column']] : "due_date"; 
						$sql.=  " ORDER BY ". $ordename ."   ".$params['request']['order'][0]['dir']."  LIMIT ".$params['request']['start']." ,".$params['request']['length']." ";
					}else{
						$sql.= " ORDER BY due_date ASC";
					}
			}
			
			$sql .= '';
			$result = $this->db->query($sql);
			return $result;

		}else{
			return false;
		}
	
	}

	function get_ref_faktur($params, $api=null, $orderlist=true) {
	
		$isWhere="";
		
		if(@$params['export'] !='' OR $params['nofakt'] !='' OR $params['kdcust'] !=''OR (@$params['outlet'] != '')){
			
			if($params['nofakt'] != '' && $params['nofakt'] != '-'){
				$isWhere .= ' AND nofakt = "'. $params['nofakt'] .'"';
			}
			if($params['kdcust'] != '' && $params['kdcust'] != '-'){
				$isWhere .= ' AND kdcust = "'. $params['kdcust'] .'"';
			}
			if($params['outlet'] != '' && $params['outlet'] != '-'){
				$isWhere .= ' AND retail_outlet_name = "'. $params['outlet'] .'"';
			}

			if( !empty($params['request']['search']['value']) ) {   
				$isWhere .=" AND (";
				$isWhere .=" nofakt LIKE '".$params['request']['search']['value']."%'";
				$isWhere .=" OR name LIKE '".$params['request']['search']['value']."%'";
				$isWhere .=" OR retail_outlet_name LIKE '".$params['request']['search']['value']."%'";
				$isWhere .=" OR payment_code LIKE '".$params['request']['search']['value']."%' )";
			}
			
			if(empty($api)){
				$sql = "select * from xendit_request where 1=1 ".$isWhere;

				if($orderlist==true){
					$ordename = ($params['columns'][$params['request']['order'][0]['column']]!=='id') ? $params['columns'][$params['request']['order'][0]['column']] : "nofakt"; 
					$sql.=  " ORDER BY ". $ordename ."   ".$params['request']['order'][0]['dir']."  LIMIT ".$params['request']['start']." ,".$params['request']['length']." ";
				}else{
					$sql.= " ORDER BY nofakt ASC";
				}
			}
			
			$sql .= '';
			$result = $this->db->query($sql);
			return $result;

		}else{
			return false;
		}
	
	}



	function insert_request_xendit_trx($params)
	{
		$tmp_cleartimestamp = explode('.', $params['expiration_date']);
		$cleartimestamp = str_replace('T', ' ', $tmp_cleartimestamp[0]);

		$this->db->set('transaksi_id', $params['transaksi_id']);
		$this->db->set('is_single_use', $params['is_single_use']);
		$this->db->set('status', $params['status']);
		$this->db->set('owner_id', $params['owner_id']);
		$this->db->set('external_id', $params['external_id']);
		$this->db->set('retail_outlet_name', $params['retail_outlet_name']);
		$this->db->set('prefix', $params['prefix']);
		$this->db->set('name', $params['name']);
		$this->db->set('payment_code', $params['payment_code']);
		$this->db->set('type', $params['type']);
		$this->db->set('expected_amount', $params['expected_amount']);
		$this->db->set('expiration_date', $cleartimestamp);
		$this->db->set('fixed_payment_code_id', $params['id']);
		$this->db->set('nofakt', $params['nofakt']);
		$this->db->set('tenor_pembayaran', $params['tenorPembayaran']);
		$this->db->set('kdcust', $params['kdcust']);
		$this->db->set('created_datetime', 'NOW()', FALSE);

		return $this->db->insert('xendit_request');
	}

	function receive_xendit_notify($params)
	{
		$payment_code = $params['payment_code'];
		$fixed_payment_code_id = $params['fixed_payment_code_id'];
		$id_nasabah = str_replace($params['prefix'], '', $params['payment_code']);
		$tmp_transaction_timestamp = explode('.', $params['transaction_timestamp']);
		$transaction_timestamp = str_replace('T', ' ', $tmp_transaction_timestamp[0]);

		$this->db->where('payment_code', $params['payment_code']);
		$this->db->where('fixed_payment_code_payment_id', $params['fixed_payment_code_payment_id']);
		$this->db->where('fixed_payment_code_id', $params['fixed_payment_code_id']);
		$this->db->where('retail_outlet_name', $params['retail_outlet_name']);

		$qry = $this->db->get('xendit_notify');

		if ($qry->num_rows() <= 0)
		{
			$this->db->select('id');
			$this->db->where('id_nasabah', $id_nasabah);
			$this->db->where('no_dpk', NULL);
			$this->db->order_by('angke', 'asc');
			
			$qry = $this->db->get('transaksi');
			$row = $qry->row();
			$params['external_id'] = $row->id;

			$this->db->set('notify_code_id', $params['id']);
			$this->db->set('external_id', $params['external_id']);
			$this->db->set('created_datetime', 'NOW()', FALSE);
			$this->db->set('prefix', $params['prefix']);
			$this->db->set('payment_code', $params['payment_code']);
			$this->db->set('retail_outlet_name', $params['retail_outlet_name']);
			$this->db->set('name', $params['name']);
			$this->db->set('amount', $params['amount']);
			$this->db->set('status', $params['status']);
			$this->db->set('transaction_timestamp', $transaction_timestamp);
			$this->db->set('payment_id', $params['payment_id']);
			$this->db->set('fixed_payment_code_payment_id', $params['fixed_payment_code_payment_id']);
			$this->db->set('fixed_payment_code_id', $params['fixed_payment_code_id']);
			$this->db->insert('xendit_notify');
		}

		return $params['external_id'];
	}

	function check_request_pembayaran($transaksi_id, $outlet)
	{
		$this->db->select('xr.transaksi_id');
		$this->db->from('xendit_request xr');
		$this->db->join('xendit_notify xn', 'xr.external_id = xn.external_id', 'LEFT');
		$this->db->where('xr.transaksi_id', $transaksi_id);
		$this->db->where('xr.retail_outlet_name', $outlet);
		$this->db->where('xn.status', 'COMPLETED');

		return $this->db->get();
	}

	function update_id_nasabah($trx_id, $id_nasabah) {
		$this->db->set('id_nasabah', $id_nasabah);
		$this->db->where('id', $trx_id);

		return $this->db->update('transaksi');
	}

	function get_dpk_trx_pembayaran($id) {
	
		$select = "(SELECT telephone FROM `data_customer_histori` WHERE kdcust=a.kdcust
		           ORDER BY created_date DESC LIMIT 1) AS notlpn1, ";
		$select .= "(SELECT TELEPHONE FROM `master` WHERE `KDCUST` = a.kdcust LIMIT 1) AS notlpn2 ";         
		$q = $this->db->query("SELECT a.*, $select FROM `transaksi` a WHERE a.`id` = '$id'");
		return $q;
	}

	function get_data_trx($trx_id)
	{
		$this->db->where('id', $trx_id);
		// $this->db->where('no_dpk', NULL);
		// $this->db->order_by('angke', 'asc');

		return $this->db->get('transaksi');
	}

	function get_pembayaran_report($params, $api=null, $orderlist=true) {
		$isWhere="";
		// OR @$params['request'] !=''
		// if(@$params['export'] !='' OR $params['nofakt'] !='' OR $params['kdcust'] !='' OR $params['collector'] !='' OR @$params['status_bayar'] !='' OR (@$params['start_date'] != '' && @$params['end_date'] != '') OR (@$params['start_pay_date'] != '' && @$params['end_pay_date'] != '')){
		
			if($params['nofakt'] != '' && $params['nofakt'] != '-'){
				$isWhere .= ' AND a.nofakt = "'. $params['nofakt'] .'"';
			}
			if($params['kdcust'] != '' && $params['kdcust'] != '-'){
				$isWhere .= ' AND a.kdcust = "'. $params['kdcust'] .'"';
			}
			if($params['collector'] != '' && $params['collector'] != '-'){
				$isWhere .= ' AND a.collector_id = "'. $params['collector'] .'"';
			}
			if(@$params['start_date'] != '' && @$params['end_date'] != ''){
				$isWhere .= ' AND (a.`due_date` >= "'.$params['start_date'].'" AND a.`due_date` <= "'.$params['end_date'].'") ';
			}
			if(@$params['start_pay_date'] != '' && @$params['end_pay_date'] != ''){
				$isWhere .= ' AND (date(a.`created_date`) >= "'.$params['start_pay_date'].'" AND date(a.`created_date`) <= "'.$params['end_pay_date'].'") AND a.kode_bayar IN ("99", "00") ';
			}
			if(@$params['status_bayar'] != '-' && @$params['status_bayar'] != ''){
				switch ($params['status_bayar']) {
					case 'bayar': $isWhere .= ' AND a.kode_bayar IN ("99", "00")'; break;
					case 'belum_bayar': $isWhere .= ' AND (a.kode_bayar NOT IN ("99", "00") OR a.kode_bayar IS NULL)'; break;
					case 'janji_bayar': $isWhere .= ' AND a.kode_bayar="01"'; break;
					default: $isWhere .= ''; break;
				}
			}

			if(@$params['outlet'] != '-' && @$params['outlet'] != ''){
				switch ($params['outlet']) {
					case 'ALFAMART': $isWhere .= ' AND c.retail_outlet_name = "ALFAMART"'; break;
					case 'INDOMARET': $isWhere .= ' AND c.retail_outlet_name = "INDOMARET"'; break;
					default: $isWhere .= ''; break;
				}
			}

			$cLunas = ($this->session->userdata['logged']['groupid'] != '1') ? "b.`ANGKE` > 0" : "1";

			if(empty($api)){
				$sql = "SELECT X.* FROM ((SELECT a.id, a.nofakt, a.collector, a.namakons, a.due_date, a.angsuran, a.angke, a.no_dpk, a.kode_bayar, a.bayar, a.keterangan, a.published, a.created_date, c.id as xendit_request_id, d.id as xendit_notify_id, c.payment_code, ifnull(c.fixed_payment_code_id, d.fixed_payment_code_id), c.status as request_status, d.status as notify_status, ifnull(c.retail_outlet_name, d.retail_outlet_name), d.created_datetime as tanggal_bayar, a.id_nasabah
					FROM `transaksi` a
                    LEFT JOIN `xendit_request` c ON a.id=c.transaksi_id
                    LEFT JOIN `xendit_notify` d ON a.id=d.external_id
					WHERE $cLunas $isWhere
					ORDER BY a.`due_date` ASC)

					UNION

					(SELECT 'total' AS id, NULL AS nofakt, NULL AS collector, NULL AS namakons, '_' AS due_date, SUM(a.angsuran), NULL AS angke, NULL AS no_dpk, NULL AS kode_bayar, NULL AS bayar, NULL AS keterangan, NULL AS published, NULL AS created_date, c.id as xendit_request_id, d.id as xendit_notify_id, c.payment_code, ifnull(c.fixed_payment_code_id, d.fixed_payment_code_id), c.status as request_status, d.status as notify_status, ifnull(c.retail_outlet_name, d.retail_outlet_name), d.created_datetime as tanggal_bayar, a.id_nasabah
					FROM `transaksi` a
					LEFT JOIN `xendit_request` c ON a.id=c.transaksi_id
                    LEFT JOIN `xendit_notify` d ON a.id=d.external_id
					WHERE $cLunas $isWhere LIMIT 1)) AS X WHERE X.angsuran <> 0";
					
			}

			$sql .= '';
			$result = $this->db->query($sql);

			return $result;

		// }else{
		// 	return false;
		// }
	
	}

	function get_detail_faktur($id){
		$sql="select 
					id,
					nofakt, 
					kdcust,
					namakons,
					angsuran,
					tenor
				from transaksi where nofakt='".$id."' and angke=1";
		return $this->db->query($sql)->row_array();
	}
}
?>