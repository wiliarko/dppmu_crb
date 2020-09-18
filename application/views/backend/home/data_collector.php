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

            <?php if($this->session->flashdata('message_success')){ ?>
                <div class="alert alert-success alert-dismissable" style="margin:20px 10px 10px 20px;">
                    <i class="fa fa-check"></i>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('message_success'); ?> 
                </div>
            <?php }elseif ($this->session->flashdata('message_error')) { ?>
                <div class="alert alert-danger alert-dismissable" style="margin:20px 10px 10px 20px;">
                    <i class="fa fa-times"></i>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('message_error'); ?> 
                </div>
            <?php } ?>

            <script>
                setTimeout(function(){ 
                    $('.alert').fadeOut('slow');
                }, 3000);
            </script>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>List Data Customer</h5>
                        </div>

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
                                    if($tablelist['row']->num_rows()){
                                        $no = 1;
                                        foreach($tablelist['row']->result() as  $arval){
                                            $status = ($arval->status == 1) ? "Enable" : "Disable";
                                            echo '<tr>
                                                    <td>'.$no.'.</td>
                                                    <td>'.$arval->nama.'</td>
                                                    <td>'.$arval->username.'</td>
                                                    <td>'.$arval->group_name.'</td>
                                                    <td>'.$status.'</td>
                                                    <td>'.$arval->created.'</td>
                                                    <td>'.$arval->updated.'</td>
                                                    <td align="center">
                                                    <a href="'.$pageInfo['url_module'].'edit_collector/'.$arval->id.'" data-toggle="tooltip" title="Edit '.$arval->nama.'"><i class="glyphicon glyphicon-pencil"></i></a></td>
                                                </tr>       
                                            ';
                                            $no++;
                                        }
                                     }else{
                                        echo '
                                            <tr><td colspan="'.$colspan.'" align="center"> No Records Found</td></tr>
                                        ';
                                     }
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

</div>

<script type="text/javascript">
    
    $(document).ready(function () {
        $('.dataTables-example').DataTable({
            pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    {extend: 'excel', title: 'Data Collector'},
                    {extend: 'pdf', title: 'Data Collector'}
                ]
        });

    });

</script>

</body>

</html>