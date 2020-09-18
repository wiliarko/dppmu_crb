<?php echo $this->load->view('backend/head'); ?>

<div id="wrapper">
	
	<?php echo $this->load->view('backend/nav-left'); ?>

	<div id="page-wrapper" class="gray-bg">

		<?php echo $this->load->view('backend/header'); ?>

        <input type="hidden" id="collector_id" value="<?= $collector_id ?>">
        <input type="hidden" id="_nofakt" value="<?= $params['nofakt'] ?>">
        <input type="hidden" id="_kdcust" value="<?= $params['kdcust'] ?>">
        <input type="hidden" id="_kdcustname" value="<?= $kdcustname ?>">
        <input type="hidden" id="_collector_id" value="<?= $params['collector'] ?>">
        <input type="hidden" id="_collectorname" value="<?= $collectorname ?>">

		<div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2><?php echo $page_header[0] ?></h2>
                <ol class="breadcrumb">
                    <li class="active">
                        <strong><?php echo $page_header[1] ?></strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">

            <?php if($this->session->flashdata('message_success')){ ?>
                <div class="alert alert-success alert-dismissable" style="margin:20px 10px 10px 20px;">
                    <i class="fa fa-check"></i>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('message_success'); ?> 
                </div>
            <?php }elseif ($this->session->flashdata('message_error')) { ?>
                <div class="alert alert-danger alert-dismissable" style="margin:20px 10px 10px 20px;">
                    <i class="fa fa-times"></i>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('message_error'); ?> 
                </div>
            <?php } ?>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab-1"> List Customer</a></li>
                            <li class=""><a data-toggle="tab" href="#tab-2">History Deskcall</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="panel-body">
                                    <div class="ibox-content m-b-sm border-bottom">
                                        <form role="form" action="<?php echo $pageInfo['url_module']?>" method="get" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="nofakt">No Faktur</label>
                                                        <select class="select2 form-control form-control-lg" name="nofakt" id="selnofakt">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="kdcust">Nama Customer</label>
                                                        <select class="select2 form-control" name="kdcust" id="selcustomer">
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
                                                <div class="col-sm-3">
                                                    <label class="control-label">Status Bayar</label>
                                                    <select class="select2 form-control" name="status_bayar" id="status_bayar">
                                                        <option value='-'>-</option>
                                                        <option value='bayar' <?= ($params['status_bayar'] == 'bayar') ? "selected='selected'" : ""; ?>>Bayar</option>
                                                        <option value='belum_bayar' <?= ($params['status_bayar'] == 'belum_bayar') ? "selected='selected'" : ""; ?>>Belum Bayar</option>
                                                        <option value='janji_bayar' <?= ($params['status_bayar'] == 'janji_bayar') ? "selected='selected'" : ""; ?>>Janji Bayar</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="collector">Jatuh Tempo</label>
                                                    <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" class="input-sm form-control" name="start_date" id="_start_date" value="<?= (@$_GET['start_date']) ? $_GET['start_date'] : "" ?>" />
                                                        <span class="input-group-addon">to</span>
                                                        <input type="text" class="input-sm form-control" name="end_date" id="_end_date" value="<?= (@$_GET['end_date']) ? $_GET['end_date'] : "" ?>"/>
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

                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover dataTables-example" id="__gettablelist" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <?php
                                                        $colspan = count($tablelist1['head']);
                                                        foreach($tablelist1['head'] as $key => $val){
                                                            echo '<th'.($val[1] == 'nosort' ? ' class="nosort"' : '').''.($val[2] != '' ? ' width="'.$val[2].'"' : '').'>'.$val[0].'</th>';
                                                        }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="tab-2" class="tab-pane">
                                <div class="panel-body">
                                    <div class="ibox-content m-b-sm border-bottom">
                                        <form role="form" action="#">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <label class="control-label">Notes</label>
                                                    <select class="select2 form-control" name="notes" id="notes">
                                                        <option></option>
                                                        <option value='sms'>SMS</option>
                                                        <option value='WA'>WA</option>
                                                        <option value='Telp'>Telp</option>
                                                        <option value='Medsos'>Medsos</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <input type="button" class="btn btn-primary" id="btn_src2" value=" Search " style="margin-top: 20px" />
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                   <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover dataTables-example" id="__gettablelist2" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <?php
                                                        $colspan = count($tablelist2['head']);
                                                        foreach($tablelist2['head'] as $key => $val){
                                                            echo '<th'.($val[1] == 'nosort' ? ' class="nosort"' : '').''.($val[2] != '' ? ' width="'.$val[2].'"' : '').'>'.$val[0].'</th>';
                                                        }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

        var _nofakt = $("#_nofakt").val();
        var _kdcust = $("#_kdcust").val();
        var collector_id = $("#collector_id").val();
        var _selnofak = $("#selnofakt");
        var _selcustomer = $("#selcustomer");
        var _kdcustname = $("#_kdcustname").val();
        var _selcollector = $("#selcollector");
        var _collector_id = $("#_collector_id").val();
        var _collectorname = $("#_collectorname").val();
        var isCollector = $("#isCollector").val();

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
        
        // /*
        // END
        // */

        // /*
        // START CUSTOMER SELECT2
        // */

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

        // /*
        // END
        // */

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

        $("#status_bayar").select2({
            tags: true
        });

        $("#notes").select2({
            width: "100%",
            allowClear: true,
            placeholder: 'Pilih Notes'
        });

        var _status_bayar = $("#status_bayar").val();
        var _start_date = $("#_start_date").val();
        var _end_date = $("#_end_date").val();
        $('#__gettablelist').DataTable({
            pageLength: 25,
            responsive: false,
            dom: '<"html5buttons"B>lTfgitp',
            serverSide: true,
            ajax:{
                url : "<?= base_url() ?>backend/deskcall/gettablelist",
                type: "get",
                data: {
                   collector: _selcollector.val(),
                   nofakt: _selnofak.val(),
                   kdcust: _selcustomer.val(),
                   status_bayar: _status_bayar,
                   start_date: _start_date,
                   end_date: _end_date
                },
                error: function(){
                    $("#__gettablelist_processing").css("display","none");
                }
              },
            buttons: [
                {
                    extend: 'pdf',
                    title: 'Collection Report',
                    orientation: 'landscape'
                }
            ]
        });

        $('#btn_src2').click(function() {
            getTable2();
        });

        getTable2();
    });

    function getTable2()
    {
        var table = $('#__gettablelist2');

        table.dataTable().fnClearTable();
        table.dataTable().fnDestroy();

        // begin first table
        table.DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            serverSide: true,
            ajax:{
                url : "<?= base_url() ?>backend/deskcall/gettablelist2",
                type: "get",
                data: { via: $("#notes").val() },
                error: function(){
                    $("#__gettablelist2_processing").css("display","none");
                }
              },
            buttons: ['excel']
        });
    }

</script>

</body>

</html>