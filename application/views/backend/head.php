<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $pageInfo['page']; ?> | <?php echo $this->config->item('website_name'); ?> Backend System </title>

	<?php if(isset($output)): ?>
		<?php 
        foreach($output->css_files as $file): ?>
	        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
	    <?php endforeach; ?>
	<?php endif; ?>

    <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/animate.css" rel="stylesheet">

    <link href="<?php echo base_url();?>assets/css/plugins/dropzone/basic.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/plugins/dropzone/dropzone.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/plugins/select2/select2.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet">

</head>

<body>