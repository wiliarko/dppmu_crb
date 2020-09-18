<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Xendit_payment extends CI_Controller {

	function Xendit_payment(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation', 'session'));
		$this->load->library(array('pagination','authentication'));
		$this->load->model(array('backend/Xendit_model','backend/transaksi_model'));
		
		$this->url_modul = base_url().'backend/Xendit_payment/';
		$this->page = 'Xendit';
	}

	public function index()
	{
		echo 'error';
	}

	public function index_old() {
		$this->authentication->restrict();

		$data['pageInfo'] = array(
			'page' => $this->page,
			'url_module' => $this->url_modul,
		);

        $cssFiles = array(
                        base_url().'assets/css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css',
                        base_url().'assets/css/plugins/jqGrid/ui.jqgrid.css'
                    );
        $data['output'] = (object) array('css_files' => $cssFiles);

        $data['collector'] = ($this->session->userdata['logged']['groupid'] == 2) ? $this->session->userdata['logged']['id'] : "";
		$data['collectorname'] = $this->transaksi_model->get_collector($data['collector'], true)->row()->nama;
		
		

		$this->load->view('backend/xendit/index', $data);
	}

	// ambil dari api xendit
	public function payment() {
		$last_transaction=$this->Xendit_model->get_last_transaction();
		$last_transaction =$last_transaction->transaction_timestamp;
		$xendit_api=$this->Xendit_model->get_xendit_api($last_transaction);
		
		$data =array();
		if(count($xendit_api) > 0)
		{
			foreach ($xendit_api as $row) {
				array_push($data, array(
						"xendit_id" 						=> $row['xendit_id'],
						"owner_id" 							=> $row['owner_id'],
						"fixed_payment_code_payment_id" 	=> $row['fixed_payment_code_payment_id'],
						"fixed_payment_code_id"				=> $row['fixed_payment_code_id'],
						"status" 							=> $row['status'],
						"sync" 								=> $row['sync'],
						"amount" 							=> $row['amount'],
						"name" 								=> $row['name'],
						"prefix" 							=> $row['prefix'],
						"payment_code" 						=> $row['payment_code'],
						"payment_id" 						=> $row['payment_id'],
						"external_id" 						=> $row['external_id'],
						"retail_outlet_name" 				=> $row['retail_outlet_name'],
						"transaction_timestamp" 			=> $row['transaction_timestamp'],
						"timestamp_xendit" 					=> $row['timestamp'],
						"timestamp" 						=> date("Y-m-d H:i:s")
				)
				);
			}
			$feed=$this->Xendit_model->post_xendit_api($data);
			$res = array(
							'result' => 'success',
							'msg' 	=> 'Data berhasil diperbarui',
							'data'  => $xendit_api
					);
			header('Content-Type: application/json');
        	echo json_encode($res);
		}else {
			header('Content-Type: application/json');
			$res = array('result' => 'success', 
						 'msg' 	  => 'Tidak ada data baru');
        	echo json_encode($res);
		}
	}

	function payment_list()
    {
    	$aColumns = array('name', 'xendit_id', 'owner_id','fixed_payment_code_payment_id','fixed_payment_code_id','status','sync','amount','name','prefix','payment_code','payment_id','retail_outlet_name','transaction_timestamp');

		// paging
        $sLimit = "";
        if (isset($_GET['start']) && $_GET['length'] != '-1') {
            $sLimit = "LIMIT " . ($_GET['start']) . ", " . ($_GET['length']);
        }

        // ordering
        if (isset($_GET['order'])) {
            $sOrder = "ORDER BY id ";
            $sOrder .= $_GET['order'][0]['dir'];
        }

        // filtering
        $sWhere = "";
        if ($_GET['search'] != "") {
            $where_ = '';
            $sWhere = "WHERE $where_ (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $_GET['search']['value'] . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }


        if($_GET['tgl_awal'] !== "" && $_GET['tgl_akhir'] !== "")
        {
            $sWhere .= " AND transaction_timestamp BETWEEN '".$_GET['tgl_awal'] ."%' AND '". $_GET['tgl_akhir']  ."%' ";
        }

        $rResult = $this->Xendit_model->get_data($sWhere, $sOrder, $sLimit);
        $iFilteredTotal = !empty($sLimit) ? $_GET['length'] : 10;

        $rResultTotal = $this->Xendit_model->get_data($sWhere);
        $iTotal = $rResultTotal->num_rows();
        $iFilteredTotal = $iTotal;

        $output = array(
            "sEcho" => isset($_GET['sEcho']) ? intval($_GET['sEcho']) : 0,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
		$i=1;
        foreach ($rResult->result_array() as $row) {

        	$status = ($row['status'] == 'SETTLING') ? 'PAID' : $row['status'];
        	$outlet = ($row['retail_outlet_name'] == 'ALFAMART') ?  'ALFAMART_LOGO_BARU.png' : 'Indomaret.png';

            $row = array(
                    'RecordID'							=> $i,
                    "xendit_id" 						=> $row['xendit_id'],
					"owner_id" 							=> $row['owner_id'],
					"fixed_payment_code_payment_id" 	=> $row['fixed_payment_code_payment_id'],
					"fixed_payment_code_id"				=> $row['fixed_payment_code_id'],
					"status" 							=> '<center><span class="label label-warning">'. $status .'</span></center>',
					"sync" 								=> $row['sync'],
					"amount" 							=> "Rp ". number_format($row['amount']),
					"name" 								=> $row['name'],
					"prefix" 							=> $row['prefix'],
					"payment_code" 						=> $row['payment_code'],
					"payment_id" 						=> $row['payment_id'],
					"external_id" 						=> $row['external_id'],
					"retail_outlet_name" 				=> '<center><img src="'.base_url().'assets/img/'.$outlet.'" height="20"></center>',
					"transaction_timestamp" 			=> date("d M, Y h:i A", strtotime($row['transaction_timestamp'])),
					"timestamp_xendit" 					=> $row['timestamp_xendit'],
            );

			$output['aaData'][] = $row;
			$i++;
        }

        header('Content-Type: application/json');
        echo json_encode($output);
	}
	
	
}