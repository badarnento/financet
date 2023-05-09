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
            <label class="col-sm-3 control-label text-left">Justifikasi <span class="pull-right">:</span></label>
            <div class="col-sm-6">
              <label id="justif_number" class="control-label text-left"><strong><?= $justif['FS_NUMBER'] ?> - <?= $justif['FS_NAME'] ?></strong></label>
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
          <!-- <div class="form-group m-b-10">
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
          </div> -->
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

    const ID_FS         = <?= $justif['ID_FS'] ?>;
    const DIRECTORATE   = <?= $justif['ID_DIR_CODE'] ?>;
    const DIVISION      = <?= $justif['ID_DIVISION'] ?>;
    const UNIT          = <?= $justif['ID_UNIT'] ?>;
    const DATE_FROM     = "<?= $justif['DATE_FROM'] ?>";
    const DATE_TO       = "<?= $justif['DATE_TO'] ?>";
    const PIC           = "<?= $justif['SUBMITTER'] ?>";
    const JABATAN       = "<?= $justif['JABATAN_SUBMITER'] ?>";
    const JUSTIF_AMOUNT = <?= $justif['NOMINAL_FS'] ?>;

   /* $('#date_from, #date_to').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight:'TRUE',
      autoclose: true,
    });
*/
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

      if(vendor_val == "" || uraian == "" || evaluasi == "" || alasan == "" || keuntungan == "" || resiko == ""){
      	customNotif('Warning', 'Please fill all field', 'warning');
  			return false;

      }

      data = {
              id_fs : ID_FS,
              directorate : DIRECTORATE,
              division : DIVISION,
              unit : UNIT,
              uraian : uraian,
              kegiatan_pengadaan : kegiatan_pengadaan,
              date_from : DATE_FROM,
              date_to : DATE_TO,
              tujuan_pengadaan : tujuan_pengadaan,
              vendor : vendor_val,
              pic : PIC,
              jabatan_sub : JABATAN,
              evaluasi : evaluasi,
              kriteria : kriteria,
              alasan : alasan,
              keuntungan : keuntungan,
              justif_amount : JUSTIF_AMOUNT,
              resiko : resiko
            }

      // console.log(data);
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
                $(location).attr('href', baseURL + 'feasibility-study');
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
  });
</script>