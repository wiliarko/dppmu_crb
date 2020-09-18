<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('encrypt', 'form_validation', 'session'));
    	$this->load->library('authentication');
    	$this->load->model(array('api_model', 'backend/log_model', 'backend/transaksi_model'));
	}
	
	public function login($uname='', $pass='')
	{
		
		if($uname == '' && $pass ==''){
			$params = array(
				'uname' => $this->input->post('username'),
				'pass' => $this->input->post('password')
			);
		}else{
			$params = array(
				'uname' => $uname,
				'pass' => $pass
			);
		}

		if($params['uname'] != '' && $params['pass'] !=''){
			$rec = $this->api_model->get_login($params);
			
			if($rec->num_rows()){
				$row = $rec->row();
				
				$device = $this->api_model->check_device( $params['uname'], $this->input->post("device_id") );
				if($device->num_rows()){
					
					$data = array(
						'collector_id' => $row->marketing_id,
						'token' => $row->marketing_id.$this->input->post("device_id"),
						'username' => $row->username,
						'name' => $row->first_name
					);

					$log = array(
						'user_name' => $row->username,
						'updated' 	=> date("Y-m-d H:i:s"),
						'content' 	=> 'Login Aplikasi via Apps.'
					);
					$this->log_model->insert($log);
					
					$response = array("status" => 1, "msg" => "Login successful, redirecting..");
					$arr = array_merge($response, $data);
				}
				else $arr = array("status" => 7, "msg" => "No Device ID found.");
			}
			else $arr = array("status" => 0, "msg" => "Failed Wrong Uname / Password.");
		}
		else $arr = array("status" => 0, "msg" => "Please complete the parameters first.");

		header('Cache-Control: no-cache, must-revalidate');
	    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	    header('Content-type: application/json');
	    header("access-control-allow-origin: *");
	    echo json_encode($arr);
	}

	public function transaksi_info()
	{

		$collector_id = $this->input->post('collector_id');
		$nofakt = $this->input->post('nofakt');
		$kdcust = $this->input->post('kdcust');
		$status_bayar = "belum_bayar";
		$token = $this->input->post('token');

		//** CHECK TOKEN **//
		$query = $this->api_model->get_deviceid($collector_id);
		$row = $query->row();
		$device_id = $row->device_id;
		
		if($token !== $collector_id.$device_id){

			$arr = array("status" => 9, "msg" => "Token not found.");

			header('Cache-Control: no-cache, must-revalidate');
		    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		    header('Content-type: application/json');
		    header("access-control-allow-origin: *");
		    echo json_encode($arr);
		    exit;

		}

		if($collector_id != '' && $nofakt != '' && $kdcust != '')
		{

			$params = array(
				'nofakt' 		=> $nofakt,
				'kdcust' 		=> $kdcust,
				'collector' 	=> $collector_id,
				'status_bayar'	=> $status_bayar,
			);

			$rec = $this->transaksi_model->get_dpk($params, true);
			if($rec->num_rows()){
				
				$data1 = $rec->result_array()[0];
				$data1['trx_id'] = $data1['id'];
				unset($data1['id']);

				$data2 = array("status" => 1, "msg" => "data loaded.");

				$arr = array_merge($data2,$data1);
			}
			else $arr = array("status" => 0, "msg" => "No Faktur empty.");
		
		}else $arr = array("status" => 0, "msg" => "Failed Empty Value.");

		header('Cache-Control: no-cache, must-revalidate');
	    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	    header('Content-type: application/json');
	    header("access-control-allow-origin: *");
	    echo json_encode($arr);
	}

	public function push_trx()
	{
		
		$collector_id = $this->input->post('collector_id');
		$trx_id = $this->input->post('trx_id');
		$kode_bayar = $this->input->post('kode_bayar');
		$keterangan = $this->input->post('keterangan');
		$token = $this->input->post('token');

		//** CHECK TOKEN **//
		$query = $this->api_model->get_deviceid($collector_id);
		$row = $query->row();
		$device_id = $row->device_id;
		
		if($token !== $collector_id.$device_id){

			$arr = array("status" => 9, "msg" => "Token not found.");

			header('Cache-Control: no-cache, must-revalidate');
		    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		    header('Content-type: application/json');
		    header("access-control-allow-origin: *");
		    echo json_encode($arr);
		    exit;

		}

		//** CHECK FAKTUR BAYAR
		$result = $this->api_model->checkfaktur_blmbayar($trx_id);
		if($result->num_rows()){

			$arr = array("status" => 9, "msg" => "Transaction has been done.");

			header('Cache-Control: no-cache, must-revalidate');
		    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		    header('Content-type: application/json');
		    header("access-control-allow-origin: *");
		    echo json_encode($arr);
		    exit;

		}

		if($collector_id != '' && $trx_id != '' && $kode_bayar != '')
		{

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, site_url('backend/transaksi/dpk_process/dpk'));
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
			            "collector_id=$collector_id&dpk_id=$trx_id&isPay=$kode_bayar&bayarLainnya=$keterangan");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$server_output = curl_exec($ch);

			curl_close ($ch);

			$response = json_decode($server_output);
			if($response->status != 'error'){

				$arr = array("status" => 1, "msg" => "Data uploaded successfully");

			}else $arr = array("status" => 0, "msg" => $response->msg);

		}else $arr = array("status" => 0, "msg" => "Failed Empty Value.");
		
		header('Cache-Control: no-cache, must-revalidate');
	    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	    header('Content-type: application/json');
	    header("access-control-allow-origin: *");
	    echo json_encode($arr);

	}

	public function getnofaktur()
	{
		$collector_id = $this->input->post('collector_id');
		$kdcust = $this->input->post('kdcust');
		$token = $this->input->post('token');

		//** CHECK TOKEN **//
		$query = $this->api_model->get_deviceid($collector_id);
		$row = $query->row();
		$device_id = $row->device_id;
		
		if($token !== $collector_id.$device_id){

			$arr = array("status" => 9, "msg" => "Token not found.");

			header('Cache-Control: no-cache, must-revalidate');
		    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		    header('Content-type: application/json');
		    header("access-control-allow-origin: *");
		    echo json_encode($arr);
		    exit;

		}

		if($collector_id != '' && $kdcust != ''){
			$rec = $this->transaksi_model->get_no_faktur($collector_id, $kdcust);
			if($rec->num_rows()){
				
				foreach($rec->result() as $row){
					$data[] = array(
						'nofakt' => $row->nofakt
					);
				}

				$arr = array("status" => 1, "msg" => "data loaded.", "list" => $data);
			}
			else $arr = array("status" => 0, "msg" => "No Faktur empty.");
		}
		else $arr = array("status" => 0, "msg" => "Failed Empty Value.");
		
		header('Cache-Control: no-cache, must-revalidate');
	    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	    header('Content-type: application/json');
	    header("access-control-allow-origin: *");
	    echo json_encode($arr);
	}

	public function getcustomer()
	{
		$collector_id = $this->input->post('collector_id');
		$token = $this->input->post('token');

		//** CHECK TOKEN **//
		$query = $this->api_model->get_deviceid($collector_id);
		$row = $query->row();
		$device_id = $row->device_id;
		
		if($token !== $collector_id.$device_id){

			$arr = array("status" => 9, "msg" => "Token not found.");

			header('Cache-Control: no-cache, must-revalidate');
		    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		    header('Content-type: application/json');
		    header("access-control-allow-origin: *");
		    echo json_encode($arr);
		    exit;

		}

		if($collector_id != ''){
			$rec = $this->transaksi_model->get_customer($collector_id);
			if($rec->num_rows()){
				
				foreach($rec->result() as $row){
					$data[] = array(
						'kdcust' => $row->kdcust,
						'nama' => $row->nama
					);
				}

				$arr = array("status" => 1, "msg" => "data loaded.", "list" => $data);
			}
			else $arr = array("status" => 0, "msg" => "No Faktur empty.");
		}
		else $arr = array("status" => 0, "msg" => "Failed Empty Value.");
		
		header('Cache-Control: no-cache, must-revalidate');
	    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	    header('Content-type: application/json');
	    header("access-control-allow-origin: *");
	    echo json_encode($arr);
	}

	public function getstatusbayar()
	{
		$collector_id = $this->input->post('collector_id');
		$token = $this->input->post('token');

		//** CHECK TOKEN **//
		$query = $this->api_model->get_deviceid($collector_id);
		$row = $query->row();
		$device_id = $row->device_id;
		
		if($token !== $collector_id.$device_id){

			$arr = array("status" => 9, "msg" => "Token not found.");

			header('Cache-Control: no-cache, must-revalidate');
		    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		    header('Content-type: application/json');
		    header("access-control-allow-origin: *");
		    echo json_encode($arr);
		    exit;

		}

		if($collector_id != ''){
			
			$isBayar = array('','Janji Bayar', 'Percepatan', 'Sisa Titipan', 'Tarik Barang', 'Tidak Ada Orang', 'Alasan Kesehatan', 'Alasan Ekonomi', 'Komplain Produk', 'Rencana Tarik Barang', 'Pindah Alamat', 'Tidak Ada Barang', 'Ada Barang + Karakter', 'Bangkrut / Pailit', 'Kabur / Hilang', 'Force Mejeur');

			$data[] = array('kode_bayar' => '99','status_bayar' => 'bayar');
			$data[] = array('kode_bayar' => '00','status_bayar' => 'Bayar Sebagian');


			for($i=1; $i <= 15; $i++) {
				$data[] = array(
					'kode_bayar' => str_pad($i, 2, '0', STR_PAD_LEFT),
					'status_bayar' => $isBayar[$i]
				);
			}

			$arr = array("status" => 1, "msg" => "data loaded.", "list" => $data);
		}
		else $arr = array("status" => 0, "msg" => "Failed Empty Value.");
		
		header('Cache-Control: no-cache, must-revalidate');
	    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	    header('Content-type: application/json');
	    header("access-control-allow-origin: *");
	    echo json_encode($arr);
	}

}