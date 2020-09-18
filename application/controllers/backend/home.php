<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation', 'session'));
		$this->load->library(array('pagination','authentication'));
		$this->load->library('grocery_CRUD');
		$this->load->model(array('backend/master_model', 'backend/transaksi_model'));
		
		$this->url_modul = base_url().'backend/home/';
		$this->page = 'Home';
    }

    public function index() {

    	$this->authentication->restrict();

		$data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'Upload Excel',
			'url_module' => $this->url_modul,
		);

        $data['sync'] = $this->db->query("SELECT * FROM `master` WHERE `sync`='0'")->num_rows();
		$data['page_header'] = array(
								"Add Bulk Data",
								"Master Data",
								"Upload Excel"
							  );
		
		$this->load->view('backend/home/upload_excel', $data);

    }

    //** DATA KONSUMEN
    public function data_konsumen() {

    	$this->authentication->restrict();

    	$crud = new grocery_CRUD();
        
        $crud->set_table('data_customer');
		$crud->unset_columns(array('id_collector', 'id_admin'));
        $crud->unset_edit_fields('id_collector', 'id_admin');
        $crud->set_subject('Customer');
        
        $crud->field_type('nama','readonly');
        $crud->field_type('kdcust','readonly');
        
        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_jquery();

        $data['output'] = $crud->render();

        $data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'Data Konsumen',
			'url_module' => $this->url_modul,
		);

		$data['page_header'] = array(
								"Data Customer",
								"Master Data",
								"customer list"
							  );

        $this->load->view('backend/home/data_konsumen', $data);

    }

    //** DATA SALES
    public function data_sales() {

    	$this->authentication->restrict();

    	$crud = new grocery_CRUD();
        
        $crud->set_table('data_sales');

        $crud->field_type('sls','readonly');

        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_jquery();

        $data['output'] = $crud->render();

        $data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'Data Sales',
			'url_module' => $this->url_modul,
		);

		$data['page_header'] = array(
								"Data Sales",
								"Master Data",
								"sales list"
							  );

        $this->load->view('backend/home/data_sales', $data);

    }

    //** DATA SURVEYOR
    public function data_surveyor() {

    	$this->authentication->restrict();

    	$crud = new grocery_CRUD();
        
        $crud->set_table('data_surveyor');
        
        $crud->field_type('surve','readonly');

        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_jquery();

        $data['output'] = $crud->render();

        $data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'Data Surveyor',
			'url_module' => $this->url_modul,
		);

		$data['page_header'] = array(
								"Data Surveyor",
								"Master Data",
								"surveyor list"
							  );

        $this->load->view('backend/home/data_surveyor', $data);

    }

    //** DATA COLLECTOR
    public function data_collector() {

        $selrec = $this->master_model->get_list_collector();

        $data['pageInfo'] = array(
            'page' => $this->page,
            'subpage' => 'Data Collector',
            'url_module' => $this->url_modul,
        );

        $data['tablelist'] = array(
            'head' => array(
                //Caption, sort, width
                array('No.','nosort', '3%'),
                array('Nama Collector', '',''),
                array('Username', '',''),
                array('User Group', '',''),
                array('Status', '',''),
                array('Created', '','15%'),
                array('Updated', '','15%'),
                array('&nbsp;', '','8%'),
            ),
            'row' => $selrec
        );

        $data['page_header'] = array(
                                "Data Collector",
                                "Master Data",
                                "collector list"
                              );

        $this->load->view('backend/home/data_collector', $data);

    }

    //** EDIT COLLECTOR
    public function edit_collector($id) {
        $data['pageInfo'] = array(
            'page' => $this->page,
            'subpage' => 'Data Collector',
            'url_module' => $this->url_modul,
        );
        
        $data['id'] = $id;
        $data['result'] = $this->master_model->get_list_collector($id);
        
        $data['user_group'] = $this->master_model->get_usergroup(2);
        // $data['canvasser'] = $this->users_model->get_canvasser();
        $data['page_header'] = array(
                                "Data Collector",
                                "Master Data",
                                "collector list"
                              );
        
        $this->load->view('backend/home/edit_collector', $data);
    }

    //** UPDATE COLLECTOR
    public function update_collector() {
        $id = $this->input->post("id");
        $username = $this->input->post("username");
        $nama = $this->input->post("nama");

        $show = $this->master_model->check_username($username, $id);
        if($show->num_rows() > 0){
            $this->session->set_flashdata('message_error', 'Data a.n <b>'. $nama .'</b> gagal, karena username sudah terdaftar!');
            redirect( base_url() ."backend/home/data_collector");
            die();
        }

        if($id){

            $check = $this->master_model->check_users($id);
            if($check->num_rows() > 0){
               
                $data = array(
                    'username'  => $username,
                    'first_name'=> $nama,
                    'status'    => $this->input->post("status"),
                    'marketing_id'=> $this->input->post("id"),
                    'group_id'  => $this->input->post("group"),
                    'updated'   => date("Y-m-d H:i:s"),
                );
                if($this->input->post("password") != ''){
                    $data['passwd'] = MD5($this->input->post("password"));
                    $data['pass_hint'] = $this->input->post("password");
                }
            
                if($this->master_model->update_collector($data, $id)){
                    $this->session->set_flashdata('message_success', 'Data berhasil diubah.');
                    redirect( base_url() ."backend/home/data_collector");
                }

            }else{
                
                $dataApps = array(
                        'username'      => $this->input->post("username"),
                        'passwd'        => MD5($this->input->post("password")),
                        'pass_hint'     => $this->input->post("password"),
                        'first_name'    => $this->input->post("nama"),
                        'status'        => $this->input->post("status"),
                        'marketing_id'  => $this->input->post("id"),
                        'group_id'      => $this->input->post("group"),
                        'created'       => date("Y-m-d H:i:s"),
                        'updated'       => date("Y-m-d H:i:s"),
                    );
                if($this->db->insert('ws_users', $dataApps)){
                    $this->session->set_flashdata('message_success', 'Data berhasil ditambahkan.');
                    redirect( base_url() ."backend/home/data_collector");
                }

            }
        }
    }

    //** DATA ADMIN
    public function data_admin() {

    	$this->authentication->restrict();

    	$crud = new grocery_CRUD();
        
        $crud->set_table('data_admin');
		$crud->set_primary_key('nama');
        
        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->unset_jquery();

        $data['output'] = $crud->render();

        $data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'Data Admin',
			'url_module' => $this->url_modul,
		);

		$data['page_header'] = array(
								"Data Admin",
								"Master Data",
								"admin list"
							  );

        $this->load->view('backend/home/data_admin', $data);

    }

    //** MUTASI COLLECTOR
    public function mutasi_collector() {

        $data['pageInfo'] = array(
            'page' => $this->page,
            'subpage' => "Mutasi Collector",
            'url_module' => $this->url_modul,
        );

        $data['tablelist'] = array(
            'head' => array(
                //Caption, sort, width
                array('No.','', '5%'),
                array('No Faktur', '','7%'),
                array('Konsumen', '','15%'),
                array('Collector', '','10%'),
                array('<center>Angsuran</center>', '','10%'),
                array('<center>Sisa Tagihan (Tenor)</center>', '','10%'),
                array('<center>Total Sisa Tagihan (Rp)</center>', '','10%'),
                array('aksi', '','10%'),
            )
        );

        $data['page_header'] = array(
                                "DPP-MU",
                                "Mutasi",
                                "mutasi collector"
                              );
        
        $this->load->view('backend/home/mutasi_collector', $data); 
    }

    public function mutasi_collector_tbl()
    {
    	$aColumns = array( 'id', 'nofakt', 'namakons', 'collector', 'angsuran', 'tenor', 'kode_bayar', 'collector_id');

    	$sIndexColumn = "id";
	
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".$_GET['iDisplayStart'].", ".
				$_GET['iDisplayLength'];
		}
		$numbering = ( $_GET['iDisplayStart'] );
        $page = $numbering + 1;
		
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
					 	". $_GET['sSortDir_'.$i] .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		
		$sWhere = "";
		if ( $_GET['sSearch'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
			}
		}		

        $rResult = $this->master_model->get_nofak_by_collector_mutasi($aColumns, $sWhere, $sLimit);
        $iFilteredTotal = !empty($sLimit) ? $_GET['iDisplayLength'] : 10;
        
        $rResultTotal = $this->master_model->get_nofak_by_collector_mutasi($aColumns, $sWhere);
        $iTotal = $rResultTotal->num_rows();
        $iFilteredTotal = $iTotal;
		
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);

    	foreach ($rResult->result() as $arval) {

    		$qSisa = $this->master_model->get_sisa_tagihan($arval->nofakt);
            $qSisaRp = $this->master_model->get_sisa_tagihan($arval->nofakt, 1);
            $qCollector = $this->master_model->get_collector_by_trx($arval->nofakt);
            $list = $qSisaRp->row();
            
            if($qCollector->num_rows() > 0){
            	$qC = $qCollector->row();
            	$collector = !empty($qC->collector) ? $qC->collector : "-";
            	$collector_id = $qC->collector_id;
            }else{
            	$collector_id = $arval->collector_id;
            	$collector = $arval->collector;
            }

            $btn = '<a class="btn btn-white btn-bitbucket" data-toggle="tooltip" data-placement="left" title="Formulir mutasi collector" onclick="viewForm(\''.$arval->nofakt.'\', \''.$collector_id.'\', \''.$collector.'\')">
                            <i class="fa fa-retweet"></i>
                        </a>
                        <a href="'. base_url() .'backend/home/mutasi_histori/'.$arval->nofakt.'" class="btn btn-white btn-bitbucket" data-toggle="tooltip" data-placement="right" title="Melihat histori mutasi">
                            <i class="fa fa-history"></i>
                        </a>';

            $row = array(
	            		'<center>'.$page.'</center>',
	            		$arval->nofakt,
	            		$arval->namakons,
	            		$collector,
	            		number_format($arval->angsuran),
	            		'<b>'.$qSisa->num_rows().'</b> ('.$arval->tenor.')',number_format($list->sisa),
	            		$btn
            		);

            $page++;
			$output['aaData'][] = $row;
        }

        echo json_encode( $output );
    }

    //** MUTASI PROCESS
    public function mutasi_process() {
        $nofakt             = $this->input->post('nofak');
        $collector_id_old   = $this->input->post('collector_id_old');
        $collector_id_new   = $this->input->post('collector_id_new');

        $q = $this->transaksi_model->get_collector($collector_id_new, 1);
        $r = $q->row();

        $data = array(
                    'collector_id'  => $collector_id_new,
                    'collector'     => $r->nama
                );
        
        $result = $this->master_model->mutasi_process($data, $collector_id_old, $nofakt);
        if($result){
            $params = array(
                        'nofakt'            => $nofakt,
                        'collector_id_old'  => $collector_id_old,
                        'collector_id_new'  => $collector_id_new,
                        'update_by_id'      => $this->session->userdata['logged']['uid']
                    );

            $this->master_model->mutasi_process_log($params);

            $res = array("status" => "success");
        }else{
            $res = array("status" => "error", "msg" => "data gagal disimpan");
        }

        print(json_encode($res));
    }

    //** MUTASI HISTORI
    function mutasi_histori($nofak) {

    	$this->authentication->restrict();

    	$crud = new grocery_CRUD();
        
        $crud->set_table('mutasi_log');

        $crud->display_as('collector_id_new','Collector Pengganti');
        $crud->display_as('collector_id_old','Collector Sebelumnya');
        $crud->display_as('update_by_id','Penganggung Jawab');

        $crud->set_relation('collector_id_new','data_collector','nama');
        $crud->set_relation('collector_id_old','data_collector','nama');
        $crud->set_relation('update_by_id','ws_users','first_name');

		// $crud->set_primary_key('nama');
        
        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_read();
        $crud->unset_delete();
        $crud->unset_jquery();

        $data['output'] = $crud->render();

        $data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'Mutasi Collector',
			'url_module' => $this->url_modul,
		);

		$data['page_header'] = array(
								"Mutasi Histori",
								"Master Data",
								"histori list"
							  );

        $this->load->view('backend/home/mutasi_histori', $data);

    }

    //** DATA USERS HAK AKSES
    public function users() {

        $selrec = $this->master_model->get_list_users();

        $data['pageInfo'] = array(
            'page' => $this->page,
            'subpage' => 'Users',
            'url_module' => $this->url_modul,
        );

        $data['tablelist'] = array(
            'head' => array(
                //Caption, sort, width
                array('No.','nosort', '3%'),
                array('Nama', '',''),
                array('Username', '',''),
                array('User Group', '',''),
                array('Status', '',''),
                array('Created', '','15%'),
                array('Updated', '','15%'),
                array('&nbsp;', '','8%'),
            ),
            'row' => $selrec
        );

        $data['page_header'] = array(
                                "Users",
                                "Master Data",
                                "user list"
                              );

        $this->load->view('backend/home/users', $data);

    }

    //** ADD USERS
    public function add_users() {
        $data['pageInfo'] = array(
            'page' => $this->page,
            'subpage' => 'Users',
            'url_module' => $this->url_modul,
        );
        
        
        $data['user_group'] = $this->master_model->get_usergroup();
        $data['formType'] = 'insert';
        $data['page_header'] = array(
                                "Users",
                                "Master Data",
                                "users add"
                              );
        
        $this->load->view('backend/home/users_form', $data);
    }

    //** EDIT COLLECTOR
    public function edit_users($id) {
        $data['pageInfo'] = array(
            'page' => $this->page,
            'subpage' => 'Users',
            'url_module' => $this->url_modul,
        );
        
        $data['id'] = $id;
        $data['result'] = $this->master_model->get_list_users($id);
        $data['user_group'] = $this->master_model->get_usergroup();
        $data['formType'] = 'update';
        $data['page_header'] = array(
                                "Users",
                                "Master Data",
                                "user edit"
                              );
        
        $this->load->view('backend/home/users_form', $data);
    }

    //** INSERT USERS
    public function insert_users() {
        $username = $this->input->post("username");
        $nama = $this->input->post("nama");

        $show = $this->master_model->check_username($username);
        if($show->num_rows() > 0){
            $this->session->set_flashdata('message_error', 'Username <b>'. $username .'</b> sudah terdaftar!');
            redirect( base_url() ."backend/home/users_form");
            die();
        }

        $dataApps = array(
                'username'      => $this->input->post("username"),
                'passwd'        => MD5($this->input->post("password")),
                'pass_hint'     => $this->input->post("password"),
                'first_name'    => $this->input->post("nama"),
                'status'        => $this->input->post("status"),
                'group_id'      => $this->input->post("group"),
                'created'       => date("Y-m-d H:i:s")
            );
        if($this->db->insert('ws_users', $dataApps)){
            $this->session->set_flashdata('message_success', 'Data berhasil ditambahkan.');
            redirect( base_url() ."backend/home/users");
        }
    }

    //** UPDATE COLLECTOR
    public function update_users() {
        $id = $this->input->post("id");
        $username = $this->input->post("username");
        $nama = $this->input->post("nama");

        $show = $this->master_model->check_username($username, $id);
        if($show->num_rows() > 0){
            $this->session->set_flashdata('message_error', 'Username <b>'. $username .'</b> sudah terdaftar!');
            redirect( base_url() ."backend/home/edit_users/".$id);
            die();
        }

        if($id){

            $data = array(
                'username'  => $username,
                'first_name'=> $nama,
                'status'    => $this->input->post("status"),
                'group_id'  => $this->input->post("group"),
                'updated'   => date("Y-m-d H:i:s"),
            );
            if($this->input->post("password") != ''){
                $data['passwd'] = MD5($this->input->post("password"));
                $data['pass_hint'] = $this->input->post("password");
            }
        
            if($this->master_model->update_users($data, $id)){
                $this->session->set_flashdata('message_success', 'Data berhasil diubah.');
                redirect( base_url() ."backend/home/users");
            }

        }else{
                
                $this->session->set_flashdata('message_error', 'User gagal diubah!');
                redirect( base_url() ."backend/home/users");
                die();

            }
    }

    //** MASTER EXCEL
    function master_excel() {

    	$this->authentication->restrict();

    	$crud = new grocery_CRUD();
        
        $crud->set_table('master');

        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->unset_jquery();

        $crud->unset_columns(array('FILEMASTER'));

        $data['output'] = $crud->render();

        $data['pageInfo'] = array(
			'page' => $this->page,
			'subpage' => 'Master Excel',
			'url_module' => $this->url_modul,
		);

		$data['page_header'] = array(
								"Master Excel",
								"Master Data",
								"list record master data"
							  );

        $this->load->view('backend/home/data_master', $data);

    }

    function get_collector($id_collector) {
        $q = $this->transaksi_model->get_collector($id_collector);
        $json = [];
        foreach($q->result() as $arval){
            $json[] = ['id'=>$arval->id, 'text'=>$arval->nama];
        }

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($json);
    }
    
}
