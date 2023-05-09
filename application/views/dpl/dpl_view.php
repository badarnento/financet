<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-sm-6">
      <h5 class="font-weight-700 m-0 text-uppercase">DOKUMEN PENUNJUKAN LANGSUNG</h5>
    </div>
    <div class="col-sm-6">


        <?php 
          if(strtolower($dpl_status) == "approved"){
            $badge = "badge-success";
          }else if(strtolower($dpl_status) == "rejected"){
            $badge = "badge-danger";
          }else if(strtolower($dpl_status) == "returned"){
            $badge = "badge-warning";
          }else if(strtolower($dpl_status) == "fs used"){
            $dpl_status = "DPL Used";
            $badge = "badge-default";
          }else if(strtolower($dpl_status) == "wait_verify"){
            $dpl_status = "Waiting for verification";
            $badge = "badge-default";
          }
          else{
            $dpl_status = "Verified and waiting approve";
            $badge = "badge-info";
          }
          ?>
        <div class="badge <?= $badge ?> pull-right font-weight-700 text-lowercase font-size-14"> <?= ucwords($dpl_status) ?> </div>
        <div></div>
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
            <label class="col-sm-5 col-md-4 control-label text-left">No eJustifikasi  <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_fs($id_fs) ?> - <?= get_fs($id_fs, "FS_NAME") ?> &nbsp; <a class="btn btn-outline btn-xs btn-info" href="<?= $fs_link ?>" target="_blank"> <i class="fa fa-external-link"></i></i> View </a>
              </label>
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
          <div class="col-MD-10">
            <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-3 control-label text-left">Submitter <span class="pull-right">:</span></label>
                <div class="col-sm-8">
                  <label class="control-label text-left"><?= $dpl_submitter ?> / <?= $dpl_jabatan_sub ?></label>
                </div>
            </div>
            <div class="form-group m-b-10">
              <label class="col-md-3 control-label text-left">Verificator <span class="pull-right">:</span></label>
                <div class="col-md-9">
                <?php foreach ($dpl_verificator as $key => $value):?>
                  <label class="control-label text-left"><?= $key+1?>. <?= $value['NAME'] ?> / <?= $value['JABATAN'] ?> &nbsp; &nbsp;<?php if($value['STATUS'] == "verified"){ echo "<i class='text-info fa fa-check-circle fa-lg' title='Verooed'></i>";}elseif($value['STATUS'] == "rejected"){  echo "<i class='text-danger fa fa-times-circle fa-lg' title='Rejected'></i>"; } elseif($value['STATUS'] == "wait_verify"){  echo "<i class='fa fa-clock-o fa-lg' title='Waiting verification'></i>"; }  ?></label>
                  <br>
                <?php endforeach;?>
                </div>
            </div>
            <div class="form-group m-b-10">
              <label class="col-md-3 control-label text-left">Approval <span class="pull-right">:</span></label>
                <div class="col-md-9">
                <?php foreach ($dpl_approval as $key => $value):?>
                  <label class="control-label text-left"><?= $key+1?>. <?= $value['NAME'] ?> / <?= $value['JABATAN'] ?> &nbsp; &nbsp;<?php if($value['STATUS'] == "approved"){ echo "<i class='text-success fa fa-check-circle fa-lg' title='Approved'></i>";}elseif($value['STATUS'] == "returned"){  echo "<i class='text-warning fa fa-arrow-circle-left fa-lg' title='Returned'></i>"; } elseif($value['STATUS'] == "rejected"){  echo "<i class='text-danger fa fa-times-circle fa-lg' title='Rejected'></i>"; } elseif($value['STATUS'] == "request_approve"){  echo "<i class='fa fa-clock-o fa-lg' title='Waiting approval'></i>"; }  ?></label>
                  <br>
                <?php endforeach;?>
                </div>
                <div class="col-md-2">
                  
                </div>
            </div>
          </div>
        </div>
      </form>
  </div>
</div>

<script>
  $(document).ready(function(){

  });
</script>