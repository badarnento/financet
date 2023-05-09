<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
    	<form class="form-horizontal">
    	<div class="col-sm-12 col-md-12">
         <div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-3 control-label text-left">DPL Name <span class="pull-right">:</span></label>
            <div class="col-sm-3 col-md-3">
              <input type="text" class="form-control" id="dpl_name" placeholder="DPL Name" autocomplete="off" value="<?= $dpl_name ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-3 col-md-3 control-label text-left">Directorate <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-9">
              <label class="control-label"><?= get_directorat($directorate) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-3 col-md-3 control-label text-left">Division <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-9">
              <label class="control-label text-left"><?= get_division($divisi) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-3 col-md-3 control-label text-left">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-9">
              <label class="control-label text-left"><?= get_unit($unit) ?></label>
            </div>
          </div>
          <?php if($id_fs > 0): ?>
          <div class="form-group m-b-10">
            <label class="col-sm-3 col-md-3 control-label text-left">No eJustifikasi <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-9">
              <label class="control-label text-left"><?= get_fs($id_fs) ?> - <?= get_fs($id_fs, "FS_NAME") ?></label>
            </div>
          </div>
          <?php endif; ?>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-3 control-label text-left">Uraian <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-9">
              <textarea type="text" class="form-control" rows="5" id="uraian" placeholder="Uraian" autocomplete="off"><?= $uraian?></textarea>
            </div>
          </div>
          <div class="form-group m-b-10 status">
          	<label class="col-sm-5 col-md-3 control-label text-left">Kegiatan Pengadaan <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-9">
                    <div>
                      <div class="radio radio-info">
                          <input type="radio" id="not_active" name="active" value="0"/>
                          <label for="active">Belum Dilaksanakan</label>
                      </div>
                      <div class="radio radio-info">
                          <input type="radio" id="active" name="active" value="1"/>
                          <label for="active">Sudah dilaksanakan sebelum proses Pengadaan dilakukan oleh Procurement</label>
                      </div>
                    </div>
                  </div>
          </div>
          <div class="form-group m-b-10">
            <label for="date_from" class="col-sm-3 col-md-3 control-label text-left">Jadwal Implementasi <span class="pull-right">:</span></label>
            <div class="col-sm-3 col-md-3">
				<div class="input-group">
					<input type="text" class="form-control mydatepicker" id="date_from" placeholder="dd-mm-yyyy" value="<?= $date_from ?>">
					<span class="input-group-addon"><i class="icon-calender"></i></span>
				</div>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="date_to" class="col-sm-3 col-md-3 control-label text-left">Target Penggunaan <span class="pull-right">:</span></label>
            <div class="col-sm-3 col-md-3">
				<div class="input-group">
					<input type="text" class="form-control mydatepicker" id="date_to" placeholder="dd-mm-yyyy" value="<?= $date_to ?>">
					<span class="input-group-addon"><i class="icon-calender"></i></span>
				</div>
            </div>
          </div>
          <div class="form-group m-b-10">
          	<label class="col-sm-3 col-md-3 control-label text-left">Tujuan Pengadaan <span class="pull-right">:</span></label>
            <div id="tujuan_pengadaan" class="col-sm-6">
                <div class="col-sm-3" id="tujuan_pengadaan">

                      <?php
                        $tujuan[] = 'Revenue Generation ';
                        $tujuan[] = 'Increase Subscriber Base ';
                        $tujuan[] = 'Cost Leadership/Cost Saving/Cost Effectiveness';
                        $tujuan[] = 'Brand Awareness';
                        $tujuan[] = 'Network Capacity /Coverage';
                        $tujuan[] = 'Customer Process';
                        $tujuan[] = 'Corporate Images/ Value';
                        $tujuan[] = 'Legal/Regulatory/Compliance/Risk Management';
                        $tujuan[] = 'Skill/Professional/Training/Management/HCM';
                        $tujuan[] = 'Others: Industry Intelligence';
                      ?>
                        <?php
                          $index = 1;
                          foreach ($tujuan as $key => $value):?>
                              <div class="checkbox checkbox-info d-inline-block m-r-10 p-t-5">
                                  <input id="tujuan_pengadaan-<?= $index ?>" class="tujuan_pengadaan" value="<?= $value ?>" type="checkbox">
                                  <label for="tujuan_pengadaan-<?= $index ?>"> <?= $value ?> </label>
                              </div>
                            <?php $index++; ?>
                        <?php endforeach; ?>
                </div>
              </div>
          </div>
          <div class="form-group m-b-10">
          	<label class="col-sm-3 col-md-3 control-label text-left">Vendor <span class="pull-right">:</span></label>
            <div class="col-sm-3">
                <select class="form-control" id="vendor">
                  <option value="">-- Pilih --</option>
                </select>
            </div>
          </div>
          <div class="form-group m-b-10">
          	<label class="col-sm-3 col-md-3 control-label text-left">PIC/Jabatan <span class="pull-right">:</span></label>
            <div class="col-sm-3">
                <select class="form-control" id="pic">
                  <option value="">-- Pilih --</option>
                </select>
            </div>
            <div class="col-sm-3">
              <label class="control-label text-left" id="jabatan_sub"></label>
            </div>
          </div>
          <div class="form-group m-b-10">
          	<label class="col-sm-3 col-md-3 control-label text-left">Evaluasi Rekanan <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-9">
              <textarea type="text" class="form-control" rows="5" id="evaluasi" placeholder="Evaluasi" autocomplete="off"><?= $evaluasi?></textarea>
            </div>
          </div>
          <div class="form-group m-b-10">
          	<label class="col-sm-3 col-md-3 control-label text-left">Kriteria Penunjukan Langsung <span class="pull-right">:</span></label>
            <div class="col-sm-3 col-md-3" id="kriteriaarr">

                      <?php
                        $arr_kriteria[] = 'Penyedia/Supplier/Rekanan Tunggal';
                        $arr_kriteria[] = 'Lanjutan dari Pekerjaan sebelumnya yang tidak terelakkan';
                        $arr_kriteria[] = 'Critical (Penting & Mendesak)';
                      ?>
                        <?php
                          $index = 1;
                          foreach ($arr_kriteria as $key => $value):?>
                              <div class="checkbox checkbox-info d-inline-block m-r-10 p-t-5">
                                  <input id="kriteriaarr-<?= $index ?>" class="kriteriaarr" value="<?= $value ?>" type="checkbox">
                                  <label for="kriteriaarr-<?= $index ?>"> <?= $value ?> </label>
                              </div>
                            <?php $index++; ?>
                        <?php endforeach; ?>
                </div>
          </div>
          <div class="form-group m-b-10">
          	<label class="col-sm-3 col-md-3 control-label text-left">Alasan melakukan Penunjukan Langsung <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-9">
              <textarea type="text" class="form-control" rows="5" id="alasan" placeholder="Alasan" autocomplete="off"><?= $alasan?></textarea>
            </div>
          </div>
          <div class="form-group m-b-10">
          	<label class="col-sm-3 col-md-3 control-label text-left">Keuntungan menggunakan Penunjukan Langsung <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-9">
              <textarea type="text" class="form-control" rows="5" id="keuntungan" placeholder="Keuntungan" autocomplete="off"><?= $keuntungan?></textarea>
            </div>
          </div>
          <div class="form-group m-b-10">
          	<label class="col-sm-3 col-md-3 control-label text-left">Resiko bila tidak menggunakan Penunjukan Langsung <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-9">
              <textarea type="text" class="form-control" rows="5" id="resiko" placeholder="Resiko" autocomplete="off"><?= $resiko?></textarea>
            </div>
          </div>
          <div class="form-group m-b-0">
            <div class="pull-right"><button type="button" id="save_data" class="btn btn-info m-10 w-100p"><i class="fa fa-save"></i> Save </button>
            </div>
          </div>
        </div>
    	</form>
    </div>
