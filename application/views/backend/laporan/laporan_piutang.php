<style type="text/css">
 .page1{
    /*margin-top: 110mm;margin-left: -12.5px;*/
    margin-top: 92mm;margin-left: -12.5px;
 }
 .page1_column1{
    width:41px;height: 5.8mm;
 }
 .page1_column2{
    width:41.57480315px;font-size:7.4pt;vertical-align: middle;
 }
 .page1_column3{
    width:75.590551181px;padding-left:7.559055118;font-size:6pt;vertical-align: middle;
 }
 .page1_column4{
    width:83.149606299px;font-size:7pt;vertical-align: middle;
 }
 .page1_column5{
    width:18.897637795px;padding-left:15.118110236;font-size:7.9pt;vertical-align: middle;
 }
 .page1_column6{
    width:18.897637795px;padding-left:15.118110236;font-size:7.9pt;vertical-align: middle;
 }
 .page1_column7{
    width:85.826771654px;padding-left:15.118110236;font-size:7.9pt;text-align: right;vertical-align: middle;
 }
 .page1_column8{
    width:96.708661417px;padding-left:15.118110236;font-size:7.9pt;text-align: right;vertical-align: middle;
 }
 .page1_column9{
    width:102.826771654px;padding-left:15.118110236;font-size:7.9pt;vertical-align: middle;
 }

 .page2{
    margin-top: 17.3mm;margin-left: -14.88px;
 }
 .page2_column1{
    width:42px;height: 5.8mm
 }
 .page2_column2{
    width:41.57480315px;font-size:7.4pt;vertical-align: middle;
 }
 .page2_column3{
    width:75.590551181px;padding-left:7.559055118;font-size:6pt;vertical-align: middle;
 }
 .page2_column4{
    width:83.149606299px;font-size:7.9pt;vertical-align: middle;
 }
 .page2_column5{
    width:20px;padding-left:15.18;font-size:7.9pt;vertical-align: middle;
 }
 .page2_column6{
    width:18.897637795px;padding-left:20;font-size:7.9pt;vertical-align: middle;
 }
 .page2_column7{
    width:92px;padding-left:15.118110236;font-size:7.9pt;text-align: right;vertical-align: middle;
 }
 .page2_column8{
    width:98px;padding-left:15.118110236;font-size:7.9pt;text-align: right;vertical-align: middle;
 }
 .page2_column9{
    width:105.826771654px;padding-left:16;font-size:7.9pt;vertical-align: middle;
 }
</style>

<?php
if($result->num_rows() > 0)
{
?>
<div class="page<?= $page ?>">
    <!-- <table border="1"> -->
    <table>
        <tr>
            <td class="page<?= $page ?>_column1"></td>
            <td class="page<?= $page ?>_column2"></td>
            <td class="page<?= $page ?>_column3"></td>
            <td class="page<?= $page ?>_column4"></td>
            <td class="page<?= $page ?>_column5"></td>
            <td class="page<?= $page ?>_column6"></td>
            <td class="page<?= $page ?>_column7"></td>
            <td class="page<?= $page ?>_column8"></td>
            <td class="page<?= $page ?>_column9"></td>
        </tr>
    <?php
    $cMaster = $this->laporan_model->get_master($nofakt);
    $rMaster = $cMaster->row();
    $uangmuka = $rMaster->UANGMUKA;
    if($uangmuka > 0 && $page == 1)
    {
        echo 
        '<tr>
            <td class="page'.$page.'_column1"></td>
            <td class="page'.$page.'_column2"></td>
            <td class="page'.$page.'_column3"></td>
            <td class="page'.$page.'_column4"></td>
            <td class="page'.$page.'_column5"></td>
            <td class="page'.$page.'_column6"></td>
            <td class="page'.$page.'_column7">'.number_format($uangmuka).'</td>
            <td class="page'.$page.'_column8"></td>
            <td class="page'.$page.'_column9"></td>
        </tr>';
    }
    // else
    // {
    ?>
        <!-- <tr>
            <td class="page<?= $page ?>_column1"></td>
            <td class="page<?= $page ?>_column2"></td>
            <td class="page<?= $page ?>_column3"></td>
            <td class="page<?= $page ?>_column4"></td>
            <td class="page<?= $page ?>_column5"></td>
            <td class="page<?= $page ?>_column6"></td>
            <td class="page<?= $page ?>_column7"></td>
            <td class="page<?= $page ?>_column8"></td>
            <td class="page<?= $page ?>_column9"></td>
        </tr> -->
    <?php 
    // }

    $row = $result->result_array();
    $hal = ($page == 1) ? 15 : 16;
    $no  = ($page == 1) ? 1 : 15;
    for($i=0; $i<=$hal; $i++)
    {
        if($i <= ($hal-2)):
            $angsuran       = !empty(@$row[$i]['angsuran']) ? $row[$i]['angsuran'] : 0;
            $angke          = !empty(@$row[$i]['angke']) ? $row[$i]['angke'] : 0;
            $update_via     = !empty(@$row[$i]['update_via']) ? $row[$i]['update_via'] : "";
            $published_date = !empty(@$row[$i]['published_date']) ? date('d-m-Y', strtotime($row[$i]['published_date'])) : "";
            $nofakt         = !empty($row[$i]['nofakt']) ? $row[$i]['nofakt'] : "";
            $no_dpk         = !empty(@$row[$i]['no_dpk']) ? $row[$i]['no_dpk'] : "";
            $angsuran       = !empty(@$row[$i]['angsuran']) ? $row[$i]['angsuran'] : "";
            $keterangan     = !empty(@$row[$i]['keterangan']) ? $row[$i]['keterangan'] : "";
            $no_bukti       = !empty(@$row[$i]['no_bukti']) ? $row[$i]['no_bukti'] : "";
            $published       = !empty(@$row[$i]['published']) ? $row[$i]['published'] : "";
            $bayar          = ($angsuran * $angke);
            $total          = number_format($total_pinjaman->total - $bayar);
            $in             = ($update_via == 'kasir') ? 'Yes' : '';
            $out            = ($update_via == 'dpk') ? 'Yes' : '';

            if($no >= $range1 && $no <= $range2)
            {
                if($published)
                {
                    echo '<tr>
                        <td class="page'.$page.'_column1"></td>
                        <td class="page'.$page.'_column2">'.$published_date.'</td>
                        <td class="page'.$page.'_column3">'.$nofakt.'</td>
                        <td class="page'.$page.'_column4">'.$no_dpk.'</td>
                        <td class="page'.$page.'_column5">'.$in.'</td>
                        <td class="page'.$page.'_column6">'.$out.'</td>
                        <td class="page'.$page.'_column7">'.number_format($angsuran).'</td>
                        <td class="page'.$page.'_column8">'.$total.'</td>
                        <td class="page'.$page.'_column9">'.$keterangan.'</td>
                    </tr>';
                }else{
                    echo tr_page($page);
                }
            }else{
                echo tr_page($page);
            }

            $no++;
        endif;
    
    }
?>
    </table>
</div>
<?php
}