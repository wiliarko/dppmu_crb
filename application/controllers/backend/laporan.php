<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation', 'session'));
		$this->load->library(array('pagination','authentication'));
		$this->load->library('grocery_CRUD');
		$this->load->model(array('backend/laporan_model', 'backend/transaksi_model'));
		
		$this->url_modul = base_url().'backend/laporan/';
		$this->page = 'Laporan';
	}

	public function index() {
		redirect(base_url().'backend/home/');
	}

	public function r($type=null) {

		$this->authentication->restrict();
		
		$data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => $type,
			'url_module' => $this->url_modul .'r/'.$type,
		);
		
		$isNotAdmin = ($this->session->userdata['logged']['groupid'] !== "1") ? $this->session->userdata['logged']['id'] : "";

		$params = array(
			'nofakt' 		=> !empty($this->input->get("nofakt")) ? urldecode($this->input->get("nofakt")) : "",
			'kdcust' 		=> !empty($this->input->get("kdcust")) ? urldecode($this->input->get("kdcust")) : "",
			'collector' 	=> !empty($this->input->get("collector")) ? urldecode($this->input->get("collector")) : $isNotAdmin,
			'start_date'	=> !empty($this->input->get("start_date")) ? urldecode($this->input->get("start_date")) : "",
			'end_date'		=> !empty($this->input->get("end_date")) ? urldecode($this->input->get("end_date")) : "",
			'bulan'			=> !empty($this->input->get("bulan")) ? urldecode($this->input->get("bulan")) : "",
			'tahun'			=> !empty($this->input->get("tahun")) ? urldecode($this->input->get("tahun")) : "",
		);
		
		$data['params'] = array(
							'start_date' => $params['start_date'],
							'end_date' => $params['end_date'],
							'nofakt' => $params['nofakt'],
							'kdcust' => $params['kdcust'],
							'collector' => $params['collector'],
						);

		$selrec	= ($type == 'lunas') ? $this->laporan_model->get_lunas_bayar($params) : $this->laporan_model->get_laporan($params, $type);
			
		$data['noFaktur']	= ($type == 'lunas') ? $this->laporan_model->get_nofakt_lunas() : $this->transaksi_model->get_no_faktur();
		$data['customer']	= ($type == 'lunas') ? $this->laporan_model->get_customer_lunas() : $this->transaksi_model->get_customer();
		$data['collector'] 	= ($type == 'lunas') ? $this->laporan_model->get_collector_lunas() : $this->transaksi_model->get_collector();
		$data['type'] 		= $type;

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
				array('KETERANGAN', '',''),
				array('', '',''),
			),
			
			'row' => $selrec
		);

		$data['page_header'] = array(
								"DPP-MU",
								"Laporan",
								ucwords(str_replace("_"," ",$type))
							  );
		
		$this->load->view('backend/laporan/list', $data);
	}

	public function rekapitulasi()
	{
		$this->authentication->restrict();

		$data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'rekapitulasi',
			'url_module' => $this->url_modul.'rekapitulasi',
		);

		$data['page_header'] = array(
                                "Laporan",
                                "Rekapitulasi Kolektor"
                              );

		$isNotAdmin = ($this->session->userdata['logged']['groupid'] !== "1") ? $this->session->userdata['logged']['id'] : "";

        $params = array(
            'status_bayar'  => !empty($this->input->get("status_bayar")) ? urldecode($this->input->get("status_bayar")) : "",
            'collector' 	=> !empty($this->input->get("collector")) ? urldecode($this->input->get("collector")) : $isNotAdmin,
            'start_date'  => !empty($this->input->get("start_date")) ? urldecode($this->input->get("start_date")) : "",
            'end_date'  => !empty($this->input->get("end_date")) ? urldecode($this->input->get("end_date")) : "",
        );

        $data['params'] = array(
        	'status_bayar' => $params['status_bayar'],
        	'collector' => $params['collector'],
        );
        
        $data['collector'] = $this->transaksi_model->get_collector();
        $data['tablelist1'] = array(
            'head' => array(
                //Caption, sort, width
                array('No.','nosort', '3%'),
                array('NOFAKT', '',''),
                array('NAME', '',''),
                array('COLLECTOR', '',''),
                array('PHONE', '',''),
                array('TENOR', '',''),
                array('ANGS', '',''),
                array('KE', '',''),
                array('DUE DATE', '',''),
                array('STATUS', '',''),
                array('TANGGAL BAYAR', '',''),
                array('KETERANGAN', '','')
            )
        );

        $this->load->view('backend/laporan/rekapitulasi', $data);
	}

	function gettables_rekap()
    {
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
            9 => 'bayar',
            10 => 'keterangan'
        );

        $params = array(
            'status_bayar'  => !empty($this->input->get("status_bayar")) ? urldecode($this->input->get("status_bayar")) : "",
            'start_date'    => !empty($this->input->get("start_date")) ? urldecode($this->input->get("start_date")) : "",
            'end_date'      => !empty($this->input->get("end_date")) ? urldecode($this->input->get("end_date")) : "",
            'collector_id'  => !empty($this->input->get("collector_id")) ? urldecode($this->input->get("collector_id")) : "",
            'request'       => $_REQUEST,
            'columns'       => $columns
        );

        $selrec = $this->laporan_model->get_customer(null, $params, true);
        $sqlTot = $this->laporan_model->get_customer(null, $params, false);
        // $totalRecords = $sqlTot->num_rows();

        $data = array();

        $page = ($_REQUEST['length'] + $_REQUEST['start']) / $_REQUEST['length'];
        $start = ($_REQUEST['start'] >= $_REQUEST['length']) ? $_REQUEST['start']-1 : $_REQUEST['start'];
        $no = $start + $page;
        $totalRecords=0;
        
        foreach($selrec->result_array() as $row)
        {   
        	$isWhere='';
        	if($params['status_bayar'] == 'bayar')
        	{
        		$isWhere .= " AND DATE_FORMAT(created_date,'%Y-%m-%d') BETWEEN '".$params['start_date']."' AND '".$params['end_date']."' AND kode_bayar='99'";
        	}
        	else if($params['status_bayar'] == 'belum_bayar')
        	{
        		$isWhere.= " AND DATE_FORMAT(created_date,'%Y-%m-%d') BETWEEN '".$params['start_date']."' AND '".$params['end_date']."' AND kode_bayar!='99'";
        	}else{
        		$isWhere .= " AND DATE_FORMAT(created_date,'%Y-%m-%d') BETWEEN '".$params['start_date']."' AND '".$params['end_date']."'";
        	}

        	if($params['collector_id'] != ''){
				$isWhere .= " AND collector_id ='".$params['collector_id']."'";
			}

        	$sql = "SELECT tenor, angke, angsuran, due_date, created_date, kode_bayar, bayar, keterangan, collector_id
        			FROM `transaksi`
        			WHERE 1 $isWhere AND `nofakt`='".$row['nofakt']."' ORDER BY `angke` ASC LIMIT 1";
        	
        	$query = $this->db->query($sql);
            if($query->num_rows() > 0)
            {
                $list = $query->row();
                $kode_bayar = $list->kode_bayar;
                $bayar = $list->bayar;
                $tenor = $list->tenor;
                $collector_id = $list->collector_id;
                $angke = $list->angke;
                $due_date = $list->due_date;
                $keterangan = $list->keterangan;
                $tgl_bayar = ($kode_bayar=='99') ? $list->created_date : '-';
                $angsuran = number_format($list->angsuran);

                $qCollector = $this->transaksi_model->get_collector($collector_id, true);
				$cRow = $qCollector->row();
				$collector_name = $cRow->nama;

	            $data[] = array(
	                '<center>'.$no.'<center>',
	                $row['nofakt'],
	                $row['nama'],
	                $collector_name,
	                $row['telephone'],
	                $tenor,
	                $angsuran,
	                $angke,
	                $due_date,
	                $bayar,
	                $tgl_bayar,
	                $keterangan
	            );

	            $no++;
	            $totalRecords++;
            }
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
		$query = $this->laporan_model->get_dpk_trx($id);
		if ($query->num_rows() > 0){
			$row = $query->row();
			$json = array(
						"id"			=> $id,
						"nofakt"		=> $row->nofakt,
						"dddate"		=> $row->dddate,
						"mydate"		=> $row->mydate,
						"namakons"		=> $row->namakons,
						"tlpn"			=> $row->tlpn,
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

	public function kas_masuk() {

		$this->authentication->restrict();

		$data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'kas_masuk',
			'url_module' => $this->url_modul .'/kas_masuk',
		);

        $params = array(
			'userkasir'	=> !empty($this->input->get("userkasir")) ? urldecode($this->input->get("userkasir")) : "",
			'start_date'	=> !empty($this->input->get("start_date")) ? urldecode($this->input->get("start_date")) : "",
			'end_date'		=> !empty($this->input->get("end_date")) ? urldecode($this->input->get("end_date")) : ""
		);

		$data['kasirname'] = $this->transaksi_model->get_userkasir($params['userkasir'])->row()->first_name;
		$data['params'] = array(
							'userkasir' => $params['userkasir'],
							'start_date' => $params['start_date'],
							'end_date' => $params['end_date']
						);

		$selrec	= $this->laporan_model->get_saldo($params);

		$data['tablelist'] = array(
			'head' => array(
				//Caption, sort, width
				array('SALDO ID.','nosort', '10%'),
				array('Saldo Masuk', '',''),
				array('Oleh', '',''),
				array('Tanggal', '','20%')
			),
			'row' => $selrec
		);

		$data['page_header'] = array("Kas Masuk","");

		$this->load->view('backend/laporan/kas_masuk', $data);

	}

	public function _setCurrency($value, $row)
	{
		return "<span style='width: 100%; text-align: right; display: block;'>Rp. ". number_format($value) ."</span>";
	}

	public function log_event() {

    	$this->authentication->restrict();

    	$crud = new grocery_CRUD();
        
        $crud->set_table('transaksi_log');
		$crud->unset_columns(array('id_trx', 'publish_by_id', 'collector_id', 'dddate', 'mydate', 'published_date', 'kode_bayar', 'update_by_id', 'published'));
		$crud->order_by('created_date', 'desc');
        
        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->unset_jquery();

        $data['output'] = $crud->render();

        $data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'Log Event',
			'url_module' => $this->url_modul,
		);

		$data['page_header'] = array("Log Event");

        $this->load->view('backend/laporan/log_event', $data);

    }

}