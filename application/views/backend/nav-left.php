<?php $groupid = $this->session->userdata['logged']['groupid']; ?>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>
                        <img alt="image" class="img-circle" src="<?php echo base_url();?>assets/img/default.png" />
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <!-- <span class="clear"> -->
                        <span class="block m-t-xs"> <strong class="font-bold"><?php echo ucwords($this->session->userdata['logged']['realname']) ?></strong></span>
                    </a>
                </div>
                <div class="logo-element">
                    DPP
                </div>
            </li>

            <li class="<?php echo ($pageInfo['page'] == 'Dashboard' ? '  active':'');?>">
                <a href="<?php echo base_url() ."backend/dashboard" ?>"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">DASHBOARD</span></a>
            </li>

            <?php if(in_array($groupid, array(1,3,4,6,8))): ?>
            <!--<li class="<?php echo ($pageInfo['page'] == 'Xendit' ? '  active':'');?>">
                <a href="<?php echo base_url() ."backend/xendit_payment" ?>"><i class="fa fa-money"></i> <span class="nav-label">PAYMENT</span></a>
            </li>-->
            <?php endif; ?>
            
            <?php if(in_array($groupid, array(1,4))): ?>
            <li class="<?php echo ($pageInfo['page'] == 'Home' ? '  active':'');?>">
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">MASTER DATA</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li class="<?php echo ($pageInfo['page'] == 'Home' && $pageInfo['subpage'] == 'Upload Excel' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/home" ?>">Upload Excel</a>
                    </li>
                    <li class="<?php echo ($pageInfo['page'] == 'Home' && $pageInfo['subpage'] == 'Master Excel' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/home/master_excel" ?>">Master Excel</a>
                    </li>
                    <li class="<?php echo ($pageInfo['page'] == 'Home' && $pageInfo['subpage'] == 'Data Konsumen' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/home/data_konsumen" ?>">Data Customer</a>
                    </li>
                    <li class="<?php echo ($pageInfo['page'] == 'Home' && $pageInfo['subpage'] == 'Data Sales' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/home/data_sales" ?>">Data Sales</a>
                    </li>
                    <li class="<?php echo ($pageInfo['page'] == 'Home' && $pageInfo['subpage'] == 'Data Surveyor' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/home/data_surveyor" ?>">Data Surveyor</a>
                    </li>
                    <li class="<?php echo ($pageInfo['page'] == 'Home' && $pageInfo['subpage'] == 'Data Collector' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/home/data_collector" ?>">Data Collector</a>
                    </li>
                    <li class="<?php echo ($pageInfo['page'] == 'Home' && $pageInfo['subpage'] == 'Data Admin' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/home/data_admin" ?>">Data Admin</a>
                    </li>
                    <li class="<?php echo ($pageInfo['page'] == 'Home' && $pageInfo['subpage'] == 'Mutasi Collector' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/home/mutasi_collector" ?>">Mutasi Collector</a>
                    </li>
                    <?php if(in_array($groupid, array(1))): ?>
                    <li class="<?php echo ($pageInfo['page'] == 'Home' && $pageInfo['subpage'] == 'Users' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/home/users" ?>">Users</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php
            endif;
            ?>

			<?php if(in_array($groupid, array(1,2,3,4,6))): ?>
            <li class="<?php echo ($pageInfo['page'] == 'Transaksi' ? '  active':'');?>">
                <a href="#"><i class="fa fa-diamond"></i> <span class="nav-label">TRANSAKSI</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <?php if(in_array($groupid, array(1,2,4,6))): ?>
                    <li class="<?php echo ($pageInfo['page'] == 'Transaksi' && $pageInfo['subpage'] == 'DPK' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/transaksi/dpk" ?>">DPK</a>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array($groupid, array(1,3))):?>
                    <li class="<?php echo ($pageInfo['page'] == 'Transaksi' && $pageInfo['subpage'] == 'KASIR' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/transaksi/kasir" ?>">KASIR</a>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array($groupid, array(1,2,4,6))):?>
                    <li class="<?php echo ($pageInfo['page'] == 'Transaksi' && $pageInfo['subpage'] == 'PEMBAYARAN' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/transaksi/pembayaran" ?>">PEMBAYARAN</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php
            endif;
            ?>

            <?php if(in_array($groupid, array(1,5,9))): ?>
            <li class="<?php echo ($pageInfo['page'] == 'Deskcall' ? '  active':'');?>">
                <a href="<?php echo base_url() ."backend/deskcall" ?>"><i class="fa fa-paste"></i> <span class="nav-label">DESKCALL</span></a>
            </li>
            <?php endif; ?>

            <?php if(in_array($groupid, array(1,6,7,8,9))): ?>
            <li class="<?php echo ($pageInfo['page'] == 'Laporan' ? '  active':'');?>">
                <a href="#"><i class="fa fa-database"></i> <span class="nav-label">LAPORAN</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
					<?php if(in_array($groupid, array(1,6,9))):?>
                    <li class="<?php echo ($pageInfo['page'] == 'Laporan' && $pageInfo['subpage'] == 'rekapitulasi' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/laporan/rekapitulasi" ?>">Rekapitulasi Kolektor</a>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array($groupid, array(1,6))):?>
                    <li class="<?php echo ($pageInfo['page'] == 'Laporan' && $pageInfo['subpage'] == 'kas_masuk' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/laporan/kas_masuk" ?>">laporan kas masuk</a>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array($groupid, array(1,6,7,8,9))):?>
                    <li class="<?php echo ($pageInfo['page'] == 'Laporan' && $pageInfo['subpage'] == 'bayar' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/laporan/r/bayar" ?>">laporan bayar</a>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array($groupid, array(1,8,9))):?>
                    <li class="<?php echo ($pageInfo['page'] == 'Laporan' && $pageInfo['subpage'] == 'blm_bayar' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/laporan/r/blm_bayar" ?>">laporan belum bayar</a>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array($groupid, array(1,6,7,8,9))):?>
                    <li class="<?php echo ($pageInfo['page'] == 'Laporan' && $pageInfo['subpage'] == 'bayar' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/laporan/r/lunas" ?>">laporan lunas bayar</a>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array($groupid, array(1,7,8,9))):?>
                    <li class="<?php echo ($pageInfo['page'] == 'Laporan' && $pageInfo['subpage'] == 'janji_bayar' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/laporan/r/janji_bayar" ?>">laporan janji bayar</a>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array($groupid, array(1,7,8,9))):?>
                    <li class="<?php echo ($pageInfo['page'] == 'Laporan' && $pageInfo['subpage'] == 'tarik_barang' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/laporan/r/tarik_barang" ?>">laporan rencana/tarik barang</a>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array($groupid, array(1))):?>
                    <li class="<?php echo ($pageInfo['page'] == 'Laporan' && $pageInfo['subpage'] == 'log_event' ? 'active':'');?>">
                        <a href="<?php echo base_url() ."backend/laporan/log_event" ?>">Log Event</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php
            endif;
            ?>

        </ul>

    </div>
</nav>