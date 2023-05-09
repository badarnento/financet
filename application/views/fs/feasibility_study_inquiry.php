  <div class="row mt-20">
    <div class="col-xs-6 col-sm-3 md-2 w-auto">
      <button id="btnPrint" class="btn btn-info border-radius-5 w-150p m-b-10" type="button"><i class="fa fa-download"></i> Download</button>
    </div>
    <?php if( ($su_budget == true || $data_binding['unit'] == true || $this->ion_auth->is_admin() == true) && $show_create == true): ?>
    <!-- <div class="col-xs-6 col-sm-3 md-2">
      <button id="btn-create" class="btn btn-info border-radius-5 w-150p m-b-10" type="button"><i class="fa fa-plus"></i> Create New</button>
    </div> -->
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

                  if($data_binding['division'][0]['ID_DIVISION'] == $value['ID_DIVISION']):
            ?>
                <option value="<?= $value['ID_UNIT'] ?>" data-unt="<?= $replace ?>" data-name="<?= $value['UNIT_NAME'] ?>"><?= $value['UNIT_NAME'] ?></option>
              <?php endif; ?>
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
            <?php foreach ($fs_status as $key => $value): ?>
            <option value='<?= $key ?>'><?= (strtolower($value) == "fs used") ? "Justif Used" : $value ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label>Date</label>
          <div class="input-group">
            <input class="form-control input-daterange-datepicker" type="text" id="fs_date" value="<?= dateFormat(strtotime('-3 days'), 'date') ?> - <?= dateFormat(time(), 'date') ?>"/>
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label>&nbsp;</label>
          <button id="btnView" class="btn btn-default btn-outline border-radius-5 w-150p btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>
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
                <th>Division</th>
                <th>Unit</th>
                <th>RKAP Desc</th>
                <th>Justif Number</th>
                <th>Justif Name</th>
                <th>Justif Date</th>
                <th>Justif Amount</th>
                <th>Reloc IN</th>
                <th>Reloc Out</th>
                <th>FPJP</th>
                <th>PR</th>
                <th>Fund Avl Justif</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>


<div id="modal-status" class="modal fade" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white" id="modal-status-label"></h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <form class="form-horizontal">
                <div class="form-group m-b-10">
                  <label class="col-sm-5 col-md-3 control-label text-left">Submitter <span class="pull-right">:</span></label>
                  <div class="col-sm-7 col-md-9">
                    <label id="submitter" class="control-label"></label>
                  </div>
                </div>
                <div class="form-group m-b-10">
                  <label class="col-sm-5 col-md-3 control-label text-left">Submitter <span class="pull-right">:</span></label>
                  <div class="col-sm-7 col-md-9">
                    <label id="submitter" class="control-label"></label>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
        </div>
    </div>
  </div>
</div>
<?php
  $all_unit = array();
  if($binding == false || $data_binding['unit'] == false):
  else:
    $all_unit = array();
    foreach($data_binding['unit'] as $value):
      $all_unit[] = $value['ID_UNIT'];
    endforeach;
  endif;
  $all_unit = json_encode($all_unit);
