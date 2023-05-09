<?php $this->load->view('ilustration') ?>
<?php $buttonAddedText = ($is_dpl > 0) ? " Justif" : ""; ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Justif Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
    <div class="row">
      <form class="form-horizontal">
      <div class="col-sm-6 col-md-5">
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Justif Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_number" class="control-label text-left"><?= $fs_number ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Justif Name <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_name" class="control-label text-left"><?= $fs_name ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Description <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_description" class="control-label text-left"><?= $fs_description ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-4 control-label text-left">Currency<?= ($fs_currency != "IDR") ? "/Rate" : "" ?> <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label id="fs_currency" class="control-label"><?= $fs_currency ?><?= ($fs_currency != "IDR") ? " / ".$fs_rate : "" ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Total Amount <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_amount" class="control-label"><?= $fs_amount ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Directorate <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= get_directorat($fs_directorat) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Division <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_division($fs_division) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_unit($fs_unit) ?></label>
            </div>
          </div>
          <?php if($district): ?>
          <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-4 control-label text-left">District Area <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $district_name ?></label>
              </div>
          </div>
          <?php endif; ?>
        </div>
        <div class="col-sm-6 col-md-5">
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Date <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $fs_date ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Attachment <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <?php if(count($fs_attachment) == 1): ?>
                <label class="control-label"><a id="fs_attachment" class="btn btn-xs btn-success" href="<?= $fs_attachment[0]['FILE_LINK'] ?>" target="_blank"> <i class="fa fa-download"></i> Download </a></label>
                <?php elseif(count($fs_attachment) > 1): ?>
              <button class="btn btn-xs mt-2 btn-success" data-toggle="modal" data-target="#modal-attachment" type="button" > <i class="fa fa-download"></i> Download </button>
              <?php else: ?>
                <label class="control-label text-left">&ndash;</label>
                <?php endif; ?>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Status <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left">
                  <?php 
                    if(strtolower($fs_status) == "approved"){
                      $badge = "badge-success";
                    }else if(strtolower($fs_status) == "returned"){
                      $badge = "badge-warning";
                    }else if(strtolower($fs_status) == "rejected"){
                      $badge = "badge-danger";
                    }else if(strtolower($fs_status) == "fs used"){
                      $badge = "badge-info";
                    }
                    else{
                      $badge = "badge-default";
                    }
                  ?>
                  <div class="badge <?= $badge ?> text-lowercase font-size-12"> <?= $fs_status ?> </div>
                </label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Submitter/Jabatan <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $fs_submitter ?> &ndash; <?= $fs_jabatan_sub ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Last Update <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $fs_status_desc ?> at <?= (isset($approver_before_name)) ? $approver_before_date : $fs_last_update ?></label>
                <?php if(isset($fs_approval_remark)): ?>
                <br>
                <label class="control-label text-left">&quot;<?= $fs_approval_remark ?>&quot;</label>
                <?php endif; ?>
              </div>
          </div>
          <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-4 control-label text-left">Comment History <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <?php if($comment_history): ?>
                <button id="btn-comment" class="btn btn-xs mt-2 btn-info" data-toggle="modal" data-target="#modal-comment" type="button" > <i class="fa fa- fa-comment"></i> Show Comment </button>
              <?php else: ?>
                <label class="control-label text-left">&ndash;</label>
                <?php endif; ?>
              </div>
          </div>
          <?php if($is_dpl > 0): ?>
            <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-4 control-label text-left">DPL <span class="pull-right">:</span></label>
                <div class="col-sm-7 col-md-8">
                    <label class="control-label text-left"><?= $dpl_number ?> &nbsp; <button class="btn btn-outline btn-xs btn-info" data-toggle="modal" data-target="#modal-dpl" type="button" > <i class="fa fa-search"></i> View DPL Detail </button> </label>
                </div>
            </div>
          <?php endif; ?>
        </div>
      </form>
    </div>
</div>
</div>

