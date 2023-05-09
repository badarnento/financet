  <div class="row mt-20">
    <div class="col-xs-6 col-sm-3 md-2 w-auto">
      <button id="btnPrint" class="btn btn-info border-radius-5 w-150p m-b-10" type="button"><i class="fa fa-download"></i> Download</button>
    </div>
   <!--  <div class="col-xs-6 col-sm-3 md-2">
      <button id="btn-create" class="btn btn-info border-radius-5 w-150p m-b-10" type="button"><i class="fa fa-plus"></i> Create New</button>
    </div> -->
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
            <?php foreach ($dpl_status as $key => $value): ?>
            <option value='<?= $key ?>'><?= $value ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label>Date From</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="date_from" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label>Date To</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="date_to" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
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
                <th>Divisi</th>
                <th>Unit</th>
                <th>DPL Number</th>
                <th>No Justifikasi</th>
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

  let directorate     = $("#directorate").val();
  let division        = $("#division").val();
  let unit            = $("#unit").val();
  let status          = $("#status").val();
  let date_from       = $("#date_from").val();
  let date_to         = $("#date_to").val();

  const IS_BOD = <?= ($this->session->userdata('is_bod')) ? 'true' : 'false' ?>;
  const binding = <?= ($binding) ? "'".$binding."'" : 'false' ?>;
                   
  let url        = baseURL + 'dpl/api/load_dpl';

  Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                  "url"  : url,
                  "type" : "POST",
                  "dataType": "json",
                  "data"    : function ( d ) {
                                        d.status     = status;
                                        d.directorat = directorate;
                                        d.division   = division;
                                        d.unit       = unit;
                                        d.date_from  = date_from;
                                        d.date_to    = date_to;
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
                      { "data": "divisi", "width": "140px"},
                      { "data": "unit", "width": "150px" },
                      { "data": "dpl_number", "width": "150px"},
                      { "data": "no_justif", "width": "150px"},
                      { "data": "status", "width": "120px"},
                      { 
                        "data": "id_dpl",
                        "width":"80px",
                        "class":"text-center",
                        "render": function (data) {
                           let $show = '<a href="javascript:void(0)" class="action-view" title="Click to view DPL" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-edit" title="Click to edit DPL" data-id="' + data + '"><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-cetak px-5" title="Click to Download PDF File" data-id="' + data + '"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';

                           let $hide = '<a href="javascript:void(0)" class="action-view" title="Click to view DPL" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-edit" title="Click to edit DPL" data-id="' + data + '" hidden><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete" hidden><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-cetak px-5" title="Click to Download PDF File" data-id="' + data + '"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';

                           let $hasi = "";

                           <?php if(in_array("DPL Inquiry", $group_name)) : ?>
                            $hasil = $hide;
                            return $hasil;

                            <?php else : ?>
                              $hasil = $show;
                              return $hasil;
                            <?php endif ?>
                        }
                      }
                ],
          "drawCallback": function ( settings ) {
            if(line_selected != null){
              setTimeout(function(){
                $('#table_data tbody tr').eq(line_selected).addClass('selected-grey');
                eraseCookie("pr_line_selected");
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
      status      = $("#status").val();
      directorate = $("#directorate").val();
      division    = $("#division").val();
      unit        = $("#unit").val();
      date_from   = $("#date_from").val();
      date_to     = $("#date_to").val();
      table.draw();
    });

    $('#directorate').on( 'change', function () {
      getDivision();
    });
    $('#division').on( 'change', function () {
      getUnit();
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
                  unit_opt += '<option value="'+ obj.id_unit +'" >'+ obj.unit +'</option>';
              }
            }
            $("#unit").html(unit_opt);        
          }
      });
    }

    $('#btn-create').on( 'click', function () {
      $(location).attr('href', baseURL + 'dpl/create');
    });

    <?php if($this->session->flashdata('messages') != ""): ?>
      customNotif('Error', '<?= $this->session->flashdata('messages') ?>', 'error');
    <?php endif; ?>

    <?php if(in_array("DPL Inquiry", $group_name)) : ?>
      $("#btn-create").hide();
    <?php endif ?>

    $('#table_data').on('click', 'a.action-view', function () {
      id_dpl = $(this).data('id');
      get_table    = table.row( $(this).parents('tr') );
      index        = get_table.index();
      data         = get_table.data();
      $(location).attr('href', baseURL + 'dpl/' + id_dpl);
    });

    $('#table_data').on('click', 'a.action-edit', function () {
      id_dpl       = $(this).data('id');
      get_table    = table.row( $(this).parents('tr') );
      index        = get_table.index();
      data         = get_table.data();
      $(location).attr('href', baseURL + 'dpl/edit/' + id_dpl);
    });

    $('#table_data').on('click', 'a.action-delete', function () {
      data      = table.row( $(this).parents('tr') ).data();
      id_delete = data.id;
      $("#modal-delete-label").html('Delete Data : ' +  data.dpl_number);
      $("#modal-delete").modal('show'); 
    });

    $('#button-delete').on( 'click', function () {

        $.ajax({
          url       : baseURL + 'dpl/api/delete_dpl',
          type      : 'post',
          data      : { id: id_delete },
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
      let id_dpl = data.id_dpl;

      url = baseURL + "dpl/print/";
      window.open(url+'/'+id_dpl, '_blank');
      window.focus();

    });

    $('.mydatepicker').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight:'TRUE',
    autoclose: true,
  });

  });
</script>