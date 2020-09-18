<?php echo $this->load->view('backend/head'); ?>

<div id="wrapper">
    <input type="hidden" id="groupid" value="<?= $this->session->userdata['logged']['groupid'] ?>">
	
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
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
							<?php
                            $row = $result->row();
                            ?>

                            <div class="row">
                                <div class="col-md-12">
                                	<form action="#" method="post" id="FormEdit">
                                        <div class="form-group">
                                            <label>Nama Konsumen</label>
                                            <input type="text" class="form-control" value="<?= $row->nama ?>" disabled/>
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat Rumah / TT</label>
                                            <input type="text" class="form-control" name="alamatkons" value="<?= $row->alamatkons ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>Pekerjaan</label>
                                            <input type="text" class="form-control" name="NAMAKOF" value="<?= $row->NAMAKOF ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat Pek/Usaha</label>
                                            <input type="text" class="form-control" name="ALAMATOF" value="<?= $row->ALAMATOF ?>"/>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>HP 1</label>
                                            <input type="text" class="form-control" name="telephone" value="<?= $row->telephone ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>HP 2</label>
                                            <input type="text" class="form-control" name="telephone2" value="<?= $row->telephone2 ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>HP 3</label>
                                            <input type="text" class="form-control" name="telephone3" value="<?= $row->telephone3 ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>BRIva</label>
                                            <input type="text" class="form-control" name="BRIva" value="<?= $row->BRIva ?>"/>
                                        </div>
                                        
                                        <div class="form-group">
                                            <button type="button" id="btn_update" class="btn btn-success"><i class="fa fa-upload"></i> <span>Update Konsumen</span></button>
                                        </div>
                                   	</form>
                                </div>
                            </div>
                            
                            <hr class="hr-line-solid"/>

                            <form role="form" action="<?php echo $pageInfo['url_module']?>create_deskcall" method="post" name="frmInput" id="frmInput" enctype="multipart/form-data" >

                                <div class="row form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">No Faktur</label>
                                        <div class="col-sm-5">
                                            <select class="select2 form-control form-control-lg" id="selnofakt" name="nofakt" required>
                                            
                                            </select>    
                                        </div>
                                    </div>
                                    
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Notes</label>
                                        <div class="col-sm-4">
                                            <select class="select2 form-control" name="notes" id="notes">
                                                <option></option>
                                                <option value='sms'>SMS</option>
                                                <option value='WA'>WA</option>
                                                <option value='Telp'>Telp</option>
                                                <option value='Medsos'>Medsos</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Date</label>
                                        <div class="col-sm-2">
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="<?= date("d/m/Y") ?>" name="date">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="hr-line-dashed"></div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Keterangan</label>
                                        <div class="col-sm-7">
                                            <textarea class="form-control rounded-0" name="catatan" rows="4" required></textarea>
                                            <span class="help-block m-b-none">Keterangan hasil dari pembicaraan via telepon.</span>  
                                        </div>
                                    </div>

                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <input type="hidden" name="kdkus" id="kdkus" value="<?= $row->kdcust ?>">
                                            <input type="hidden" name="id" value="<?= $row->id ?>">
                                            <button class="btn btn-white" onclick="location.replace('<?php echo $pageInfo['url_module'];?>')" type="button">Cancel</button>
                                            <button class="btn btn-primary" type="submit">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <hr class="hr-line-solid"/>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" id="_gettablelist">
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="fInput" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-dialog">
                <div class="modal-content animated fadeIn">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h2 class="modal-title">Form <?php echo ($via == 'dpk') ? "DPK" : "Kasir" ?></h2>
                    </div>
                    
                    <div class="sk-spinner sk-spinner-wave loader" style="display: none; height: 45px;">
                        <div class="sk-rect1"></div>
                        <div class="sk-rect2"></div>
                        <div class="sk-rect3"></div>
                        <div class="sk-rect4"></div>
                        <div class="sk-rect5"></div>
                    </div>

                    <form id="form_insert" class="form-horizontal">
                        <input type="hidden" name="dpk_id" id="dpk_id">
                        <div class="modal-body">
                            <div class="box-body">
                                
                                <div class="row" style="margin-right:10px; margin-left: 10px">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">No Faktur</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="nofakt" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tanggal Jatuh Tempo</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="dddate" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Bulan/Tahun JT</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="mydate" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Nama Konsumen</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="namakons" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Nomer Telp / HP</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="tlpn" name="tlpn">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Angsuran</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="angsuran_rp" disabled>
                                            <input type="hidden" class="form-control" id="angsuran" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group payOthers" id="bayarSisa" style="display: none">
                                        <label class="col-sm-4 control-label"><span class="text-danger">Sisa Bayar</span></label>
                                        <div class="col-sm-8">
                                            <div id="bayarSisaSpanLast" class="text-danger" style="margin-top: -15px; position: absolute;"></div>
                                            <input type="hidden" class="form-control" id="bayarSisaValLast">
                                            <div id="bayarSisaSpan" class="text-danger" style="margin-top: 7px;"></div>
                                            <input type="hidden" class="form-control" id="bayarSisaVal" name="bayarSisa">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tenor</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="tenor" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Angsuran Ke</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="angke" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Bayar</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="bayar" disabled style="display: none">
                                            <select class="form-control" style="width: 100%" id="isPay" name="isPay">
                                                <option value="" selected="">Pilih status</option>
                                                
                                                <?php if($this->session->userdata['logged']['groupid'] <> 4): ?>
                                                <option value="99">Bayar</option>
                                                <option value="00">Bayar Sebagian</option>
                                                <?php endif; ?>
                                                
                                                <option disabled="">---</option>

                                                <?php if($via == 'dpk' && $this->session->userdata['logged']['groupid'] <> 6): ?>
                                                <option value="01">Janji Bayar</option>
                                                <option value="02">Percepatan</option>
                                                <option value="03">Sisa Titipan</option>
                                                <option value="04">Tarik Barang</option>
                                                <option value="05">Tidak Ada Orang</option>
                                                <option value="06">Alasan Kesehatan</option>
                                                <option value="07">Alasan Ekonomi</option>
                                                <option value="08">Komplain Produk</option>
                                                <option value="09">Rencana Tarik Barang</option>
                                                <option value="10">Pindah Alamat</option>
                                                <option value="11">Tidak Ada Barang + Karakter</option>
                                                <option value="12">Ada Barang + Karakter</option>
                                                <option value="13">Bangkrut / Pailit</option>
                                                <option value="14">Kabur / Hilang</option>
                                                <option value="15">Force Mejeur</option>
                                                <?php endif; ?>

                                            </select>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group payOthers" id="janjiBayar" style="display: none">
                                        <label class="col-sm-4 control-label">Tanggal</label>
                                        <div class="col-sm-8">
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="date" class="form-control" name="nextDate">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group payOthers" id="bayarSebagian" style="display: none">
                                        <label class="col-sm-4 control-label">Bayar Sebagian</label>
                                        <div class="col-sm-8">
                                            <div id="bayarSebagianValLast" class="text-danger m-b-sm"></div>
                                            <input type="text" class="form-control" placeholder="" id="bayarSebagianVal">
                                            <input type="hidden" class="form-control" placeholder="" id="bayarSebagianValTmp" name="bayarSebagian">
                                            <span class="help-block">masukan jumlah yang dibayarkan sebagian</span>
                                        </div>
                                    </div>

                                    <div class="form-group payOthers" id="bayarLainnya" style="display: none">
                                        <label class="col-sm-4 control-label">Keterangan</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" rows="4" name="bayarLainnya" id="bayarLainnyaVal"></textarea>
                                            <span class="help-block" id="ketNote">masukan catatan</span>
                                        </div>
                                    </div>

                                    <div class="form-group" id="updateBy" style="display: none">
                                        <label class="col-sm-4 control-label">Admin</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" placeholder="" id="updateByVal" disabled="">
                                        </div>
                                    </div>

                                    <div class="form-group" id="publishBy" style="display: none">
                                        <label class="col-sm-4 control-label">Kasir</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" placeholder="" id="publishByVal" disabled="">
                                        </div>
                                    </div>

                                    <div class="form-group" id="updateAt" style="display: none">
                                        <label class="col-sm-4 control-label">Pada Tgl</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" placeholder="" id="updateAtVal" disabled="">
                                        </div>
                                    </div>

                                    <div class="form-group" id="updateVia" style="display: none">
                                        <label class="col-sm-4 control-label">Melalui Menu</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" placeholder="" id="updateViaVal" disabled="">
                                        </div>
                                    </div>

                                </div>                          
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button id="bt_cancel" type="button" class="btn  btn-sm default" ><i class="icon-action-undo"></i> Batal </button>
                            <button id="bt_save" type="button" class="btn  btn-sm blue"><i class="fa fa-send"></i> Simpan </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php echo $this->load->view('backend/footer'); ?>

    </div>

