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
          <div class="col-sm-8">
	        <div class="form-group m-b-10">
	          <label class="col-sm-5 col-md-3 control-label text-left">Verificator <span class="pull-right">:</span></label>
	            <div class="col-sm-8">
	            <?php foreach ($dpl_verificator as $key => $value):?>
	              <label class="control-label text-left"><?= $key+1?>. <?= $value['NAME'] ?> / <?= $value['JABATAN'] ?> &nbsp; &nbsp;<?php if($value['STATUS'] == "verified"){ echo "<i class='text-info fa fa-check-circle fa-lg' title='Verooed'></i>";}elseif($value['STATUS'] == "rejected"){  echo "<i class='text-danger fa fa-times-circle fa-lg' title='Rejected'></i>"; } elseif($value['STATUS'] == "wait_verify"){  echo "<i class='fa fa-clock-o fa-lg' title='Waiting verification'></i>"; }  ?></label>
	              <br>
	            <?php endforeach;?>
	            </div>
	        </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8">
            <div class="form-group m-b-30">
              <label class="col-sm-5 col-md-3 control-label text-left">Last update <span class="pull-right">:</span></label>
                <div class="col-sm-8">
                  <label class="control-label text-left"><?= $dpl_status_desc ?> at <?=  $dpl_last_update ?></label>
                </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div id="approvaL_display" class="col-sm-8">
            <div class="m-b-15">
		      <?php if($trx_status == "request_approve"){ ?>
		          <button class="btn btn-success waves-effect btn-approval m-r-5" data-approve="approve" type="button"<?= ($disabled_act == true) ? ' disabled' : '' ?>><i class="fa fa-check-circle"></i> Approve</button>
		          <button class="btn btn-warning waves-effect btn-approval m-r-5" data-approve="return" type="button"<?= ($disabled_act == true) ? ' disabled' : '' ?>><i class="fa fa-arrow-circle-left"></i> Return</button>
		          <button class="btn btn-danger waves-effect btn-approval" data-approve="reject" type="button"<?= ($disabled_act == true) ? ' disabled' : '' ?>><i class="fa fa-times-circle"></i> Reject</button>
		      <?php } else if($trx_status == "approved"){ ?>
		          <h3 class="text-success">Thank You For Your <b>Confirmation!</b></h3>
		          <h5>Approved at <?= $trx_date ?></h5>
		      <?php } else if($trx_status == "rejected"){ ?>
		          <h3 class="text-danger">This GR has been <b>Rejected!</b></h2>
		          <h5>Rejected at <?= $trx_date ?></h5>
		      <?php } else if($trx_status == "returned"){ ?>
		          <h3 class="text-warning">This GR has been <b>Returned!</b></h3>
		          <h5>Returned at <?= $trx_date ?></h5>
		      <?php } ?>
		      </div>
            </div>
          </div>
        </div>
      </form>
  </div>
</div>

<div id="modal-approve" class="modal fade" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white" id="modal-approve-label">Approve</h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group m-b-10">
                  <label for="approval_remark" class="control-label">Description <span class="pull-right">:</span></label>
                    <textarea class="form-control" id="approval_remark" rows="3" placeholder="Optional"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
          <button type="button" class="btn btn-success waves-effect" id="btn_approval"><i class="fa fa-check-circle"></i> Approve</button>
        </div>
    </div>
  </div>
</div>



<script>
  $(document).ready(function(){

  const ID_DPL       = '<?= $id_dpl ?>';
  const LEVEL       = '<?= $level ?>';
  
  $('.btn-approval').on( 'click', function () {
    approve = $(this).data('approve');

    if(approve == "reject"){
      $("#btn_approval").removeClass('btn-warning btn-success');
      $("#btn_approval").addClass('btn-danger');
      $("#modal-approve-label").html('Reject');
      btn_approval = '<i class="fa fa-times-circle"></i> Reject';
      approvaL_display = '<h3 class="text-danger">This GR has been <b>Rejected!</b></h2>';
    }
    else if(approve == "return"){
      $("#btn_approval").removeClass('btn-danger btn-success');
      $("#btn_approval").addClass('btn-warning');
      $("#modal-approve-label").html('Return');
      btn_approval = '<i class="fa fa-arrow-circle-left"></i> Return';
      approvaL_display = '<h3 class="text-warning">This GR has been <b>Returned!</b></h3>';
    }
    else{
      $("#btn_approval").removeClass('btn-danger btn-warning');
      $("#btn_approval").addClass('btn-success');
      $("#modal-approve-label").html('Approve');
      btn_approval = '<i class="fa fa-check-circle"></i> Approve';
      approvaL_display = '<h3 class="text-success">Thank You For Your <b>Confirmation!</b></h3>';
    }

    $("#btn_approval").html(btn_approval);
    setTimeout(function(){
      $('#modal-approve').modal('show');
    }, 200);

  });

  $('#modal-approve').on('show.bs.modal', function () {
    $("#approval_remark").val('');
  });

  $('#btn_approval').on( 'click', function () {
    remark = $("#approval_remark").val();
      $.ajax({
        url       : baseURL + 'dpl/approval/api/action_approval',
        type      : 'post',
        data      : { id_dpl : ID_DPL, level : LEVEL, approval : approve, remark : remark},
        beforeSend  : function(){
                        customLoading('show');
                      },
        dataType : 'json',
        success : function(result){
          customLoading('hide');
          if (result.status == true) {
            $("#approvaL_display").html(approvaL_display);
            $("#modal-approve").modal('hide');
            customNotif('Success', result.messages, 'success');
          } else {
            customNotif('Failed', result.messages, 'error');
          }
        }
      });
  });

  });
</script>