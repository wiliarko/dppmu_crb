<?php echo $this->load->view('backend/head'); ?>

<div id="wrapper">
	
	<?php echo $this->load->view('backend/nav-left'); ?>
		
	<div id="page-wrapper" class="gray-bg">

		<?php echo $this->load->view('backend/header'); ?>

		<div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2><?php echo $page_header[0] ?></h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="javascript:void(0)"><?php echo $page_header[1] ?></a>
                    </li>
                    <li class="active">
                        <strong><?php echo $page_header[2] ?></strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">

            <?php if($this->session->flashdata('msg_response')){ ?>
            <div class="row m-b-md">
                <div class="col-lg-12">
                    <?php echo $this->session->flashdata('msg_response'); ?>
                </div>
            </div>
            <?php } ?>
            
            <?php
            $date_added = !empty($params['start_date']) ? date('Y-m-d', strtotime($params['start_date'])) : "";
            $date_modified = !empty($params['end_date']) ? date('Y-m-d', strtotime($params['end_date'])) : "";
            ?>

            <input type="hidden" value="<?php echo @$selNoFak ?>" id="selNoFak">
            <div class="ibox-content m-b-sm border-bottom">
                <form role="form" action="<?php echo $pageInfo['url_module']?>" method="get" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="nofakt">No Faktur</label>
                                <select class="select2 form-control" name="nofakt">
                                    <?php
                                        if($noFaktur->num_rows() > 0)
                                        {
                                            echo "<option value=''>-</option>";
                                            foreach ($noFaktur->result() as $arrFak) {
                                                echo "<option value='$arrFak->nofakt'";
                                                echo ($params['nofakt'] == $arrFak->nofakt) ? "selected='selected'" : "";
                                                echo ">$arrFak->nofakt</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="kdcust">Nama Customer</label>
                                <select class="select2 form-control" name="kdcust">
                                    <?php
                                        if($customer->num_rows() > 0)
                                        {
                                            echo "<option value=''>-</option>";
                                            foreach ($customer->result() as $arrCustomer) {
                                                echo "<option value='$arrCustomer->kdcust'";
                                                echo ($params['kdcust'] == $arrCustomer->kdcust) ? "selected='selected'" : "";
                                                echo ">$arrCustomer->nama</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <?php 
                                $isCollector = ($this->session->userdata['logged']['groupid'] == 1) ? "" : "disabled";
                            ?>

                            <div class="form-group">
                                <label class="control-label" for="collector">Nama Collector</label>
                                <select class="select2 form-control" name="collector" <?php echo $isCollector; ?>>
                                    <?php
                                        if($collector->num_rows() > 0)
                                        {
                                            echo "<option value=''>-</option>";
                                            foreach ($collector->result() as $arrCollector) {
                                                echo "<option value='$arrCollector->id'";
                                                echo ($params['collector'] == $arrCollector->id) ? "selected='selected'" : "";
                                                echo ">$arrCollector->nama</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <?php if($type <> 'lunas') : ?>
                        <div class="col-sm-4">
                            <label class="control-label" for="collector">Periode</label>
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-sm form-control" name="start_date" <?php echo !empty($date_added) ? "value='$date_added'" : "" ?>/>
                                <span class="input-group-addon">to</span>
                                <input type="text" class="input-sm form-control" name="end_date" <?php echo !empty($date_modified) ? "value='$date_modified'" : "" ?> />
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value=" Search " style="margin-top: 20px" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" >
                                    <thead>
                                        <tr>
                                            <?php
                                                $colspan = count($tablelist['head']);
                                                foreach($tablelist['head'] as $key => $val){
                                                    echo '<th'.($val[1] == 'nosort' ? ' class="nosort"' : '').''.($val[2] != '' ? ' width="'.$val[2].'"' : '').'>'.$val[0].'</th>';
                                                }
                                            ?>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                    if($tablelist['row']->num_rows()) {
                                        $no = 1; $btn_disable = 0;
                                        foreach($tablelist['row']->result() as  $arval){
                                            if($btn_disable == 0){
                                                if($arval->kode_bayar <> '04') {
                                                    $btn_act = '<a class="btn btn-white btn-bitbucket" onclick="viewUpdate('.$arval->id.')"><i class="fa fa-folder"></i></a>';
                                                }else{
                                                    $btn_disable = 1;
                                                    $btn_act = "";
                                                }
                                            }else{
                                                $btn_act = "";
                                            }

                                            echo '<tr>
                                                    <td align="center">'.$no.'.</td>
                                                    <td>'.$arval->nofakt.'</td>
                                                    <!-- <td>'.$arval->dddate.'</td> -->
                                                    <!-- <td>'.$arval->mydate.'</td> -->
                                                    <td>'.$arval->collector.'</td>
                                                    <td>'.$arval->namakons.'</td>
                                                    <td>'.$arval->due_date.'</td>
                                                    <!-- <td>'.$arval->tlpn.'</td> -->
                                                    <td align="right">'. number_format($arval->angsuran).'</td>
                                                    <!-- <td>'.$arval->tenor.'</td> -->
                                                    <td align="right">'.$arval->angke.'</td>
                                                    <td>'.$arval->no_dpk.'</td>
                                                    <td>'.$arval->bayar.'</td>
                                                    <td>'.$arval->keterangan.'</td>
                                                    <td align="center">
                                                        '.$btn_act.'
                                                    </td>
                                                 </tr>      
                                                    ';
                                            $no++;
                                        }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
               	</div>
            </div>
        </div>

        <div class="modal fade" id="fInput" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-dialog">
                <div class="modal-content animated fadeIn">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h2 class="modal-title">Form DPK</h2>
                    </div>
                    
                    <div class="sk-spinner sk-spinner-wave loader" style="display: none; height: 45px;">
                        <div class="sk-rect1"></div>
                        <div class="sk-rect2"></div>
                        <div class="sk-rect3"></div>
                        <div class="sk-rect4"></div>
                        <div class="sk-rect5"></div>
                    </div>

                    <form id="form_insert" class="form-horizontal">
                        <input type="hidden" name="dpk_id" id="dpk_id">
                        <div class="modal-body">
                            <div class="box-body">
                                
                                <div class="row" style="margin-right:10px; margin-left: 10px">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">No Faktur</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="nofakt" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tanggal Jatuh Tempo</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="dddate" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Bulan/Tahun JT</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="mydate" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Nama Konsumen</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="namakons" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Nomer Telp / HP</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="tlpn" name="tlpn">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Angsuran</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="angsuran" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tenor</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="tenor" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Angsuran Ke</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="angke" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Bayar</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="bayar" disabled style="display: none">
                                            <select class="form-control" style="width: 100%" id="isPay" name="isPay">
                                                <option value="" selected="">Pilih status</option>
                                                <option value="99">Bayar</option>
                                                <option value="00">Bayar Sebagian</option>
                                                <option value="">---</option>
                                                <option value="01">Janji Bayar</option>
                                                <option value="02">Percepatan</option>
                                                <option value="03">Sisa Titipan</option>
                                                <option value="04">Tarik Barang</option>
                                                <option value="05">Tidak Ada Orang</option>
                                                <option value="06">Alasan Kesehatan</option>
                                                <option value="07">Alasan Ekonomi</option>
                                                <option value="08">Komplain Produk</option>
                                                <option value="09">Rencana Tarik Barang</option>
                                                <option value="10">Pindah Alamat</option>
                                                <option value="11">Tidak Ada Barang + Karakter</option>
                                                <option value="12">Ada Barang + Karakter</option>
                                                <option value="13">Bangkrut / Pailit</option>
                                                <option value="14">Kabur / Hilang</option>
                                                <option value="15">Force Mejeur</option>
                                            </select>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group payOthers" id="janjiBayar" style="display: none">
                                        <label class="col-sm-4 control-label">Janji Bayar</label>
                                        <div class="col-sm-8">
                                            <div class="input-group isDate date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="janjiBayar">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group payOthers" id="bayarSebagian" style="display: none">
                                        <label class="col-sm-4 control-label">Bayar Sebagian</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" placeholder="" name="bayarSebagian">
                                            <span class="help-block">masukan jumlah yang dibayarkan sebagian</span>
                                        </div>
                                    </div>

                                    <div class="form-group payOthers" id="bayarLainnya" style="display: none">
                                        <label class="col-sm-4 control-label">Keterangan</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" rows="4" name="bayarLainnya" id="bayarLainnyaVal"></textarea>
                                            <span class="help-block" id="ketNote">masukan catatan</span>
                                        </div>
                                    </div>

                                    <div class="form-group" id="updateBy" style="display: none">
                                        <label class="col-sm-4 control-label">Admin</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" placeholder="" id="updateByVal" disabled="">
                                        </div>
                                    </div>

                                    <div class="form-group" id="updateAt" style="display: none">
                                        <label class="col-sm-4 control-label">Pada Tgl</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" placeholder="" id="updateAtVal" disabled="">
                                        </div>
                                    </div>

                                    <div class="form-group" id="updateVia" style="display: none">
                                        <label class="col-sm-4 control-label">Melalui Menu</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" placeholder="" id="updateViaVal" disabled="">
                                        </div>
                                    </div>

                                </div>                          
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button id="bt_cancel" type="button" class="btn  btn-sm default" ><i class="icon-action-undo"></i> Batal </button>
                            <button id="bt_save" type="button" class="btn  btn-sm blue"><i class="fa fa-send"></i> Simpan </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php echo $this->load->view('backend/footer'); ?>

    </div>

</div>

<script type="text/javascript">
    
    $(document).ready(function () {
        $("body").addClass('mini-navbar');
        SmoothlyMenu();

        $(".select2").select2({
          tags: true
        });

        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {
                    extend: 'pdf',
                    title: 'Daftar Penyerahan Kwitansi (DPK)',
                    orientation: 'landscape'
                }
            ]

        });

        $('.isDate').datepicker({
            format: 'yyyy-mm-dd',
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });

        $('#isPay').change(function(){
            var val = $(this).val();

            hideOthers();
            clearField();

            switch(val) {
                /*case '99':
                    hideOthers();
                    break;
                case '00':
                    $("#bayarSebagian").show();
                    break;
                case '01':
                    $("#janjiBayar").show();
                    break;*/
                default:
                    $("#bayarLainnya").show();
                    break;
            }

        });


        $('#fInput').on('show.bs.modal', function () {
            clearField();
        });

        $('.input-daterange').datepicker({
            format: 'yyyy-mm-dd',
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });

        $('#bt_cancel').click(function() {
            $("#fInput").modal("hide");
        });

        $('#bt_save').click(function() {
            dpk_update();
        });

    });

    function clearField() {
        $("#payOthers").find("input[type=text],textarea,select").val("");
        // $("#isPay").val("");
        hideOthers();
    }

    function hideOthers(){
        $("#bayarSebagian, #janjiBayar, #bayarLainnya").hide();
    }

    function viewUpdate(id) {
        
        $('#fInput').modal('show');
        $('#dpk_id').val(id);

        $("#form_insert").hide();
        $(".loader").show();

        $.ajax({
            url: "<?php echo site_url('backend/laporan/get_dpk_trx'); ?>",
            type: 'POST',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(msg) {
                if(msg.status == 'success'){
                    var dt = msg.data;

                    $('#nofakt').val(dt.nofakt);
                    $('#dddate').val(dt.dddate);
                    $('#mydate').val(dt.mydate);
                    $('#namakons').val(dt.namakons);
                    $('#tlpn').val(dt.tlpn);
                    $('#angsuran').val(dt.angsuran);
                    $('#tenor').val(dt.tenor);
                    $('#angke').val(dt.angke);
                    $("#isPay").val("");

                    // if(dt.no_dpk){
                        $("#bayar").show();
                        $("#updateBy").show();
                        $("#bayarLainnya").show();
                        $("#updateAt").show();
                        $("#updateVia").show();
                        $("#isPay").hide();
                        $("#ketNote").hide();

                        $("#bayarLainnyaVal").attr("disabled", true);

                        $("#bayar").val(dt.bayar);                        
                        $("#bayarLainnyaVal").val(dt.keterangan);
                        $("#updateByVal").val(dt.update_by);
                        $("#updateAtVal").val(dt.update_at);
                        $("#updateViaVal").val(dt.update_via);
                    // }else{
                    //     $("#bayar").hide();
                    //     $("#updateBy").hide();
                    //     $("#bayarLainnya").hide();
                    //     $("#updateAt").hide();
                    //     $("#updateVia").hide();
                    //     $("#isPay").show();
                    //     $("#ketNote").show();

                    //     $("#bayarLainnyaVal").attr("disabled", false);
                    //     $("#bayarLainnyaVal").val("");
                    // }
                    
                    $("#form_insert").show();
                    $(".loader").hide();
                }else{
                    toastr.error('Error', msg.msg);
                }
            }
        });

    }

    function dpk_update(){

        $("#form_insert").hide();
        $(".loader").show();

        $.ajax({
            url: "<?php echo site_url('backend/transaksi/dpk_process/kasir'); ?>",
            type:"POST",
            data:$("#form_insert").serialize(),
            dataType:"json",
            success:function(data){
                if(data.status != "error"){
                    toastr.success('Data Berhasil disimpan', 'Form DPK!')
                    $('#form_insert').modal('hide');
                    location.reload();                
                }else{
                    toastr.error(data.msg, 'Form DPK');
                }
            }
        });
    }

</script>

</body>

</html>