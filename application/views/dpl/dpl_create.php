<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Dokumen Penunjukan Langsung</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
    <form class="form-horizontal">
      <div class="row">
         <div class="col-sm-12">
        <div class="form-group m-b-10">
              <label for="directorat" class="col-sm-3 control-label text-left">Directorate <span class="pull-right">:</span></label>
              <div class="col-sm-9 col-md-3">
                <select class="form-control input-sm" id="directorat">
                  <?php
                    $id_directorat   = $this->session->userdata('id_dir_code');
                    $directorat_name = get_directorat($id_directorat);

                    if($id_directorat > 0 && $binding == true):
                      echo '<option value="'.$id_directorat.'" data-name="'.$directorat_name.'">'.$directorat_name.'</option>';
                    else:

                      $opt_dir = '<option value="0">-- Choose --</option>';

                      $last_dir = "";

                      foreach($directorat as $value):

                    $id_dir_code = $value['ID_DIR_CODE'];
                    $dir_name  = $value['DIRECTORAT_NAME'];

                    if($dir_name != $last_dir):

                            $opt_dir .= '<option value="'.$id_dir_code.'" data-name="'.$dir_name.'">'.$dir_name.'</option>';
                          endif;
                          $last_dir = $dir_name;

                        endforeach;

                        echo $opt_dir;

                      endif;

                  ?>
                </select>
              </div>
        </div>
        <div class="form-group m-b-10">
              <label for="division" class="col-sm-3 control-label text-left">Division <span class="pull-right">:</span></label>
              <div class="col-sm-9 col-md-3">
                <select class="form-control input-sm" id="division">
                <?php
                  if($binding == false || $data_binding['division'] == false):
                    echo '<option value="0">-- Choose --</option>';
                  else:
                  
                    foreach($data_binding['division'] as $value):
                      $replace = str_replace("&","|AND|", $value['DIVISION_NAME']);
                ?>
                    <option value="<?= $value['ID_DIVISION'] ?>" data-name="<?= $value['DIVISION_NAME'] ?>"><?= $value['DIVISION_NAME'] ?></option>
                <?php
                    endforeach; 
                  endif;
                ?>
                </select>
              </div>
          </div>
        <div class="form-group m-b-10">
              <label for="unit" class="col-sm-3 control-label text-left">Unit <span class="pull-right">:</span></label>
              <div class="col-sm-9 col-md-3">
                <select class="form-control input-sm" id="unit">
                  <?php
                    if($binding == false || $data_binding['unit'] == false):
                      echo '<option value="0">-- Choose --</option>';
                    else:
                    
                      foreach($data_binding['unit'] as $value):
                        $replace = str_replace("&","|AND|", $value['UNIT_NAME']);
                  ?>
                      <option value="<?= $value['ID_UNIT'] ?>" data-unt="<?= $replace ?>" data-name="<?= $value['UNIT_NAME'] ?>"><?= $value['UNIT_NAME'] ?></option>
                  <?php
                      endforeach; 
                    endif;
                  ?>
                </select>
              </div>
          </div>
          <div class="form-group m-b-10">
                <label for="fs_header" class="col-sm-3 control-label text-left">No eJustifikasi <span class="pull-right">:</span></label>
                <div class="col-sm-9 col-md-3">
                <select class="form-control input-sm" id="fs_header">
                  <option value="0">-- Choose --</option>
                </select>
              </div>
          </div>
          <div class="form-group m-b-10">
                <label for="pic" class="col-sm-3 control-label text-left">PIC <span class="pull-right">:</span></label>
                <div class="col-sm-3">
                <select class="form-control input-sm" id="pic">
                  <option value="0">-- Choose --</option>
                </select>
              </div>
              <div class="col-sm-4">
                <label class="control-label text-left" id="jabatan_sub"></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label for="uraian" class="col-sm-3 control-label text-left">Uraian Pekerjaan<span class="pull-right">:</span></label>
            <div class="col-sm-6">
              <textarea class="form-control input-sm" id="uraian" rows="6"></textarea>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="kegiatan_pengadaan" class="col-sm-3 control-label text-left">Kegiatan Pengadaan <span class="pull-right">:</span></label>
            <div class="col-sm-9">
              <div class="radio radio-info">
                <input type="radio" id="active" name="kegiatan_pengadaan" value="0"/>
                <label for="active">Belum Dilaksanakan</label>
              </div>
              <div class="radio radio-info">
                <input type="radio" id="not_active" name="kegiatan_pengadaan" value="1"/>
                <label for="not_active">Sudah dilaksanakan sebelum proses Pengadaan dilakukan oleh Procurement</label>
              </div>
            </div>
          </div>
          <div class="form-group m-b-10">
              <label for="jadwal_implementasi" class="col-sm-3 control-label text-left">Jadwal Implementasi <span class="pull-right">:</span></label>
              <div class="col-sm-9 col-md-3">
                <div class="input-group">
                  <input type="text" class="form-control input-sm mydatepicker" id="date_from" placeholder="dd/mm/yyyy" value="<?= date("d-m-Y")?>">
                  <span class="input-group-addon"><i class="icon-calender"></i></span>
                </div>
              </div>
          </div>
          <div class="form-group m-b-10">
              <label for="target_pengadaan" class="col-sm-3 control-label text-left">Target Penggunaan <span class="pull-right">:</span></label>
              <div class="col-sm-9 col-md-3">
                <div class="input-group">
                  <input type="text" class="form-control input-sm mydatepicker" id="date_to" placeholder="dd/mm/yyyy" value="<?= date("d-m-Y")?>">
                  <span class="input-group-addon"><i class="icon-calender"></i></span>
                </div>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label for="tujuan_pengadaan" class="col-sm-3 control-label text-left">Tujuan Pengadaan <span class="pull-right">:</span></label>
            <div id="tujuan_pengadaan" class="col-sm-9">
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
                $index = 1;
              ?>
              <div class="col-sm-4" id="tujuan_pengadaan">
              <?php for ($i=0; $i < 5; $i++): ?>
                <div class="checkbox checkbox-info d-inline-block m-r-10 p-t-5">
                  <input id="tujuan_pengadaan-<?= $index ?>" class="tujuan_pengadaan" value="<?= $tujuan[$i] ?>" type="checkbox">
                  <label for="tujuan_pengadaan-<?= $index ?>"> <?= $tujuan[$i] ?> </label>
                </div>
              <?php $index++; ?>
              <?php endfor; ?>
              </div>
              <div class="col-sm-4" id="tujuan_pengadaan">
              <?php for ($i=5; $i < 10; $i++): ?>
                <div class="checkbox checkbox-info d-inline-block m-r-10 p-t-5">
                  <input id="tujuan_pengadaan-<?= $index ?>" class="tujuan_pengadaan" value="<?= $tujuan[$i] ?>" type="checkbox">
                  <label for="tujuan_pengadaan-<?= $index ?>"> <?= $tujuan[$i] ?> </label>
                </div>
              <?php $index++; ?>
              <?php endfor; ?>
              </div>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="notes" class="col-sm-3 control-label text-left">Rekanan yang ditunjuk <span class="pull-right">:</span></label>
            <div class="col-sm-6">
              <div class="mt-5">
                <div class="radio radio-info d-inline mr-10">
                  <input type="radio" id="rekanan_lama" name="rekanan_opsi" value="0" checked/>
                  <label for="rekanan_lama">Rekanan Lama</label>
                </div>
                <div class="radio radio-info d-inline">
                  <input type="radio" id="rekanan_baru" name="rekanan_opsi" value="1"/>
                  <label for="rekanan_baru">Rekanan Baru</label>
                </div>
              </div>
              <select class="form-control input-sm mt-10" id="vendor" name="vendor">
                <option value="0">-- Choose --</option>
                <?php foreach ($vendor as $key => $value):?>
                <option value="<?= $value['NAMA_VENDOR'] ?>"><?= $value['NAMA_VENDOR'] ?></option>
                <?php endforeach;?>
              </select>
              <input type="text" class="form-control input-sm mt-10 d-none" id="vendor_baru" name="vendor" placeholder="Rekanan Baru">
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="evaluasi" class="col-sm-3 control-label text-left">Evaluasi Rekanan <span class="pull-right">:</span></label>
            <div class="col-sm-6">
              <textarea class="form-control input-sm" id="evaluasi" rows="6"></textarea>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="kriteria" class="col-sm-3 control-label text-left">Kriteria Penunjukan Langsung <span class="pull-right">:</span></label>
              <div class="col-sm-5" id="kriteria">
              <?php
                $arr_kriteria[] = 'Penyedia/Supplier/Rekanan Tunggal';
                $arr_kriteria[] = 'Lanjutan dari Pekerjaan sebelumnya yang tidak terelakkan';
                $arr_kriteria[] = 'Critical (Penting & Mendesak)';
                $index = 1;
              ?>
              <?php foreach ($arr_kriteria as $key => $value):?>
                <div class="checkbox checkbox-info d-inline-block m-r-10 p-t-5">
                    <input id="kriteria-<?= $index ?>" class="kriteria" value="<?= $value ?>" type="checkbox">
                    <label for="kriteria-<?= $index ?>"> <?= $value ?> </label>
                </div>
              <?php $index++; ?>
              <?php endforeach; ?>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label for="alasan" class="col-sm-3 control-label text-left">Alasan Penunjukan Langsung <span class="pull-right">:</span></label>
            <div class="col-sm-6">
              <textarea class="form-control input-sm" id="alasan" rows="6"></textarea>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="keuntungan" class="col-sm-3 control-label text-left">Keuntungan menggunakan Penunjukan Langsung <span class="pull-right">:</span></label>
            <div class="col-sm-6">
              <textarea class="form-control input-sm" id="keuntungan" rows="6"></textarea>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="resiko" class="col-sm-3 control-label text-left">Resiko bila tidak menggunakan Penunjukan Langsung <span class="pull-right">:</span></label>
            <div class="col-sm-6">
              <textarea class="form-control input-sm" id="resiko" rows="6"></textarea>
            </div>
          </div>

        </div>
      </div>
      <div class="row">
        <div class="col-sm-6 col-sm-offset-6">
          <div class="form-group pull-right">
            <button type="button" id="save_data" class="btn btn-info border-radius-5 m-10 w-150p"><i class="fa fa-save"></i> Save </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

        

