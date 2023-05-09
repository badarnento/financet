<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">PR Approval</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
    <div class="row">
      <form class="form-horizontal">
    	<div class="col-sm-6 col-md-5">
        	<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">PR Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_number" class="control-label"><?= $pr_number ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">PR Name <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_name" class="control-label text-left"><?= $pr_name ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-4 control-label text-left">Currency<?= ($pr_currency != "IDR") ? "/Rate" : "" ?> <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label id="fs_currency" class="control-label"><?= $pr_currency ?><?= ($pr_currency != "IDR") ? " / ".$pr_rate : "" ?></label>
              </div>
          </div>
        	<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">Total Amount <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_amount" class="control-label"><?= $pr_amount ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Directorate <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= get_directorat($pr_directorat) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Division <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_division($pr_division) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_unit($pr_unit) ?></label>
            </div>
          </div>
          <?php if($id_fs > 0): ?>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Justification  <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_fs($id_fs) ?> - <?= get_fs($id_fs, "FS_NAME") ?> &nbsp; <a class="btn btn-outline btn-xs btn-info" href="<?= $fs_link ?>" target="_blank"> <i class="fa fa-external-link"></i></i> View </a></label>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <div class="col-sm-6 col-md-5">
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">PR Date <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $pr_date ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Document <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
              <?php if($pr_document): ?>
              <button class="btn btn-xs mt-2 btn-success" data-toggle="modal" data-target="#modal-attachment" type="button" > <i class="fa fa-download"></i> Download </button>

                <?php else: ?>
                <label class="control-label text-left">&ndash;</label>
                <?php endif; ?>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Status <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $pr_status ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Submitter/Jabatan <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $pr_submitter ?>/<?= $pr_jabatan_sub ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Last Update <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $pr_status_desc ?> at <?= (isset($approver_before_name)) ? $approver_before_date : $pr_last_update ?></label>
                <?php if(isset($pr_approval_remark)): ?>
                <br>
                <label class="control-label text-left">&quot;<?= $pr_approval_remark ?>&quot;</label>
                <?php endif; ?>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">PR History <span class="pull-right">:</span></label>
                <div class="col-sm-7 col-md-8">
              <?php if($pr_history): ?>
              <button id="btn-comment" class="btn btn-xs mt-2 btn-info" data-toggle="modal" data-target="#modal-comment" type="button" > <i class="fa fa- fa-comment"></i> Show History </button>
            <?php else: ?>
                  <label class="control-label text-left">&ndash;</label>
                <?php endif; ?>
                </div>
            </div>
        </div>
    	</form>
    </div>
</div>
</div>

<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">PR Lines</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
  <div class="row">
    <div id="tbl_search" class="col-md-12 positon-relative">
      <i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
      <input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
    </div>
    <div class="col-md-12">
        <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
            <thead>
              <tr>
                <th class="text-center">No</th>
                <th class="text-center">RKAP Name (Description)</th>
                <th class="text-center">Name</th>
                <th class="text-center">Fund Available</th>
                <th class="text-center">Nominal</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 id="table_detail_title" class="font-weight-700 m-0 text-uppercase">PR Lines Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
    <div class="row">
      <div class="col-md-12">
          <table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
            <thead>
              <tr>
                  <th class="text-center">No</th>
                  <th class="text-center">Item Name</th>
                  <th class="text-center">Item Description</th>
                  <th class="text-center">Category Item</th>
                  <th class="text-center">Category Item</th>
                  <th class="text-center">Nature</th>
                  <th class="text-center">Nature</th>
                  <th class="text-center">Capex/Opex</th>
                  <th class="text-center">Capex/Opex</th>
                  <th class="text-center">Quantity</th>
                  <th class="text-center">UoM</th>
                  <th class="text-center">Unit Price</th>
                  <th class="text-center">Total Price</th>
              </tr>
            </thead>
          </table>
        </div>
    </div>

    <div class="row">
      <div id="approvaL_display" class="col-sm-12">
        <?php if(isset($approver_before_name)): ?>
        <div class="m-b-15">
          <h5>Approved by <?= $approver_before_name ?> at <?= $approver_before_date ?></h5>
          <h5>Remark : <?= (empty($approver_before_remark)) ? "-" : $approver_before_remark ?></h5>
        </div>
        <?php endif;?>
      <?php if($review_status == "N"){ ?>
          <button class="btn btn-info waves-effect m-r-5 pull-right" data-toggle="modal" data-target="#modal-confirm" type="button" ><i class="fa fa-save"></i> Save</button>
          <!-- <button class="btn btn-warning waves-effect action-edit m-r-5" type="button" ><i class="fa fa-pencil"></i> Edit COA</button> -->
      <?php } else if($review_status == "Y"){ ?>
          <h3 class="text-success">Thank You For Your <b>Confirmation!</b></h3>
          <h5>Confirm at <?= date("d F Y", strtotime($review_date)) ?></h5>
      <?php } ?>
      </div>
    </div>
  </div>
