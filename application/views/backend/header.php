<?php 
$name = $this->session->userdata['logged']['realname'];
if(isset($name)){
    $realname = $name;
}else{
    header("Location: ". base_url() . "backend/auth/logout");
    die();
}

?>
<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span class="m-r-sm text-muted welcome-message">Selamat datang di DPP Online System, <?php echo $this->session->userdata['logged']['realname'] ?></span>
            </li>
            <li>
                <a href="<?php echo base_url() ?>backend/auth/logout">
                    <i class="fa fa-sign-out"></i> Log out
                </a>
            </li>
        </ul>
    </nav>
</div>