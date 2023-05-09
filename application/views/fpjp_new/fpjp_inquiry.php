  <div class="row mt-20">
    <div class="col-xs-6 col-sm-3 md-2 w-auto">
      <button id="btnPrint" class="btn btn-info border-radius-5 w-150p m-b-10" type="button"><i class="fa fa-download"></i> Download</button>
    </div>
    <?php if( ($su_budget == true || $data_binding['unit'] == true || $this->ion_auth->is_admin() == true) && $show_create == true): ?>
    <!-- <div class="col-xs-6 col-sm-3 md-2">
      <button id="btn-create" class="btn btn-info border-radius-5 w-150p m-b-10" type="button"><i class="fa fa-plus"></i> Create New</button>
    </div> -->
    <?php endif; ?>
    <?php if( $force_create_form == true ): ?>
    <div class="col-xs-6 col-sm-3 md-2">
      <button id="btn-create" class="btn btn-info border-radius-5 w-150p m-b-10" type="button"><i class="fa fa-plus"></i> Create New</button>
    </div>
    <?php endif; ?>
  </div>

<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Filter</h5>
    </div>
  </div>
  <div class="white-box boxshadow mt-0 z-index-2 py-10 border-bottom-only-5">
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label>Directorate</label>
          <select class="form-control" id="directorate">
            <?php
              if(count($directorat) > 1):
                echo '<option value="0">--All Directorate--</option>';
              endif;
              foreach($directorat as $value): ?>
              <option value="<?= $value['ID_DIR_CODE'] ?>" data-name="<?= $value['DIRECTORAT_NAME'] ?>"><?= $value['DIRECTORAT_NAME'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label>Division</label>
          <select class="form-control" id="division">
          <?php
            if($binding == false || $data_binding['division'] == false):
              echo '<option value="0">-- All Division --</option>';
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
      <div class="col-md-2">
        <div class="form-group">
          <label>Unit</label>
          <select class="form-control" id="unit">
            <?php
              if($binding == false || $data_binding['unit'] == false):
                echo '<option value="0">-- All Unit --</option>';
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
    </div>
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label>Status</label>
          <select class="form-control" id="status">
            <option value="0">--All Status--</option>
            <?php foreach ($fpjp_status as $key => $value): ?>
            <option value='<?= $key ?>'><?= $value ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label>Date</label>
          <div class="input-group">
            <input class="form-control" type="text" id="fpjp_date" value="<?= dateFormat(strtotime('-3 days'), 'date') ?> - <?= dateFormat(time(), 'date') ?>"/>
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label>&nbsp;</label>
          <button id="btnView" class="btn btn-info btn-outline border-radius-5 w-150p btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div id="tbl_search" class="col-md-12 positon-relative">
          <i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
          <input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
      </div>
      <div class="col-md-12">
          <table id="table_data" class="table table-hover small display table-striped table-responsive dataTable w-full">
            <thead>
              <tr>
                <th>Directorate</th>
                <th>FPJP Number</th>
                <th>FPJP Name</th>
                <th>FPJP Date</th>
                <th>Currency</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>


<script>
  $(document).ready(function(){

  const c_fpjp_date     = readCookie('fpjp_date');
  const line_selected = readCookie('fpjp_line_selected');
  let status_disabled = '';

  const IS_BOD = <?= ($this->session->userdata('is_bod')) ? 'true' : 'false' ?>;
  const binding = <?= ($binding) ? "'".$binding."'" : 'false' ?>;
  
  let clickView = false;

  if(binding != false){
    if(binding == 'directorat'){
      getDivision();
    }
    if(binding == 'division'){
      getUnit();
    }

    clickView  = true;

  }else if(IS_BOD == true){
    status_disabled = ' disabled';
    getDivision();
  }

  if(c_fpjp_date != null){
    $("#fpjp_date").val(c_fpjp_date);
    eraseCookie("fpjp_date");
  }

  let directorate     = $("#directorate").val();
  let division        = $("#division").val();
  let unit            = $("#unit").val();
  let fpjp_status       = $("#status").val();
  let fpjp_date         = $("#fpjp_date").val();

                   
  let url        = baseURL + 'fpjp/api/load_fpjp_header';

  let hideEdit = "<?= (!in_array("FPJP Inquiry", $group_name) || in_array("FPJP COA", $group_name)) ? '' : 'hidden' ?>";
  let hideDelete = "<?= (in_array("FPJP Inquiry", $group_name)) ? 'hidden' : '' ?>";

  Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                  "url"  : url,
                  "type" : "POST",
                  "dataType": "json",
                  "data"    : function ( d ) {
                                        d.status     = fpjp_status;
                                        d.directorat = directorate;
                                        d.division   = division;
                                        d.unit       = unit;
                                        d.fpjp_date  = fpjp_date;
                                      }
                          },
        "language"        : {
                              "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
                              "infoEmpty"   : "Data Kosong",
                              "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
                              "search"      : "_INPUT_",
                              "paginate": {
                                          'previous': '<i class="fa fa-angle-left"></i>',
                                          'next': '<i class="fa fa-angle-right"></i>',
                                        },
                              "lengthMenu": " &nbsp; _MENU_ &nbsp; rows per page"
                            },
        "columns"         : [
                      { "data": "directorat", "width": "100px" },
                      { "data": "fpjp_number", "width": "140px"},
                      { "data": "fpjp_name", "width": "150px" },
                      { "data": "fpjp_date", "width": "80px"},
                      { "data": "currency", "width": "80px"},
                      { "data": "total_amount", "width": "100px"},
                      { "data": "status", "width": "100px"},
                      { 
                        "data": "fpjp_header_id",
                        "width":"80px",
                        "class":"text-center",
                        "render": function (data) {
                           return '<a href="javascript:void(0)" class="action-view px-5" title="Click to view FPJP" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a><a href="javascript:void(0)" class="action-edit px-5" title="Click to edit FPJP" data-id="' + data + '" '+ hideEdit +'><i class="fa fa-edit text-warning" aria-hidden="true"></i></a><a href="javascript:void(0)" class="action-delete px-5" title="Click to delete" '+ hideDelete +'><i class="fa fa-trash text-danger" aria-hidden="true"></i></a><a href="javascript:void(0)" class="action-cetak px-5" title="Click to Download PDF File" data-id="' + data + '"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
                        }
                      }
                ],
          "drawCallback": function ( settings ) {
            if(line_selected != null){
              setTimeout(function(){
                $('#table_data tbody tr').eq(line_selected).addClass('selected-grey');
                eraseCookie("fpjp_line_selected");
              }, 300);
            }
          },
          "fnDrawCallback": function () {
            $('#table_data_length').prepend($('#table_data_info'));
          },
           "dom": '<"top"i>rt<"bottom"flp><"clear">',
        "ordering"        : false,
        "scrollY"         : 520,
        "scrollX"         : true,
        "scrollCollapse"  : true,
        "autoWidth"       : true,
        "bAutoWidth"      : true,
      });
  });

  let table = $('#table_data').DataTable();
  $("#table_data_filter").remove();
  $("#tbl_search").on('keyup', "input[type='search']", function(){
      table.search( $(this).val() ).draw();
  });

   $('#table_data tbody').on( 'click', 'tr', function () {
    if (! $(this).hasClass('selected') ) {
      table.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
    }
  });


    $('#btnView').on( 'click', function () {
      directorate = $("#directorate").val();
      division    = $("#division").val();
      unit        = $("#unit").val();
      fpjp_status = $("#status").val();     
      fpjp_date   = $("#fpjp_date").val();
      table.draw();
    });



    $('#directorate').on( 'change', function () {
      getDivision();
    });
    $('#division').on( 'change', function () {
      getUnit();
    });

    let change_date = false;
    $("#fpjp_date").bind("click", function(e) {
      change_date = true;
    }).bind("change", function(e) {
      if(change_date){
        eraseCookie("fpjp_date");
        createCookie("fpjp_date", $(this).val());
      }
    });

    $('#table_data').on('click', 'a.action-view', function () {
      fpjp_header_id = $(this).data('id');
      get_table    = table.row( $(this).parents('tr') );
      index        = get_table.index();
      eraseCookie("fpjp_line_selected");
      createCookie("fpjp_line_selected", index);
      $(location).attr('href', baseURL + 'fpjp/' + fpjp_header_id);
    });

    let edit_delete = false;
    <?php if(in_array("FPJP COA", $group_name) || $enable_edit === true) : ?>
      edit_delete = true;
    <?php endif; ?>

    $('#table_data').on('click', 'a.action-edit', function () {

        fpjp_header_id = $(this).data('id');
        get_table    = table.row( $(this).parents('tr') );
        index        = get_table.index();
        data         = get_table.data();
        status       = data.status;
        
        if(status.toLowerCase() != "returned"){
          if(edit_delete == true){
            eraseCookie("fpjp_line_selected");
            createCookie("fpjp_line_selected", index);
            $(location).attr('href', baseURL + 'fpjp/edit/' + fpjp_header_id);
          }else{
            customNotif('Warning', 'Tidak bisa di edit karena status '+status, 'warning');
          }
        } 
        else{
          eraseCookie("fpjp_line_selected");
          createCookie("fpjp_line_selected", index);
          $(location).attr('href', baseURL + 'fpjp/edit/' + fpjp_header_id);
        }
    });

    $('#table_data').on('click', 'a.action-delete', function () {
      data      = table.row( $(this).parents('tr') ).data();
      id_delete = data.id;
      status    = data.status

      if(status.toLowerCase() == "rejected" || status.toLowerCase() == "returned"){
        $("#modal-delete-label").html('Delete Data : ' +  data.fpjp_name);
        $("#modal-delete").modal('show');
      }else{
        <?php if( $enable_delete === false ):?>
        customNotif('Warning', 'Tidak bisa di hapus karena status '+status, 'warning');
        <?php else: ?>
        $("#modal-delete-label").html('Delete Data : ' +  data.fpjp_name);
        $("#modal-delete").modal('show');
        <?php endif; ?>
      }
      
    });

    $('#button-delete').on( 'click', function () {

        $.ajax({
          url       : baseURL + 'fpjp/api/delete',
          type      : 'post',
          data      : { id: id_delete, category: 'header' },
          beforeSend  : function(){
                          customLoading('show');
                        },
          dataType : 'json',
          success : function(result){
            id_delete = 0;
            $("#modal-delete").modal('hide');
            customLoading('hide');
            if (result.status == true) {
              table.ajax.reload(null, false);
              customNotif('Success', result.messages, 'success');
            } else {
              customNotif('Failed', result.messages, 'error');
            }
          }
        });
    });

    $('#table_data').on('click', 'a.action-cetak', function () {
      data      = table.row( $(this).parents('tr') ).data();
      let fpjp_header_id = data.id_fpjp_enc;

      status = data.status;
      status_coa = data.status_coa;
      console.log(status)

      if(status != 'Approved'){
        customNotif('Failed', 'This FPJP not yet approved', 'warning');
      }else{

        if(status_coa != 'Y'){
          customNotif('Failed', 'This FPJP waiting for Review by Accounting', 'warning');
        }else{
          url = baseURL + "fpjp/api/printPDF";
          window.open(url+'/'+fpjp_header_id, '_blank');
          window.focus();
        }

      }
    });

    $('#btn-create').on( 'click', function () {
      customLoading('show');
      setTimeout(function(){
        $(location).attr('href', baseURL + 'fpjp/create');
      }, 100);
    });

    $('.mydatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight:'TRUE',
      autoclose: true,
    });

    $("#btnPrint").on("click", function(){
        url = 'fpjp/api/download_fpjp/';
        let params  = { 'id_dir_code' : $("#directorate").val(), 'id_division' : $("#division").val(), 'id_unit' : $("#unit").val(), 'status' : $("#status").val(), 'fpjp_date' : $("#fpjp_date").val() };

        open_url(url, params);
   });

    <?php if($this->session->flashdata('messages') != ""): ?>
      customNotif('Error', '<?= $this->session->flashdata('messages') ?>', 'error');
    <?php endif; ?>

    <?php if(in_array("FPJP Inquiry", $group_name)) : ?>
      $("#btn-create").hide();
    <?php endif ?>


    function getDivision(){

      let directorat    = $("#directorate").find(':selected').attr('data-name');

      $.ajax({
          url   : baseURL + 'api-budget/load_data_rkap_view',
          type  : "POST",
          data  : {category : "division", directorat:directorat},
          dataType: "json",
          success : function(result){
            let division_opt = '<option value="0">--All Division--</option>';
            if(result.status == true){
              let data     = result.data;
              for(var i = 0; i < data.length; i++) {
                  obj = data[i];
                  division_opt += '<option value="'+ obj.id_division +'" data-name="'+ obj.division +'">'+ obj.division +'</option>';
              }
            }
            $("#division").html(division_opt);        
          }
      });
    }

    function getUnit(){

      let directorat = $("#directorate").find(':selected').attr('data-name');
      let division   = $("#division").find(':selected').attr('data-name');

      $.ajax({
          url   : baseURL + 'api-budget/load_data_rkap_view',
          type  : "POST",
          data  : {category : "unit", directorat : directorat, division : division},
          dataType: "json",
          success : function(result){
            let unit_opt = '<option value="0">--All Unit--</option>';
            if(result.status == true){
              let data     = result.data;
              for(var i = 0; i < data.length; i++) {
                  obj = data[i];
                  unit_opt += '<option value="'+ obj.id_unit +'" >'+ obj.unit +'</option>';
              }
            }
            $("#unit").html(unit_opt);        
          }
      });
    }

    $('#fpjp_date').daterangepicker({
      locale:{format:'DD/MM/YYYY'},
      "autoApply":true
    });

  });
</script>