</div>


 <div class="modal fade" id="modal-confirm" role="dialog" aria-labelledby="modal-confirm">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title text-white">Confirmation</h4>
          </div>
          <div class="modal-body">
              Are You Sure?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
            <button type="button" class="btn btn-success waves-effect" id="btn_approval"><i class="fa fa-check-circle"></i> Confirm</button>
          </div>
      </div>
    </div>
  </div>

<div id="modal-approve" class="modal fade" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white" id="modal-approve-label">Edit COA</h2>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
              <div class="col-md-6">
                <h5 id="table_coa_title" class="font-weight-700 m-0 text-uppercase">PR Detail</h5>
              </div>
            </div>
            <div class="white-box boxshadow border-bottom-only-5">
              <div class="row">
                <div class="col-md-12 small ">
                    <table id="table_coa" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
                      <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Item Name</th>
                            <th class="text-center">Item Description</th>
                            <th class="text-center">Category Item</th>
                            <th class="text-center">Nature</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">UoM</th>
                            <th class="text-center">Unit Price</th>
                            <th class="text-center">Total Price</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
          <button type="button" class="btn btn-success waves-effect" id="btn_approval"><i class="fa fa-check-circle"></i> Save</button>
        </div>
    </div>
  </div>
</div>

<?php if($pr_document): ?>
<div id="modal-attachment" class="modal fade small" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white font-size-18" id="modal-attachment-label">PR Document</h2>
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
                  <?php foreach ($pr_document as $key => $value):?>
                  <tr>
                    <td><?= $value['FILE_NAME'] ?></td>
                    <td><?= $value['UPLOADED_BY'] ?></td>
                    <td><?= dateFormat($value['DATE_UPLOADED'], 'fintool') ?></td>
                    <td>
                      <label class="control-label w-full"><a class="btn btn-xs btn-success w-full py-5" title="Click to Download" href="<?= $value['FILE_LINK'] ?>"> <i class="fa fa-download"></i> </a></label>
                    </td>
                  </tr>
                <?php endforeach;?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <div class="form-group m-b-10 mt-10">
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
                      <!-- <i class='text-success fa fa-check-circle fa-lg' title='Approved'></i -->
                        <?php
                          $index = 1;
                          foreach ($arr_doc_list as $key => $value):?>
                          <?php if(is_array($value)): ?>
                              <div class="row">
                                <label class="control-label"><i><?= $value['name'] ?></i> : </label>
                                <?php foreach ($value['detail'] as $vldtl):?>
                                  <?php $valx = $value['key']."_".strtolower($vldtl); ?>
                                  <div class="d-inline-block m-r-20">
                                      <label for="document_list-<?= $index ?>" class="<?= $valx ?>"><strong><?= $vldtl ?> <i class='<?=(in_array($valx, $doc_checklist)) ? 'fa fa-check-circle text-success ':'fa fa-times-circle '?>m-l-5' title='Approved'></i></strong></label>
                                  </div>
                                <?php $index++; ?>
                              <?php endforeach; ?>
                              </div>
                          <?php else: ?>
                              <?php $valx = strtolower(str_replace(" ", "_", $value)); ?>
                              <div class="row">
                                  <label for="document_list-<?= $index ?>"><strong><?= $value ?> <i class='<?=(in_array($valx, $doc_checklist)) ? 'fa fa-check-circle text-success ':'fa fa-times-circle '?>m-l-5' title='Approved'></i></strong></label>                                  
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


<?php if($pr_history): ?>
<style>
  table.comment-history tbody td {
    word-break: break-word;
    vertical-align: midle;
  }
