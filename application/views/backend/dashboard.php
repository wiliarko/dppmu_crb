<?php echo $this->load->view('backend/head'); ?>

<div id="wrapper">
    
    <?php echo $this->load->view('backend/nav-left'); ?>

    <div id="page-wrapper" class="gray-bg">
        
        <?php echo $this->load->view('backend/header'); ?>

        <div class="wrapper wrapper-content">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Chart Revenue</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <canvas id="lineChart" height="140"></canvas>
                                </div>
                            </div>
                        </div>
            
                    </div>
                </div>
            </div> -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Kalkulasi Status Bayar</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label class="control-label" for="bulan">Bulan</label>
                                    <div class="form-group">
                                        <select class="select2 form-control" name="bulan" id="bulan">
                                            <?php
                                                $months = array("Pilih Bulan","Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                                                foreach ($months as $key => $month) {
                                                    echo "<option value=\"" . $key . "\">" . $month . "</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label" for="tahun">Tahun</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" placeholder="Contoh : <?= date("Y") ?>" id="tahun"/>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <?php $isCollector = !in_array($this->session->userdata['logged']['groupid'], array(2)) ? "" : "disabled"; ?>
										<input type="hidden" id="_collector_id" value="<?= $collector ?>">
										<input type="hidden" id="_collectorname" value="<?= $collectorname ?>">
                                        <input type="hidden" id="isCollector" value="<?= $isCollector ?>">
                                        <label class="control-label" for="collector">Nama Collector</label>
                                        <!-- <select class="select2 form-control" name="collector" id="selcollector"> -->
                                        <select data-placeholder="Pilih Collector..." class="chosen-select form-control" multiple tabindex="4" name="collector" id="selcollector">
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="button" id="search_kalkulasi" class="btn btn-primary" value=" Search " style="margin-top: 20px" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div>
                                        <table class="table">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_01" data-status="janji_bayar"><span id="status_01">0</span></button>
                                                    Janji Bayar
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_02" data-status="percepatan"><span id="status_02">0</span></button>
                                                   Percepatan
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_03" data-status="sisa_titipan"><span id="status_03">0</span></button>
                                                    Sisa Titipan
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_04" data-status="tarik_barang"><span id="status_04">0</span></button>
                                                    Tarik Barang
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_05" data-status="tidak_ada_orang"><span id="status_05">0</span></button>
                                                    Tidak Ada Orang
                                                </td>
                                            </tr>
                                            <tr>
                                                
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_06" data-status="alasan_kesehatan"><span id="status_06">0</span></button>
                                                    Alasan Kesehatan
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_07" data-status="alasan_ekonomi"><span id="status_07">0</span></button>
                                                    Alasan Ekonomi
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_08" data-status="komplain_produk"><span id="status_08">0</span></button>
                                                    Komplain Produk
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_09" data-status="rencana_tarik_Barang"><span id="status_09">0</span></button>
                                                    Rencana Tarik Barang
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_10" data-status="pindah_alamat"><span id="status_10">0</span></button>
                                                    Pindah Alamat
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_11" data-status="tidak_ada_barang"><span id="status_11">0</span></button>
                                                    Tidak Ada Barang + Karakter
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_12" data-status="ada_barang"><span id="status_12">0</span></button>
                                                    Ada Barang + Karakter
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_13" data-status="bangkrut"><span id="status_13">0</span></button>
                                                    Bangkrut / Pailit
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_14" data-status="kabur"><span id="status_14">0</span></button>
                                                    Kabur / Hilang
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default m-r-sm status_15" data-status="force_mejeur"><span id="status_15">0</span></button>
                                                    Force Mejeur
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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

        <!-- ChartJS -->
        <script src="<?php echo base_url();?>assets/js/plugins/chartJs/Chart.min.js"></script>

		<!-- jqGrid -->
	    <script src="<?php echo base_url();?>assets/js/plugins/jqGrid/i18n/grid.locale-en.js"></script>
    	<script src="<?php echo base_url();?>assets/js/plugins/jqGrid/jquery.jqGrid.min.js"></script>

        <!-- jQuery UI -->
        <script src="<?php echo base_url();?>assets/js/plugins/jquery-ui/jquery-ui.min.js"></script> 

        <!-- Count To -->
        <script src="<?php echo base_url();?>assets/js/jqueryCountTo.js"></script>

        <script>
            $(document).ready(function() {
          
                $("body").addClass('mini-navbar');
                SmoothlyMenu();

                status_bayar();

                $("#search_kalkulasi").click(function(e) {
                    var bulan = $("#bulan").val();
                    var tahun = $("#tahun").val();
                    
                    if(bulan !== '' && tahun !== "")
                    {
                        status_bayar();
                    }else{
                        toastr.warning("Filter bulan atau tahun tidak boleh kosong!", 'WARNING...');
                    }

                });

                var _selcollector = $("#selcollector");
                var _collector_id = $("#_collector_id").val();
                var _collectorname = $("#_collectorname").val();
                var isCollector = $("#isCollector").val();

                _selcollector.select2({
                    placeholder: 'Pilih Collector',
                    width: "100%",
                    allowClear: true,
                    delay: 250,
                    ajax: {
                        url: "<?php echo site_url('backend/transaksi/get_collector'); ?>",
                        dataType: 'json',
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });

                // set collector
                if(_collector_id){
                	_selcollector.empty().append('<option selected value="'+_collector_id+'">'+_collectorname+'</option>');
	                _selcollector.select2('data', {
	                  id: _collector_id,
	                  text: _collectorname
	                });
                }
                
                _selcollector.trigger('change');

                if(isCollector=='disabled'){
                    _selcollector.prop('disabled', !$('#one').prop('disabled'));
                }

                $.ajax({url: "<?php echo site_url('backend/dashboard/get_jml_jb'); ?>", success: function(result){
                    $("#jml_jb").countTo(result);
                }});

                $.ajax({url: "<?php echo site_url('backend/dashboard/get_jml_bayar'); ?>", success: function(result){
                    $("#jml_bayar").countTo(result);
                }});

                $.ajax({url: "<?php echo site_url('backend/dashboard/get_jml_blm_bayar'); ?>", success: function(result){
                    $("#jml_blm_bayar").countTo(result);
                }});

                $.ajax({url: "<?php echo site_url('backend/dashboard/get_revenue'); ?>", success: function(result){
                    $("#jml_revenue").countTo(result);
                }});

                /*var lineData = {
			        labels: [
			        <?php
		            $arr = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
					
					for($i=5; $i > 0; $i-=1){
						$len = $i-1;
						$m = date('n', strtotime('-'.$i.' months'));
						echo '"'.$arr[$m].'",';
						if ($len == 0) {
							echo '"'.$arr[date('n')].'"';
						}
					}
					
			    	?>
			        ],
			        datasets: [
			            {
			                label: "Revenue",
			                backgroundColor: 'rgba(26,179,148,0.5)',
			                borderColor: "rgba(26,179,148,0.7)",
			                pointBackgroundColor: "rgba(26,179,148,1)",
			                pointBorderColor: "#fff",
			                data: [
			                <?php
			                for($i=5; $i > 0; $i-=1){
								$len = $i-1;
								$yearMonth = date('Y-m', strtotime('-'.$i.' months'));
								$result = $this->dashboard_model->get_chart_revenue($yearMonth);
								echo $result->row()->revenue;
								echo ',';
								
								if ($len == 0) {
									$result = $this->dashboard_model->get_chart_revenue(date('Y-m'));
									echo $result->row()->revenue;
								}
							}

			                ?>
			                ]
			            }
			        ]
			    };*/

			    // var lineOptions = {
			    //     responsive: true
			    // };


			    /*var ctx = document.getElementById("lineChart").getContext("2d");
			    new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});*/

	            $("#table_list_kasir").jqGrid({
	            	url: "<?php echo site_url('backend/dashboard/kasir_event'); ?>",
	                datatype: "json",
	                mtype: "GET",
	                height: "auto",
	                autowidth: true,
	                shrinkToFit: true,
	                rowNum: 20,
	                rowList: [10, 20, 30],
	                colNames:['No', 'No Faktur', 'Konsumen', 'Collector', 'Angsuran', 'Angke', 'Kasir', 'Last Time'],
	                colModel:[
	                    {name:'id', index:'id', align:"center", width:40, sorttype:"int", search:true},
	                    {name:'nofakt', index:'nofakt', width:80, search:true},
	                    {name:'namakons', index:'namakons', width:100, search:true},
	                    {name:'collector', index:'collector', width:100, search:true},
	                    {name:'angsuran', index:'angsuran', align:"right", width:80, sorttype:"float", formatter:"number", search:true},
                        {name:'angke', index:'angke', align:"center", width:50, sorttype:"int",search:true},
	                    {name:'kasir', index:'kasir', align:"left", width:100,search:true},
	                    {name:'created_date', index:'created_date', width:90, sorttype:"date", formatter:"date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "d-m-Y H:i:s"},search:true}
	                ],
	                pager: "#pager_list_kasir",
	                viewrecords: true,
	                caption: "Log Event Kasir (100 record, log terakhir)",
	                hidegrid: false
	            });

	            // Setup buttons
	            $("#table_list_kasir").jqGrid('navGrid', '#pager_list_kasir',
                    {edit: false, add: false, del: false, search: true},
                    {height: 200, reloadAfterSubmit: false}
	            );

	            // Add responsive to jqGrid
	            $(window).bind('resize', function () {
	                var width = $('.jqGrid_wrapper').width();
	                $('#table_list_kasir').setGridWidth(width);
	            });


	            setTimeout(function(){
	                $('.wrapper-content').removeClass('animated fadeInRight');
	            },700);

             });

            $("#bulan").select2({
              tags: true
            });

            function status_bayar()
            {
                var i, status_bayar;
                for (i = 1; i <= 15; i++) {
                    status_bayar = pad(i);
                    checkStatusBayar(status_bayar);
                }
            }

            function checkStatusBayar(status_bayar) {
                var bulan = $("#bulan").val();
                var tahun = $("#tahun").val();
                var collector = $("#selcollector").val();                

                $.ajax({
                    url: "<?php echo site_url('backend/dashboard/get_status_bayar'); ?>",
                    method:"POST",
                    data:{
                    bulan: bulan, tahun: tahun, collector:collector, status_bayar:status_bayar
                    },
                    success: function(val){
                        $("#status_"+status_bayar).countTo(val);

                        if(val>0){
                            $(".status_"+status_bayar).removeClass("btn-default");
                            $(".status_"+status_bayar).addClass( "btn-success");
                            $(".status_"+status_bayar).attr("onclick", "gotoreport('"+status_bayar+"')");
                        }
                        else{
                            $(".status_"+status_bayar).removeClass("btn-success");
                            $(".status_"+status_bayar).addClass("btn-default");
                            $(".status_"+status_bayar).removeAttr("onclick");
                        }
                    }
                });
            }

            function pad(d) {
                return (d < 10) ? '0' + d.toString() : d.toString();
            }

            function gotoreport(index)
            {
                var status = $(".status_"+index).data("status");
                var bulan = $("#bulan").val();
                var tahun = $("#tahun").val();
                var collector = $("#selcollector").val();
                if(collector==null){
                    collector = "";
                }
                window.open(
                  base_urls + "backend/laporan/r/" + status + "?collector=" + collector + "&bulan=" + bulan + "&tahun=" + tahun,
                  '_blank' // <- This is what makes it open in a new window.
                );
            }

        </script>
    </div>
</div>

</body>

</html>