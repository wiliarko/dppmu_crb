<?php echo $this->load->view('backend/head'); ?>

<div id="wrapper">
	
	<?php echo $this->load->view('backend/nav-left'); ?>
		
	<div id="page-wrapper" class="gray-bg">

		<?php echo $this->load->view('backend/header'); ?>

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
            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab-1"> List Rekap</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="panel-body">
                                    <div class="ibox-content m-b-sm border-bottom">
                                        <form role="form" action="<?php echo $pageInfo['url_module']?>" method="get" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <label class="control-label">Status Bayar</label>
                                                    <select class="form-control" name="status_bayar" id="status_bayar">
                                                        <option value='-'>-</option>
                                                        <option value='bayar' <?= ($params['status_bayar'] == 'bayar') ? "selected='selected'" : ""; ?>>Bayar</option>
                                                        <option value='belum_bayar' <?= ($params['status_bayar'] == 'belum_bayar') ? "selected='selected'" : ""; ?>>Belum Bayar</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="collector">Range Tanggal</label>
                                                    <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" class="input-sm form-control" name="start_date" id="_start_date" value="<?= (@$_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d') ?>" />
                                                        <span class="input-group-addon">to</span>
                                                        <input type="text" class="input-sm form-control" name="end_date" id="_end_date" value="<?= (@$_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="control-label" for="collector">Nama Collector</label>
                                                    <select class="select2 form-control" name="collector" id="collector_id">
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

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <input type="submit" class="btn btn-primary" value=" Search " style="margin-top: 20px" />
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover dataTables-example" id="__gettablelist">
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

        $(".select2").select2({
          tags: true
        });

        $('.input-daterange').datepicker({
            format: 'yyyy-mm-dd',
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });

        var _status_bayar = $("#status_bayar").val();
        var _start_date = $("#_start_date").val();
        var _end_date = $("#_end_date").val();
        var _collector_id = $("#collector_id").val();
        $('#__gettablelist').DataTable({
            pageLength: 25,
            responsive: false,
            dom: '<"html5buttons"B>lTfgitp',
            serverSide: true,
            ajax:{
                url : "<?= base_url() ?>backend/laporan/gettables_rekap",
                type: "get",
                data: {
                   status_bayar: _status_bayar,
                   start_date: _start_date,
                   end_date: _end_date,
                   collector_id: _collector_id
                },
                error: function(){
                    $("#_gettablelist_processing").css("display","none");
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

    });

</script>

</body>

</html>