</style>
<div id="modal-comment" class="modal fade small" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white font-size-18" id="modal-comment-label">PR History</h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
            <table class="table comment-history display table-striped table-responsive dataTable w-full">
              <thead>
                <tr>
              <th width="30%">PIC</th>
              <th width="20%">STATUS</th>
              <th width="30%">REMARK</th>
              <th width="20%">ACTION DATE</th>
                </tr>
              </thead>
              <tbody>
                    <?php foreach ($pr_history as $key => $value):
                      $statusHistory = strtolower($value['STATUS']);
                    if($statusHistory == "approved" || $statusHistory == "verified"){
                      $badge = "badge-success";
                    }else if($statusHistory == "returned"){
                      $badge = "badge-warning";
                    }else if($statusHistory == "assigned" || $statusHistory == "po created"){
                      $badge = "badge-info";
                    }else if($statusHistory == "rejected"){
                      $badge = "badge-danger";
                    }else{
                      $badge = "badge-default";
                    }
                   ?>
                <tr>
                  <td><?= $value['PIC_NAME'] ?></td>
                  <td><div class="badge <?= $badge ?> text-lowercase"> <?= $statusHistory ?> </div> </td>
                  <td><?= $value['REMARK'] ?></td>
                  <td><?= dateFormat($value['ACTION_DATE'], 'fintool', false) ?></td>
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


<script>
  $(document).ready(function(){

  const ID_PR       = '<?= $id_pr ?>';
  const PR_CURRENCY = '<?= $pr_currency ?>';
  const REVIEW      = <?= ($review_status == "N") ? 'true' : 'false'?>;
  let status        = $('#status').val();
  
  let hou_procurement = <?= ($hou_procurement) ? '1' : '0' ?>;
  let proc_support    = <?= ($proc_support) ? '1' : '0' ?>;
	
  let    pr_category = 0;
  
	let url = baseURL + 'purchase-requisition/api/load_data_lines';

	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.pr_header_id = ID_PR;
													d.category     = 'view';
					                            }
			                    },
	      "language"        : {
	                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
	                            "infoEmpty"   : "Empty Data",
	                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                            "search"      : "_INPUT_"
	                          },
	      "columns"         : [
								{"data": "no", "width": "10px", "class": "text-center" },
				    			{"data": "rkap_name", "width": "200px" },
				    			{"data": "line_name", "width": "250px" },
				    			{"data": "fund_available", "width": "100px", "class": "text-right"  },
				    			{"data": "nominal", "width": "150px", "class": "text-right"  }
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
	let url_detail  = baseURL + 'coa-review/api/load_data_details';
	let pr_header_id = 0;

	Pace.track(function(){
	    $('#table_detail').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url_detail,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.pr_header_id = ID_PR;
													d.category    = 'view';
					                            }
			                    },
	      "language"        : {
	                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
	                            "infoEmpty"   : "Data Kosong",
	                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                            "search"      : "_INPUT_"
	                          },
	      "columns"         : [
                  {"data": "no", "width": "10px", "class": "text-center" },
                  {"data": "item_desc", "width": "200px" },
                  {"data": "detail_desc", "width": "200px" },
                  {"data": "category_item", "width": "200px" },
                  {"data": "category_item_opt", "width": "200px" },
                  {"data": "nature", "width": "200px" },
                  {"data": "nature_opt", "width": "200px" },
                  {"data": "capex", "width": "100px", "class": "text-center"  },
                  {"data": "capex_opex", "width": "100px", "class": "text-center"  },
                  {"data": "quantity", "width": "50px", "class": "text-center"  },
                  {"data": "uom", "width": "50px", "class": "text-center"  },
                  {"data": "price", "width": "100px", "class": "text-right"  },
                  {"data": "nominal", "width": "200px", "class": "text-right" }
				    		],
        "drawCallback": function ( settings ) {
            setTimeout(function(){
              $(".select2").select2();
            }, 400);
        },
	      "pageLength"      : 100,
	      "ordering"        : false,
	      "scrollY"         : 480,
	      "scrollX"         : true,
	      "scrollCollapse"  : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : true
	    });
	});


  let table_detail = $('#table_detail').DataTable();
  $('#table_detail_length').remove()
  $('#table_detail_info').remove()
  $('#table_detail_filter').remove()
  $('#table_detail_paginate').remove()

	$('#table_detail').on( 'draw.dt', function () {

      if(REVIEW){
        disabled  = [4,5,7];
        enabled = [3,6,8];
      }else{
        enabled  = [3,5,7];
        disabled = [4,6,8];
      }
      table_detail.columns( enabled ).visible( true );
      table_detail.columns( disabled ).visible( false );
	});

  // $(".select2").select2();


	/*$('#table_data tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
			let data = table.row( this ).data();
			pr_lines_id = data.pr_lines_id;

			$("#table_detail_title").html('Detail of '+ data.line_name);
			table_detail.draw();
		}
	});
*/

  $('.btn-approval').on( 'click', function () {
    approve = $(this).data('approve');

    $("#pr_category_group").removeClass('d-none');
    $("#btn_approval").removeClass('btn-danger btn-warning');
    $("#btn_approval").addClass('btn-success');
    $("#modal-approve-label").html('Confirm');
    btn_approval = '<i class="fa fa-check-circle"></i> Confirm';
    approvaL_display = '<h3 class="text-success">Thank You For Your <b>Confirmation!</b></h3>';

    $("#btn_approval").html(btn_approval);
    setTimeout(function(){
      $('#modal-approve').modal('show');
    }, 200);

  });


  $('#btn_approval').on( 'click', function () {
    fatalError = false;

    if (fatalError == true)
    {
      customNotif('Warning', notice, 'warning');
      return false;
    }
    else{

      let detail_data = [];
      table_detail.rows().eq(0).each( function ( index ) {
        j = index+1;
        data = table_detail.row( index ).data();

        // category_item = $("#category_item_opt-"+j).val();
        nature        = $("#nature_opt-"+j).val();
        capex         = $("#capex_opt-"+j).val();
        
        pr_detail_code = data.pr_detail_code;

        detail_data.push( {'pr_detail_code' : pr_detail_code, /*'category_item' : category_item,*/ 'nature' : nature, 'capex' : capex} );

      });

      $.ajax({
        url       : baseURL + 'coa-review/api/update_coa',
        type      : 'post',
        data      : { id_pr : ID_PR, data_lines : detail_data},
        beforeSend  : function(){
                        customLoading('show');
                      },
        dataType : 'json',
        success : function(result){
          customLoading('hide');
          if (result.status == true) {
            $("#approvaL_display").html(approvaL_display);
            $("#modal-confirm").modal('hide');
            customNotif('Success', result.messages, 'success');
            setTimeout(function(){
              location.reload();
            }, 400);
          } else {
            customNotif('Failed', result.messages, 'error');
          }
        }
      });
    }
  });