</div>
</div>

<script>
  $(document).ready(function(){

	const id_dpl       = '<?= $id_dpl ?>';
	const opt_default = '<option value="0" data-name="">-- Choose --</option>';

	const directorat  = <?= $directorate ?>;
	const division    = <?= $divisi ?>;
	const unit        = <?= $unit ?>;
	const submitter   = '<?= $pic ?>';
  const vendor      = '<?= $vendor ?>';
  const kegiatan    = '<?= $kegiatan ?>';

	getSubmitter();
  getVendorName();

    $('#date_from, #date_to').datepicker({
		format: 'dd-mm-yyyy',
		todayHighlight:'TRUE',
		autoclose: true,
    });

    $("#pic").on("change", function(){
		getJabatan();
    });

    function getSubmitter(){

	    $.ajax({
	        url   : baseURL + 'feasibility-study/api/load_data_submitter',
	        type  : "POST",
	        data  : {directorat: directorat, division: division, unit: unit},
	        dataType: "json",
	        success : function(result){      
        		let submitter_opt = '';
	        	if(result.status == true){
					data = result.data;
					if(data.length == 1){
						for(var i = 0; i < data.length; i++) {
						    obj = data[i];
						    // submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
						    selected = (submitter === obj.nama) ? ' selected': '';
						    submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'" '+ selected +'>'+ obj.nama +'</option>';
						}
					}else{
						submitter_opt = opt_default;
						for(var i = 0; i < data.length; i++) {
						    obj = data[i];
						    // submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
						    selected = (submitter === obj.nama) ? ' selected': '';
						    submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'" '+ selected +'>'+ obj.nama +'</option>';
						}
					}
		        	
	        	}
				$("#pic").html(submitter_opt);				
				getJabatan("pic");
	        }
	    });
	}

	function getJabatan(category){
			valJabtan = $("#pic").find(':selected').attr('data-jabatan');
			$("#jabatan_sub").html(valJabtan);
	}

  $("#save_data").on('click', function () {
    
    let dpl_name            = $("#dpl_name").val();
    let uraian              = $("#uraian").val();
    var kegiatan_pengadaan  = $("input[name='active']:checked").val();
    let date_from           = $("#date_from").val();
    let date_to             = $("#date_to").val();
    let vendor              = $("#vendor").val();
    let pic        	        = $("#pic").val();
    let jabatan_sub         = $("#jabatan_sub").html();
    let evaluasi   	        = $("#evaluasi").val();
    let kriteria   	        = $("#kriteria").val();
    let alasan   	          = $("#alasan").val();
    let keuntungan          = $("#keuntungan").val();
    let resiko    	        = $("#resiko").val();

        data = {
        		id_dpl : id_dpl,
                dpl_name : dpl_name,
                uraian : uraian,
                kegiatan_pengadaan : kegiatan_pengadaan,
                date_from : date_from,
                date_to : date_to,
                vendor : vendor,
                pic : pic,
                jabatan_sub : jabatan_sub,
                evaluasi : evaluasi,
                kriteria : kriteria,
                alasan : alasan,
                keuntungan : keuntungan,
                resiko : resiko
            }

        $.ajax({
            url   : baseURL + 'dpl/api/save_dpl_edit',
            type  : "POST",
            data  : data,
            dataType: "json",
            beforeSend  : function(){
                          // customLoading('show');
                        },
            success : function(result){
              console.log(result)
              if(result.status == true){
                customNotif('Success', "DPL Updated", 'success');
                setTimeout(function(){
                  customLoading('hide');
                  $(location).attr('href', baseURL + 'dpl/dpl');
                }, 500);
              }
              else{
                customLoading('hide');
                customNotif('Error', result.messages, 'error');
              }
            }
        });
    });

  function getVendorName(){

      $.ajax({
          url   : baseURL + 'dpl/api/load_vendor_edit',
          type  : "POST",
          data  : {},
          dataType: "json",
          success : function(result){      
            let vendor_opt = '';
            if(result.status == true){
          data = result.data;
          if(data.length == 1){
            for(var i = 0; i < data.length; i++) {
                obj = data[i];
                selected = (vendor === obj.vendor_name) ? ' selected': '';
                vendor_opt += '<option value="'+ obj.vendor_name +'" '+ selected +'>'+ obj.vendor_name +'</option>';
            }
          }else{
            vendor_opt = opt_default;
            for(var i = 0; i < data.length; i++) {
                obj = data[i];
                selected = (vendor === obj.vendor_name) ? ' selected': '';
                vendor_opt += '<option value="'+ obj.vendor_name +'" '+ selected +'>'+ obj.vendor_name +'</option>';
            }
          }
              
            }
        $("#vendor").html(vendor_opt);
          }
      });
  }

    if (kegiatan == '1') {
        $("#active").prop("checked", true );
    }
    else{
        $("#not_active").prop("checked", true );
    }

  });
</script>