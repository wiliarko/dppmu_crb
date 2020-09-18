<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_to_pdf extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('html2pdf');
        $this->load->helper('ifunction_helper');
        $this->load->model(array('backend/transaksi_model','backend/laporan_model'));
    }

    public function export_kasir()
    {
        ini_set('memory_limit', '1G');
        set_time_limit(0);

        $nofakt         = $this->input->get('nofakt');
        $kdcust         = $this->input->get('kdcust');
        $collector      = $this->input->get('collector');
        $start_date     = $this->input->get('start_date');
        $end_date       = $this->input->get('end_date');
        $start_pay_date = $this->input->get('start_pay_date');
        $end_pay_date   = $this->input->get('end_pay_date');
        $status_bayar   = $this->input->get('status_bayar');
        $via            = $this->input->get('via');

        ob_start();

        $content = ob_get_clean();
        
        $isNotAdmin = ($this->session->userdata['logged']['groupid'] !== "1") ? $this->session->userdata['logged']['id'] : "";
        $params = array(
            'nofakt'        => (@$nofakt!='-') ? urldecode($nofakt) : "",
            'kdcust'        => (@$kdcust!='-') ? urldecode($kdcust) : "",
            'collector'     => (@$collector!='undefined') ? urldecode($collector) : $isNotAdmin,
            'start_date'    => (@$start_date!='-') ? urldecode($start_date) : "",
            'end_date'      => (@$end_date!='-') ? urldecode($end_date) : "",
            'start_pay_date'=> (@$start_pay_date!='-') ? urldecode($start_pay_date) : "",
            'end_pay_date'  => (@$end_pay_date!='-') ? urldecode($end_pay_date) : "",
            'status_bayar'  => (@$status_bayar!='undefined') ? urldecode($status_bayar) : "",
            'export'        => true
        );

        $content = $this->content_kasir($params, $via);

        $title = ($via=='kasir') ? "Collection Report_" : "Daftar Penyerahan Kwitansi_";
        // ob_end_clean();

        // conversion HTML => PDF
        try
        {
            $html2pdf = new HTML2PDF('L', 'A4', 'fr', true, 'UTF-8', 5);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output($title.date('Ymd').'".pdf');
            // $html2pdf->Output('Collection Report_"'.date('Ymd').'".pdf', 'D');
        }
        catch(HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    public function content_kasir($params=array(),$via='kasir')
    {
        $title = ($via=='kasir') ? "COLLECTION REPORT" : "Daftar Penyerahan Kwitansi";
        $diserahkan = ($via=='kasir') ? 'Collector' : "Admin AR";
        $diterima = ($via=='kasir') ? 'Kasir' : "Collector";

        $content = "<table style='border-bottom: 1px solid #999999; padding-bottom: 10px; width: 283mm;'>
                            <tr valign='top'>
                                <td style='width: 283mm;' valign='middle'>
                                    <div style='text-transform: uppercase; font-weight: bold; padding-top:15px; padding-bottom: 5px; font-size: 14pt;text-align: center;'>
                                        ". $title ."
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <table cellpadding='1' cellspacing='1' style='width: 280mm;'>
                            <tr bgcolor='#CCCCCC'>
                                <th style='width: 10mm; padding-top: 10px;padding-bottom: 10px;padding-left:5px; text-transform: uppercase;'>No.</th>
                                <th style='width: 30mm; padding-top: 10px;padding-bottom: 10px;padding-left:5px; text-transform: uppercase;'>NO Faktur</th>
                                <th style='width: 25mm; padding-top: 10px;padding-bottom: 10px;padding-left:5px; text-transform: uppercase;'>COLLECTOR</th>
                                <th style='width: 25mm; padding-top: 10px;padding-bottom: 10px;padding-left:5px; text-transform: uppercase;'>NAMAKONS</th>
                                <th style='width: 30mm; padding-top: 10px;padding-bottom: 10px;padding-left:5px; text-transform: uppercase;'>DUE DATE</th>
                                <th style='width: 35mm; padding-top: 10px;padding-bottom: 10px;padding-left:5px; text-transform: uppercase;'>ANGSURAN</th>
                                <th style='width: 25mm; padding-top: 10px;padding-bottom: 10px;padding-left:5px; text-transform: uppercase;'>ANGKE</th>
                                <th style='width: 25mm; padding-top: 10px;padding-bottom: 10px;padding-left:5px; text-transform: uppercase;'>TGL BAYAR</th>
                                <th style='width: 43mm; padding-top: 10px;padding-bottom: 10px;padding-left:5px; text-transform: uppercase;'>KETERANGAN</th>
                            </tr>";

            $result = $this->transaksi_model->get_dpk($params, false, false);
            $i=1; $numItems = count($result->result_array());

            if($numItems > 1000){
                echo "<script>alert('Data terlalu banyak, PDF berat untuk menampilkan data!');</script>";
                exit;
            }

            foreach($result->result_array() as $row)
            {
                $angsuran = ($i!=$numItems) ? number_format($row['angsuran']) : '<b>'. number_format($row['angsuran']) .'</b>';
                $no = ($i!=$numItems) ? $i : '';
                $created_date = (in_array($row['kode_bayar'], array('99', '00'))) ? $row['created_date'] : "";

                $content.="<tr bgcolor='#FFFFFF'>
                            <td style='border-bottom: 1px solid #999999; padding-top: 6px;padding-bottom: 6px;padding-left:5px;' align='center'>".$no."</td>
                            <td style='border-bottom: 1px solid #999999; padding-top: 6px;padding-bottom: 6px;padding-left:5px;'>".$row['nofakt']."</td>
                            <td style='border-bottom: 1px solid #999999; padding-top: 6px;padding-bottom: 6px;padding-left:5px;'>".wordwrap($row['collector'], 18, "<br>", TRUE)."</td>
                            <td style='border-bottom: 1px solid #999999; padding-top: 6px;padding-bottom: 6px;padding-left:5px;'>".wordwrap($row['namakons'], 15, "<br>", TRUE)."</td>
                            <td style='border-bottom: 1px solid #999999; padding-top: 6px;padding-bottom: 6px;padding-left:5px;' align='center'>".$row['due_date']."</td>
                            <td style='border-bottom: 1px solid #999999; padding-top: 6px;padding-bottom: 6px;padding-left:5px;' align='right'>".$angsuran."</td>
                            <td style='border-bottom: 1px solid #999999; padding-top: 6px;padding-bottom: 6px;padding-left:5px;' align='center'>".$row['angke']."</td>
                            <td style='border-bottom: 1px solid #999999; padding-top: 6px;padding-bottom: 6px;padding-left:5px;' align='center'>".wordwrap($created_date, 15, "<br>", TRUE)."</td>
                            <td style='border-bottom: 1px solid #999999; padding-top: 6px;padding-bottom: 6px;padding-left:5px;'>".$row['keterangan']."</td>
                        </tr>"; 
                $i++;
            }                         
            $content.="</table><br><br><br><br><br>";

            $content .= "<table style='padding-bottom: 10px; width: 280mm;'>
                            <tr valign='top'>
                                <td style='width: 60mm;' valign='middle'>
                                    <div style='padding-top:15px; padding-bottom: 5px; font-size: 12pt;text-align: center;'>
                                        Diserahkan Oleh,
                                    </div>
                                </td>
                                <td style='width: 150mm;' valign='middle'>&nbsp;</td>
                                <td style='width: 60mm;' valign='middle'>
                                    <div style='padding-top:15px; padding-bottom: 5px; font-size: 12pt;text-align: center;'>
                                        Diterima Oleh,
                                    </div>
                                </td>
                            </tr>
                            <tr valign='top'>
                                <td style='width: 60mm;' valign='middle'>&nbsp;</td>
                                <td style='width: 150mm;' valign='middle'>&nbsp;</td>
                                <td style='width: 60mm;' valign='middle'>&nbsp;</td>
                            </tr>
                            <tr valign='top'>
                                <td style='width: 60mm;' valign='middle'>&nbsp;</td>
                                <td style='width: 150mm;' valign='middle'>&nbsp;</td>
                                <td style='width: 60mm;' valign='middle'>&nbsp;</td>
                            </tr>
                            <tr valign='top'>
                                <td style='width: 60mm;' valign='middle'>&nbsp;</td>
                                <td style='width: 150mm;' valign='middle'>&nbsp;</td>
                                <td style='width: 60mm;' valign='middle'>&nbsp;</td>
                            </tr>
                            <tr valign='top'>
                                <td style='width: 50mm;' valign='middle'>
                                    <div style='padding-top:15px; padding-bottom: 5px; font-size: 12pt;text-align: center;'>
                                        ".$diserahkan."
                                    </div>
                                </td>
                                <td style='width: 150mm;' valign='middle'>&nbsp;</td>
                                <td style='width: 60mm;' valign='middle'>
                                    <div style='padding-top:15px; padding-bottom: 5px; font-size: 12pt;text-align: center;'>
                                        ".$diterima."
                                    </div>
                                </td>
                            </tr>
                        </table>";

        return $content;
    }

    function export_piutang($page=1,$nofakt=0,$range1=null,$range2=null)
    {
        ob_start();

        $data['page'] = $page;
        $data['result'] =  $this->laporan_model->get_cetak_piutang($nofakt, $page);
        $data['total_pinjaman'] =  $this->laporan_model->get_total_pinjaman($nofakt);
        $data['range1'] =  $range1;
        $data['range2'] =  $range2;
        $data['nofakt'] =  $nofakt;
        $this->load->view('backend/laporan/laporan_piutang', $data);

        $content = ob_get_clean();

        try
        {
            $width_in_mm = 10.82677 * 25.4; 
            $height_in_mm = 9.645669 * 25.4;
            $html2pdf = new HTML2PDF('P', array($width_in_mm,$height_in_mm), 'en', true, 'UTF-8', array(0, 0, 0, 0));
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('123.pdf');
        }
        catch(HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

}