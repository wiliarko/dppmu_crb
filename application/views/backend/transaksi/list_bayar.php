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
						<?php if($via == 'pembayaran') : ?>
						
						<div class="col-sm-2">
						    <label class="control-label">Status Bayar</label>
                            <select class="select2 form-control" name="status_bayar" id="_status_bayar">
                                <option value='-'>-</option>
                                <option value='bayar' <?= ($params['status_bayar'] == 'bayar') ? "selected='selected'" : ""; ?>>Bayar</option>
                                <option value='belum_bayar' <?= ($params['status_bayar'] == 'belum_bayar') ? "selected='selected'" : ""; ?>>Belum Bayar</option>
                                <option value='janji_bayar' <?= ($params['status_bayar'] == 'janji_bayar') ? "selected='selected'" : ""; ?>>Janji Bayar</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label class="control-label">Outlet</label>
                            <select class="select2 form-control" name="outlet" id="_outlet">
                                <option value='-'>-</option>
                                <option value='ALFAMART' <?= ($params['outlet'] == 'ALFAMART') ? "selected='selected'" : ""; ?>>ALFAMART</option>
                                <option value='INDOMARET' <?= ($params['outlet'] == 'INDOMARET') ? "selected='selected'" : ""; ?>>INDOMARET</option>
                            </select>
                        </div>

						<?php endif; ?>

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

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="clearfix">
                                <form action="" id="formExportToExcel" method="POST" target="_blank"></form>
                                <div class="btn-group pull-right">
                                    <button class="btn btn-danger" id="exportToExcel">Export To Excel</button>
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

        <div class="modal fade" id="fInputPembayaran" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-dialog">
                <div class="modal-content animated fadeIn">
                	<div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h2 class="modal-title">Form Pembayaran</h2>
                    </div>
                    <div class="sk-spinner sk-spinner-wave loader" style="display: none; height: 45px;">
                        <div class="sk-rect1"></div>
                        <div class="sk-rect2"></div>
                        <div class="sk-rect3"></div>
                        <div class="sk-rect4"></div>
                        <div class="sk-rect5"></div>
                    </div>

					<div class="modal-body">
                        <div class="box-body">
                			<form id="form_insert" class="form-horizontal">
                				<input type="hidden" name="transaksi_id" id="transaksi_id">
                                <input type="hidden" name="kdcust_pembayaran" id="kdcust_pembayaran">
                				<div class="row">
                					<div class="form-group">
                                        <label class="col-sm-4 control-label">Nasabah</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="nasabah" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">ID Nasabah</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="idnasabah" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Jumlah Tagihan</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="ammount">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Angsuran Ke</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="angkePembayaran" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Channel Payment Code</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="externalid" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">No Faktur</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="nofaktPembayaran" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tenor</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="tenorPembayaran" readonly>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="col-sm-4 control-label">Channel</label>
                                        <div class="col-sm-8">
                                       		<select class="form-control" id="channelPembayaran" readonly>
                                       			<option value="ALFAMART">ALFAMART</option>
                                       			<option value="INDOMARET">INDOMARET</option>
                                       		</select>
                                        </div>
                                    </div> -->
                                    <input type="hidden" id="channelPembayaran">
                				</div>
							</form>                        
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="bt_cancelPembayaran" type="button" class="btn  btn-sm default" ><i class="icon-action-undo"></i> Batal </button>
                        <button id="bt_submitPembayaran" type="button" class="btn  btn-sm blue"><i class="fa fa-send"></i> Minta Kode Bayar </button>
                    </div>
                </div>
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

        $('.input-daterange').datepicker({
            format: 'yyyy-mm-dd',
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
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
                url : "<?= base_url() ?>backend/transaksi/gettablelist_pembayaran",
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

        $('#exportToExcel').click(function(e) {
            e.preventDefault();

            var cols = JSON.stringify({
                ColsIdx: [
                    'number',
                    'nofakt',
                    'id_nasabah',
                    'namakons',
                    'collector',
                    'due_date',
                    'angsuran',
                    'angke',
                    'retail_outlet_name',
                    'tanggal_bayar',
                    'keterangan'
                ],
                ColsName: [
                    'NO',
                    'NO FAKTUR',
                    'ID NASABAH',
                    'NAMA NASABAH',
                    'COLLECTOR',
                    'DUE DATE',
                    'ANGSURAN', 
                    'ANGKE',
                    'OTLET',
                    'TGL BAYAR',
                    'KETERANGAN'
                ]
            });

            $('<input />').attr('type', 'hidden')
                .attr('name', 'cols')
                .attr('value', cols)
                .appendTo('#formExportToExcel');

            $('<input />').attr('type', 'hidden')
                .attr('name', 'nofakt')
                .attr('value', _selnofak.val())
                .appendTo('#formExportToExcel');

            $('<input />').attr('type', 'hidden')
                .attr('name', 'kdcust')
                .attr('value', _selcustomer.val())
                .appendTo('#formExportToExcel');

            $('<input />').attr('type', 'hidden')
                .attr('name', 'collector')
                .attr('value', _selcollector.val())
                .appendTo('#formExportToExcel');

            $('<input />').attr('type', 'hidden')
                .attr('name', 'start_date')
                .attr('value', _start_date.val())
                .appendTo('#formExportToExcel');

            $('<input />').attr('type', 'hidden')
                .attr('name', 'end_date')
                .attr('value', _end_date.val())
                .appendTo('#formExportToExcel');

            $('<input />').attr('type', 'hidden')
                .attr('name', 'start_pay_date')
                .attr('value', _start_pay_date.val())
                .appendTo('#formExportToExcel');

            $('<input />').attr('type', 'hidden')
                .attr('name', 'end_pay_date')
                .attr('value', _end_pay_date.val())
                .appendTo('#formExportToExcel');

            $('<input />').attr('type', 'hidden')
                .attr('name', 'status_bayar')
                .attr('value', _status_bayar.val())
                .appendTo('#formExportToExcel');

            $('<input />').attr('type', 'hidden')
                .attr('name', 'outlet')
                .attr('value', _outlet.val())
                .appendTo('#formExportToExcel');
            
            $('<input />').attr('type', 'hidden')
                .attr('name', 'via')
                .attr('value', _via.val())
                .appendTo('#formExportToExcel');

            var form = $('#formExportToExcel');

            var formData = form.serializeArray();
            var newData = {};

            jQuery.each(formData, function(idx, field) {
                newData[field.name] = field.value;
            });

            $.ajax({
                url: "<?php echo site_url('backend/transaksi/validate_export_to_excel'); ?>",
                type: 'POST',
                data: newData,
                dataType: 'json',
                success: function(result) {
                    if(result.success){
                        form.attr('action', "<?php echo site_url('backend/transaksi/export_to_excel_pembayaran'); ?>");
                        
                        form.submit();
                    }else{
                        alert('Tidak bisa di export. Data lebih dari 500 row.');
                    }
                }
            });

        });
    });

    function viewPembayaranAlfamart(id) {
        $('#transaksi_id').val('');
        $('#kdcust_pembayaran').val('');
        $('#nasabah').val('');
        $('#ammount').val('');
        $('#angkePembayaran').val('');
        $('#externalid').val('');
        $('#nofaktPembayaran').val('');
        $('#tenorPembayaran').val('');
    	$('#fInputPembayaran').modal('show');
        $('#channelPembayaran').val('ALFAMART');
        $('#idnasabah').val('');
        $('#idnasabah').attr('readonly', true);


    	$.ajax({
            url: "<?php echo site_url('backend/transaksi/get_dpk_trx_pembayaran'); ?>",
            type: 'POST',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(result) {
                if(result.status == 'success'){
                	var data = result.data;

                    if (data.id_nasabah !== null) {
                        $('#idnasabah').val(data.id_nasabah);
                        $('#idnasabah').attr('readonly', true);
                    } else {
                        $('#idnasabah').attr('readonly', false)                       
                    }

                   	var ammount = data.bayar_sebagian !== null ? data.bayar_sebagian : data.angsuran;

                    $('#transaksi_id').val(data.id);
                    $('#kdcust_pembayaran').val(data.kdcust);
                    $('#nasabah').val(data.namakons);
                    $('#ammount').val(ammount);
                    $('#angkePembayaran').val(data.angke);
                    $('#externalid').val(id);
                    $('#nofaktPembayaran').val(data.nofakt);
                    $('#tenorPembayaran').val(data.tenor);
                }else{
                    toastr.error('Error', result.result);
                }
            }
        });
    }

    function viewPembayaranIndomaret(id) {
        $('#transaksi_id').val('');
        $('#kdcust_pembayaran').val('');
        $('#nasabah').val('');
        $('#ammount').val('');
        $('#angkePembayaran').val('');
        $('#externalid').val('');
        $('#nofaktPembayaran').val('');
        $('#tenorPembayaran').val('');
        $('#fInputPembayaran').modal('show');
        $('#channelPembayaran').val('INDOMARET');
        $('#idnasabah').val('');
        $('#idnasabah').attr('readonly', true);

        
        $.ajax({
            url: "<?php echo site_url('backend/transaksi/get_dpk_trx_pembayaran'); ?>",
            type: 'POST',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(result) {
                if(result.status == 'success'){
                    var data = result.data;

                    if (data.id_nasabah !== null) {
                        $('#idnasabah').val(data.id_nasabah);
                        $('#idnasabah').attr('readonly', true);
                    } else {
                        $('#idnasabah').attr('readonly', false)                       
                    }

                    var ammount = data.bayar_sebagian !== null ? data.bayar_sebagian : data.angsuran;

                    $('#transaksi_id').val(data.id);
                    $('#kdcust_pembayaran').val(data.kdcust);
                    $('#nasabah').val(data.namakons);
                    $('#ammount').val(ammount);
                    $('#angkePembayaran').val(data.angke);
                    $('#externalid').val(id);
                    $('#nofaktPembayaran').val(data.nofakt);
                    $('#tenorPembayaran').val(data.tenor);
                }else{
                    toastr.error('Error', result.result);
                }
            }
        });
    }

    $('#bt_cancelPembayaran').click(function(e) {
    	e.preventDefault();
        $("#fInputPembayaran").modal("hide");
    });

    $('#bt_submitPembayaran').click(function(e) {
    	e.preventDefault();

    	if(confirm('Anda yakin akan mengeksekusi form ini?')){
        	$("#fInputPembayaran").modal("hide");
            $(".loader").show();
	    	$.ajax({
	            url: "<?php echo site_url('backend/transaksi/minta_kode_pembayaran'); ?>",
	            type: 'POST',
	            data: {
	                transaksi_id : $('#transaksi_id').val(),
                    kdcust : $('#kdcust_pembayaran').val(),
	                nasabah : $('#nasabah').val(),
	                id_nasabah : $('#idnasabah').val(),
	                ammount : $('#ammount').val(),
	                angkePembayaran : $('#angkePembayaran').val(),
	                externalid : $('#transaksi_id').val(),
	                nofaktPembayaran : $('#nofaktPembayaran').val(),
	                tenorPembayaran : $('#tenorPembayaran').val(),
	                channel : $('#channelPembayaran').val()
	            },
	            dataType: 'json',
	            success: function(result) {
	                if (result.success) {
	                	$(".loader").fadeOut('slow').hide();
                        console.log('asdasdasdssd');
                        location.reload();
	                } else {
                        alert('Xendit : ' + result.msg);
                        $(".loader").fadeOut('slow').hide();
                        location.reload();
                    }
	            }
	        });
        }
    });

    
</script>

</body>

</html>