<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Rkap Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
    <div class="row">
      <div id="tbl_search" class="col-md-12 positon-relative">
        <i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
        <input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
      </div>
      <div class="col-md-12">
        <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full" style="font-size:11px !important;">
            <thead>
              <tr>
                <th class="text-center">No</th>
                <th class="text-center">Tribe/Usecase</th>
                <th class="text-center">RKAP Name</th>
                <th class="text-center">Proc Type</th>
                <th class="text-center">Proc Type Desc</th>
                <th class="text-center">Description</th>
                <th class="text-center">Period Start</th>
                <th class="text-center">Period End</th>
                <th class="text-center">FA RKAP</th>
                <th id="currency_text" class="text-center">Nominal <?= $fs_currency ?></th>
                <th class="text-center">Nominal</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
    <hr class="my-20">
    <div class="row">
      <div id="approvaL_display" class="col-sm-8">
        <?php if(isset($approver_before_name)): ?>
        <div class="m-b-15">
          <h5>Approved by <?= $approver_before_name ?> at <?= $approver_before_date ?></h5>
          <h5>Remark : <?= (empty($approver_before_remark)) ? "-" : $approver_before_remark ?></h5>
        </div>
        <?php endif;?>
      <?php if($trx_status == "request_approve"){ ?>

        <?php if($enable_approve): ?>
            <?php if($pic_level == "RISK" || $pic_level == "FRAUD" || $pic_level == "BUDGET ADMIN"): ?>
            <button class="btn btn-success waves-effect btn-approval border-radius-5 m-r-5" data-approve="approve" type="button"<?= ($disabled_act == true) ? ' disabled' : '' ?>><i class="fa fa-check-circle"></i> Review</button>
            <?php else: ?>
            <button class="btn btn-success waves-effect btn-approval border-radius-5 m-r-5" data-approve="approve" type="button"<?= ($disabled_act == true) ? ' disabled' : '' ?>><i class="fa fa-check-circle"></i> Approve<?= $buttonAddedText?></button>
            <?php endif; ?>
            <button class="btn btn-warning waves-effect btn-approval border-radius-5 m-r-5" data-approve="return" type="button"<?= ($disabled_act == true) ? ' disabled' : '' ?>><i class="fa fa-arrow-circle-left"></i> Return<?= $buttonAddedText?></button>
            <button class="btn btn-danger waves-effect btn-approval border-radius-5" data-approve="reject" type="button"<?= ($disabled_act == true) ? ' disabled' : '' ?>><i class="fa fa-times-circle"></i> Reject<?= $buttonAddedText?></button>
        <?php endif; ?>
      <?php } else if($trx_status == "approved"){ ?>
          <h3 class="text-success">Thank You For Your <b>Confirmation!</b></h3>
          <h5>Approved at <?= $trx_date ?></h5>
      <?php } else if($trx_status == "rejected"){ ?>
          <h3 class="text-danger">This justification has been <b>Rejected!</b></h2>
          <h5>Rejected at <?= $trx_date ?></h5>
      <?php } else if($trx_status == "returned"){ ?>
          <h3 class="text-warning">This justification has been <b>Returned!</b></h3>
          <h5>Returned at <?= $trx_date ?></h5>
      <?php } ?>
      </div>
      <div class="col-sm-8 m-t-15">
          <?php if($doble_approve): ?>
            <button class="btn btn-success waves-effect btn-approval-dpl border-radius-5 m-r-5" data-approve="approve" type="button"><i class="fa fa-check-circle"></i> Approve DPL</button>
            <button class="btn btn-danger waves-effect btn-approval-dpl border-radius-5" data-approve="reject" type="button"><i class="fa fa-times-circle"></i> Reject DPL</button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div id="modal-approve" class="modal fade" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <?php if($pic_level == "RISK" || $pic_level == "FRAUD" || $pic_level == "BUDGET ADMIN"): ?>
          <h2 class="modal-title text-white" id="modal-approve-label">Review</h2>
          <?php else: ?>
          <h2 class="modal-title text-white" id="modal-approve-label">Approve</h2>
          <?php endif; ?>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group m-b-10">
                  <label for="approval_remark" class="control-label">Description <span class="pull-right">:</span></label>
                  <textarea class="form-control" id="approval_remark" <?= ($this->session->userdata('email') == 'ikhsan_ramdan@linkaja.id') ? 'rows="5" maxlength="800"' : 'rows="3" maxlength="300"' ?> placeholder="Optional"></textarea>
              </div>
            </div>
          </div>
          <?php if($boc_attachment || $risk_attachment || $cfo_attachment):

                if($boc_attachment){
                  $attachment_name = 'boc'; 
                }
                elseif($risk_attachment){
                  $attachment_name = 'risk'; 
                }
                elseif($cfo_attachment){
                  $attachment_name = 'cfo'; 
                }
          ?>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group m-b-10">
                  <div class="attachment_group">
                    <label class="control-label">Attach File <span class="pull-right">:</span></label>
                      <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                          <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-outline btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
                          <input id="attachment" type="file" data-name="<?= $attachment_name ?>" name="attachment" accept=".pdf,.doc,.docx" required="required"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
                      </div>
                        <div class="progress progress-lg d-none">
                            <div id="progress" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
                        </div>
                  </div>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger w-150p border-radius-5 btn-outline waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
          <button type="button" class="btn btn-success w-150p border-radius-5 waves-effect" id="btn_approval"><i class="fa fa-check-circle"></i> Approve</button>
        </div>
    </div>
  </div>
