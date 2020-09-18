<?php echo $this->load->view('backend/head'); ?>

<div id="wrapper">
	
	<?php echo $this->load->view('backend/nav-left'); ?>
    <input type="hidden" id="groupid" value="<?= $this->session->userdata['logged']['groupid'] ?>">
    <input type="hidden" id="collector_id" value="<?= $collector_id ?>">
    <input type="hidden" id="_nofakt" value="<?= $params['nofakt'] ?>">
    <input type="hidden" id="_kdcust" value="<?= $params['kdcust'] ?>">
    <input type="hidden" id="_kdcustname" value="<?= $kdcustname ?>">
    <input type="hidden" id="_collector_id" value="<?= $params['collector'] ?>">
    <input type="hidden" id="_collectorname" value="<?= $collectorname ?>">
	<input type="hidden" id="_via" value="<?= $via ?>">
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
            if($params['start_date'] != '') $date_added = date('Y-m-d', strtotime($params['start_date']));
            if($params['end_date'] != '') $date_modified = date('Y-m-d', strtotime($params['end_date']));

            if(@$params['start_pay_date'] != '') $start_pay_date = date('Y-m-d', strtotime($params['start_pay_date']));
            if(@$params['end_pay_date'] != '') $end_pay_date = date('Y-m-d', strtotime($params['end_pay_date']));
            ?>

            <input type="hidden" value="<?php echo @$selNoFak ?>" id="selNoFak">
            <div class="ibox-content m-b-sm border-bottom">
                <form role="form" action="<?php echo $pageInfo['url_module']?>" method="get" enctype="multipart/form-data" id="frmInput">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="nofakt">No Faktur</label>
                                <select class="select2 form-control form-control-lg" name="nofakt" id="selnofakt">

                                    <?php
                                        // if($noFaktur->num_rows() > 0)
                                        // {
                                        //     echo "<option value=''>-</option>";
                                        //     foreach ($noFaktur->result() as $arrFak) {
                                        //         echo "<option value='$arrFak->nofakt'";
                                        //         echo ($params['nofakt'] == $arrFak->nofakt) ? "selected='selected'" : "";
                                        //         echo ">$arrFak->nofakt</option>";
                                        //     }
                                        // }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="kdcust">Nama Customer</label>
                                <select class="select2 form-control" name="kdcust" id="selcustomer">
                                    <?php
                                        // if($customer->num_rows() > 0)
                                        // {
                                        //     echo "<option value=''>-</option>";
                                        //     foreach ($customer->result() as $arrCustomer) {
                                        //         echo "<option value='$arrCustomer->kdcust'";
                                        //         echo ($params['kdcust'] == $arrCustomer->kdcust) ? "selected='selected'" : "";
                                        //         echo ">$arrCustomer->nama</option>";
                                        //     }
                                        // }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <?php $isCollector = in_array($this->session->userdata['logged']['groupid'], array(1,3,4,6)) ? "" : "disabled"; ?>
                            <div class="form-group">
                                <input type="hidden" id="isCollector" value="<?= $isCollector ?>">
                                <label class="control-label" for="collector">Nama Collector</label>
                                <select class="select2 form-control" name="collector" id="selcollector">
                                </select>
                            </div>	
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="control-label" for="collector">Periode</label>
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-sm form-control" name="start_date" id="_start_date" value="<?php echo (@$date_added) ? $date_added : ""; ?>"/>
                                <span class="input-group-addon">to</span>
                                <input type="text" class="input-sm form-control" name="end_date" id="_end_date"  value="<?php echo (@$date_modified) ? $date_modified : ""; ?>" />
                            </div>
                        </div>
						<?php if($via == 'dpk') : ?>
						
						<div class="col-sm-2">
						    <label class="control-label">Status Bayar</label>
                            <select class="select2 form-control" name="status_bayar" id="_status_bayar">
                                <option value='-'>-</option>
                                <option value='bayar' <?= ($params['status_bayar'] == 'bayar') ? "selected='selected'" : ""; ?>>Bayar</option>
                                <option value='belum_bayar' <?= ($params['status_bayar'] == 'belum_bayar') ? "selected='selected'" : ""; ?>>Belum Bayar</option>
                                <option value='janji_bayar' <?= ($params['status_bayar'] == 'janji_bayar') ? "selected='selected'" : ""; ?>>Janji Bayar</option>
                            </select>
                        </div>

						<?php endif; ?>

                        <div class="col-sm-2">
                            <label class="control-label">Outlet</label>
                            <select class="select2 form-control" name="outlet" id="_outlet">
                                <option value='-'>-</option>
                                <option value='ALFAMART' <?= ($params['outlet'] == 'ALFAMART') ? "selected='selected'" : ""; ?>>ALFAMART</option>
                                <option value='INDOMARET' <?= ($params['outlet'] == 'INDOMARET') ? "selected='selected'" : ""; ?>>INDOMARET</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label" for="collector">Tanggal Bayar</label>
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-sm form-control" name="start_pay_date" id="_start_pay_date" value="<?php echo (@$start_pay_date) ? $start_pay_date : ""; ?>"/>
                                <span class="input-group-addon">to</span>
                                <input type="text" class="input-sm form-control" name="end_pay_date" id="_end_pay_date" value="<?php echo (@$end_pay_date) ? $end_pay_date : ""; ?>" />
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value=" Search " style="margin-top: 20px" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <?php if($via != 'dpk'): ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Total KAS <small>yang masuk</small></h5> 
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="alert alert-warning">
                                <?php
                                if($total_kas->num_rows() > 0){
                                    $list = $total_kas->row();
                                    $total = number_format($list->total);
                                }else $total=0;

                                echo "<h1><b>Rp. $total</b></h1>"
                                ?>
                            </div>

                            <?php if(in_array($this->session->userdata['logged']['groupid'], array(1,3,6))): ?>
                            <button class="ladda-button btn btn-success btn-rounded btn-block" data-style="expand-right" id="cutoff">
                            	<span class="ladda-label">CUT OFF</span>
                            	<span class="ladda-spinner"></span>
                            </button>
                        	<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="clearfix">
                                <?php 
                            	if(in_array($this->session->userdata['logged']['groupid'], array(1,4,6))){
	                            ?>
	                                <div class="btn-group pull-left" style="margin-right: 20px">
	                                    <select class="select2 form-control form-control-lg" id="cetak_halaman">
		                                    <option value="" selected="">Pilih Cetak Halaman</option>
		                                 	<option value="1">1</option>
		                                    <option value="2">2</option>
		                                </select>
									</div>

                                    <div class="btn-group pull-left" style="margin-right: 10px">
                                        <select class="select2 form-control form-control-lg" id="selrangebayar1">
                                        </select>
                                    </div>

                                    <div class="btn-group pull-left" style="margin-right: 20px">
                                        <select class="select2 form-control form-control-lg" id="selrangebayar2">
                                        </select>
                                    </div>
									
	                                <div class="btn-group pull-left">
	                                    <button class="btn btn-warning" id="exportPiutang" style="margin-right:10px">Cetak Piutang</button>
									</div>
								<?php
								}
	                            ?>

                                <div class="btn-group pull-right">
                                    <button class="btn btn-danger" id="previewpdf">Print to PDF</button>
                                </div>
                            </div>
                            <div class="table-responsive" style="margin-top: 10px;">
                                <table class="table table-striped table-bordered table-hover dataTables-example" id="_gettablelist">
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
                        <h2 class="modal-title">Form <?php echo ($via == 'dpk') ? "DPK" : "Kasir" ?></h2>
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
                                            <input type="text" class="form-control" id="angsuran_rp" disabled>
                                            <input type="hidden" class="form-control" id="angsuran" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group payOthers" id="bayarSisa" style="display: none">
                                        <label class="col-sm-4 control-label"><span class="text-danger">Sisa Bayar</span></label>
                                        <div class="col-sm-8">
                                            <div id="bayarSisaSpanLast" class="text-danger" style="margin-top: -15px; position: absolute;"></div>
                                            <input type="hidden" class="form-control" id="bayarSisaValLast">
                                            <div id="bayarSisaSpan" class="text-danger" style="margin-top: 7px;"></div>
                                            <input type="hidden" class="form-control" id="bayarSisaVal" name="bayarSisa">
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
                                        <label class="col-sm-4 control-label">Status</label>
                                        <div class="col-sm-8">
                                        	<input type="text" class="form-control" id="bayar" disabled style="display: none">
                                            <select class="form-control" style="width: 100%" id="isPay" name="isPay">
                                                <option value="" selected="">Pilih status</option>
                                             	
                                             	<?php if($this->session->userdata['logged']['groupid'] <> 4): ?>
                                                <option value="99">Bayar</option>
                                                <option value="00">Bayar Sebagian</option>
                                            	<?php endif; ?>
                                                
                                                <option disabled="">---</option>

                                                <?php if($via == 'dpk' && $this->session->userdata['logged']['groupid'] <> 6): ?>
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
                                                <?php endif; ?>

                                            </select>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group payOthers" id="janjiBayar" style="display: none">
                                        <label class="col-sm-4 control-label">Tanggal</label>
                                        <div class="col-sm-8">
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="date" class="form-control" name="nextDate">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group payOthers" id="bayarSebagian" style="display: none">
                                        <label class="col-sm-4 control-label">Bayar Sebagian</label>
                                        <div class="col-sm-8">
                                        	<div id="bayarSebagianValLast" class="text-danger m-b-sm"></div>
                                            <input type="text" class="form-control" placeholder="" id="bayarSebagianVal">
                                            <input type="hidden" class="form-control" placeholder="" id="bayarSebagianValTmp" name="bayarSebagian">
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

                                    <div class="form-group" id="publishBy" style="display: none">
                                        <label class="col-sm-4 control-label">Kasir</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" placeholder="" id="publishByVal" disabled="">
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

		<div class="modal fade" id="modalPrint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-dialog">
            </div>
        </div>

        <?php echo $this->load->view('backend/footer'); ?>

    </div>

