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
            
            <?php if ($this->session->flashdata('message_error')) { ?>
                <div class="alert alert-danger alert-dismissable" style="margin:20px 10px 10px 20px;">
                    <i class="fa fa-times"></i>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('message_error'); ?> 
                </div>
            <?php } ?>    

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <!-- form start -->
                        <form role="form" action="<?php echo $pageInfo['url_module'] . $formType ?>_users" method="post" name="frmInput" id="frmInput" enctype="multipart/form-data" >
                            <div class="box-body">

                                <?php
                                    if($formType == 'update'){
                                        $row = $result->row(); 
                                    }
                                ?>

                                <div id="group-canvasser" class="form-group">
                                    <label for="canvasser">Nama</label>
                                    <input type="text" class="form-control" name="nama" id="nama" <?php echo ($formType=='update') ? 'value="'.$row->first_name.'"'  : '' ?> placeholder="required" required />
                                </div>

                                <div id="group-name" class="form-group">
                                    <label for="name">Username</label>
                                    <input type="text" class="form-control" name="username" id="username" <?php echo ($formType=='update') ? 'value="'.$row->username.'"'  : '' ?> placeholder="required" required/>
                                </div>
                                
                                <div id="group-password" class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Leave blank if password not changes" <?php echo ($formType=='insert') ? 'required' : '' ?>/>
                                </div>
                                
                                <div id="group-group" class="form-group">
                                    <label for="group">Group</label>
                                    <select class="form-control" name="group" id="group" required>
                                        <option value=""></option>
                                        <?php foreach($user_group->result() as $gr){?>
                                            <?php if($gr->group_id <> 1 && $gr->group_id <> 2): ?>
                                            <option value="<?php echo $gr->group_id; ?>" <?= ($formType=='update') ? (($gr->group_id == $row->group_id) ? 'selected' : "") : '' ?>><?php echo $gr->group_name; ?></option>
                                            <?php endif;?>
                                        <?php }?>
                                    </select>                               
                                </div>
                                
                                <div id="group-status" class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="1" <?php echo ($formType=='update') ? ($row->status == 1 ? 'selected':'') : ""; ?>>Enable</option>
                                        <option value="0" <?php echo ($formType=='update') ? ($row->status == 0 ? 'selected':'') : ""; ?>>Disabled</option>
                                    </select>                                    
                                </div>                                                                                      
                            </div>
                            
                            <div class="box-footer pad">
                                <?php if($formType=='update'): ?>
                                <input type="hidden" name="id" value="<?php echo $row->user_id; ?>" />
                                <?php endif; ?>
                                <input type="submit" class="btn btn-primary btn-sm" value=" Save " />
                                <input type="button" onclick="location.replace('<?php echo $pageInfo['url_module']."users";?>')" class="btn btn-danger btn-sm" value=" Cancel " />
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