</div>
<?php if($is_dpl): ?>
<div id="modal-approve-dpl" class="modal fade" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white" id="modal-approve-dpl-label">Approve</h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group m-b-10">
                  <label for="approval_remark_dpl" class="control-label">Description <span class="pull-right">:</span></label>
                  <textarea class="form-control" id="approval_remark_dpl" placeholder="Optional"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger w-150p border-radius-5 btn-outline waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
          <button type="button" class="btn btn-success w-150p border-radius-5 waves-effect" id="btn_approval_dpl"><i class="fa fa-check-circle"></i> Approve</button>
        </div>
    </div>
  </div>
</div>


<div id="modal-dpl" class="modal fade small" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white font-size-18" id="modal-dpl-label">DPL</h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="col-sm-12 col-md-12">
                    <div class="form-group m-b-10">
                      <label class="col-sm-5 col-md-4 control-label text-left">DPL Number <span class="pull-right">:</span></label>
                      <div class="col-sm-7 col-md-8">
                        <label id="dpl_number" class="control-label"><?= $dpl_number ?></label>
                      </div>
                    </div>
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
                  <div class="col-md-12">

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
            </form>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger w-100p border-radius-5 btn-outline waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
        </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if($comment_history): ?>
<div id="modal-comment" class="modal fade small" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white font-size-18" id="modal-comment-label">Comment History</h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
            <table class="table table-hover display table-striped table-responsive dataTable w-full">
              <thead>
                <tr>
              <th width="30%">PIC</th>
              <th width="20%">STATUS</th>
              <th width="30%">REMARK</th>
              <th width="20%">ACTION DATE</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($comment_history as $key => $value):
                if($value['STATUS'] == "approved"){
                  $badge = "badge-success";
                }else if($value['STATUS'] == "returned"){
                  $badge = "badge-warning";
                }else if($value['STATUS'] == "rejected"){
                  $badge = "badge-danger";
                }
                        ?>
                <tr>
                  <td><?= $value['PIC_NAME'] ?></td>
                  <td><div class="badge <?= $badge ?> text-lowercase"> <?= ucfirst($value['STATUS']) ?> </div> </td>
                  <td><?= $value['REMARK'] ?></td>
                  <td><?= dateFormat($value['UPDATED_DATE'], 'fintool', false) ?></td>
                </tr>
              <?php endforeach;?>
              </tbody>
            </table>
          </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger w-100p border-radius-5 btn-outline waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
        </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if(count($fs_attachment) > 1): ?>