</div>

<script type="text/javascript">
    
    $(document).ready(function () {
        $("body").addClass('mini-navbar');
        SmoothlyMenu();

        $("#notes").select2({
            allowClear: true,
            placeholder: 'Pilih Notes'
        });

        $('.input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            format: "dd/mm/yyyy"
        });

        var kdkus = $("#kdkus").val();
        $("#frmInput #selnofakt").select2({
            placeholder: 'Pilih No Faktur',
            width: "100%",
            allowClear: true,
            ajax: {
                url: "<?php echo site_url('backend/deskcall/get_nofak_cus'); ?>/" + kdkus,
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        var gettablelist = $('#_gettablelist').DataTable( {
            paging: false
        } );
         
        $('#selnofakt').change(function(){
            var nofakt = $(this).val();

            if(nofakt !== '')
            {
                gettablelist.destroy();
         
                gettablelist = $('#_gettablelist').DataTable( {
                    pageLength: 25,
                    responsive: false,
                    dom: '<"html5buttons"B>lTfgitp',
                    serverSide: true,
                    ajax:{
                        url : "<?= base_url() ?>backend/transaksi/gettablelist",
                        type: "get",
                        data: { nofakt: nofakt },
                        error: function(){
                            $("#_gettablelist_processing").css("display","none");
                        }
                      },
                    buttons: [ ]
                });
            }
        });

        var form = $('#FormEdit');
        $("#btn_update").click(function(e){
        	var conf = confirm('Anda yakin akan merubah data konsumen ini ?');
          	if(conf){
	        	$.ajax({
	        		url: "<?php echo site_url('backend/deskcall/update_kons'); ?>/" + kdkus,
		            type: 'POST',
		            data: form.serialize(),
		            dataType: 'json',
		            success: function(msg) {
		               if(msg.status == 'success'){
							toastr.success('', msg.status);
							location.reload(); 
		               }else{
		               		toastr.error('Error', msg.msg);
		               }
		            }
		      	});
		    }
        });

        $('#bt_cancel').click(function() {
            $("#fInput").modal("hide");
        });

        $('#bt_save').click(function() {
            dpk_update();
        });
    });

    function viewUpdate(id) {
        
        $('#fInput').modal('show');
        $('#dpk_id').val(id);
        var groupid = $('#groupid').val();

        $("#form_insert").hide();
        $(".loader").show();

        $.ajax({
            url: "<?php echo site_url('backend/transaksi/get_dpk_trx'); ?>",
            type: 'POST',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(msg) {
                if(msg.status == 'success'){
                    var dt = msg.data;

                    $('#nofakt').val(dt.nofakt);
                    $('#dddate').val(dt.dddate);
                    $('#mydate').val(dt.mydate);
                    $('#namakons').val(dt.namakons);
                    $('#tlpn').val(dt.tlpn);
                    $('#angsuran_rp').val(dt.angsuran_rp);
                    $('#angsuran').val(dt.angsuran);
                    $('#tenor').val(dt.tenor);
                    $('#angke').val(dt.angke);
                    $("#isPay").val("");

                    if((dt.no_dpk && dt.kode_bayar == '99') && groupid !== '6'){
                        $("#bayar").show();
                        $("#updateBy").show();
                        $("#publishBy").show();
                        $("#bayarLainnya").show();
                        $("#updateAt").show();
                        $("#updateVia").show();
                        $("#isPay").hide();
                        $("#ketNote").hide();

                        $("#bayarLainnyaVal").attr("disabled", true);

                        $("#bayar").val(dt.bayar);                        
                        $("#bayarLainnyaVal").val(dt.keterangan);
                        $("#updateByVal").val(dt.update_by);
                        $("#publishByVal").val(dt.publish_by);
                        $("#updateAtVal").val(dt.update_at);
                        $("#updateViaVal").val(dt.update_via);

                        $('#bt_save').prop('disabled', true);
                    }else{
                        $("#bayar").hide();
                        $("#updateBy").hide();
                        $("#publishBy").hide();
                        $("#bayarLainnya").hide();
                        $("#updateAt").hide();
                        $("#updateVia").hide();
                        $("#isPay").show();
                        $("#ketNote").show();

                        $("#isPay").val(dt.kode_bayar);

                        switch(dt.kode_bayar) {
                            case '00':
                                $("#bayarSebagian").show();
                                $("#bayarSisa").show();
                                $("#bayarSebagianValLast").html("Pembayaran sebelumnya : "+ formatCurrency(dt.bayar_sebagian));
                                $("#bayarSebagianVal").val("");
                                $("#bayarSebagianValTmp").val(dt.bayar_sebagian);
                                $("#bayarSisaSpan").html(formatCurrency(dt.bayar_sisa));
                                $("#bayarSisaSpanLast").html("Sisa pembayaran sebelumnya : " + formatCurrency(dt.bayar_sisa));
                                $("#bayarSisaVal").val(dt.bayar_sisa);
                                $("#bayarSisaValLast").val(dt.bayar_sisa);
                                break;
                        }

                        $("#bayarLainnyaVal").attr("disabled", false);
                        $("#bayarLainnyaVal").val("");
                        $('#bt_save').prop('disabled', false);
                    }
                    
                    $("#form_insert").show();
                    $(".loader").hide();
                }else{
                    toastr.error('Error', msg.msg);
                }
            }
        });
    }

    function dpk_update(){

        if($("#isPay").val() !== ""){
            if(confirm('Anda yakin akan mengeksekusi form ini?')){
                $("#form_insert").hide();
                $(".loader").show();

                $.ajax({
                    url: "<?php echo site_url('backend/transaksi/dpk_process/'. $via); ?>",
                    type:"POST",
                    data:$("#form_insert").serialize(),
                    dataType:"json",
                    success:function(data){
                        if(data.status != "error"){
                            toastr.success('Data Berhasil disimpan', 'Form DPK!')
                            $('#form_insert').modal('hide');
                            location.reload();                
                        }else{
                            toastr.error(data.msg, 'Form DPK');
                            
                            $("#form_insert").show();
                            $(".loader").fadeOut('slow').hide();
                        }
                    }
                });
            }
        }else{
            toastr.error("Form tidak lengkap", 'Form DPK');
        }
    }

</script>

</body>

</html>