?>
<script>
  $(document).ready(function(){

  const opt_default   = '<option value="0" data-name="">-- Choose --</option>';
  const c_fs_date     = readCookie('fs_date');
  const line_selected = readCookie('fs_line_selected');
  let status_disabled = '';

  const IS_BOD        = <?= ($this->session->userdata('is_bod')) ? 'true' : 'false' ?>;
  const enable_edit   = <?= ($enable_edit) ? 'true' : 'false' ?>;
  const enable_delete = <?= ($enable_delete) ? 'true' : 'false' ?>;
  const binding       = <?= ($binding) ? "'".$binding."'" : 'false' ?>;

  let  all_unit = <?= $all_unit ?>;

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


  if(c_fs_date != null){
    $("#fs_date").val(c_fs_date);
    eraseCookie("fs_date");
  }

  let directorate     = $("#directorate").val();
  let division        = $("#division").val();
  let unit            = $("#unit").val();
  let fs_status       = $("#status").val();
  let fs_date         = $("#fs_date").val();


  let url        = baseURL + 'feasibility-study/api_load_header';

  let hide_directorat = '<?= ($binding == false || $data_binding['directorat'] == false) ? '' : 'hide' ?>';
  let hide_division = '<?= ($binding == false || $data_binding['division'] == false) ? '' : 'hide' ?>';
  let hide_unit = '<?= ($binding == false || $data_binding['unit'] == false) ? '' : 'hide' ?>';

  Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "searchDelay": 350,
        "ajax"            : {
                  "url"  : url,
                  "type" : "POST",
                  "dataType": "json",
                  "data"    : function ( d ) {
                                  d.status     = fs_status;
                                  d.directorat = directorate;
                                  d.division   = division;
                                  d.unit       = unit;
                                  d.fs_date    = fs_date;
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
                            { "data": "directorat", "width": "100px", "class": hide_directorat },
                            { "data": "division", "width": "150px", "class": hide_division },
                            { "data": "unit", "width": "150px", "class": hide_unit },
                            { "data": "rkap_name", "width": "150px" },
                            { "data": "fs_number", "width": "150px" },
                            { "data": "fs_name", "width": "200px" },
                            { "data": "fs_date", "width": "80px" },
                            { "data": "total_amount", "width": "100px" },
                            { "data": "reloc_in", "width": "100px" },
                            { "data": "reloc_out", "width": "100px" },
                            { "data": "fpjp", "width": "100px" },
                            { "data": "pr", "width": "100px" },
                            { "data": "fa_fs", "width": "100px" },
                            { 
                                "data": "status",
                                "width":"150px",
                                "class":"text-center",
                                "render": function (data) {
                                    stts_desc = data.toLowerCase();
                                    if(stts_desc == "approved"){
                                      badge = "badge-success";
                                    }else if(stts_desc == "fs used"){
                                      stts_desc = 'Used';
                                      badge = "badge-info";
                                    }else if(stts_desc == "returned"){
                                      badge = "badge-warning";
                                    }else if(stts_desc == "rejected"){
                                      badge = "badge-danger";
                                    }else{
                                      badge = "badge-default";
                                    }
                                    return '<div class="badge '+badge+' text-lowercase"> '+ stts_desc +'</div>';
                                }
                              },
                            { 
                              "data": "id_fs",
                              "width":"80px",
                              "class":"text-center",
                              "render": function (data) {

                                hide_delete = (IS_BOD) ? " d-none" : "";

                                return '<a href="javascript:void(0)" class="action-view" title="Click to view Justif" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-edit" id="edit_data" title="Click to edit Justif" data-id="' + data + '"><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete'+hide_delete+'" id="delete_data" title="Click to delete"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-cetak px-5" title="Click to Download PDF File" data-id="' + data + '"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
                              }
                            }
                ],
          "drawCallback": function ( settings ) {
            if(line_selected != null){
              setTimeout(function(){
                $('#table_data tbody tr').eq(line_selected).addClass('selected-grey');
                eraseCookie("fs_line_selected");
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
        "bAutoWidth"      : true
      });
  });

  let table = $('#table_data').DataTable();
  
  $("#table_data_filter").remove();

  function delay(callback, ms) {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  }

  /*$('#tbl_search input').keyup( delay(function (e) {
     let kcode = e.keyCode;
      if (kcode == 8 ||
          kcode == 9 ||
          kcode == 32 ||
          kcode == 34 ||
          kcode == 38 ||
          kcode == 44 ||
          kcode == 45 ||
          kcode == 46 ||
          kcode == 47 ||
          kcode == 95 ||
          (kcode > 47 && kcode < 58) ||
          (kcode > 64 && kcode < 91) ||
          (kcode > 96 && kcode < 123)
          )
      {
        table.search( $(this).val() ).draw();
      }
  }, 600));*/

  $("#tbl_search").on('change', "input[type='search']", function(evt){
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
      fs_status   = $("#status").val();     
      fs_date     = $("#fs_date").val();
      table.draw();
    });

    let change_date = false;
    $("#fs_date").bind("click", function(e) {
      change_date = true;
    }).bind("change", function(e) {
      if(change_date){
        eraseCookie("fs_date");
        createCookie("fs_date", $(this).val());
      }
    });

    $('#directorate').on( 'change', function () {
      getDivision();
    });
    $('#division').on( 'change', function () {
      getUnit();
    });

    $('#table_data').on('click', 'a.action-view', function () {
      id_fs     = $(this).data('id');
      get_table = table.row( $(this).parents('tr') );
      index     = get_table.index();
      eraseCookie("fs_line_selected");
      createCookie("fs_line_selected", index);
      $(location).attr('href', baseURL + 'feasibility-study/' + id_fs);
    });

    $('#table_data').on('click', 'a.status-info', function () {
      get_table = table.row( $(this).parents('tr') );
      data      = get_table.data();

      $("#submitter").html(data.submitter);
      $("#modal-status-label").html(data.fs_number);

      $.ajax({
        url       : baseURL + 'feasibility-study/api/get_status_info',
        type      : 'post',
        data      : { id_fs: data.id_fs},
        beforeSend  : function(){
                        customLoading('show');
                      },
        dataType : 'json',
        success : function(result){
          id_delete = 0;
          $("#modal-delete").modal('hide');
          customLoading('hide');
          if (result.status == true) {
            $("#modal-status").modal('show')
          } else {
          }
        }
      });
    });

    $('#table_data').on('click', 'a.action-edit', function () {
      id_fs     = $(this).data('id');
      get_table = table.row( $(this).parents('tr') );
      index     = get_table.index();
      data      = get_table.data();

      status = data.status;
      if(status.toLowerCase() != "returned"){
        if(status == 'fs used'){
          status = 'justif used';
        }
        <?php if( $enable_edit === false ):?>
        customNotif('Warning', 'Tidak bisa di edit karena status '+status, 'warning');
        <?php else: ?>

        eraseCookie("fs_line_selected");
        createCookie("fs_line_selected", index);
        $(location).attr('href', baseURL + 'feasibility-study/edit/' + id_fs);
        <?php endif; ?>
      }else{
        eraseCookie("fs_line_selected");
        createCookie("fs_line_selected", index);
        $(location).attr('href', baseURL + 'feasibility-study/edit/' + id_fs);
      }
    });


    $('#btn-create').on( 'click', function () {
      customLoading('show');
      setTimeout(function(){
        $(location).attr('href', baseURL + 'feasibility-study/create'/* + result.pr_number*/);
      }, 300);
    });

    $('.mydatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight:'TRUE',
      autoclose: true,
    });

    $('#table_data').on('click', 'a.action-delete', function () {
      data      = table.row( $(this).parents('tr') ).data();
      id_delete = data.id;
      status    = data.status;

      if(status.toLowerCase() == "rejected" || status.toLowerCase() == "returned"){
        $("#modal-delete-label").html('Delete Data : ' +  data.fs_number);
        $("#modal-delete").modal('show');
      }else{
        <?php if( $enable_delete === false ):?>
        customNotif('Warning', 'Tidak bisa di hapus karena status '+status, 'warning');
        <?php else: ?>
        $("#modal-delete-label").html('Delete Data : ' +  data.fs_number);
        $("#modal-delete").modal('show');
        <?php endif; ?>
      }
    });

    $('#button-delete').on( 'click', function () {
        $.ajax({
          url       : baseURL + 'feasibility-study/api/delete',
          type      : 'post',
          data      : { id: id_delete},
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

    $('#table_data').on('change', 'select.action-status', function () {
      data   = table.row( $(this).parents('tr') ).data();
      id_fs  = data.id;
      status = $(this).val();

      $.ajax({
          url       : baseURL + 'feasibility-study/api/change_status_fs',
          type      : 'post',
          data      : { id_fs: id_fs, status: status },
          dataType : 'json',
          success : function(result){
            if (result.status == true) {
              table.ajax.reload(null, false);
              customNotif('Success', result.messages, 'success');
            } else {
              customNotif('Failed', result.messages, 'error');
            }
          }
        });
    });


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
                  if(all_unit.length > 0){
                    if(all_unit.indexOf(obj.id_unit) != -1){
                      unit_opt += '<option value="'+ obj.id_unit +'" >'+ obj.unit +'</option>';
                    }
                  }
                  else{
                    unit_opt += '<option value="'+ obj.id_unit +'" >'+ obj.unit +'</option>';
                  }
              }
            }
            $("#unit").html(unit_opt);        
          }
      });
    }

    <?php if($this->session->flashdata('messages') != ""): ?>
      customNotif('Error', '<?= $this->session->flashdata('messages') ?>', 'error');
    <?php endif; ?>

    <?php if(in_array("BOD", $group_name)): ?>
      //$("#btn-create").hide();
    <?php endif ?>

    $("#btnPrint").on("click", function(){

       
       let url = baseURL + 'feasibility-study/api/download_data_inquiry';
       
       fs_date = $("#fs_date").val();
       vdir    = $("#directorate").val();
       vstat   = $("#status").val();
       vdiv    = $("#division").val();
       vunt    = $("#unit").val();

       window.open(url+'?fs_date='+fs_date +'&vdir='+vdir+'&vstat='+vstat+'&divisi='+vdiv+'&unit='+vunt, '_blank');

       window.focus();

     });

    if(clickView){
      setTimeout(function(){
        $("#btnView").trigger("click");
      }, 1000);
    }


    $('#table_data').on('click', 'a.action-cetak', function () {
      let id_fs     = $(this).data('id');
      url = baseURL + "feasibility-study/print";
      window.open(url+'/'+id_fs, '_blank');
      window.focus();

    });

  });
</script>