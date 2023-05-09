<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">DOKUMEN PENUNJUKAN LANGSUNG</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
    <div class="row">
      <form class="form-horizontal">
      <div class="col-sm-12 col-md-12">
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">DPL Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="dpl_number" class="control-label"><?= $dpl_number ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Directorate <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="directorate" class="control-label text-left"><?= get_directorat($directorate) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Division <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="divisi" class="control-label"><?= get_division($divisi) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="unit" class="control-label"><?= get_unit($unit) ?></label>
            </div>
          </div>
          <?php if($id_fs > 0): ?>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Justifikasi  <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_fs($id_fs) ?> - <?= get_fs($id_fs, "FS_NAME") ?> &nbsp; <a class="btn btn-outline btn-xs btn-info" href="<?= $fs_link ?>" target="_blank"> <i class="fa fa-external-link"></i></i> View </a></label>
            </div>
          </div>
          <?php endif; ?>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Uraian <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= $uraian ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Kegiatan Pengadaan <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= $pengadaan ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Jadwal Implementasi <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $date_from ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Target Penggunaan <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $date_to ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Tujuan Pengadaan <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <?php if($tujuan_pgdn): ?>
                <?php foreach ($tujuan_pgdn as $key => $value):?>
                  <?php if(count($tujuan_pgdn) > 1): ?>
                  <label class="control-label d-block text-left"><?= $key+1 .". " . $value ?></label>
                  <?php else: ?>
                  <label class="control-label d-block text-left"><?= $value ?></label>
                  <?php endif; ?>
                <?php endforeach; ?>
              <?php else: ?>
              <label class="control-label text-left">-</label>
              <?php endif; ?>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Vendor <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $vendor ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">PIC/Jabatan <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $pic ?> / <?= $jabatan_sub ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Evaluasi Rekanan <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $evaluasi ?></label>
              </div>
          </div>
          
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Kriteria Penunjukan Langsung  <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <?php if($kriteria_pgdn): ?>
                <?php foreach ($kriteria_pgdn as $key => $value):?>
                  <?php if(count($kriteria_pgdn) > 1): ?>
                  <label class="control-label d-block text-left"><?= $key+1 .". " . $value ?></label>
                  <?php else: ?>
                  <label class="control-label d-block text-left"><?= $value ?></label>
                  <?php endif; ?>
                <?php endforeach; ?>
              <?php else: ?>
              <label class="control-label text-left">-</label>
              <?php endif; ?>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Alasan melakukan Penunjukan Langsung <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $alasan ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Keuntungan menggunakan Penunjukan Langsung <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $keuntungan ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Resiko bila tidak menggunakan Penunjukan Langsung <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $resiko ?></label>
              </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>



<div class="row">
  <div class="white-box border-radius-5 small">
      <form class="form-horizontal">
        <div class="row">
          <div class="col-sm-8">
            <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-3 control-label text-left">Submitter <span class="pull-right">:</span></label>
                <div class="col-sm-8">
                  <label class="control-label text-left"><?= $dpl_submitter ?> / <?= $dpl_jabatan_sub ?></label>
                </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div id="approvaL_display" class="col-sm-8">
            <div class="m-b-15">
              <h5><?= $last_update ?></h5>
            </div>
            <?php if($confirmed == false): ?>
            <button class="btn btn-info waves-effect btn-verify border-radius-5 m-r-5" data-verify="verify" type="button"><i class="fa fa-check-circle"></i> Verify</button>
            <button class="btn btn-warning waves-effect btn-verify border-radius-5 m-r-5" data-verify="return" type="button"><i class="fa fa-arrow-circle-left"></i> Return</button>
            <button class="btn btn-danger waves-effect btn-verify border-radius-5" data-verify="reject" type="button"><i class="fa fa-times-circle"></i> Reject</button>
            <?php endif; ?>
          </div>
        </div>
      </form>
  </div>
</div>



<div id="modal-verify" class="modal fade" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white" id="modal-verify-label">Verify</h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group m-b-10">
                  <label for="approval_remark" class="control-label">Remark verification<span class="pull-right">:</span></label>
                  <textarea class="form-control" id="approval_remark" placeholder="Optional"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger w-100p border-radius-5 btn-outline waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
          <button type="button" class="btn btn-success w-100p border-radius-5 waves-effect" id="btn_verify"><i class="fa fa-check-circle"></i> Verify</button>
        </div>
    </div>
  </div>
</div>



<script>
  $(document).ready(function(){

    const ID_DPL = <?= $id_dpl ?>;
    const ID_FS = <?= $id_fs ?>;
    const LEVEL = <?= $level ?>;

    $('.btn-verify').on( 'click', function () {
      verify = $(this).data('verify');

      if(verify == "reject"){
        $("#btn_verify").removeClass('btn-warning btn-info');
        $("#btn_verify").addClass('btn-danger');
        $("#modal-verify-label").html('Reject');
        btn_verify = '<i class="fa fa-times-circle"></i> Reject';
        approvaL_display = '<h3 class="text-danger">This DPL has been <b>Rejected!</b></h2>';
      }
      else if(verify == "return"){
        $("#btn_verify").removeClass('btn-danger btn-success');
        $("#btn_verify").addClass('btn-warning');
        $("#modal-verify-label").html('Return');
        btn_verify = '<i class="fa fa-arrow-circle-left"></i> Return';
        approvaL_display = '<h3 class="text-warning">This DPL has been <b>Returned!</b></h3>';
      }
      else{
        $("#btn_verify").removeClass('btn-danger btn-warning');
        $("#btn_verify").addClass('btn-info');
        $("#modal-verify-label").html('Verify');
        btn_verify = '<i class="fa fa-check-circle"></i> Verify';
        approvaL_display = '<h3 class="text-info">Thank You For Your <b>Verification!</b></h3>';
      }

      $("#btn_verify").html(btn_verify);
      setTimeout(function(){
        $('#modal-verify').modal('show');
      }, 200);

    });


    $('#modal-verify').on('show.bs.modal', function () {
      $("#approval_remark").val('');
    });

    $('#btn_verify').on( 'click', function () {
      remark = $("#approval_remark").val();
        $.ajax({
          url       : baseURL + 'dpl/verification/api/action_verification',
          type      : 'post',
          data      : { id_dpl : ID_DPL, id_fs : ID_FS, level : LEVEL, verify : verify, remark : remark },
          beforeSend  : function(){
                          customLoading('show');
                        },
          dataType : 'json',
          success : function(result){
            // customLoading('hide');
            if (result.status == true) {
              $("#approvaL_display").html(approvaL_display);
              $("#modal-verify").modal('hide');
              customNotif('Success', result.messages, 'success');
              setTimeout(function(){
                location.reload();
              }, 1000);
            } else {
              customNotif('Failed', result.messages, 'error');
            }
          }
        });
    });

  });
</script>