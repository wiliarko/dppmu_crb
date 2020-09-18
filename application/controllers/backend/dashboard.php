<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation', 'session'));
		$this->load->library(array('pagination','authentication'));
		$this->load->model(array('backend/dashboard_model','backend/transaksi_model'));
		
		$this->url_modul = base_url().'backend/dashboard/';
		$this->page = 'Dashboard';
    }

    public function index() {

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

		$this->load->view('backend/dashboard', $data);

    }

    function get_jml_jb() {

    	$result = $this->dashboard_model->get_jml_jb();
    	echo $result->num_rows();

    }

    function get_jml_bayar() {

    	$result = $this->dashboard_model->get_jml_bayar();
    	echo $result->num_rows();

    }

    function get_jml_blm_bayar() {

    	$result = $this->dashboard_model->get_jml_blm_bayar();
    	echo $result->num_rows();

    }
    

    function get_revenue() {

        $result = $this->dashboard_model->get_revenue();
        $revenue = $result->row()->revenue;
        echo $revenue;

    }

    function get_status_bayar() {
        $collector = $this->input->post('collector');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $status_bayar = $this->input->post('status_bayar');
        
        $result = $this->dashboard_model->get_status_bayar($collector,$bulan,$tahun,$status_bayar);
        // $status = $result->result_array();
        $jml = $result->num_rows();
        // echo json_encode($status);
        echo $jml;
    }

    function kasir_event() {

        $rec = $this->dashboard_model->get_kasir_event();
        if($rec->num_rows() > 0){
            $i=1;
            foreach ($rec->result() as $row) {
                $arr[]  = array(
                    'id'            => $i,
                    'nofakt'        => $row->nofakt,
                    'namakons'      => $row->namakons,
                    'collector'     => $row->collector,
                    'angsuran'      => $row->angsuran,
                    'angke'         => $row->angke,
                    'kasir'         => $row->kasir,
                    'created_date'  => $row->created_date
                );
                $i++;
            }
        }else{
            $arr[]  = array(
                    'id'    => '',
                    'nofakt'    => '',
                    'namakons'    => '',
                    'collector'    => '',
                    'angsuran'    => '',
                    'angke'    => '',
                    'created_date' => ''
                );
        }
        
        echo json_encode($arr);

    }
}
