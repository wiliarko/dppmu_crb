<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Insertcsv extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
    	ini_set('memory_limit', '1G');
    	
        $this->load->library('spreadsheet_excel_reader');
 
        $tmp_file = $_FILES['epfile']['tmp_name'];
        $source = new Spreadsheet_Excel_Reader($tmp_file);
        $sheetName = $source->boundsheets[0]['name'];
        $sheet = $source->getSheetIndex($sheetName);
        $rowcount = $source->rowcount($sheet);
        $colcount = $source->colcount($sheet);
        $update_by = $this->session->userdata['logged']['realname'];

        $upload = $this->_uploads();
        $filemaster = $upload['filename'];

        if($rowcount > 2000) :
            $message = "Batas limit rows tidak mencukupi, maksimal rows yang ditersedia yaitu 2000 rows!";
           	echo $message;
            exit();
        endif;

        $uniqCode = $source->val(1, 'a');
    	if($uniqCode !== 'ZGV2LmlzbWlhZGk='):
    		$message = "Anda tidak menggunakan template excel yang sudah di sediakan, harap download terlebih dahulu!";
    		echo $message;
            exit();
    	endif;

        $response = "";
        $msg = "";
        for ($a = 3; $a <= $rowcount; $a++) {

            //*** CREATE TABLE ***//
            /*$MySQL  = 'CREATE TABLE `master` (';
            $MySQL .='<br>';
            $MySQL .='`ID` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,';
            $MySQL .='<br>';

            for ($i = 'a', $j = 0; $i < 'zz', $j < $colcount; $i++, $j++) {
                $key = str_replace(' ', '_', $source->val($a, $i));
                $MySQL .= "`". $key ."` VARCHAR(50) NOT NULL,";
                $MySQL .='<br>';
            }

            $MySQL .= ")";
        
            echo $MySQL;
            exit;*/
            //*** END CREATE TABLE ***//

            //**** UPLOAD BIASA ****//
            $data = array();

            for ($i = 'a', $j = 0; $i < 'zz', $j < $colcount; $i++, $j++) {
                
                $key = $source->val(2, $i);
                $key = str_replace(' ', '_', $key);
                $val = $source->val($a, $i);
                if($val <> ''):

                    $val = preg_replace('/[-?]/', '', utf8_decode($val));
                    
                    if($key == "GP" && $key == "RATIO"):
                        $val = preg_match("/^[0-9.]+$/i", $val);
                    endif;

                    $data[$key] = trim($val);

                endif;

            }

            $NOFAKT = !empty($data['NOFAKT']) ? $data['NOFAKT'] : "";
            $KDCUST = !empty($data['KDCUST']) ? $data['KDCUST'] : "";
            $COLLECTOR = !empty($data['COLLECTOR']) ? $data['COLLECTOR'] : "";
            $ADMIN = !empty($data['ADMIN']) ? $data['ADMIN'] : "";
            $NOKTP = !empty($data['NOKTP']) ? $data['NOKTP'] : "";
            $NAMAKONS = !empty($data['NAMAKONS']) ? $data['NAMAKONS'] : "";
            $ALAMATKONS = !empty($data['ALAMATKONS']) ? $data['ALAMATKONS'] : "";
            $TELEPHONE = !empty($data['TELEPHONE']) ? $data['TELEPHONE'] : 0;
            $NAMAKOF = !empty($data['NAMAKOF']) ? $data['NAMAKOF'] : "";
            $ALAMATOF = !empty($data['ALAMATOF']) ? $data['ALAMATOF'] : "";
            $SPA = !empty($data['SPA']) ? $data['SPA'] : 0;
            $SLS = !empty($data['SLS']) ? $data['SLS'] : "";
            $SALES = !empty($data['SALES']) ? $data['SALES'] : "";
            $SURVE = !empty($data['SURVE']) ? $data['SURVE'] : "";
            $SURVEYOR = !empty($data['SURVEYOR']) ? $data['SURVEYOR'] : "";
            
            if(!empty($NOFAKT)):
	            //** jika ada no_faktur yang sama maka data tidak bisa masuk
	            $sql1 = "SELECT * FROM `master` WHERE `NOFAKT` = '$NOFAKT'";
	            $check1 = $this->db->query($sql1)->num_rows();
	            
	            if($check1 > 0 && $NOFAKT !== "") {

	                $msg .= '<p> <b>(1) NOFAKT</b> : '. $NOFAKT .' </p>';

	            }else{

	                //** jika no_kostumer sama tapi no_faktur berbeda maka data dapat diterima
	                //** jika no_kostumer dan no_faktur sama maka data tidak bisa masuk
	                $sql2 = "SELECT * FROM `master` WHERE `KDCUST` = '$KDCUST' AND `NOFAKT` = '$NOFAKT'";
	                $check2 = $this->db->query($sql2)->num_rows();

	                if($check2 > 0) {

	                    //** INFO ERROR TO ADMIN UPLOADED FILES **//
	                    if(empty($NOFAKT)){
	                        $id =  $NOFAKT;
	                        $info = "KDCUST";
	                    }else{
	                        $id = $NOFAKT;
	                        $info = "NOFAKT";
	                    }

	                    $msg .= '<p> <b>(2.3) '. $info .'</b> : '. $id.' </p>';
	                    //** END **//

	                }else{

	                    $id_collector='';
	                    $id_admin='';

	                    //** CHECK & INSERT COLLECTOR
	                    if(!empty($COLLECTOR)):
	                        /*$sql3 = "SELECT * FROM `data_collector` WHERE `nama` = '".addslashes($COLLECTOR)."'";
	                        $check3 = $this->db->query($sql3);*/
                            $this->db->select('*');
                            $this->db->from('data_collector');
                            $this->db->where('nama', htmlentities($COLLECTOR));
                            $check3 = $this->db->get();
	                        if(!$check3->num_rows()) {

	                            $dataColl = array(
	                                            'nama'      => htmlentities($COLLECTOR),
	                                            'update_at' => date('Y-m-d H:i:s')
	                                        );

	                            $this->db->insert('data_collector', $dataColl);
	                            $id_collector = $this->db->insert_id();

	                        }else{
	                            $row = $check3->row();
	                            $id_collector = $row->id;
	                        }
	                    endif;

	                    //** CHECK & INSERT ADMIN
	                    if(!empty($ADMIN)):
	                        /*$sql4 = "SELECT * FROM `data_admin` WHERE `nama` = '".addslashes($ADMIN)."'";
	                        $check4 = $this->db->query($sql4);*/
                            $this->db->select('*');
                            $this->db->from('data_admin');
                            $this->db->where('nama', htmlentities($ADMIN));
                            $check4 = $this->db->get();
	                        if(!$check4->num_rows()) {

	                            $dataAdm = array(
	                                            'nama'      => htmlentities($ADMIN),
	                                            'update_at' => date('Y-m-d H:i:s')
	                                        );

	                            $this->db->insert('data_admin', $dataAdm);
	                            $id_admin = $this->db->insert_id();

	                        }else{
	                            $row = $check4->row();
	                            $id_admin = $row->id;
	                        }
	                    endif;

	                    //** CHECK & INSERT CUSTOMER
	                    if(!empty($KDCUST)):
	                        /*$sql5 = "SELECT * FROM `data_customer` WHERE `kdcust` = '$KDCUST' AND `nama` = '".addslashes($NAMAKONS)."'";
	                        $check5 = $this->db->query($sql5)->num_rows();*/
                            $this->db->select('*');
                            $this->db->from('data_customer');
                            $this->db->where('kdcust', $KDCUST);
                            $this->db->where('nama', htmlentities($NAMAKONS));
                            $check5 = $this->db->get();
	                        if(!$check5->num_rows()) {

	                            $dataCust = array(
	                                            'kdcust'            => $KDCUST,
	                                            'nik'               => $NOKTP,
	                                            'nama'              => htmlentities($NAMAKONS),
	                                            'alamatkons'        => htmlentities($ALAMATKONS),
	                                            'telephone'         => $TELEPHONE,
	                                            'pekerjaan'         => htmlentities($NAMAKOF),
	                                            'alamat_pekerjaan'  => htmlentities($ALAMATOF),
	                                            'spa'               => $SPA,
	                                            'id_collector'      => $id_collector,
	                                            'id_admin'          => $id_admin,
	                                            'update_at'         => date("Y-m-d H:i:s")
	                                        );

	                            $this->db->insert('data_customer', $dataCust);

	                        }
	                    endif;

	                    //** CHECK & INSERT SALES
	                    if(!empty($SLS)):
	                        /*$sql6 = "SELECT * FROM `data_sales` WHERE `sls` = '$SLS' AND `nama` = '".addslashes($SALES)."'";
	                        $check6 = $this->db->query($sql6)->num_rows();*/
                            $this->db->select('*');
                            $this->db->from('data_sales');
                            $this->db->where('sls', $SLS);
                            $this->db->where('nama', htmlentities($SALES));
                            $check6 = $this->db->get();
	                        if(!$check6->num_rows()) {

	                            $dataSls = array(
	                                            'sls'           => $SLS,
	                                            'nama'          => htmlentities($SALES),
	                                            'update_at'     => date("Y-m-d H:i:s")
	                                        );

	                            $this->db->insert('data_sales', $dataSls);

	                        }
	                    endif;

	                    //** CHECK & INSERT SURVEYOR
	                    if(!empty($SURVE)):
	                        /*$sql7 = "SELECT * FROM `data_surveyor` WHERE `surve` = '$SURVE' AND `nama` = '".addslashes($SURVEYOR)."'";
	                        $check7 = $this->db->query($sql7)->num_rows();*/
                            $this->db->select('*');
                            $this->db->from('data_surveyor');
                            $this->db->where('surve', $SURVE);
                            $this->db->where('nama', htmlentities($SURVEYOR));
                            $check7 = $this->db->get();
	                        if(!$check7->num_rows()) {

	                            $dataSurve = array(
	                                            'surve'         => $SURVE,
	                                            'nama'          => htmlentities($SURVEYOR),
	                                            'update_at'     => date("Y-m-d H:i:s")
	                                        );

	                            $this->db->insert('data_surveyor', $dataSurve);

	                        }
	                    endif;

	                    // MASUKAN DATA YANG SUDAH DIVALIDASI
	                    $data['UPDATE_AT'] = date("Y-m-d H:i:s");
	                    $data['UPDATE_BY'] = $update_by;
	                    $data['FILEMASTER'] = @$filemaster;
	                    $this->db->insert('master', $data);

	                }
	            }
	            //**** END UPLOAD BIASA ****//
        	endif;
        
        	$this->db->close();
        }

        if($tmp_file){
        	if($msg <> ""){
        		$status = "error";
	            $response .= "<font style='color:red;font-size: 15pt;'>Terjadi kesalahan.!</font>";
        	}else{
        		$status = "success";
	        	$response .= "<font style='color:red;font-size: 15pt;'>Data Berhasil disimpan!</font>";
        	}
        }else{
        	$status = "error";
            $response .= "<font style='color:red;font-size: 15pt;'>File Excel Harap diisi!</font>";
        }
        
        if($msg <> ""){
            $response .= "<h4>No Faktur Berikut tidak masuk kedalam sistem karena formula validasi!</h4>";
            $response .= "<i>NOTE : <br>";
            $response .= "<b>(1)</b> jika ada no_faktur yang sama maka data tidak bisa masuk<br>";
            $response .= "<b>(2.3)</b> jika no_kostumer sama tapi no_faktur berbeda maka data dapat diterima <b> DAN </b> jika no_kostumer dan no_faktur sama maka data tidak bisa masuk</i> <br><hr>";
            $response .= $msg;
        }

        $res = array("status" => $status, "msg" => $response);

        print(json_encode($res));
    }

    function _uploads() {
        $config['upload_path'] = './uploads';
        $config['allowed_types'] = 'xls';
        // $config['max_size'] = 1024 * 8;
        $config['encrypt_name'] = TRUE;
        
        $this->load->library('upload', $config);

        foreach ($_FILES as $key => $value) {
            if (!empty($value['tmp_name'])) {
                if (!$this->upload->do_upload($key)) {
                    $error = array('error' => $this->upload->display_errors());
                } else {
                    //success
                    $data = $this->upload->data();
                    $file_name = $data['file_name'];
                }
            }
        }
        return array('filename' => $file_name);
    }

    public function create_no_bukti($limit=500)
    {

        $sql = $this->db->query("SELECT * FROM `transaksi` WHERE `no_bukti` IS NULL AND `kode_bayar`='99' OR `no_bukti` = '' AND `kode_bayar`='99' LIMIT $limit");
        if($sql->num_rows() > 0)
        {
            foreach($sql->result() as $row){
                $id = $row->id;
                $nofakt = $row->nofakt;

                // Input No. Bukti
                $seql = $this->db->query("SELECT `seq` FROM `transaksi` ORDER BY `seq` DESC LIMIT 1")->row();
                $seq = $seql->seq+1;
                $year = date('y');
                $nofak_str = preg_replace('/\d/', '', $row->nofakt);
                $seqs = str_pad($seq, 10, '0', STR_PAD_LEFT);
                $no_bukti =  $nofak_str.$year.$seqs;

                $this->db->query("UPDATE `transaksi` SET `seq`='$seq', `no_bukti`='$no_bukti' WHERE `id`='$id'");

                echo $id.' : <b>'.$nofakt. '</b> (ANGKE-'.$row->angke.', '.$no_bukti .') <br/>';
            }
        }else{

            echo 'NO DATA';
            exit;

        }
    }
}