<div id="modal-attachment" class="modal fade small" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white font-size-18" id="modal-attachment-label">Attachment History</h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <table class="table_comment display table-striped table-responsive dataTable w-full">
                <thead>
                  <tr>
                <th width="50%">File Name</th>
                <th width="20%">Upload By</th>
                <th width="20%">Date Uploaded</th>
                <th width="10%">Download</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($fs_attachment as $key => $value):?>
                  <tr>
                    <td><?= $value['FILE_NAME'] ?></td>
                    <td><?= $value['UPLOADED_BY'] ?></td>
                    <td><?= dateFormat($value['DATE_UPLOADED'], 'fintool') ?></td>
                    <td>
                      <label class="control-label w-full"><a class="btn btn-xs btn-success w-full py-5" title="Click to Download" href="<?= $value['FILE_LINK'] ?>" target="_blank"> <i class="fa fa-download"></i> </a></label>
                    </td>
                  </tr>
                <?php endforeach;?>
                </tbody>
              </table>
            </div>
          </div><div class="row">
            <div class="col-md-12">
                <div class="form-group m-b-10">
                    <label for="approval_remark" class="col-sm-3 control-label text-left">Document Checklist <span class="pull-right">:</span></label>
                    <div class="col-sm-9" id="document_list">

                      <?php
                        $arr_doc_list[] = 'Justifikasi';
                        $arr_doc_list[] = 'Program Control Review';
                        $arr_doc_list[] = 'BoQ';
                        $arr_doc_list[] = array('name' => 'DPL',
                                                'key' => 'dpl',
                                                'detail' => array('PL') );
                        $arr_doc_list[] = array('name' => 'RKS',
                                                'key' => 'rks',
                                                'detail' => array('Tender','PL') );
                        $arr_doc_list[] = array('name' => 'MoM Joint Planning Process',
                                                'key' => 'mom',
                                                'detail' => array('PO') );
                        $arr_doc_list[] = array('name' => 'Nodin Pembuatan MPA/Amandemen',
                                                'key' => 'nodin',
                                                'detail' => array('Amd','Tender','PL') );
                      ?>
                        <?php
                          $index = 1;
                          foreach ($arr_doc_list as $key => $value):?>
                          <?php if(is_array($value)): ?>
                              <div class="row p-t-5">
                                <label class="control-label"><i><?= $value['name'] ?></i> : </label>
                                <?php foreach ($value['detail'] as $vldtl):?>
                                  <div class="checkbox checkbox-info d-inline-block m-r-10">
                                      <input id="document_list-<?= $index ?>" class="document_list" value="<?= $value['key'] ?>_<?= strtolower($vldtl) ?>" type="checkbox">
                                      <label for="document_list-<?= $index ?>"> <strong><?= $vldtl ?></strong> </label>
                                  </div>
                                <?php $index++; ?>
                              <?php endforeach; ?>
                              </div>
                          <?php else: ?>
                              <div class="checkbox checkbox-info d-inline-block m-r-10 p-t-5">
                                  <input id="document_list-<?= $index ?>" class="document_list" value="<?= $value ?>" type="checkbox">
                                  <label for="document_list-<?= $index ?>"> <strong><?= $value ?></strong> </label>
                              </div>
                            <?php $index++; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger w-100p border-radius-5 btn-outline waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
        </div>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
  $(document).ready(function(){

    const ID_FS       = '<?= $id_fs ?>';
    const ID_DPL      = '<?= $id_dpl ?>';
    const LEVEL       = '<?= $level ?>';
    const LEVEL_DPL   = '<?= $level_dpl ?>';
    const FS_CURRENCY = '<?= $fs_currency ?>';
    let status        = $('#status').val();
    let attachment_file = "";

    let url = baseURL + 'feasibility-study/api/load_data_fs_lines';

    Pace.track(function(){
        $('#table_data').DataTable({
          "serverSide"      : true,
          "processing"      : true,
          "ajax"            : {
                    "url"  : url,
                    "type" : "POST",
                    "dataType": "json",
                    "data"    : function ( d ) {
                            d.id_fs    = ID_FS;
                            d.category = 'view';
                                        }
                            },
          "language"        : {
                                "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
                                "infoEmpty"   : "Data Kosong",
                                "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
                                "search"      : "_INPUT_"
                              },
          "columns"         : [
                            {"data": "no", "width": "20px", "class": "text-center" },
                            {"data": "tribe", "width": "100px"  },
                            {"data": "rkap_name", "width": "200px" },
                            {"data": "proc_type", "width": "120px"  },
                            {"data": "proc_desc", "width": "120px"  },
                            {"data": "line_name", "width": "200px"  },
                            {"data": "period_start", "width": "100px"  },
                            {"data": "period_end", "width": "100px"  },
                            {"data": "fund_available", "width": "100px", "class": "text-right"  },
                            {"data": "nominal_currency", "width": "100px", "class": "text-right"  },
                            {"data": "nominal", "width": "100px", "class": "text-right"  }
                  ],
        "columnDefs": [
                {
                    "targets": [<?= ($fs_currency == "IDR" ) ? "9" : "" ?> ],
                    "visible": false
                }
            ],
          "drawCallback": function ( settings ) {
            // $('#table_data_paginate').html('');
          },
          "pageLength"      : 100,
          "ordering"        : false,
          "scrollY"         : 480,
          "scrollCollapse"  : true,
          "scrollX"         : true,
          "autoWidth"       : true,
          "bAutoWidth"      : false
        });
    });

    let table = $('#table_data').DataTable();
    $('#table_data_filter').remove();
    $('#table_data_length').remove();
    $('#table_data_paginate').remove();

    $("#tbl_search").on('keyup', "input[type='search']", function(){
        table.search( $(this).val() ).draw();
    });


    $('.btn-approval').on( 'click', function () {
      approve = $(this).data('approve');

      if(approve == "reject"){
        $("#btn_approval").removeClass('btn-warning btn-success');
        $("#btn_approval").addClass('btn-danger');
        $("#modal-approve-label").html('Reject');
        btn_approval = '<i class="fa fa-times-circle"></i> Reject';
        approvaL_display = '<h3 class="text-danger">This justification has been <b>Rejected!</b></h2>';
      }
      else if(approve == "return"){
        $("#btn_approval").removeClass('btn-danger btn-success');
        $("#btn_approval").addClass('btn-warning');
        $("#modal-approve-label").html('Return');
        btn_approval = '<i class="fa fa-arrow-circle-left"></i> Return';
        approvaL_display = '<h3 class="text-warning">This justification has been <b>Returned!</b></h3>';
      }
      else{
        $("#btn_approval").removeClass('btn-danger btn-warning');
        $("#btn_approval").addClass('btn-success');
        $("#modal-approve-label").html('Approve');
        <?php if($pic_level == "RISK" || $pic_level == "FRAUD" || $pic_level == "BUDGET ADMIN"): ?>
        btn_approval = '<i class="fa fa-check-circle"></i> Review';
        <?php else: ?>
        btn_approval = '<i class="fa fa-check-circle"></i> Approve';
        <?php endif; ?>
        approvaL_display = '<h3 class="text-success">Thank You For Your <b>Confirmation!</b></h3>';
      }

      $("#btn_approval").html(btn_approval);
      setTimeout(function(){
        $('#modal-approve').modal('show');
      }, 200);

    });

    $('.btn-approval-dpl').on( 'click', function () {
      approve_dpl = $(this).data('approve');

      if(approve_dpl == "reject"){
        $("#btn_approval_dpl").removeClass('btn-warning btn-success');
        $("#btn_approval_dpl").addClass('btn-danger');
        $("#modal-approve-label").html('Reject');
        btn_approval_dpl = '<i class="fa fa-times-circle"></i> Reject DPL';
        approvaL_display_dpl = '<h3 class="text-danger">This justification has been <b>Rejected!</b></h2>';
      }
      else{
        $("#btn_approval_dpl").removeClass('btn-danger btn-warning');
        $("#btn_approval_dpl").addClass('btn-success');
        $("#modal-approve-label").html('Approve');
        btn_approval_dpl = '<i class="fa fa-check-circle"></i> Approve DPL';
        approvaL_display_dpl = '<h3 class="text-success">Thank You For Your <b>Confirmation!</b></h3>';
      }

      $("#btn_approval_dpl").html(btn_approval_dpl);
      setTimeout(function(){
        $('#modal-approve-dpl').modal('show');
      }, 200);

    });


    $('#modal-approve').on('show.bs.modal', function () {
      $("#approval_remark").val('');
    });

    let attach_category = $('#attachment').data('name');

    $('#btn_approval').on( 'click', function () {
      remark = $("#approval_remark").val();
        $.ajax({
          url       : baseURL + 'budget/approval/api/action_approval',
          type      : 'post',
          data      : { id_fs : ID_FS, id_dpl : ID_DPL, level : LEVEL, approval : approve, remark : remark, category : attach_category, attachment_file : attachment_file},
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
              setTimeout(function(){
                location.reload();
              }, 1000);
            } else {
              customNotif('Failed', result.messages, 'error');
            }
          }
        });
    });

    $('#btn_approval_dpl').on( 'click', function () {
      remark_dpl = $("#approval_remark_dpl").val();
        $.ajax({
          url       : baseURL + 'budget/approval/api/action_approval_dpl',
          type      : 'post',
          data      : { id_dpl : ID_DPL, level : LEVEL_DPL, approval : approve_dpl, remark : remark_dpl},
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
              setTimeout(function(){
                location.reload();
              }, 1000);
            } else {
              customNotif('Failed', result.messages, 'error');
            }
          }
        });
    });

    let jqXHR;



    $("#fileinput_remove").on("click", function(e) {
        deleteFile();
      jqXHR.abort();
        reset_proggress();
    });

    $("#attachment").bind("click", function(e) {
        lastValue = $(this).val();
        reset_proggress();
    }).bind("change", function(e) {
      upload = true;
      if(lastValue != ""){
        upload = false;
        if(deleteFile()){
          upload = true;
        }
      }
      if($(this).val() != ""){

        fileSize = this.files[0].size;
        fileSize = fileSize/1000
        j=0;

        if(fileSize > 5800){
          upload = false;
          alert('Max file size 5M')
              $(this).val(lastValue);
        }


        if(upload){
          $("#progress").parent().removeClass('d-none');
              
          file = $('#attachment')[0].files[0];
          formData = new FormData();
          formData.append('file', file);
          formData.append('category', attach_category);

              jqXHR  = $.ajax({
                  url: baseURL + "upload/do_upload",
                  type: 'POST',
                  data: formData,
                  cache: false,
                  dataType: 'json',
                  enctype   : 'multipart/form-data',
                  contentType: false,
                  processData: false,
                  xhr: function() {
                      var xhr = new window.XMLHttpRequest();
                      xhr.upload.addEventListener("progress", function(evt) {
                          if (evt.lengthComputable) {
                              percentage = Math.round((evt.loaded / evt.total) * 100);
                              if(percentage == 100){
                                percentage = 99;
                              }
                          $("#progress").css('width', percentage+'%');
                          $("#progress").html(percentage+'%');
                          
                          }
                      }, false);
                      return xhr;
                  },
                  success: function(result) {
                      $("#progress").css('width', '100%');
                      if(result.status == true){
                              $("#progress").html('100%');
                              setTimeout(function(){
                                $("#progress").html('Complete!');
                                $("#progress").removeClass('progress-bar-info');
                                $("#progress").addClass('progress-bar-success');
                                attachment_file = result.messages;
                      }, 1000);
                            }else{
                              setTimeout(function(){
                                $("#progress").html(result.messages);
                                $("#progress").removeClass('progress-bar-info');
                                $("#progress").addClass('progress-bar-danger');
                      }, 500);
                      }
                  },
              error: function (xhr, ajaxOptions, thrownError) {
                $("#progress").parent().addClass('d-none');
                    $("#progress").css('width', '100%');
                    $("#progress").removeClass('progress-bar-info');
                    setTimeout(function(){
                      $("#progress").addClass('progress-bar-danger');
                  $("#progress").parent().removeClass('d-none');
                      $("#progress").html('Error Connection');
            }, 200);
              }
              });
              return false;

        }
              
      }
    });


    function reset_proggress(){
        $('#file_input').val("");
        $("#delete_file").addClass('d-none');
        $("#progress").removeClass("progress-bar-success");
        $("#progress").removeClass("progress-bar-danger");
        $("#progress").parent().addClass('d-none');
        $("#progress").css('width', '0%');
        $("#progress").html('0%');
      }

    function deleteFile(){

      $.ajax({
            url   : baseURL + 'upload/delete',
            type  : "POST",
            data  : {file: attachment_file, category: attach_category},
            dataType: "json",
            success : function(result){
              attachment_file = '';
              return true;
            }
        });

        return true;
    }

  });
</script>