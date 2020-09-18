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
                <div class="col-md-12">
                    <div class="box box-primary">
                        <!-- form start -->
                        <form role="form" action="<?php echo $pageInfo['url_module']?>update_collector" method="post" name="frmInput" id="frmInput" enctype="multipart/form-data" >
                            <div class="box-body">
                                <?php
                                    $row = $result->row();
                                    // echo '<pre>';
                                    // print_r($row);
                                ?>

                                <div id="group-canvasser" class="form-group">
                                    <label for="canvasser">Collector Name</label>
                                    <input type="text" class="form-control" value="<?php echo $row->nama; ?>" disabled/>
                                    <input type="hidden" class="form-control" name="nama" id="nama" value="<?php echo $row->nama; ?>" placeholder="required" />
                                </div>

                                <div id="group-name" class="form-group">
                                    <label for="name">Username</label>
                                    <input type="text" class="form-control" name="username" id="username" value="<?php echo $row->username; ?>" placeholder="required" required/>
                                </div>
                                
                                <div id="group-password" class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Leave blank if password not changes" <?php echo ($row->user_id == '') ? 'required' : '' ?>/>
                                </div>
                                
                                <div id="group-group" class="form-group">
                                    <label for="group">Group</label>
                                    <?php 
                                    $ugroup = $user_group->row();
                                    ?>
                                    <input type="text" class="form-control" value="<?php echo $ugroup->group_name; ?>" disabled/>
                                    <input type="hidden" class="form-control" name="group" id="group" value="<?php echo $ugroup->group_id; ?>" placeholder="required" />                                  
                                </div>
                                
                                <div id="group-status" class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="1" <?php echo ($row->status == 1 ? 'selected':''); ?>>Enable</option>
                                        <option value="0" <?php echo ($row->status == 0 ? 'selected':''); ?>>Disabled</option>
                                    </select>                                    
                                </div>                                                                                      
                            </div>
                            
                            <div class="box-footer pad">
                                <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
                                <input type="submit" class="btn btn-primary btn-sm" value=" Save " />
                                <input type="button" onclick="location.replace('<?php echo $pageInfo['url_module']."data_collector";?>')" class="btn btn-danger btn-sm" value=" Cancel " />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $this->load->view('backend/footer'); ?>

    </div>

</div>

<script type="text/javascript">
    
    $(document).ready(function () {


    });

</script>

</body>

</html>