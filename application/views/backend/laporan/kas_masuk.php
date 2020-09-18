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
            
            <?php
            if($params['start_date'] != '') $date_added = date('Y-m-d', strtotime($params['start_date']));
            if($params['end_date'] != '') $date_modified = date('Y-m-d', strtotime($params['end_date']));
            ?>

            <div class="ibox-content m-b-sm border-bottom">
                <form role="form" action="<?php echo $pageInfo['url_module']?>" method="get">
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="control-label">Periode</label>
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-sm form-control" name="start_date" value="<?php echo (@$date_added) ? $date_added : ""; ?>"/>
                                <span class="input-group-addon">to</span>
                                <input type="text" class="input-sm form-control" name="end_date" value="<?php echo (@$date_modified) ? $date_modified : ""; ?>" />
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="userkasir">User Kasir</label>
                                <select class="select2 form-control" name="userkasir" id="selkasir">
                                </select>
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
                                        if($tablelist['row']):
                                            if($tablelist['row']->num_rows()) {
                                                foreach($tablelist['row']->result() as  $arval){
                                                    echo '<tr>
                                                            <td align="center">'.$arval->cutOff_id.'</td>
                                                            <td align="right">Rp. '.number_format($arval->jumlah, 0, ',','.').'</td>
                                                            <td>'.$arval->update_by.'</td>
                                                            <td>'.$arval->date_cutoff.'</td>
                                                         </tr>      
                                                            ';
                                                }
                                            }
                                        endif;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $this->load->view('backend/footer'); ?>

    </div>
    
    <input type="hidden" id="_userkasir_id" value="<?= $params['userkasir'] ?>">
    <input type="hidden" id="_kasirname" value="<?= $kasirname ?>">
</div>

<script type="text/javascript">
    
    $(document).ready(function () {

        var _selkasir = $("#selkasir");
        var _userkasir_id = $("#_userkasir_id").val();
        var _kasirname = $("#_kasirname").val();

        $('.input-daterange').datepicker({
            format: 'yyyy-mm-dd',
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });

        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {
                    extend: 'pdf',
                    text: 'Print to PDF',
                    title: 'Laporan Kas Masuk',
                    orientation: 'portrait',
                    customize: function ( doc ) {
                         doc.pageMargins = [30,15,30,15]
                    }
                }
            ]

        });

        _selkasir.select2({
            placeholder: 'Pilih User Kasir',
            width: "100%",
            allowClear: true,
            delay: 250,
            ajax: {
                url: "<?php echo site_url('backend/transaksi/get_userkasir'); ?>",
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
        _selkasir.empty().append('<option selected value="'+_userkasir_id+'">'+_kasirname+'</option>');
        _selkasir.select2('data', {
          id: _userkasir_id,
          text: _kasirname
        });
        _selkasir.trigger('change');

    });

</script>

</body>

</html>