</div>

<!-- Ladda -->
<script src="<?php echo base_url() ?>assets/js/plugins/ladda/spin.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/plugins/ladda/ladda.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/plugins/ladda/ladda.jquery.min.js"></script>

<script type="text/javascript">
    
    $(document).ready(function () {
        $("body").addClass('mini-navbar');
        SmoothlyMenu();

        var collector_id = $("#collector_id").val();
        var _selnofak = $("#frmInput #selnofakt");
        var _selcustomer = $("#frmInput #selcustomer");
        var _selcollector = $("#frmInput #selcollector");
        var _via = $("#_via");
        var _nofakt = $("#_nofakt").val();
        var _kdcust = $("#_kdcust").val();
        var _kdcustname = $("#_kdcustname").val();
        var _collector_id = $("#_collector_id").val();
        var _collectorname = $("#_collectorname").val();
        var isCollector = $("#isCollector").val();

        $("#_status_bayar").select2({
          tags: true
        });

        $("#_outlet").select2({
          tags: true
        });

        $("#cetak_halaman").select2({
          tags: true
        });

        /*
        START NO FAKTUR SELECT2
        */

        //get no faktur
        _selnofak.select2({
            placeholder: 'Pilih No Faktur',
            width: "100%",
            allowClear: true,
            delay: 250,
            minimumInputLength: 3,
            ajax: {
                url: "<?php echo site_url('backend/transaksi/get_nofak'); ?>/" + collector_id,
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        // set no faktu selected
        _selnofak.empty().append('<option selected value="'+_nofakt+'">'+_nofakt+'</option>');
        _selnofak.select2('data', {
          id: _nofakt,
          text: _nofakt
        });
        _selnofak.trigger('change');
        
        /*
        END
        */

        /*
        START CUSTOMER SELECT2
        */

        //get customer
        _selcustomer.select2({
            placeholder: 'Pilih Customer',
            width: "100%",
            allowClear: true,
            delay: 250,
            minimumInputLength: 3,
            ajax: {
                url: "<?php echo site_url('backend/transaksi/get_customer'); ?>/" + collector_id,
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        // set no faktu selected
        _selcustomer.empty().append('<option selected value="'+_kdcust+'">'+_kdcustname+'</option>');
        _selcustomer.select2('data', {
          id: _kdcust,
          text: _kdcustname
        });
        _selcustomer.trigger('change');

        /*
        END
        */

        /*
        START COLLECTOR SELECT2
        */
        //get collector
        _selcollector.select2({
            placeholder: 'Pilih Collector',
            width: "100%",
            allowClear: true,
            delay: 250,
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo site_url('backend/transaksi/get_collector'); ?>/" + collector_id,
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        // set collector
        _selcollector.empty().append('<option selected value="'+_collector_id+'">'+_collectorname+'</option>');
        _selcollector.select2('data', {
          id: _collector_id,
          text: _collectorname
        });
        _selcollector.trigger('change');

        if(isCollector=='disabled'){
            _selcollector.prop('disabled', !$('#one').prop('disabled'));
        }
        /*
        END
        */

        $("#selrangebayar1").select2({
            placeholder: 'Range Bayar',
            width: "100%",
            allowClear: true,
            ajax: {
                url: "<?php echo site_url('backend/transaksi/get_range'); ?>/" + _nofakt,
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $("#selrangebayar2").select2({
            placeholder: 'Range Bayar',
            width: "100%",
            allowClear: true,
            ajax: {
                url: "<?php echo site_url('backend/transaksi/get_range'); ?>/" + _nofakt,
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $("#cutoff").click(function(e) {
        	var l = Ladda.create(this);
            l.start();
        	if(confirm('Anda yakin akan menjalankan cut-off?')){
        		
        		$.ajax({
                    url: "<?php echo site_url('backend/transaksi/cutoff_process'); ?>",
                    type:"GET",
                    data: {},
                    dataType:"json",
                    success:function(data){
                        if(data.status != "error"){
                            toastr.success(data.msg, 'Cut-Off!');
                            location.reload();
                    		l.stop();             
                        }else{
                            toastr.error(data.msg, 'Cut-Off!');
                            l.stop();
                        }
                    }
                });

        	}

        });

        var _start_date = $("#_start_date");
        var _end_date = $("#_end_date");
        var _start_pay_date = $("#_start_pay_date");
        var _end_pay_date = $("#_end_pay_date");
        var _status_bayar = $("#_status_bayar");
        var _outlet = $("#_outlet");
        $('#_gettablelist').DataTable({
            pageLength: 25,
            responsive: false,
            dom: '<"html5buttons"B>lTfgitp',
            serverSide: true,
            ajax:{
                url : "<?= base_url() ?>backend/transaksi/gettablelist",
                type: "get",
                data: {
                   nofakt: _selnofak.val(),
                   kdcust: _selcustomer.val(),
                   collector: _selcollector.val(),
                   start_date: _start_date.val(),
                   end_date: _end_date.val(),
                   start_pay_date: _start_pay_date.val(),
                   end_pay_date: _end_pay_date.val(),
                   status_bayar: _status_bayar.val(),
                   outlet: _outlet.val(),
                   via: _via.val()
                },
                error: function(){
                    $("#_gettablelist_processing").css("display","none");
                }
              },
            buttons: [ ]
        });

        $('#isPay').change(function(){
            var val = $(this).val();

            hideOthers();
            clearField();

            switch(val) {
                // case '99':
                //     hideOthers();
                //     break;
                case '00':
                    $("#bayarSebagian").show();
                    $("#bayarSisa").show();
                    $("#bayarLainnya").show();
                    break;
                case '01': case '04': case '09':
                    $("#janjiBayar").show();
                    $("#bayarLainnya").show();
                    break;
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

        /* Dengan Rupiah */
		var dengan_rupiah = document.getElementById('bayarSebagianVal');
		var bayarSisa = document.getElementById('bayarSisaValLast');
		var angsuran = document.getElementById('angsuran');
		dengan_rupiah.addEventListener('keyup', function(e)
		{
			var bayar = clearRupiah(dengan_rupiah.value);
			var pembayaran = bayarSisa.value ? bayarSisa.value : angsuran.value;

			if(parseInt(bayar) > parseInt(pembayaran)){
				toastr.error('Error', "Pembayaran tidak boleh lebih dari biaya angsuran atau sisa pembayaran sebelumnya!");
				$('#bt_save').prop('disabled', true);
			}else{
				$('#bt_save').prop('disabled', false);
			}
			
			dengan_rupiah.value = formatRupiah(this.value);
		});
		
		dengan_rupiah.addEventListener('keydown', function(event)
		{
			limitCharacter(event, false);
		});

        $('#bt_cancel').click(function() {
            $("#fInput").modal("hide");
        });

        $('#bt_save').click(function() {
            dpk_update();
        });

        //export PDF
        $("#previewpdf").click(function()
        {
            if(_selnofak.val()==''){
                nofakt_pdf = '-';
            }else{
                nofakt_pdf = _selnofak.val();
            }

            if(_selcustomer.val()==''){
                customer_pdf = '-';
            }else{
                customer_pdf = _selcustomer.val();
            }

            if(_selcollector.val()==''){
                collector_pdf = '-';
            }else{
                collector_pdf = _selcollector.val();
            }

            if(_start_date.val()==''){
                start_date_pdf = '-';
            }else{
                start_date_pdf = _start_date.val();
            }

            if(_end_date.val()==''){
                end_date_pdf = '-';
            }else{
                end_date_pdf = _end_date.val();
            }

            if(typeof _start_pay_date.val()== 'undefined'){
                start_pay_date_pdf = '-';
            }else{
                start_pay_date_pdf = _start_pay_date.val();
            }

            if(typeof _end_pay_date.val()== 'undefined'){
                end_pay_date_pdf = '-';
            }else{
                end_pay_date_pdf = _end_pay_date.val();
            }

            if(_status_bayar.val()==''){
                status_bayar_pdf = '-';
            }else{
                status_bayar_pdf = _status_bayar.val();
            }

            // window.open('<?php echo base_url();?>backend/laporan_to_pdf/export_kasir/'+ nofakt_pdf +'/'+ customer_pdf +'/'+ collector_pdf +'/'+ start_date_pdf +'/'+ end_date_pdf +'/'+ start_pay_date_pdf +'/'+ end_pay_date_pdf +'/'+ status_bayar_pdf +'/'+ _via.val());

            window.open('<?php echo base_url();?>backend/laporan_to_pdf/export_kasir?nofakt='+ nofakt_pdf +'&kdcust=' + customer_pdf +'&collector=' + collector_pdf +'&start_date=' + start_date_pdf +'&end_date=' + end_date_pdf +'&start_pay_date=' + start_pay_date_pdf +'&end_pay_date=' + end_pay_date_pdf +'&status_bayar=' + status_bayar_pdf +'&via=' + _via.val());
        });

        $("#exportPiutang").click(function(){
        	var conf1 = confirm('Apakah anda sudah pilih cetak halaman ?');
            var hal = $("#cetak_halaman").val();
            var rangebayar1 = $("#selrangebayar1").val();
          	var rangebayar2 = $("#selrangebayar2").val();

            if(hal==1){
                if(rangebayar1 > 14){
                    toastr.error('Error', 'Range pada halaman tidak sesuai!');
                    return false;
                }

                if(rangebayar2 > 14){
                    toastr.error('Error', 'Range pada halaman tidak sesuai!');
                    return false;
                }
            }else if(hal==2){
                if(rangebayar1 < 15){
                    toastr.error('Error', 'Range pada halaman tidak sesuai!');
                    return false;
                }

                if(rangebayar2 < 15){
                    toastr.error('Error', 'Range pada halaman tidak sesuai!');
                    return false;
                }
            }

            if(rangebayar1 && rangebayar2){
                if(rangebayar1 > rangebayar2){
                    toastr.error('Error', 'Range Bayar tidak sesuai!');
                }else{
                    if(conf1){
                        if(hal){
                            var conf2 = confirm('Kartu piutang sudah sesuai di mesin printer ?');
                            if(conf2){
                                if(_selnofak.val()){
                                    var win = window.open();
                                    win.document.write('<input type="button" value="validasi"/></br></br><iframe width="100%" height="100%" src="<?php echo base_url();?>backend/laporan_to_pdf/export_piutang/'+ hal + '/' + _selnofak.val() +'/' + rangebayar1 +'/' + rangebayar2 +'" frameborder="0" allowfullscreen></iframe>')
                                    // window.open('<?php echo base_url();?>backend/laporan_to_pdf/export_piutang/'+ hal + '/' + _selnofak.val());
                                }else{
                                    toastr.error('Error', 'No Faktor Harus Diisi!');
                                }
                            }
                        }else{
                            toastr.error('Error', 'Cetak Halaman Belum Dipilih');
                        }
                    }else{
                        location.reload();
                    }
                }
            }else{
                toastr.error('Error', 'Range Bayar Harus Diisi!');
            }
        });

    });

    function clearField() {
        $("#payOthers").find("input[type=text],textarea,select").val("");
        $("#bayarSebagianVal").val("");
        $("#bayarSebagianValTmp").val("");
        $("#bayarSisaVal").val("");
        $("#bayarSisaValLast").val("");
        $("#bayarSisaSpan").empty();
        hideOthers();
    }

    function hideOthers(){
        $("#bayarSebagian, #janjiBayar, #bayarLainnya, #bayarSisa").hide();
    }

    function clearRupiah(str){
    	var angsuran = $("#bayarSisaValLast").val() ? $("#bayarSisaValLast").val() : $("#angsuran").val();
    	var bayar = str.replace(/\./g,'');
		$("#bayarSebagianValTmp").val(bayar);

		var sisa = parseInt(angsuran) - parseInt(bayar);
		$("#bayarSisaVal").val(sisa);
		$("#bayarSisaSpan").html(formatCurrency(sisa));
		return bayar;
    }

    function formatCurrency(num)
	{
	    num = num.toString().replace(/\$|\,/g, '');
	    if (isNaN(num))
	    {
	        num = "0";
	    }

	    sign = (num == (num = Math.abs(num)));
	    num = Math.floor(num * 100 + 0.50000000001);
	    // cents = num % 100;
	    num = Math.floor(num / 100).toString();

	    // if (cents < 10)
	    // {
	    //     cents = "0" + cents;
	    // }
	    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
	    {
	        num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
	    }

	    return (((sign) ? '' : '-') + 'Rp. ' + num);
	}

    function formatRupiah(bilangan, prefix)
	{
		var number_string = bilangan.replace(/[^,\d]/g, '').toString(),
			split	= number_string.split(','),
			sisa 	= split[0].length % 3,
			rupiah 	= split[0].substr(0, sisa),
			ribuan 	= split[0].substr(sisa).match(/\d{1,3}/gi);
			
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
	}

	function limitCharacter(event, check)
	{
		key = event.which || event.keyCode;
		if ( key != 188 // Comma
			 && key != 8 // Backspace
			 && key != 17 && key != 86 & key != 67 // Ctrl c, ctrl v
			 && (key < 48 || key > 57) // Non digit
			 // Dan masih banyak lagi seperti tombol del, panah kiri dan kanan, tombol tab, dll
			) 
		{
			event.preventDefault();
			return false;
		}
	}

    function published(id) {
        alert(id);
    }

    function kasir_publising(id,nofakt){
        $("#prop-"+id).css('opacity', '0.6');
        var url = "<?php echo site_url('backend/transaksi/kasir_publising'); ?>";
        $('#properties-list-'+id).animate({opacity: 0}, 250, function() {
            $('#properties-list-'+id).load(url +'/'+ id +'/'+ nofakt, function() {
                $('#properties-list-'+id).animate({opacity: 1}, 1000);
                // location.reload();
            });
        });
    }

    function viewUpdate(id) {
        
        $('#fInput').modal('show');
        $('#dpk_id').val(id);
        var groupid = $('#groupid').val();

        $("#form_insert").hide();
        $(".loader").show();

        $.ajax({
            url: "<?php echo site_url('backend/transaksi/get_dpk_trx'); ?>",
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
                    $('#angsuran_rp').val(dt.angsuran_rp);
                    $('#angsuran').val(dt.angsuran);
                    $('#tenor').val(dt.tenor);
                    $('#angke').val(dt.angke);
                    $("#isPay").val("");

                    if((dt.no_dpk && dt.kode_bayar == '99') && groupid !== '6'){
                        $("#bayar").show();
                        $("#updateBy").show();
                        $("#publishBy").show();
                        $("#bayarLainnya").show();
                        $("#updateAt").show();
                        $("#updateVia").show();
                        $("#isPay").hide();
                        $("#ketNote").hide();

                        $("#bayarLainnyaVal").attr("disabled", true);

                        $("#bayar").val(dt.bayar);                        
                        $("#bayarLainnyaVal").val(dt.keterangan);
                        $("#updateByVal").val(dt.update_by);
                        $("#publishByVal").val(dt.publish_by);
                        $("#updateAtVal").val(dt.update_at);
                        $("#updateViaVal").val(dt.update_via);

                        $('#bt_save').prop('disabled', true);
                    }else{
                        $("#bayar").hide();
                        $("#updateBy").hide();
                        $("#publishBy").hide();
                        $("#bayarLainnya").hide();
                        $("#updateAt").hide();
                        $("#updateVia").hide();
                        $("#isPay").show();
                        $("#ketNote").show();

                        $("#isPay").val(dt.kode_bayar);

                        switch(dt.kode_bayar) {
			                case '00':
			                    $("#bayarSebagian").show();
			                    $("#bayarSisa").show();
			                    $("#bayarSebagianValLast").html("Pembayaran sebelumnya : "+ formatCurrency(dt.bayar_sebagian));
			                    $("#bayarSebagianVal").val("");
			                    $("#bayarSebagianValTmp").val(dt.bayar_sebagian);
			                    $("#bayarSisaSpan").html(formatCurrency(dt.bayar_sisa));
			                    $("#bayarSisaSpanLast").html("Sisa pembayaran sebelumnya : " + formatCurrency(dt.bayar_sisa));
			                    $("#bayarSisaVal").val(dt.bayar_sisa);
			                    $("#bayarSisaValLast").val(dt.bayar_sisa);
			                    break;
			            }

                        $("#bayarLainnyaVal").attr("disabled", false);
                        $("#bayarLainnyaVal").val("");
                        $('#bt_save').prop('disabled', false);
                    }
                    
                    $("#form_insert").show();
                    $(".loader").hide();
                }else{
                    toastr.error('Error', msg.msg);
                }
            }
        });

    }

    function dpk_update(){

        if($("#isPay").val() !== ""){
            if(confirm('Anda yakin akan mengeksekusi form ini?')){
                $("#form_insert").hide();
                $(".loader").show();

                $.ajax({
                    url: "<?php echo site_url('backend/transaksi/dpk_process/'. $via); ?>",
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
                            
                            $("#form_insert").show();
                			$(".loader").fadeOut('slow').hide();
                        }
                    }
                });
            }
        }else{
            toastr.error("Form tidak lengkap", 'Form DPK');
        }
    }

</script>

</body>

</html>