<script>
  $(document).ready(function(){

    const opt_default  = '<option value="0" data-name="">-- Choose --</option>';

    const binding = <?= ($binding) ? "'".$binding."'" : 'false' ?>;
    if(binding != false){
        if(binding == 'directorat'){
          getDivision();
        }
        if(binding == 'division'){
          getUnit();
        }
          if(binding == 'unit'){
            setTimeout(function(){
          getFS();
        getSubmitter();
            }, 1000);
          }

    }

  $("#directorat").bind("click", function(e) {
      lastValue = $(this).val();
    }).bind("change", function(e) {
    change = true;
    if(change == true){

      $("#division").html(opt_default);
        if( $(this).val() != "0"){
          getDivision();
        }
      $("#unit").html(opt_default);
      $("#fs_header").html(opt_default);

        id_fs = 0;
    }
  });

  $("#division").bind("click", function(e) {
      lastValue = $(this).val();
  }).bind("change", function(e) {
    change = true;
    if(change == true){

      $("#unit").html(opt_default);
        if( $(this).val() != "0"){
          getUnit();
        }
      $("#fs_header").html(opt_default);

        id_fs = 0;
    }
  });

  $("#unit").bind("click", function(e) {
      lastValue = $(this).val();
  }).bind("change", function(e) {
    change = true;
    if(change == true){

      $("#fs_header").html(opt_default);
        if( $(this).val() != "0"){
          getFS();
        }
        getSubmitter();

        id_fs = 0;
    }
  });

  function getDivision() {

    let directorat    = $("#directorat").find(':selected').attr('data-name');

    $("#division").attr('disabled', true);
    $("#division").css('cursor', 'wait');

      $.ajax({
          url   : baseURL + 'api-budget/load_data_rkap_view',
          type  : "POST",
          data  : {category : "division", directorat : directorat},
          dataType: "json",
          success : function(result){
            let division_opt = opt_default;
            if(result.status == true){
          data = result.data;
              for(var i = 0; i < data.length; i++) {
              obj = data[i];
              division_opt += '<option value="'+ obj.id_division +'" data-name ="'+ obj.division +'">'+ obj.division +'</option>';
          }
            }
        $("#division").html(division_opt);
        $("#division").attr('disabled', false);
        $("#division").css('cursor', 'default');
          }
      });
  }

  function getUnit() {

    let directorat = $("#directorat").find(':selected').attr('data-name');
    let division   = $("#division").find(':selected').attr('data-name');

    $("#unit").attr('disabled', true);
    $("#unit").css('cursor', 'wait');

      $.ajax({
          url   : baseURL + 'api-budget/load_data_rkap_view',
          type  : "POST",
          data  : {category : "unit", directorat : directorat, division : division},
          dataType: "json",
          success : function(result){
            let unit = opt_default;
            if(result.status == true){
          data = result.data;
              for(var i = 0; i < data.length; i++) {
              obj = data[i];
              unit += '<option value="'+ obj.id_unit +'" data-name ="'+ obj.unit +'">'+ obj.unit +'</option>';
          }
            }
        $("#unit").html(unit);
        $("#unit").attr('disabled', false);
        $("#unit").css('cursor', 'default');
          }
      });
  }

  function getFS() {

    let directorat = $("#directorat").val();
    let division   = $("#division").val();
    let unit       = $("#unit").val();

    $("#fs_header").attr('disabled', true);
    $("#fs_header").css('cursor', 'wait');

      $.ajax({
          url   : baseURL + 'api-budget/load_fs_header_dpl',
          type  : "POST",
          data  : {directorat : directorat, division : division, unit : unit},
          dataType: "json",
          success : function(result){
            let fs_header = opt_default;
            if(result.status == true){
          data = result.data;
              for(var i = 0; i < data.length; i++) {
              obj = data[i];
              fs_header += '<option value="'+ obj.id_fs +'" data-amount="'+ obj.amount +'" data-rate="'+ obj.rate +'">'+ obj.fs_number +' - '+ obj.fs_name +'</option>';
          }
            }
        $("#fs_header").html(fs_header);
        $("#fs_header").attr('disabled', false);
        $("#fs_header").css('cursor', 'default');
          setTimeout(function(){
          $("#fs_header").select2();
        }, 300);
          }
      });
  }
    $("input[name=rekanan_opsi]").on('click', function () {
      if($(this).val() == 1){
        $("#vendor").addClass('d-none');
        $("#vendor_baru").removeClass('d-none');
      }else{
        $("#vendor_baru").addClass('d-none');
        $("#vendor").removeClass('d-none');
      }
    });

    $("#save_data").on('click', function () {

      let directorate   = $("#directorat").val();
      let division      = $("#division").val();
      let unit          = $("#unit").val();
      let id_fs         = $("#fs_header").val();
      let pic           = $("#pic").val();
      let jabatan_sub   = $("#jabatan_sub").html();
      let date_from     = $("#date_from").val();
      let date_to       = $("#date_to").val();
      let justif_amount = $("#fs_header").find(':selected').attr('data-amount');

      let uraian       = $("#uraian").val();
      let vendor_check = $("input[name='rekanan_opsi']:checked").val();
      let vendor_val   = '';

      if(vendor_check == 1){
        vendor_val = $("#vendor_baru").val();
      }else{
        vendor_val = $("#vendor").val();
      }
      let kegiatan_pengadaan = $("input[name='kegiatan_pengadaan']:checked").val();
      let evaluasi           = $("#evaluasi").val();
      let alasan             = $("#alasan").val();
      let keuntungan         = $("#keuntungan").val();
      let resiko             = $("#resiko").val();
      let tujuan_pengadaan   = [];
      $('#tujuan_pengadaan input[type=checkbox]').each(function() {
        if ($(this).is(":checked")) {
          valTujuan = ($(this).val() != '') ? $(this).val() : $("#others_doc").val();
          if(valTujuan != ''){
            tujuan_pengadaan.push(valTujuan);
          }
        }
      });
      let kriteria = [];
      $('#kriteria input[type=checkbox]').each(function() {
        if ($(this).is(":checked")) {
          valkriteria = ($(this).val() != '') ? $(this).val() : $("#others_doc").val();
          if(valkriteria != ''){
            kriteria.push(valkriteria);
          }
        }
      });

      if(id_fs == "0" || vendor_val == "" || uraian == "" || evaluasi == "" || alasan == "" || keuntungan == "" || resiko == ""){
        customNotif('Warning', 'Please fill all field', 'warning');
        return false;

      }

      data = {
              id_fs : id_fs,
              directorate : directorate,
              division : division,
              unit : unit,
              uraian : uraian,
              kegiatan_pengadaan : kegiatan_pengadaan,
              date_from : date_from,
              date_to : date_to,
              tujuan_pengadaan : tujuan_pengadaan,
              vendor : vendor_val,
              pic : pic,
              jabatan_sub : jabatan_sub,
              evaluasi : evaluasi,
              kriteria : kriteria,
              alasan : alasan,
              keuntungan : keuntungan,
              justif_amount : justif_amount,
              resiko : resiko,
              is_new : 1
            }

      $.ajax({
          url   : baseURL + 'dpl/api/save_dpl',
          type  : "POST",
          data  : data,
          dataType: "json",
          beforeSend  : function(){
                        customLoading('show');
                      },
          success : function(result){
            if(result.status == true){
              customNotif('Success', "DPL Created", 'success');
              setTimeout(function(){
                customLoading('hide');
                $(location).attr('href', baseURL + 'dpl');
              }, 500);
            }
            else{
              customLoading('hide');
              customNotif('Error', result.messages, 'error');
            }
          }
      });
    });


    setTimeout(function(){
        $("#vendor").select2();
    }, 500);


  function getSubmitter(){

    let directorat = $("#directorat").val();
    let division   = $("#division").val();
    let unit       = $("#unit").val();

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
                submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
            }
          }else{
            submitter_opt = opt_default;
            for(var i = 0; i < data.length; i++) {
                obj = data[i];
                submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
            }
          }
              
            }
        $("#pic").html(submitter_opt);        
        getJabatan("pic");
          }
      });
  }

  function getJabatan(category){
    if(category == "pic"){
      valJabtan = $("#pic").find(':selected').attr('data-jabatan');
      $("#jabatan_sub").html(valJabtan);
    }
  }

  $("#pic").on("change", function(){
    getJabatan("pic");
    });

  $('#date_from, #date_to').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight:'TRUE',
    autoclose: true,
    });
  });
</script>