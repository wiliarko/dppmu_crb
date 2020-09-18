<?php echo $this->load->view('backend/head'); ?>

<style type="text/css">
    span.select2-container {
        z-index:10050;
    }
</style>

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
                                                    echo '<th style="vertical-align: middle;" '.($val[1] == 'nosort' ? ' class="nosort"' : '').''.($val[2] != '' ? ' width="'.$val[2].'"' : '').'>'.$val[0].'</th>';
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

        <?php echo $this->load->view('backend/footer'); ?>

    </div>

    <div class="modal fade" id="fInput" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h2 class="modal-title">Form Mutasi Collector</h2>
                </div>
                
                <div class="sk-spinner sk-spinner-wave loader" style="display: none; height: 45px;">
                    <div class="sk-rect1"></div>
                    <div class="sk-rect2"></div>
                    <div class="sk-rect3"></div>
                    <div class="sk-rect4"></div>
                    <div class="sk-rect5"></div>
                </div>

                <form id="form_insert" class="form-horizontal">
                    <input type="hidden" name="nofak" id="nofak_id">
                    <input type="hidden" name="collector_id_old" id="collector_id_old">
                    <div class="modal-body">
                        <div class="box-body">
                            
                            <div class="row" style="margin-right:10px; margin-left: 10px">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">No Faktur</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nofak" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Collector</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="collector_old" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label"></label>
                                    <div class="col-sm-6">
                                        digantikan oleh :
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Pengganti</label>
                                    <div class="col-sm-8">
                                        <select class="select2 form-control" id="collector" name="collector_id_new">
                                        
                                        </select>
                                    </div>                                    
                                </div>

                            </div>                          
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="bt_cancel" type="button" class="btn btn-sm default" ><i class="icon-action-undo"></i> Batal </button>
                        <button id="bt_save" type="button" class="btn btn-sm blue"><i class="fa fa-send"></i> Simpan </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function () {
        $("body").addClass('mini-navbar');
        $('[data-toggle="tooltip"]').tooltip();

        $('#fInput #collector').css("width","50%");

        $('.dataTables-example').DataTable({
            pageLength: 15,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            bProcessing: true,
            bServerSide: true,
            ordering: false,
            sAjaxSource: "<?php echo site_url('backend/home/mutasi_collector_tbl'); ?>",
            buttons: [
                {
                    extend: 'pdf',
                    title: 'Daftar Penyerahan Kwitansi (DPK)',
                    orientation: 'landscape'
                }
            ]

        });

        $('#bt_cancel').click(function() {
            $("#fInput").modal("hide");
        });

        $('#bt_save').click(function() {
            mutasi_update();
        });
    });

    function viewForm(nofakt, collector_id_old, collector_name_new) {
        
        $('#fInput').modal('show');

        $("#fInput #nofak").val(nofakt);
        $("#fInput #nofak_id").val(nofakt);
        $("#fInput #collector_id_old").val(collector_id_old);
        $("#fInput #collector_old").val(collector_name_new);
        $("#fInput #collector").empty();
        $("#fInput #collector").trigger('change');
        $("#fInput #collector").select2({
            placeholder: 'Pilih collector pengganti',
            width: "100%",
            allowClear: true,
            ajax: {
                url: "<?php echo site_url('backend/home/get_collector'); ?>/" + collector_id_old,
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function mutasi_update() {
        if($("#collector").val() !== null){
            if(confirm('Anda yakin akan mengeksekusi form ini?')){
                $("#form_insert").hide();
                $(".loader").show();

                $.ajax({
                    url: "<?php echo site_url('backend/home/mutasi_process'); ?>",
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
            toastr.error("Form tidak lengkap", 'Form Mutasi');
        }
    }

</script>

</body>

</html>