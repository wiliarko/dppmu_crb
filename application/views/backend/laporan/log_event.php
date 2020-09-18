<?php echo $this->load->view('backend/head'); ?>

<div id="wrapper">
	
	<?php echo $this->load->view('backend/nav-left'); ?>
		
	<div id="page-wrapper" class="gray-bg">

		<?php echo $this->load->view('backend/header'); ?>

		<div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2><?php echo $page_header[0] ?></h2>
            </div>
            <div class="col-lg-2">

            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>List event log all transaction</h5>
                        </div>

                        <div class="ibox-content">
                            <?php echo $output->output; ?>
                        </div>
                    </div>
               	</div>
            </div>

            <div class="row m-b-md">
                <div class="col-lg-12">
                    <?php if($this->session->flashdata('msg_response')){ ?>
                        <?php echo $this->session->flashdata('msg_response'); ?> 
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php echo $this->load->view('backend/footer'); ?>

        <?php foreach($output->js_files as $file): ?>
            <script src="<?php echo $file; ?>"></script>
        <?php endforeach; ?>

    </div>

</div>

</body>

</html>