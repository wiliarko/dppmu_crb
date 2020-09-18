<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Deskcall extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation', 'session'));
		$this->load->library(array('pagination','authentication'));
		$this->load->library('grocery_CRUD');
		$this->load->model(array('backend/master_model','backend/transaksi_model'));
		
		$this->url_modul = base_url().'backend/deskcall/';
		$this->page = 'Deskcall';
    }

    public function index() {

    	$this->authentication->restrict();

		$data['pageInfo'] = array(
			'page' => $this->page,
			'url_module' => $this->url_modul,
		);

        $isNotAdmin = ($this->session->userdata['logged']['groupid'] !== "1") ? $this->session->userdata['logged']['id'] : "";

        $params = array(
            'status_bayar'  => !empty($this->input->get("status_bayar")) ? urldecode($this->input->get("status_bayar")) : "",
            'nofakt'        => !empty($this->input->get("nofakt")) ? urldecode($this->input->get("nofakt")) : "",
            'kdcust'        => !empty($this->input->get("kdcust")) ? urldecode($this->input->get("kdcust")) : "",
            'catatan'        => !empty($this->input->get("catatan")) ? urldecode($this->input->get("catatan")) : "",
            'collector'     => !empty($this->input->get("collector")) ? urldecode($this->input->get("collector")) : $isNotAdmin,
        );
        $data['collector_id'] = !empty($params['collector']) ? $params['collector'] : "";
        $data['kdcustname'] = $this->transaksi_model->get_customer(null, null, $params['kdcust'])->row()->nama;
        $data['collectorname'] = $this->transaksi_model->get_collector($params['collector'], true)->row()->nama;

        $data['params'] = array(
                            'status_bayar' => $params['status_bayar'],
                            'collector' => $params['collector'],
                            'nofakt' => $params['nofakt'],
                            'kdcust' => $params['kdcust'],
                            'catatan' => $params['catatan'],
                        );

        $data['tablelist1'] = array(
            'head' => array(
                //Caption, sort, width
                array('No.','nosort', '3%'),
                array('NIK', '',''),
                array('NOFAKT', '',''),
                array('NAME', '',''),
                array('HP 1', '',''),
                array('HP 2', '',''),
                array('HP 3', '',''),
                array('TENOR', '',''),
                array('ANGS', '',''),
                array('KE', '',''),
                array('DUE DATE', '',''),
                array('TERAKHIR BAYAR', '',''),
                array('', '',''),
            )
        );

        $data['page_header'] = array(
                                "Deskcall",
                                "list note call"
                              );

        $data['tablelist2'] = array(
            'head' => array(
                //Caption, sort, width
                array('No.','nosort', '3%'),
                array('No Faktur', '',''),
                array('Nama Konsumen', '',''),
                array('Collector', '',''),
                array('HP 1', '',''),
                array('HP 2', '',''),
                array('HP 3', '',''),
                array('Notes', '',''),
                array('keterangan', '',''),
                array('Created', '','')
            )
        );

		$this->load->view('backend/deskcall/list', $data);

    }

    function gettablelist()
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
            'nofakt'        => !empty($this->input->get("nofakt")) ? urldecode($this->input->get("nofakt")) : "",
            'kdcust'        => !empty($this->input->get("kdcust")) ? urldecode($this->input->get("kdcust")) : "",
            'collector'     => !empty($this->input->get("collector")) ? urldecode($this->input->get("collector")) : "",
            'request'       => $_REQUEST,
            'columns'       => $columns
        );

        $selrec = $this->master_model->get_customer(null, $params, true);
        $sqlTot = $this->master_model->get_customer(null, $params, false);
        $totalRecords = $sqlTot->num_rows();

        $data = array();

        $page = ($_REQUEST['length'] + $_REQUEST['start']) / $_REQUEST['length'];
        $start = ($_REQUEST['start'] >= $_REQUEST['length']) ? $_REQUEST['start']-1 : $_REQUEST['start'];
        $no = $start + $page;
        $btn_disable = 0;
        foreach($selrec->result_array() as $row)
        {   
        	$isWhere='';
        	if($params['status_bayar'] == 'bayar')
        	{
        		if($params['start_date'] != '' && $params['end_date'] != ''){
					$isWhere.= " AND due_date BETWEEN '".$params['start_date']."' AND '".$params['end_date']."'";
				}else{
					$isWhere.= " AND due_date < NOW() ";
				}
        	}
        	else if($params['status_bayar'] == 'belum_bayar')
        	{
        		if($params['start_date'] != '' && $params['end_date'] != ''){
					$isWhere.= " AND due_date BETWEEN '".$params['start_date']."' AND '".$params['end_date']."'";
				}else{
					$isWhere.= " AND NOW() > due_date ";
				}
        	}
        	else if($params['status_bayar'] == 'janji_bayar')
        	{
        		if($params['start_date'] != '' && $params['end_date'] != ''){
					$isWhere.= " AND due_date BETWEEN '".$params['start_date']."' AND '".$params['end_date']."'";
				}
        	}else{
        		if($params['start_date'] != '' && $params['end_date'] != ''){
					$isWhere .= " AND due_date BETWEEN '".$params['start_date']."' AND '".$params['end_date']."'";
				}
        	}

            if($params['collector'] != '' && $params['collector'] != '-'){
                $isWhere .= ' AND collector_id = "'. $params['collector'] .'"';
            }

        	$sql = "SELECT tenor, angke, angsuran, due_date
        			FROM `transaksi`
        			WHERE 1 $isWhere AND `kode_bayar` IS NULL AND `nofakt`='".$row['nofakt']."' ORDER BY `angke` ASC LIMIT 1";
        	
        	$query = $this->db->query($sql);
            if($query->num_rows() > 0)
            {
                $list = $query->row();
                $tenor = $list->tenor;
                $angke = $list->angke;
                $due_date = $list->due_date;
                $angsuran = number_format($list->angsuran);
                $btn = '<a href="'.base_url().'backend/deskcall/new_note/'.$row['id'].'" class="btn btn-white btn-bitbucket" ><i class="fa fa-plus"></i></a>';
            
                $query2 = $this->db->query("SELECT published_date FROM `transaksi` WHERE `kode_bayar` IS NOT NULL AND `nofakt`='".$row['nofakt']."' ORDER BY `angke` DESC LIMIT 1");
	            if($query2->num_rows()){
	                $list = $query2->row();
	                $last_pay = $list->published_date;
	            }else{
	                $last_pay = '-';;
	            }

	            $data[] = array(
	                '<center>'.$no.'<center>',
	                $row['nik'],
	                $row['nofakt'],
	                $row['nama'],
	                $row['telephone'],
	                $row['telephone2'],
	                $row['telephone3'],
	                $tenor,
	                $angsuran,
	                $angke,
	                $due_date,
	                $last_pay,
	                $btn,
	            );

	            $no++;
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

    function gettablelist2()
    {
        $columns = array( 
            0 =>'id',
            1 =>'nofakt', 
            2 => 'kdcust',
            3 => 'namakons',
            4 => 'collector_id',
            5 => 'collector',
            6 => 'hp1',
            7 => 'hp2',
            8 => 'hp3',
            9 => 'notes',
            10 => 'via',
            11 => 'via_date',
            12 => 'update_by_id',
            13 => 'update_by',
            14 => 'created_date'
        );

        $params = array(
        	'via'  		=> !empty($this->input->get("via")) ? urldecode($this->input->get("via")) : "",
            'request'   => $_REQUEST,
            'columns'   => $columns
        );

        $selrec = $this->master_model->get_deskcall(null, $params, true);
        $sqlTot = $this->master_model->get_deskcall(null, $params, false);
        $totalRecords = $sqlTot->num_rows();

        $data = array();

        $page = ($_REQUEST['length'] + $_REQUEST['start']) / $_REQUEST['length'];
        $start = ($_REQUEST['start'] >= $_REQUEST['length']) ? $_REQUEST['start']-1 : $_REQUEST['start'];
        $no = $start + $page;
        foreach($selrec->result_array() as $row)
        {   
            $data[] = array(
                '<center>'.$no.'<center>',
                $row['nofakt'],
                $row['namakons'],
                $row['collector'],
                $row['hp1'],
                $row['hp2'],
                $row['hp3'],
                $row['via'].' '.$row['via_date'],
                $row['notes'],
                $row['created_date'],
            );

            $no++;
        }

        $json_data = array(
            "draw"            => intval( $_REQUEST['draw'] ),   
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $data
        );

        echo json_encode($json_data);

    }

    function new_note($id) {

        $data['pageInfo'] = array(
            'page' => $this->page,
            'url_module' => $this->url_modul,
        );
        
        $data['id'] = $id;
        $data['result'] = $this->master_model->get_customer($id);
        $data['page_header'] = array(
                                "Deskcall",
                                "Create New Deskcall",
                                "form deskcall"
                              );

        $data['via'] = "dpk";
       	$data['tablelist'] = array(
			'head' => array(
				array('No.','nosort', '3%'),
				array('NOFAKT', '',''),
				array('COLLECTOR', '',''),
				array('NAMAKONS', '','1'),
				array('DUE DATE', '',''),
				array('ANGSURAN', '',''),
				array('ANGKE', '',''),
				array('NO. DPK', '',''),
				array('BAYAR', '',''),
				array('KETERANGAN', '',''),
				array('TGL BAYAR', '',''),
				array('', '',''),
			)
		);
        
        $this->load->view('backend/deskcall/new_notes', $data);

    }

    function update_kons($kdcust=null)
    {
        $alamatkons = $this->input->post('alamatkons');
        $telephone = $this->input->post('telephone');
        $telephone2 = $this->input->post('telephone2');
        $telephone3 = $this->input->post('telephone3');
        $BRIva = $this->input->post('BRIva');
        $NAMAKOF = $this->input->post('NAMAKOF');
        $ALAMATOF = $this->input->post('ALAMATOF');

        $data1 = array(
                    'alamatkons'    => $alamatkons,
                    'telephone'     => $telephone,
                    'telephone2'    => $telephone2,
                    'telephone3'    => $telephone3,
                    'BRIva'         => $BRIva
                );

        $data2 = array(
                    'NAMAKOF'    => $NAMAKOF,
                    'ALAMATOF'    => $ALAMATOF
                );

        $this->db->where('kdcust', $kdcust);
        $_result = $this->db->update('data_customer', $data1);
        if($_result){
            $this->db->where('KDCUST', $kdcust);
            $__result = $this->db->update('master', $data2);
            if($__result){

                $data = array(
                            'kdcust' => $kdcust,
                            'alamatkons' => $alamatkons,
                            'telephone' => $telephone,
                            'telephone2' => $telephone2,
                            'telephone3' => $telephone3,
                            'BRIva' => $BRIva,
                            'pekerjaan' => $NAMAKOF,
                            'alamat_pekerjaan' => $ALAMATOF,
                            'created_by' => $this->session->userdata['logged']['realname'],
                            'created_date' => date("Y-m-d H:i:s"),
                        );

                $this->db->insert('data_customer_histori', $data);
                $res = array("status" => "success", "msg" => "Data berhasil di ubah!");
            }else{
                $res = array("status" => "error", "msg" => "Gagal ubah data konsumen"); 
            }
        }else{
            $res = array("status" => "error", "msg" => "Gagal ubah data konsumen");
        }

        print(json_encode($res));
    }

    function create_deskcall() {

        $id = $this->input->post('id');
        $nofakt = $this->input->post('nofakt');
        $catatan = $this->input->post('catatan');
        $notes = $this->input->post('notes');
        $date = $this->input->post('date');
        $kdcust = $this->input->post('kdkus');
        $arr = explode('/', $date);
        $via_date = $arr[2].'-'.$arr[1].'-'.$arr[0];

        if($id){

            $result = $this->master_model->get_customer($id);
            $list = $this->master_model->get_collector_by_trx($nofakt);
            $row = $result->row();
            
            $data = array(
                    'nofakt'        => $nofakt,
                    'kdcust'        => $kdcust,
                    'namakons'      => $row->nama,
                    'hp1'          	=> $row->telephone,
                    'hp2'          	=> $row->telephone2,
                    'hp3'          	=> $row->telephone3,
                    'notes'         => $catatan,
                    'via'         	=> $notes,
                    'via_date'      => $via_date,
                    'update_by_id'  => $this->session->userdata['logged']['uid'],
                    'update_by'     => $this->session->userdata['logged']['realname'],
                    'created_date'  => date("Y-m-d H:i:s")
                );

            if($list->num_rows() > 0){
                $row = $list->row();
                $data['collector_id'] = $row->collector_id;
                $data['collector'] = $row->collector;
            }

            if($this->db->insert('deskcall', $data)){
                $this->session->set_flashdata('message_success', 'Data berhasil ditambahkan.');
                redirect( base_url() ."backend/deskcall");
            }

        }else{
            $this->session->set_flashdata('message_error', 'Data gagal ditambahkan.');
            redirect( base_url() ."backend/deskcall");
        }

    }
    
    function get_nofak_cus($idkus) {

        $q = $this->master_model->get_nofak_by_consumen($idkus);
        $json = [];
        foreach($q->result() as $arval){
            $json[] = ['id'=>$arval->nofakt, 'text'=>$arval->nofakt];
        }

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($json);

    }

}
