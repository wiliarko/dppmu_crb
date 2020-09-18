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

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <!-- <label>Upload Master Data</label> -->
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <!-- <a id="exec_bill">
                                    <i class="fa fa-refresh"></i>
                                </a> -->
                                <a href="<?php echo base_url() ?>assets/TEMPLATE DPP MASTER.xls" download>
                                    <i class="fa fa-download"></i>
                                </a>
                            </div>
                        </div>

                        <div class="ibox-content">
                            <?php echo ($sync > 0) ? '<button class="btn btn-warning " id="exec_bill" type="button"><i class="fa fa-refresh"></i> <span class="bold">&nbsp;Synchronize</span></button>' : ''; ?>

                            <div class="spiner-example" id="spiner-load" style="display: none;">
                                <div class="sk-spinner sk-spinner-cube-grid">
                                    <div class="sk-cube"></div>
                                    <div class="sk-cube"></div>
                                    <div class="sk-cube"></div>
                                    <div class="sk-cube"></div>
                                    <div class="sk-cube"></div>
                                    <div class="sk-cube"></div>
                                    <div class="sk-cube"></div>
                                    <div class="sk-cube"></div>
                                    <div class="sk-cube"></div>
                                </div><br/>
                                <center><label class="text-danger">Jangan di refresh page ini, kecuali koneksi time-out / terputus!</label></center>
                            </div>

                            <form action="<?php echo site_url('Insertcsv') ?>" class="dropzone" id="dropzoneForm" method="post" enctype="multipart/form-data">
                                <div class="fallback">
                                    <input name="epfile" type="file" />
                                </div>
                            </form>
                        </div>
                    </div>
               	</div>
            </div>

            <div class="row m-b-md">
                <div class="col-lg-12" id="msg_response">
                    
                </div>
            </div>
        </div>

        <?php echo $this->load->view('backend/footer'); ?>

        <!-- DROPZONE -->
        <script src="<?php echo base_url();?>assets/js/plugins/dropzone/dropzone.js"></script>

    </div>

    <script type="text/javascript">
        
        Dropzone.options.dropzoneForm = {
            paramName: "epfile", // The name that will be used to transfer the file
            maxFilesize: 20, // MB
            dictDefaultMessage: "<strong>Drop files here or click to upload. </strong></br> <small>(Silahkan masukan data excel DPP - Master sesuai dengan template excel yang sudah disediakan.)</small>",
            success: function(file, response){

            	try {
			        var res = JSON.parse(response);
			    } catch (e) {
			        alert(response);
			        return false;
			    }

                if(res.status == "success" || res.status == "error"){
                    toastr.warning('Please wait.......');

                    $("#msg_response").empty();
                    $("#msg_response").append(res.msg).hide().fadeIn(2500);
                    
                }else{
                    alert(response);
                }
            }
        };

        $(document).ready(function () {
            $('#exec_bill').click(function() {

                $("#spiner-load").show();
                $("#dropzoneForm").hide();
                $("#exec_bill").hide();

                $.ajax({
                    url: "<?php echo site_url('backend/transaksi/exec_bill'); ?>",
                    type: 'GET',
                    success: function(result){
                        $("#spiner-load").hide();
                        $("#dropzoneForm").show();

                        toastr.success('Synchronize data success!');
                    }
                });
            });
        });
    </script>

</div>

</body>

</html>