/*
  function getCategoryItem(id_row,id_select=0) {

    $("#category_item_opt-"+id_row).attr('disabled', true);
    $("#category_item_opt-"+id_row).css('cursor', 'wait');

      $.ajax({
          url   : baseURL + 'api-budget/load_category_item',
          type  : "POST",
          data  : {},
          dataType: "json",
          success : function(result){
            let category_item_opt = opt_default2;
            if(result.status == true){
                data = result.data;

                if(data.length > 1){
                      for(var i = 0; i < data.length; i++) {
                    obj = data[i];
                    let selected = '';
                    if(id_select == obj.category_item){
                        selected = ' selected';
                      }
                      category_item_opt += '<option value="'+obj.category_item+'" data-nature="'+obj.nature+'"'+selected+'>'+ obj.category_item +'</option>';
                  }
                }
            }
              $("#category_item_opt-"+id_row).html(category_item_opt);
              $("#category_item_opt-"+id_row).attr('disabled', false);
              $("#category_item_opt-"+id_row).css('cursor', 'default');
              setTimeout(function(){
                $("#category_item_opt-"+id_row).select2();
              }, 300);
          }
      });
  }*/


    /*$('#table_detail tbody').on('change', 'tr td select.category_item_opt', function () {

      index  = $(this).data('id');
      nature = $(this).find(':selected').attr('data-nature');
      if(nature != ""){
        $("#nature_opt-"+index).val(nature).trigger('change.select2');
      }
    });*/


    setTimeout(function(){
      $(".select2").select2();
    }, 500);
});
</script>