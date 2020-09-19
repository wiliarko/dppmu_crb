<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation', 'session'));
		$this->load->library(array('pagination','authentication'));
		$this->load->model(array('backend/transaksi_model', 'backend/master_model', 'backend/laporan_model'));
		
		$this->url_modul = base_url().'backend/transaksi/';
		$this->page = 'Transaksi';
	}

	public function index() {
		redirect(base_url().'backend/home/');
	}


	public function exec_bill() {
		
		$thisMonth = date("m", time() + 86400);

		$sql = $this->db->query("
					SELECT *, `DDDATE` AS due_d, SUBSTRING(`MYDATE`,5,2) AS due_m, SUBSTRING(`MYDATE`,1,4) AS due_y
					FROM `master`
					WHERE `sync` = '0'");
					// WHERE SUBSTRING(`MYDATE`,5,2) <= '$thisMonth' AND `sync` = '0'");
		if($sql->num_rows() > 0){
		
			foreach ($sql->result() AS $arval) {

				for($i=1; $i <= $arval->TENOR; $i++) {

					$str = $i-1; $angke = $i;
					$due_date = $arval->due_y.'-'.$arval->due_m.'-'.$arval->due_d;
					$newdate = date("Y-m-d", strtotime($due_date. " +".$str." Month"));

					$query = $this->db->query("SELECT * FROM `transaksi` WHERE `nofakt`='$arval->NOFAKT' AND `due_date` = '$newdate'");
					if(!$query->num_rows()){

						$check = $this->db->query("SELECT * FROM `data_collector` WHERE `nama` = '$arval->COLLECTOR' LIMIT 1");
						$collector_id=0;
						if($check->num_rows()){
							$list = $check->row();
							$collector_id = $list->id;
						}

						$data = array(
									'nofakt' => $arval->NOFAKT,
									'due_date' => $newdate,
									'dddate' => $arval->DDDATE,
									'mydate' => $arval->MYDATE,
									'kdcust' => $arval->KDCUST,
									'namakons' => $arval->NAMAKONS,
									'collector_id' => $collector_id,
									'collector' => $arval->COLLECTOR,
									'angsuran' => $arval->ANGSURAN,
									'tenor' => $arval->TENOR,
									'angke' => $i,
								);

						// Eksekusi pembayaran yang sudah terbayar
						if(($i < $arval->ANGKE) || ($arval->ANGKE == '0'))
						{
							$data['no_dpk'] = $i.$arval->ANGKE.time();
							$data['kode_bayar'] = '99';
							$data['bayar'] = 'Bayar';
							$data['update_via'] = 'kasir';
							$data['update_by_id'] = $this->session->userdata['logged']['uid'];
							$data['update_by'] = $this->session->userdata['logged']['realname'];
							$data['created_date'] = date('Y-m-d H:i:s');
							$data['published'] = '1';
							$data['publish_by_id'] = $this->session->userdata['logged']['uid'];
							$data['publish_by'] = $this->session->userdata['logged']['realname'];
							$data['published_date'] = date('Y-m-d H:i:s');
						}
						// End

						$this->db->insert('transaksi', $data);

					}
				}

				$this->db->query("UPDATE `master` SET sync='1' WHERE `ID`='$arval->ID'");
			}

			die("proses selesai");

		}else{

			die("No Data");

		}
	}

	public function dpk() {

		$this->authentication->restrict();
		
		$data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'DPK',
			'url_module' => $this->url_modul .'dpk',
		);
		
		$isNotAdmin = ($this->session->userdata['logged']['groupid'] !== "1") ? $this->session->userdata['logged']['id'] : "";

		$params = array(
			'nofakt' 		=> !empty($this->input->get("nofakt")) ? urldecode($this->input->get("nofakt")) : "",
			'kdcust' 		=> !empty($this->input->get("kdcust")) ? urldecode($this->input->get("kdcust")) : "",
			'collector' 	=> !empty($this->input->get("collector")) ? urldecode($this->input->get("collector")) : $isNotAdmin,
			'start_date'	=> !empty($this->input->get("start_date")) ? urldecode($this->input->get("start_date")) : "",
			'end_date'		=> !empty($this->input->get("end_date")) ? urldecode($this->input->get("end_date")) : "",
			'status_bayar'	=> !empty($this->input->get("status_bayar")) ? urldecode($this->input->get("status_bayar")) : "",
			'outlet'		=> !empty($this->input->get("outlet")) ? urldecode($this->input->get("outlet")) : "",
		);

		$data['params'] = array(
							'start_date' => $params['start_date'],
							'end_date' => $params['end_date'],
							'nofakt' => $params['nofakt'],
							'kdcust' => $params['kdcust'],
							'collector' => $params['collector'],
							'status_bayar' => $params['status_bayar'],
							'outlet' => $params['outlet'],
						);

		$data['kdcustname'] = $this->transaksi_model->get_customer(null, null, $params['kdcust'])->row()->nama;
		$data['collectorname'] = $this->transaksi_model->get_collector($params['collector'], true)->row()->nama;
		$data['collector_id'] = !empty($params['collector']) ? $params['collector'] : "";

		$data['tablelist'] = array(
			'head' => array(
				//Caption, sort, width
				array('No.','nosort', '3%'),
				array('NOFAKT', '',''),
				array('COLLECTOR', '',''),
				array('NAMAKONS', '','1'),
				array('DUE DATE', '',''),
				array('ANGSURAN', '',''),
				array('ANGKE', '',''),
				array('NO. DPK', '',''),
				array('STATUS', '',''),
				array('OUTLET', '',''),
				array('KETERANGAN', '',''),
				array('', '',''),
			)
		);

		$data['via'] = "dpk";
		$data['page_header'] = array(
								"DPP-MU",
								"Transaksi",
								"DPK"
							  );
		
		$this->load->view('backend/transaksi/list', $data);
	}

	public function kasir() {

		$this->authentication->restrict();
		
		$data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'KASIR',
			'url_module' => $this->url_modul .'kasir',
		);
		
		$id = $this->session->userdata['logged']['id'];
		$isNotAdmin = ($this->session->userdata['logged']['groupid'] !== "1") ? (($id) ? $id : "") : "";
		
		$params = array(
			'nofakt' 		=> !empty($this->input->get("nofakt")) ? urldecode($this->input->get("nofakt")) : "",
			'kdcust' 		=> !empty($this->input->get("kdcust")) ? urldecode($this->input->get("kdcust")) : "",
			'collector' 	=> !empty($this->input->get("collector")) ? urldecode($this->input->get("collector")) : $isNotAdmin,
			'start_date'	=> !empty($this->input->get("start_date")) ? urldecode($this->input->get("start_date")) : "",
			'end_date'		=> !empty($this->input->get("end_date")) ? urldecode($this->input->get("end_date")) : "",
			'outlet'		=> !empty($this->input->get("outlet")) ? urldecode($this->input->get("outlet")) : "",
			'start_pay_date'=> !empty($this->input->get("start_pay_date")) ? urldecode($this->input->get("start_pay_date")) : "",
			'end_pay_date'  => !empty($this->input->get("end_pay_date")) ? urldecode($this->input->get("end_pay_date")) : ""
		);

		$data['params'] = array(
							'start_date' => $params['start_date'],
							'end_date' => $params['end_date'],
							'start_pay_date' => $params['start_pay_date'],
							'end_pay_date' => $params['end_pay_date'],
							'nofakt' => $params['nofakt'],
							'kdcust' => $params['kdcust'],
							'collector' => $params['collector'],
							'outlet' => $params['outlet'],
						);
		
		// $selrec	= $this->transaksi_model->get_dpk($params);


		// $data['noFaktur'] = $this->transaksi_model->get_no_faktur($params['collector']);
		// $data['customer'] = $this->transaksi_model->get_customer($params['collector']);
		// $data['collector'] = $this->transaksi_model->get_collector();

		$data['kdcustname'] = $this->transaksi_model->get_customer(null, null, $params['kdcust'])->row()->nama;
		$data['collectorname'] = $this->transaksi_model->get_collector($params['collector'], true)->row()->nama;
		$data['collector_id'] = !empty($params['collector']) ? $params['collector'] : "";
		$data['total_kas'] = $this->transaksi_model->get_total_kas();

		$data['tablelist'] = array(
			'head' => array(
				//Caption, sort, width
				array('No.','nosort', '3%'),
				array('NOFAKT', '',''),
				// array('DDDATE', '',''),
				array('COLLECTOR', '',''),
				array('NAMAKONS', '','1'),
				array('DUE DATE', '',''),
				// array('TELEPHONE', '',''),
				array('U.M', '',''),
				array('ANGSURAN', '',''),
				array('ANGKE', '',''),
				array('NO. DPK', '',''),
				array('STATUS', '',''),
				array('OUTLET', '',''),
				array('KETERANGAN', '',''),
				array('TGL BAYAR', '',''),
				array('', '',''),
			)
		);

		$data['page_header'] = array(
								"DPP-MU",
								"Transaksi",
								"KASIR"
							  );
		
		$cssFiles = array(base_url().'assets/css/plugins/ladda/ladda-themeless.min.css');
		$data['output'] = (object) array('css_files' => $cssFiles);

		$data['via'] = "kasir";
		$this->load->view('backend/transaksi/list', $data);
	}

	function gettablelist()
	{

		$isNotAdmin = ($this->session->userdata['logged']['groupid'] !== "1") ? $this->session->userdata['logged']['id'] : "";

		$columns = array( 
			0 =>'id',
			1 =>'nofakt', 
			2 => 'collector',
			3 => 'namakons',
			4 => 'due_date',
			5 => 'angsuran',
			6 => 'angke',
			7 => 'no_dpk',
			8 => 'kode_bayar',
			11 => 'Outlet',
			9 => 'bayar',
			10 => 'keterangan'
		);

		$params = array(
			'nofakt' 		=> !empty($this->input->get("nofakt")) ? urldecode($this->input->get("nofakt")) : "",
			'kdcust' 		=> !empty($this->input->get("kdcust")) ? urldecode($this->input->get("kdcust")) : "",
			'collector' 	=> !empty($this->input->get("collector")) ? urldecode($this->input->get("collector")) : $isNotAdmin,
			'start_date'	=> !empty($this->input->get("start_date")) ? urldecode($this->input->get("start_date")) : "",
			'end_date'		=> !empty($this->input->get("end_date")) ? urldecode($this->input->get("end_date")) : "",
			'start_pay_date'=> !empty($this->input->get("start_pay_date")) ? urldecode($this->input->get("start_pay_date")) : "",
			'end_pay_date'	=> !empty($this->input->get("end_pay_date")) ? urldecode($this->input->get("end_pay_date")) : "",
			'status_bayar'	=> !empty($this->input->get("status_bayar")) ? urldecode($this->input->get("status_bayar")) : "",
			'outlet'		=> !empty($this->input->get("outlet")) ? urldecode($this->input->get("outlet")) : "",
			'request' 		=> $_REQUEST,
			'columns'		=> $columns
		);

		$selrec	= $this->transaksi_model->get_dpk($params, false, true);
		$sqlTot	= $this->transaksi_model->get_dpk($params, false, false);
		$totalRecords = ($sqlTot!=false) ? $sqlTot->num_rows() : 0;

		$data = array();

		$page = ($_REQUEST['length'] + $_REQUEST['start']) / $_REQUEST['length'];
		$start = ($_REQUEST['start'] >= $_REQUEST['length']) ? $_REQUEST['start']-1 : $_REQUEST['start'];
		$no = $start + $page;
		$btn_disable = 0;

		if($totalRecords > 0)
		{

			foreach($selrec->result_array() as $row)
			{	
				if($btn_disable == 0){
	                if($row['kode_bayar'] <> '04') {
	                    $btn_act = '<a class="btn btn-white btn-bitbucket" onclick="viewUpdate('.$row['id'].')"><i class="fa fa-wrench"></i></a>';
	                }else{
	                    $btn_disable = 1;
	                    $btn_act = "";
	                }
	            }else{
	                $btn_act = "";
	            }

	            $publish = (in_array($row['kode_bayar'], array('00', '99'))) ? (($row['published'] == 0) ? '<span id="properties-list-'.$row['id'].'" onclick="kasir_publising('.$row['id'].',\''.$row['nofakt'].'\')"><a class="btn btn-danger btn-bitbucket" id="prop-'.$row['id'].'"><i class="fa fa-warning"></i></a></span>' : '<a class="btn btn-primary btn-bitbucket"><i class="fa fa-check"></i></a>') : "";
	            $publish_kasir = ($this->input->get('via') == "kasir") ? $publish : "";

	            $created_date = (in_array($row['kode_bayar'], array('99', '00'))) ? $row['created_date'] : "";
	            $created = ($this->input->get('via') == 'kasir') ? '<td>'.$created_date.'</td>' : '';
	            $arAngsuran = number_format($row['angsuran']);
	            $angsuran = ($row['id'] == 'total') ? "<b>$arAngsuran</b>" : $arAngsuran;
	            $cMaster = $this->laporan_model->get_master($row['nofakt'])->row();

				if($this->input->get('via') == 'dpk'){
					$data[] = array(
			  				'<center>'.$no.'<center>',
			  				$row['nofakt'],
			  				$row['collector'],
			  				$row['namakons'],
			  				$row['due_date'],
			  				$angsuran,
			  				$row['angke'],
			  				$row['no_dpk'],
			  				$row['bayar'],
			  				$row['retail_outlet_name'],
			  				$row['keterangan'],
			  				$btn_act
			  			);
				}else{
					$data[] = array(
				  				'<center>'.$no.'<center>',
				  				$row['nofakt'],
				  				$row['collector'],
				  				$row['namakons'],
				  				$row['due_date'],
				  				(@$cMaster->UANGMUKA) ? number_format($cMaster->UANGMUKA) : "<center>-</center>",
				  				$angsuran,
				  				$row['angke'],
				  				$row['no_dpk'],
				  				$row['bayar'],
			  					$row['retail_outlet_name'],
				  				$row['keterangan'],
				  				$created,
				  				$btn_act.$publish_kasir
				  			);
				}

				$no++;
			}

		}else{

			$data = array();

		}

		$json_data = array(
			"draw"            => intval( $_REQUEST['draw'] ),   
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval($totalRecords),
			"data"            => $data
		);

		echo json_encode($json_data);

	}

	function get_dpk_trx()
	{
		$id = $this->input->post('id');
		$query = $this->transaksi_model->get_dpk_trx($id);
		if ($query->num_rows() > 0){
			$row = $query->row();
			$json = array(
						"id"			=> $id,
						"nofakt"		=> $row->nofakt,
						"dddate"		=> $row->dddate,
						"mydate"		=> $row->mydate,
						"namakons"		=> $row->namakons,
						"tlpn"			=> ($row->tlpn == "") ? (($row->notlpn1 == "") ? $row->notlpn2 : $row->notlpn1) : $row->tlpn,
						"angsuran_rp"	=> "Rp. ". number_format($row->angsuran),
						"angsuran"		=> $row->angsuran,
						"tenor"			=> $row->tenor,
						"angke"			=> $row->angke,
						"no_dpk"		=> $row->no_dpk,
						"kode_bayar"	=> $row->kode_bayar,
						"bayar"			=> $row->bayar,
						"bayar_sebagian"=> $row->bayar_sebagian,
						"bayar_sisa"	=> $row->bayar_sisa,
						"keterangan"	=> $row->keterangan,
						"update_by"		=> $row->update_by,
						"update_at"		=> $row->created_date,
						"update_via"	=> $row->update_via,
						"publish_by_id"	=> $row->publish_by_id,
						"publish_by"	=> $row->publish_by,
					);

			$res = array("status" => "success", "data" => $json);

		}else{
			$res = array("status" => "error", "msg" => "tidak ada data");
		}

		print(json_encode($res));
	}

	function dpk_process($via=null) {

		$id = $this->input->post('dpk_id');
		$tlpn = $this->input->post('tlpn');
		$isPay = $this->input->post('isPay');
		
		$collector_id = $this->input->post('collector_id');
		$qCollector = $this->transaksi_model->get_collector($collector_id, true);
		$cRow = $qCollector->row();
		$collector_name = $cRow->nama;

		$update_by_id = empty($collector_id) ? $this->session->userdata['logged']['uid'] : $collector_id;
		$update_by = empty($collector_id) ? $this->session->userdata['logged']['realname'] : $collector_name;

		if($isPay == '99'){
			$bayar = "Bayar";
			$keterangan = $this->input->post('bayarLainnya');
		}else{
			$arr = $this->otherPay($isPay, $this->input->post());
			$bayar = $arr['bayar'];
			$keterangan = $arr['keterangan'];
			$nextDate = $arr['nextDate'];
		}

		$query = $this->transaksi_model->get_dpk_trx($id);
		$row = $query->row();
		$no_dpk = !empty($row->no_dpk) ? $row->no_dpk : $id.time();

		// CHECK ANGSURAN SEBELUMNYA
		$resLast = $this->transaksi_model->angsuran_last_check($row->nofakt, $row->angke);
		if($resLast->num_rows() > 0){
			$ls = $resLast->row();
			if($ls->kode_bayar !== '99' && $ls->kode_bayar !== '02' && $ls->kode_bayar !== '09') {
				$msg = ($ls->kode_bayar == '00') ? "Harap melunasi tagihan sebelumnya u/ Angke : ($ls->angke)" : "Harap segera melakukan pembayaran sebelumnya!";
				$res = array("status" => "error", "msg" => $msg);
				print(json_encode($res));
				exit;
			}
		}
		// END

		$params = array(
					'no_dpk'		=> $no_dpk,
					'tlpn' 			=> $tlpn,
					'kode_bayar'	=> $isPay,
					'bayar' 		=> $bayar,
					'keterangan'	=> $keterangan,
					'update_via'	=> isset($via) ? $via : 'dpk',
					'update_by_id'	=> $update_by_id,
					'update_by'		=> $update_by,
					'created_date'	=> date('Y-m-d H:i:s')
				);

		if(($via == 'kasir' || in_array($this->session->userdata['logged']['groupid'], array(1,4,6))) && in_array($isPay, array('00', '99')))
		{
			$cutOff_id = $this->transaksi_model->get_cutOff_id();
			$params['cutOff_id'] = $cutOff_id;
			$params['cutOff'] = '0';
			$params['published'] = '1';
			$params['publish_by_id'] = $this->session->userdata['logged']['uid'];
			$params['publish_by'] = $this->session->userdata['logged']['realname'];
			$params['published_date'] = date('Y-m-d H:i:s');
			if(in_array($isPay, array('00', '99'))){
				// Input No. Bukti
				$seql = $this->db->query("SELECT `seq` FROM `transaksi` ORDER BY `seq` DESC LIMIT 1")->row();
				$seq = $seql->seq+1;
				$year = date('y');
				$nofak_str = preg_replace('/\d/', '', $row->nofakt);
				$seqs = str_pad($seq, 7, '0', STR_PAD_LEFT);
				$no_bukti =  $nofak_str.$year.$seqs;
				
				$params['seq'] = $seq;
				$params['no_bukti'] = $no_bukti;
			}
		}else{
			$params['published'] = '0';
			$params['publish_by_id'] = '0';
			$params['publish_by'] = '0';
			$params['published_date'] = NULL;
		}
		
		switch ($isPay) {
			case '00':

				if($this->input->post('bayarSisa') == '0'){
					$params2 = array(
						'kode_bayar'	=> '99',
						'keterangan'	=> "Pembayaran angsuran ke ". $row->angke ." LUNAS",
						'bayar'			=> "Bayar"
					);

					$params = array_replace($params, $params2);
					$params['bayar_sisa']	= !empty($this->input->post('bayarSisa')) ? $this->input->post('bayarSisa') : NULL;
				}else{
					$params['bayar_sebagian'] = !empty($this->input->post('bayarSebagian')) ? $this->input->post('bayarSebagian') : NULL;
					$params['bayar_sisa'] = $this->input->post('bayarSisa');
				}

				break;
			case '01':
			case '04':
			case '09':

				if(empty($nextDate)) {
					$msg = ($isPay == '01') ? "Tanggal Janji Bayar harap di isi!" : "Tanggal rencana tarik bayar harap di isi!";
					$res = array("status" => "error", "msg" => $msg);
					print(json_encode($res));
					exit;
				}

				$params['nextDate'] = $nextDate;

				break;

		}

		$result = $this->transaksi_model->dpk_process($params, $id);
		if($result){

			$list = array(
				'id_trx'		=> $id,
				'nofakt'		=> $row->nofakt,
				'due_date'		=> $row->due_date,
				'dddate'		=> $row->dddate,
				'mydate'		=> $row->mydate,
				'kdcust'		=> $row->kdcust,
				'namakons'		=> $row->namakons,
				'collector_id'	=> $row->collector_id,
				'collector'		=> $row->collector,
				'tlpn'			=> $tlpn,
				'angsuran'		=> $row->angsuran,
				'tenor'			=> $row->tenor,
				'angke'			=> $row->angke,
				'no_dpk'		=> $no_dpk,
				'kode_bayar'	=> $isPay,
				'bayar'			=> $bayar,
				'bayar_sebagian'=> !empty($this->input->post('bayarSebagian')) ? $this->input->post('bayarSebagian') : NULL,
				'bayar_sisa'	=> !empty($this->input->post('bayarSisa')) ? $this->input->post('bayarSisa') : NULL,
				'keterangan'	=> $keterangan,
				'nextDate'		=> !empty($nextDate) ? $nextDate : NULL,
				'update_via'	=> isset($via) ? $via : 'dpk',
				'update_by_id'	=> $update_by_id,
				'update_by'		=> $update_by,
				'created_date'	=> date('Y-m-d H:i:s')
			);

			if(($via == 'kasir' || in_array($this->session->userdata['logged']['groupid'], array(1,4,6))) && in_array($isPay, array('00', '99')))
			{
				$cutOff_id = $this->transaksi_model->get_cutOff_id();
				$list['cutOff_id'] = $cutOff_id;
				$list['cutOff'] = '0';
				$list['published'] = '1';
				$list['publish_by_id'] = $this->session->userdata['logged']['uid'];
				$list['publish_by'] = $this->session->userdata['logged']['realname'];
				$list['published_date'] = date('Y-m-d H:i:s');
			}else{
				$list['published'] = '0';
				$list['publish_by_id'] = '0';
				$list['publish_by'] = '0';
				$list['published_date'] = NULL;
			}

			$this->transaksi_model->dpk_process_log($list);
			$res = array("status" => "success");
		}else{
			$res = array("status" => "error", "msg" => "data gagal disimpan");
		}

		print(json_encode($res));

	}

	public function cutoff_process()
	{
		$cutOff_id = $this->transaksi_model->get_cutOff_id();
		$total_kas = $this->transaksi_model->get_total_kas();
		$list = $total_kas->row();

		$data['cutOff_id'] = $cutOff_id;
		$data['jumlah'] = $list->total;
		$data['update_by_id'] = $this->session->userdata['logged']['uid'];
		$data['update_by'] = $this->session->userdata['logged']['realname'];
		$data['date_cutoff'] = date('Y-m-d H:i:s');
		
		$insert = $this->db->insert('saldo', $data);
		if($insert){
			$this->db->query("UPDATE `transaksi` SET cutOff='1' WHERE `cutOff_id`='$cutOff_id'");
			$this->db->query("UPDATE `transaksi_log` SET cutOff='1' WHERE `cutOff_id`='$cutOff_id'");

			$res = array("status" => "success", "msg" => "Berhasil di cut-off");
		}else{
			$res = array("status" => "error", "msg" => "Gagal");
		}

		print(json_encode($res));
	}

	private function otherPay($isPay, $post)
	{
		
		switch($isPay) {
            case '00':
            	$bayar = 'Bayar Sebagian';
            	$keterangan = $post['bayarLainnya'];
            	break;
            case '01': 
            	$bayar = 'Janji Bayar. Tgl '. $post['nextDate'];
            	$keterangan = $post['bayarLainnya'];
            	$nextDate = $post['nextDate'];
            	break;
            case '02':
            	$bayar = 'Percepatan';
            	$keterangan = $post['bayarLainnya'];
            	break;
            case '03':
            	$bayar = 'Sisa Titipan';
            	$keterangan = $post['bayarLainnya'];
            	break;
            case '04':
            	$bayar = 'Tarik Barang. Tgl '. $post['nextDate'];
            	$keterangan = $post['bayarLainnya'];
            	$nextDate = $post['nextDate'];
            	break;
            case '05':
            	$bayar = 'Tidak Ada Orang';
            	$keterangan = $post['bayarLainnya'];
            	break;
            case '06':
            	$bayar = 'Alasan Kesehatan';
            	$keterangan = $post['bayarLainnya'];
            	break;
            case '07':
            	$bayar = 'Alasan Ekonomi';
            	$keterangan = $post['bayarLainnya'];
            	break;
            case '08':
            	$bayar = 'Komplain Produk';
            	$keterangan = $post['bayarLainnya'];
            	break;
            case '09':
            	$bayar = 'Rencana Tarik Barang. Tgl '. $post['nextDate'];
            	$keterangan = $post['bayarLainnya'];
            	$nextDate = $post['nextDate'];
            	break;
            case '10':
            	$bayar = 'Pindah Alamat';
            	$keterangan = $post['bayarLainnya'];
            	break;
            case '11':
            	$bayar = 'Tidak Ada Barang + Karakter';
            	$keterangan = $post['bayarLainnya'];
            	break;
            case '12':
            	$bayar = 'Ada Barang + Karakter';
            	$keterangan = $post['bayarLainnya'];
            	break;
            case '13':
            	$bayar = 'Bangkrut / Pailit';
            	$keterangan = $post['bayarLainnya'];
            	break;
            case '14':
            	$bayar = 'Kabur / Hilang';
            	$keterangan = $post['bayarLainnya'];
            	break;
            case '15':
            	$bayar = 'Force Mejeur';
            	$keterangan = $post['bayarLainnya'];
            	break;
        }

        return array(
        		'bayar' => $bayar,
        		'keterangan' => $keterangan,
        		'nextDate' => isset($nextDate) ? $nextDate : ""
        	);
	}

	function kasir_publising($id,$nofakt) {
		
		// UPDATE IF LUNAS PEMBAYARAN
		$rSisa = $this->master_model->get_sisa_tagihan($nofakt, '1');
		$rs = $rSisa->row();
		if($rs->sisa == ''){
			$arr = array('ANGKE' => '0', 'STATUS' => 'LUNAS');
			$this->master_model->update_to_lunas($arr, $nofakt);
		}
		
		$params = array(
					'published'		=> '1',
					'publish_by_id'	=> $this->session->userdata['logged']['uid'],
					'publish_by'	=> $this->session->userdata['logged']['realname'],
					'published_date'=> date('Y-m-d H:i:s')
				);
		$result = $this->transaksi_model->dpk_process($params, $id);
		if($result){
			
			$this->transaksi_model->dpkProcessLogUpdate($params, $id);
			echo '<a class="btn btn-primary btn-bitbucket"><i class="fa fa-check"></i></a>';

		}
	}

	function get_nofak($collector_id=null) {

		$keyword = $this->input->get('q');
        $q = $this->transaksi_model->get_no_faktur($collector_id, null, $keyword);
        $json = [];
        foreach($q->result() as $arval){
            $json[] = ['id'=>$arval->nofakt, 'text'=>$arval->nofakt];
        }

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($json);

    }

    function get_customer($collector_id=null) {
    	
    	$keyword = $this->input->get('q');
        $q = $this->transaksi_model->get_customer($collector_id, $keyword);
        
        $json = [];
        
        foreach($q->result() as $arval){
        	// $check_lunas = $this->db->query("SELECT 1 FROM `master` WHERE `STATUS`='LUNAS' AND NAMAKONS='$arval->nama'")->num_rows();
        	$check_blm_lunas = $this->db->query("SELECT 1 FROM `master` WHERE `ANGKE` > 0 AND NAMAKONS='".addslashes($arval->nama)."'")->num_rows();

			if($check_blm_lunas > 0 || $this->session->userdata['logged']['groupid'] == 1){
				$json[] = ['id'=>$arval->kdcust, 'text'=>$arval->nama];
			}
        }

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($json);
    }

    function get_collector() {
    	
    	$keyword = $this->input->get('q');
        $q = $this->transaksi_model->get_collector();
        $json = [];
        foreach($q->result() as $arval){
            $json[] = ['id'=>$arval->id, 'text'=>$arval->nama];
        }

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($json);
    }

    function get_range($nofakt) {
    	
        $q = $this->transaksi_model->get_range($nofakt);
        $json = [];
        foreach($q->result() as $arval){
            $json[] = ['id'=>$arval->angke, 'text'=>$arval->angke];
        }

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($json);
    }

    function get_userkasir() {
    	
        $q = $this->transaksi_model->get_userkasir();
        $json = [];
        foreach($q->result() as $arval){
            $json[] = ['id'=>$arval->user_id, 'text'=>$arval->first_name];
        }

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($json);
    }



    /*function saldo_update()
    {
    	$result = $this->db->query("SELECT SUM(angsuran) as total_kas, cutOff_id FROM `transaksi_log` WHERE cutOff_id <> '' GROUP BY cutOff_id ORDER BY cutOff_id DESC ");
    	
    	foreach ($result->result() as $row) {

    		$data = array( 'jumlah' => $row->total_kas, 'flag_new' => '1' );
    		$this->db->where('cutOff_id', $row->cutOff_id);
    		$this->db->update('saldo', $data);

    	}

    	echo 'DONE';
    }*/

	public function pembayaran() {


		$this->authentication->restrict();
		
		$data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'PEMBAYARAN',
			'url_module' => $this->url_modul .'pembayaran',
		);
		
		$isNotAdmin = ($this->session->userdata['logged']['groupid'] !== "1") ? $this->session->userdata['logged']['id'] : "";

		$params = array(
			'nofakt' 		=> !empty($this->input->get("nofakt")) ? urldecode($this->input->get("nofakt")) : "",
			'kdcust' 		=> !empty($this->input->get("kdcust")) ? urldecode($this->input->get("kdcust")) : "",
			'collector' 	=> !empty($this->input->get("collector")) ? urldecode($this->input->get("collector")) : $isNotAdmin,
			'start_date'	=> !empty($this->input->get("start_date")) ? urldecode($this->input->get("start_date")) : "",
			'end_date'		=> !empty($this->input->get("end_date")) ? urldecode($this->input->get("end_date")) : "",
			'status_bayar'	=> !empty($this->input->get("status_bayar")) ? urldecode($this->input->get("status_bayar")) : "",
			'outlet'		=> !empty($this->input->get("outlet")) ? urldecode($this->input->get("outlet")) : "",
		);

		$data['params'] = array(
							'start_date' => $params['start_date'],
							'end_date' => $params['end_date'],
							'nofakt' => $params['nofakt'],
							'kdcust' => $params['kdcust'],
							'collector' => $params['collector'],
							'status_bayar' => $params['status_bayar'],
							'outlet' => $params['outlet'],
						);

		$data['kdcustname'] = $this->transaksi_model->get_customer(null, null, $params['kdcust'])->row()->nama;
		$data['collectorname'] = $this->transaksi_model->get_collector($params['collector'], true)->row()->nama;
		$data['collector_id'] = !empty($params['collector']) ? $params['collector'] : "";

		$data['tablelist'] = array(
			'head' => array(
				//Caption, sort, width
				array('No.','nosort', '3%'),
				array('NOFAKT', '',''),
				array('NAMAKONS', '','1'),
				array('TENOR', '',''),
				array('ANGSURAN', '',''),
				array('OUTLET', '',''),
				array('PAYMENT CODE', '','')
			)
		);

		$data['via'] = "pembayaran";
		$data['page_header'] = array(
								"DPP-MU",
								"Transaksi",
								"PEMBAYARAN"
							  );

		$this->load->view('backend/transaksi/list_bayar', $data);

	}

	public function laporanpembayaran() {


		$this->authentication->restrict();
		
		$data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'LAPORAN PEMBAYARAN',
			'url_module' => $this->url_modul .'laporanpembayaran',
		);
		
		$isNotAdmin = ($this->session->userdata['logged']['groupid'] !== "1") ? $this->session->userdata['logged']['id'] : "";

		$params = array(
			'nofakt' 		=> !empty($this->input->get("nofakt")) ? urldecode($this->input->get("nofakt")) : "",
			'kdcust' 		=> !empty($this->input->get("kdcust")) ? urldecode($this->input->get("kdcust")) : "",
			'collector' 	=> !empty($this->input->get("collector")) ? urldecode($this->input->get("collector")) : $isNotAdmin,
			'start_pay_date'	=> !empty($this->input->get("start_pay_date")) ? urldecode($this->input->get("start_pay_date")) : "",
			'end_pay_date'		=> !empty($this->input->get("end_pay_date")) ? urldecode($this->input->get("end_pay_date")) : "",
			'status_bayar'	=> !empty($this->input->get("status_bayar")) ? urldecode($this->input->get("status_bayar")) : "",
			'outlet'		=> !empty($this->input->get("outlet")) ? urldecode($this->input->get("outlet")) : "",
		);

		$data['params'] = array(
							'start_pay_date' => $params['start_pay_date'],
							'end_pay_date' => $params['end_pay_date'],
							'nofakt' => $params['nofakt'],
							'kdcust' => $params['kdcust'],
							'collector' => $params['collector'],
							'status_bayar' => $params['status_bayar'],
							'outlet' => $params['outlet'],
						);

		$data['kdcustname'] = $this->transaksi_model->get_customer(null, null, $params['kdcust'])->row()->nama;
		$data['collectorname'] = $this->transaksi_model->get_collector($params['collector'], true)->row()->nama;
		$data['collector_id'] = !empty($params['collector']) ? $params['collector'] : "";

		$data['tablelist'] = array(
			'head' => array(
				//Caption, sort, width
				array('No.','nosort', '3%'),
				array('NOFAKT', '',''),
				array('NAMAKONS', '','1'),
				array('DATE', '',''),
				array('TENOR', '',''),
				array('ANGSURAN', '',''),
				array('ANGKE', '',''),
				array('OUTLET', '','')
			)
		);

		$data['via'] = "laporanpembayaran";
		$data['page_header'] = array(
								"DPP-MU",
								"Transaksi",
								"LAPORAN PEMBAYARAN"
							  );

		$this->load->view('backend/transaksi/laporan_bayar', $data);

	}

	function gettablelist_pembayaran()
	{
		$isNotAdmin = ($this->session->userdata['logged']['groupid'] !== "1") ? $this->session->userdata['logged']['id'] : "";

		$columns = array( 
			0 =>'id',
			1 =>'nofakt', 
			2 => 'name',
			3 => 'tenor_pembayaran',
			4 => 'expected_amount',
			5 => 'retail_outlet_name',
			6 => 'payment_code'
		);

		$params = array(
			'nofakt' 		=> !empty($this->input->get("nofakt")) ? urldecode($this->input->get("nofakt")) : "",
			'kdcust' 		=> !empty($this->input->get("kdcust")) ? urldecode($this->input->get("kdcust")) : "",
			'outlet'		=> !empty($this->input->get("outlet")) ? urldecode($this->input->get("outlet")) : "",
			'request' 		=> $_REQUEST,
			'columns'		=> $columns
		);

		$selrec	= $this->transaksi_model->get_ref_faktur($params, false, true);

		$sqlTot	= $this->transaksi_model->get_ref_faktur($params, false, false);
		$totalRecords = ($sqlTot!=false) ? $sqlTot->num_rows() : 0;
		$data = array();
		$page = ($_REQUEST['length'] + $_REQUEST['start']) / $_REQUEST['length'];
		$start = ($_REQUEST['start'] >= $_REQUEST['length']) ? $_REQUEST['start']-1 : $_REQUEST['start'];
		$no = $start + $page;
		$btn_disable = 0;

		if($totalRecords > 0)
		{

			foreach($selrec->result_array() as $row)
			{	
				if($this->input->get('via') == 'pembayaran'){
					$data[] = array(
			  				'<center>'.$no.'<center>',
			  				$row['nofakt'], 
							$row['name'],
							$row['tenor_pembayaran'],
							$row['expected_amount'],
							$row['retail_outlet_name'],
							$row['payment_code']
			  			);
				}

				$no++;
			}

		}else{

			$data = array();

		}

		$json_data = array(
			"draw"            => intval( $_REQUEST['draw'] ),   
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval($totalRecords),
			"data"            => $data
		);

		echo json_encode($json_data);

	}

	function gettablelist_laporan_pembayaran()
	{
		$isNotAdmin = ($this->session->userdata['logged']['groupid'] !== "1") ? $this->session->userdata['logged']['id'] : "";

		$columns = array( 
			0 =>'id',
			1 =>'nofakt', 
			2 => 'namakons',
			3 => 'transaction_timestamp',
			4 => 'tenor',
			5 => 'angsuran',
			6 => 'angke',
			7 => 'retail_outlet_name'
		);

		$params = array(
			'nofakt' 		=> !empty($this->input->get("nofakt")) ? urldecode($this->input->get("nofakt")) : "",
			'kdcust' 		=> !empty($this->input->get("kdcust")) ? urldecode($this->input->get("kdcust")) : "",
			'outlet'		=> !empty($this->input->get("outlet")) ? urldecode($this->input->get("outlet")) : "",
			'start_pay_date'	=> !empty($this->input->get("start_pay_date")) ? urldecode($this->input->get("start_pay_date")) : "",
			'end_pay_date'		=> !empty($this->input->get("end_pay_date")) ? urldecode($this->input->get("end_pay_date")) : "",
			'request' 		=> $_REQUEST,
			'columns'		=> $columns
		);

		$selrec	= $this->transaksi_model->get_laporan_pembayaran($params, false, true);

		$sqlTot	= $this->transaksi_model->get_laporan_pembayaran($params, false, false);
		$totalRecords = ($sqlTot!=false) ? $sqlTot->num_rows() : 0;
		$data = array();
		$page = ($_REQUEST['length'] + $_REQUEST['start']) / $_REQUEST['length'];
		$start = ($_REQUEST['start'] >= $_REQUEST['length']) ? $_REQUEST['start']-1 : $_REQUEST['start'];
		$no = $start + $page;
		$btn_disable = 0;
		// var_dump($selrec->result_array());die;
		if($totalRecords > 0)
		{

			foreach($selrec->result_array() as $row)
			{	
				if($this->input->get('via') == 'laporanpembayaran'){
					$data[] = array(
			  				'<center>'.$no.'<center>',
			  				$row['nofakt'], 
							$row['namakons'],
							$row['transaction_timestamp'],
							$row['tenor'],
							$row['angsuran'],
							$row['angke'],
							$row['retail_outlet_name']
			  			);
				}

				$no++;
			}

		}else{

			$data = array();

		}

		$json_data = array(
			"draw"            => intval( $_REQUEST['draw'] ),   
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval($totalRecords),
			"data"            => $data
		);

		echo json_encode($json_data);

	}

	function get_detail_faktur(){
		$id = $this->input->post('id');
		$query = $this->transaksi_model->get_detail_faktur($id);	
		if($query){
			$res = array("status" => "success", "data" => $query);
		}else{
			$res = array("status" => "failed", "data" => "data tidak ditemukan");
		}
		print(json_encode($res));
	}

	function get_dpk_trx_pembayaran()
	{
		$id = $this->input->post('id');
		$query = $this->transaksi_model->get_dpk_trx_pembayaran($id);
		if ($query->num_rows() > 0){
			$row = $query->row();
			$json = array(
						"id"			=> $id,
						"id_nasabah"	=> $row->id_nasabah,
						"kdcust"		=> $row->kdcust,
						"nofakt"		=> $row->nofakt,
						"dddate"		=> $row->dddate,
						"mydate"		=> $row->mydate,
						"namakons"		=> $row->namakons,
						"tlpn"			=> ($row->tlpn == "") ? (($row->notlpn1 == "") ? $row->notlpn2 : $row->notlpn1) : $row->tlpn,
						"angsuran_rp"	=> "Rp. ". number_format($row->angsuran),
						"angsuran"		=> $row->angsuran,
						"tenor"			=> $row->tenor,
						"angke"			=> $row->angke,
						"no_dpk"		=> $row->no_dpk,
						"kode_bayar"	=> $row->kode_bayar,
						"bayar"			=> $row->bayar,
						"bayar_sebagian"=> $row->bayar_sebagian,
						"bayar_sisa"	=> $row->bayar_sisa,
						"keterangan"	=> $row->keterangan,
						"update_by"		=> $row->update_by,
						"update_at"		=> $row->created_date,
						"update_via"	=> $row->update_via,
						"publish_by_id"	=> $row->publish_by_id,
						"publish_by"	=> $row->publish_by,
					);

			$res = array("status" => "success", "data" => $json);

		}else{
			$res = array("status" => "error", "msg" => "tidak ada data");
		}

		print(json_encode($res));
	}

	function check_request_pembayaran($transaksi_id, $outlet)
	{
		$check_request_pembayaran = $this->transaksi_model->check_request_pembayaran($transaksi_id, $outlet);

		if ($check_request_pembayaran->num_rows() > 0) return FALSE;
		else return TRUE;
	}

    function minta_kode_pembayaran()
    {
		$this->load->library('xendit');

    	$post = $this->input->post(NULL, TRUE);
		$xendit = new Xendit;
		$xendit->_payment_code = $post['id_nasabah'];
		$xendit->_external_id = $post['externalid'];
		$xendit->_retail_outlet = $post['channel'];
		$xendit->_name = $post['nasabah'];
		$xendit->_expected_amount = $post['angkePembayaran'];
		$xendit->_cust_code = $post['kdcust'];
    	$result = $xendit->create_payment_code();
    	// var_dump($result);die;

    	if (isset($result['error_code']) && !empty($result['error_code'])) echo json_encode(array('success' => FALSE, 'msg' => $result['message']));
    	else
    	{
	    	$result['transaksi_id'] = $post['transaksi_id'];
	    	$result['external_id'] = $post['transaksi_id'];
	    	$result['nofakt'] = $post['nofaktPembayaran'];
	    	$result['tenorPembayaran'] = $post['tenorPembayaran'];
	    	$result['kdcust'] = $post['kdcust'];

	    	$this->insert_request_xendit_trx($result);
	    	// $this->update_id_nasabah($post['transaksi_id'], $post['id_nasabah']);
			
	    	echo json_encode(array('success' => TRUE, 'data' => $result));
    	}
    } 

    function insert_request_xendit_trx($data) {
    	return $this->transaksi_model->insert_request_xendit_trx($data);
    }

    function update_id_nasabah($kdcust, $id_nasabah) {
    	return $this->transaksi_model->update_id_nasabah($kdcust, $id_nasabah);
    }

    function receive_xendit_notify() {
		$this->load->library('xendit');
		
		$xendit = new Xendit;
    	$result = $xendit->validate_notify();

    	if ($result !== FALSE)
    	{
		    $notify_data = (array) $result;
		    $external_id = $this->transaksi_model->receive_xendit_notify($notify_data);

		    $this->update_data_transaksi($external_id);
    	}
    	else
    	{
		    print_r("Cannot ".$_SERVER["REQUEST_METHOD"]." ".$_SERVER["SCRIPT_NAME"]);
		}
    }

    function update_data_transaksi($trx_id) {

    	$get_data_trx = $this->transaksi_model->get_data_trx($trx_id);
    	$row = $get_data_trx->row();
    	$trx_id = $row->id;
    	$no_dpk = !empty($row->no_dpk) ? $row->no_dpk :  $trx_id.time();
    	$tlpn = !empty($row->tlpn) ? $row->tlpn :  '';
    	$keterangan = !empty($row->keterangan) ? $row->keterangan :  '';
    	$params = array(
			'no_dpk' => $no_dpk,
			'tlpn' => $tlpn,
			'kode_bayar' => '99',
			'bayar' => 'Bayar',
			'keterangan' => $keterangan,
			'update_via' => 'xendit',
			'update_by_id' => 999,
			'update_by' => 'Xendit',
			'created_date' => date('Y-m-d H:i:s')
		);

		$result = $this->transaksi_model->dpk_process($params, $trx_id);

		if($result){

			$list = array(
				'id_trx' => $trx_id,
				'nofakt' => $row->nofakt,
				'due_date' => $row->due_date,
				'dddate' => $row->dddate,
				'mydate' => $row->mydate,
				'kdcust' => $row->kdcust,
				'namakons' => $row->namakons,
				'collector_id' => $row->collector_id,
				'collector' => $row->collector,
				'tlpn' => $tlpn,
				'angsuran' => $row->angsuran,
				'tenor' => $row->tenor,
				'angke' => $row->angke,
				'no_dpk' => $no_dpk,
				'kode_bayar' => '99',
				'bayar' => 'Bayar',
				'bayar_sebagian' => NULL,
				'bayar_sisa' => NULL,
				'keterangan' => $keterangan,
				'nextDate' => NULL,
				'update_via' => 'xendit',
				'update_by_id' => 999,
				'update_by' => 'Xendit',
				'created_date' => date('Y-m-d H:i:s')
			);

			return $this->transaksi_model->dpk_process_log($list);
		}
    }

    public function validate_export_to_excel()
    {
    	$post = $this->input->post(NULL, TRUE);

   		$post['request'] = array();
		$post['columns'] = array();

   		$get_pembayaran_report	= $this->transaksi_model->get_pembayaran_report($post, false, false);

   		if ($get_pembayaran_report->num_rows() > 500)
   		{
   			echo json_encode(array('success' => FALSE));
   		}
   		else
   		{
   			echo json_encode(array('success' => TRUE));
   		}
    }

    public function validate_export_to_excel_pembayaran()
    {
    	$post = $this->input->post(NULL, TRUE);

   		$post['request'] = array();
		$post['columns'] = array();

   		$get_pembayaran_report	= $this->transaksi_model->get_ref_faktur($post, false, false);

   		if ($get_pembayaran_report->num_rows() > 500)
   		{
   			echo json_encode(array('success' => FALSE));
   		}
   		else
   		{
   			echo json_encode(array('success' => TRUE));
   		}
    }

   	function export_to_excel_pembayaran()
   	{
   		$post = $this->input->post(NULL, TRUE);

   		$post['request'] = array();
		$post['columns'] = array();

   		$get_pembayaran_report	= $this->transaksi_model->get_ref_faktur($post, false, false);

   		if ($get_pembayaran_report->num_rows() > 0) 
   		{
   			$result = $get_pembayaran_report->result();

   			$number = 1;

   			$new_data = array();

   			foreach ($result as $k => $v) 
   			{
   				$v->number = $number++;
   				$new_data[$k] = (array)$v;
   			}

   			$this->load->library('xls_writer');

   			$this->xls_writer->config($post);
	        $qry = $this->xls_writer->get_grid();
	        $this->xls_writer->store_data($new_data);
	        $this->xls_writer->add_sheet('Sheet1');
	        $this->xls_writer->save('Laporan_pembayaran_' . date('Y-m-d_H-i-s') . '.xls');
   		}
   	}
}