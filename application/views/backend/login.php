<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $this->config->item('website_name');?> | User Login</title>

    <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="<?php echo base_url();?>assets/css/animate.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet">

</head>

    <body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name" style="font-size: 50px; letter-spacing: 1px;">DPP-MU</h1>

            </div>
            <h3>Selamat datang di DPP-MU</h3> 
            <p>Login in. To see it in action.</p>
            <form class="m-t" role="form" action="<?php echo base_url(); ?>backend/auth/login" method="post">
                <?php if(isset($error) && $error != ''){ ?>
                <div class="alert alert-danger alert-dismissable">
                    <i class="fa fa-ban"></i>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $error; ?> 
                </div>
                <?php } ?>            
    
                <div class="form-group">
                    <input type="text" name="uname" class="form-control" placeholder="Username" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="upass" class="form-control" placeholder="Password" required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
            </form>
            <p class="m-t">
                <small>Powered by <a href="http://sewa-beli.co.id/" target="_blank">SEWA-BELI.CO.ID</a> &copy; <?php echo date('Y') ?></small>
            </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="<?php echo base_url();?>assets/js/jquery-2.1.1.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>

</body>

</html>

</html>