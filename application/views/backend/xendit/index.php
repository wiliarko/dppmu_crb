<?php echo $this->load->view('backend/head'); ?>

<div id="wrapper">
    
    <?php echo $this->load->view('backend/nav-left'); ?>

    <div id="page-wrapper" class="gray-bg">
        
        <?php echo $this->load->view('backend/header'); ?>
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Transaksi Xendit</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="control-label" for="collector">Tanggal Bayar</label>
                                    <div class="input-daterange input-group" id="datepicker">
                                        <input type="text" class="input-sm form-control" name="tanggal_awal" id="tanggal_awal" value="" />
                                        <span class="input-group-addon">to</span>
                                        <input type="text" class="input-sm form-control" name="tanggal_akhir" id="tanggal_akhir" value=""/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="button" id="search_payment" class="btn btn-primary" value=" Search " style="margin-top: 20px"/>
                                    </div>
                                </div>
                                <div class="col-md-1 float-right">
                                    <div class="form-group">
                                        <input type="button" id="reload_data" class="btn btn-danger" value="Reload data" style="margin-top: 20px"/>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row"> -->
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover"  id="tbl_payment" >
                                        <thead>
                                            <tr class="">
                                                <th style="width:50px">Status</th>
                                                <th style="width:50px">Outlet</th>
                                                <th style="width:150px">Kode Pembayaran</th>
                                                <th>Nama</th>
                                                <th>Jumlah</th>
                                                <th>Referensi</th>
                                                <th>Tanggal Bayar</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            <!-- </div> -->
                        </div>

                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-lg-12">
                    <div class="jqGrid_wrapper">
                        <table id="table_list_kasir"></table>
                        <div id="pager_list_kasir"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php echo $this->load->view('backend/footer'); ?>

        <script>
            $(document).ready(function() {
                load_table_payment();
                $('.input-daterange').datepicker({
                    format: 'yyyy-mm-dd',
                    keyboardNavigation: false,
                    forceParse: false,
                    autoclose: true
                });
                $('#search_payment').on('click', function(e){
                    load_table_payment();
                    $('#tanggal_awal').val('');
                    $('#tanggal_akhir').val('');
                });
                $('#reload_data').on('click', function(e){
                    $.ajax({
                        url:"<?= site_url('backend/xendit_payment/payment') ?>",
                        dataType:"json",
                        success:function(result){
                            toastr.success(result.msg, 'SUCCESS...');
                            load_table_payment();
                        }
                    });
                });
             });

            function load_table_payment()
  	        {
                var table = $('#tbl_payment');
                table.dataTable().fnClearTable();
                table.dataTable().fnDestroy();

                table.DataTable({
                    "pageLength"    : 25,
                    "responsive"    : true,
                    "dom"           : '<"html5buttons"B>lTfgitp',
                    "buttons"       : ['excel'],
                    "serverSide"    : true,
                    "order"         : [[ 6, "desc" ]],
                    "ajax"		    : {
                        "url"	: "<?= site_url('backend/xendit_payment/payment_list') ?>",
                        "type"	: "GET",
                        "data"	: function ( d ) {
                            d.tgl_awal= $('#tanggal_awal').val();
                            d.tgl_akhir = $('#tanggal_akhir').val();
                        }
                    },
                    "columns"     : [
                        {data: 'status'},
                        {data: 'retail_outlet_name'},  
                        {data: 'payment_code'},  
                        {data: 'name'},
                        {data: 'amount'},
                        {data: 'external_id'},
                        {data: 'transaction_timestamp'}
                        
                    ]
                });
            }
            
            




        </script>
    </div>
</div>

</body>

</html>