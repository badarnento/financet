<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-md-3 col-sm-4">
        <div class="form-group">
          <label>Directorate</label>
          <select class="form-control" id="inq_directorat">
            <option value="">-- All Directorate --</option>
            <?php foreach($directorat as $value): ?>
              <option value="<?= $value['ID_DIR_CODE'] ?>" data-name="<?= $value['DIRECTORAT_NAME'] ?>"><?= $value['DIRECTORAT_NAME'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-md-2 col-sm-4">
        <div class="form-group">
          <label>Level</label>
          <select class="form-control" id="inq_level">
            <option value="">-- All Level --</option>
              <option value="Submitter">HOU / Submitter</option>
              <option value="HOG User">HOG User</option>
              <option value="Risk">Risk Management</option>
              <option value="Fraud">Fraud Management</option>
              <option value="Budget Admin">Budget Admin</option>
              <option value="HOG Budget">HOG Budget</option>
              <option value="BOD">BOD</option>
              <option value="Budget Comitee">Budget Comitee</option>
              <option value="BOC">BOC</option>
          </select>
        </div>
      </div>
      <div class="col-md-2 col-sm-4">
        <div class="form-group">
          <label>&nbsp;</label>
          <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">   
  <div class="col-lg-12">
    <div id="accordion" class="panel panel-info boxshadow animated slideInDown">
      <div class="panel-heading">
        <div class="row">
          <div class="col-md-6">
            <a id="aTitleList" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-data"></a>
          </div>
          <div class="col-md-6">
            <div class="pull-right">
            <button id="btnAdd" class="btn btn-success custom-input-width" data-toggle="modal" data-target="#modal-edit" type="button" ><i class="fa fa-plus"></i> Add New</button>
          </div>
          </div>
        </div>
      </div>
      <div id="collapse-data" class="panel-collapse collapse in">
        <div class="panel-body">
          <div class="table-responsive">
            <table width="100%" class="table display table-bordered table-striped table-responsive hover small" id="table_data"> 
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">PIC LEVEL</th>
                  <th class="text-center">PIC NAME</th>
                  <th class="text-center">PIC EMAIL</th>
                  <th class="text-center">JABATAN</th>
                  <th class="text-center">DIRECTORATE</th>
                  <th class="text-center">DIVISION</th>
                  <th class="text-center">UNIT</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <div class="col-md-offset-5 col-md-2 m-b-15">
            <button id="btnPrint" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa fa-file-excel-o"></i> <span>Download</span></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div id="modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title text-white" id="modal-edit-label">Edit Data</h2>
      </div>
      <div class="modal-body">

        <form class="form-horizontal">
          <div class="row">
            <div class="col-sm-10">

              <div class="form-group m-b-15">
                  <label for="pic_name" class="col-sm-3 control-label">PIC Name <span class="pull-right">:</span></label>
                  <div class="col-sm-9">
                  <input type="hidden" id="isNewRecord" value="0">
                  <input type="hidden" id="id_approval">
                    <select class="form-control select2" id="pic_name" name="pic_name" required>
                      <option value="0">-- Choose --</option>
                      <?php foreach($employee as $value): ?>
                        <option value="<?= $value['NAMA'] ?>" data-email="<?= $value['ALAMAT_EMAIL'] ?>" data-jabatan="<?= $value['JABATAN'] ?>"><?= $value['NAMA'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
              </div>
            
              <div class="form-group m-b-15">
                  <label for="pic_email" class="col-sm-3 control-label">PIC Email <span class="pull-right">:</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="pic_email" placeholder="PIC Email" name="pic_email">
                  </div>
              </div>
            
              <div class="form-group m-b-15">
                  <label for="pic_jabatan" class="col-sm-3 control-label">PIC Jabatan <span class="pull-right">:</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="pic_jabatan" placeholder="PIC Jabatan" name="pic_jabatan">
                  </div>
              </div>

              <div class="form-group m-b-15">
                  <label for="division" class="col-sm-3 control-label">PIC Level <span class="pull-right">:</span></label>
                  <div class="col-sm-9">
                    <select class="form-control" id="pic_level" name="pic_level" required>
                      <option value="">-- Choose --</option>
                      <option value="Submitter">Submitter</option>
                      <option value="HOG User">HOG User</option>
                      <option value="Risk">Risk Management</option>
                      <option value="Fraud">Fraud Management</option>
                      <option value="Budget Admin">Budget Admin</option>
                      <option value="HOG Budget">HOG Budget</option>
                      <option value="CEO">CEO</option>
                      <option value="CFO">CFO</option>
                      <option value="CMO">CMO</option>
                      <option value="COO">COO</option>
                      <option value="CTO">CTO</option>
                      <option value="Budget Comitee">Budget Comitee</option>
                      <option value="BOC">BOC</option>
                    </select>
                  </div>
              </div>
              <div class="directorat_group d-none">
                <div class="form-group m-b-15 directorat_display">
                    <label for="directorat" class="col-sm-3 control-label">Directorate <span class="pull-right">:</span></label>
                    <div class="col-sm-9">
                      <select class="form-control" id="directorat" name="directorat">
                        <option value="0">-- Choose --</option>
                        <?php foreach($directorat as $value): ?>
                          <option value="<?= $value['ID_DIR_CODE'] ?>" data-name="<?= $value['DIRECTORAT_NAME'] ?>"><?= $value['DIRECTORAT_NAME'] ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                </div>
                <div class="form-group m-b-15 division_display">
                    <label for="division" class="col-sm-3 control-label">Division <span class="pull-right">:</span></label>
                    <div class="col-sm-9">
                      <select class="form-control" id="division" name="division">
                        <option value="0">-- Choose --</option>
                      </select>
                    </div>
                </div>
                <div class="form-group m-b-15 unit_display">
                    <label for="unit" class="col-sm-3 control-label">Unit <span class="pull-right">:</span></label>
                    <div class="col-sm-9">
                      <select class="form-control" id="unit" name="unit">
                        <option value="0">-- Choose --</option>
                      </select>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </form>

      </div>

      <div class="modal-footer">
        <button type="reset" class="btn btn-default"><i class="fa fa-trash-o"></i> Clear</button>
        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> CANCEL</button>
        <button type="button" class="btn btn-info waves-effect" id="save_data"><i class="fa fa-save"></i> Save</button>
      </div>

    </div>
  </div>
</div>

<script>
  $(document).ready(function () {

  const opt_default  = '<option value="0" data-name="">-- Choose --</option>';
  let inq_directorat = $("#inq_directorat").val();
  let inq_level      = $("#inq_level").val();

  $('.mydatepicker').datepicker({
      format: 'dd/mm/yyyy',
      todayHighlight:'TRUE',
      autoclose: true,
    });

    let url = baseURL + 'master/load_data_master_approval';
    let ajaxData = {
      "url"  : url,
      "type" : "POST",
      "data"    : function ( d ) {
        d.directorat = inq_directorat;
        d.level = inq_level;
      }
    }
    let jsonData = [
    { "data": "no", "width":"10px", "class":"text-center"},
    { "data": "pic_level", "width":"100px", "class":"text-left"},
    { "data": "pic_name", "width":"150px", "class":"text-left"},
    { "data": "pic_email", "width":"150px", "class":"text-left"},
    { "data": "jabatan", "width":"200px", "class":"text-left"},
    { "data": "directorat_name", "width":"100px", "class":"text-left"},
    { "data": "division_name", "width":"120px", "class":"text-left"},
    { "data": "unit_name", "width":"180px", "class":"text-left"},
    /*{ "data": "pic_delegation", "width":"100px", "class":"text-left"},
    { "data": "delegation_start", "width":"100px", "class":"text-left"},
    { "data": "delegation_end", "width":"100px", "class":"text-left"},*/
    { 
      "data": "",
      "width":"80px",
      "class":"text-center",
      "render": function (data) {
       return '<a href="javascript:void(0)" class="action-edit" title="Click to edit ' + data + '"><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete ' + data + '"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';
     }
   }
   ];

   data_table(ajaxData,jsonData);

   let table = $('#table_data').DataTable();

   $('#btnAdd').on( 'click', function () {
    $("#modal-edit-label").html('Add New');
    $("#isNewRecord").val("1");

  });

  $('#btnView').on( 'click', function () {
    inq_directorat = $("#inq_directorat").val();
    inq_level      = $("#inq_level").val();
    table.draw();
  });


  $('#modal-edit').on('hidden.bs.modal', function () {

    $("#id_approval").val(0);
    $("#isNewRecord").val(0);
    $("#directorat").val(0);
    $("#division").val(0);
    $("#unit").val(0);
    $("#pic_name").select2('data', {id: 0, text: '-- Choose --'});
    $("#pic_email").val("");
    $("#pic_jabatan").val("");
    $("#pic_level").val("");
    $(".directorat_group").addClass('d-none');
    $(".directorat_display").addClass('d-none');
    $(".division_display").addClass('d-none');
    $(".unit_display").addClass('d-none');

  });



   $('#table_data').on( 'click', 'a.action-edit', function () {
      data      = table.row( $(this).parents('tr') ).data();
      
      edit_dir  = data.id_dir_code;
      edit_div  = data.id_division;
      edit_unit = data.id_unit;
      
      $("#id_approval").val(data.id_approval);
      $("#isNewRecord").val(0);
      $("#directorat").val(edit_dir);
      $("#division").val(edit_div);
      $("#unit").val(edit_unit);
      $("#pic_name").select2('data', {id: data.pic_name, text: data.pic_name});
      $("#pic_email").val(data.pic_email);
      $("#pic_jabatan").val(data.jabatan);
      $("#pic_level").val(data.pic_level);
      
      if(edit_dir > 0 || edit_div > 0 || edit_unit > 0){
        $(".directorat_group").removeClass('d-none');
        if(edit_dir > 0){
          $(".directorat_display").removeClass('d-none');
        }
        if(edit_div > 0){
          $(".division_display").removeClass('d-none');

          $("#directorat").trigger("change")
          getDivision(edit_div)
        }
        if(edit_unit > 0){
          $(".unit_display").removeClass('d-none');
        }
      }
      else{
        $(".directorat_group").addClass('d-none');
      }
      
      $("#modal-edit-label").html('Edit  ');
      $("#modal-edit").modal('show');
   });


  $("#save_data").on('click', function () {

    let id_approval = $("#id_approval").val();
    let isNewRecord = $("#isNewRecord").val();
    let directorat  = $("#directorat").val();
    let division    = $("#division").val();
    let unit        = $("#unit").val();
    
    let pic_name    = $("#pic_name").val();
    let pic_email   = $("#pic_email").val();
    let pic_jabatan = $("#pic_jabatan").val();
    let pic_level   = $("#pic_level").val();

    data = {
            id_approval : id_approval,
            isNewRecord : isNewRecord,
            directorat : directorat,
            division : division,
            unit : unit,
            pic_name : pic_name,
            pic_email : pic_email,
            pic_jabatan : pic_jabatan,
            pic_level : pic_level
            }
      $.ajax({
          url   : baseURL + 'master/save_master_approval',
          type  : "POST",
          data  : data,
          dataType: "json",
          beforeSend  : function(){
                        customLoading('show');
                      },
          success : function(result){
              customLoading('hide');
              if(result.status == true){
                table.draw();
                customNotif('Success', "Data successfully added", 'success');
                $("#modal-edit").modal('hide');
              }
              else{
                customNotif('Error', result.messages, 'error');
              }
          }
      });
  });



   $('#table_data ').on( 'click', 'a.action-delete', function () {

    data      = table.row( $(this).parents('tr') ).data();
    id_delete = data.id_approval  ;

    $("#modal-delete-label").html('Delete Data : ');
    $("#modal-delete").modal('show');

  });

   $('#button-delete').on( 'click', function () {

    $.ajax({
      url       : baseURL + 'master/delete_master_approval/' + id_delete,
      type      : 'GET',
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

  $("#btnPrint").on("click", function(){
    var url   =  baseURL + "Master/cetak_data_master_approval";
    window.open(url,'_blank');
    window.focus();
  });

  $("#pic_name").on("change", function(){
    email   = $(this).find(':selected').attr('data-email');
    jabatan = $(this).find(':selected').attr('data-jabatan');

    $("#pic_email").val(email);
    $("#pic_jabatan").val(jabatan);

  });


  $("#pic_level").on("change", function(){
    val_level = $(this).val();
    if( val_level == "Budget Comitee" || val_level == "BOC" || val_level == "Risk" || val_level == "Fraud" || val_level == "Budget Admin" || val_level == "HOG Budget" ){
        $(".directorat_group").addClass("d-none");
        $(".directorat_display").addClass("d-none");
        $(".division_display").addClass("d-none");
        $(".unit_display").addClass("d-none");

        $("#directorat").removeAttr("required");
        $("#division").removeAttr("required");
        $("#unit").removeAttr("required");
    }
    else{
      $(".directorat_group").removeClass("d-none");
      $("#directorat").val(0);
      $("#division").val(0);
      $("#unit").val(0);

      if(val_level == "HOG User"){
        $(".directorat_display").removeClass("d-none");
        $(".division_display").removeClass("d-none");
        $(".unit_display").addClass("d-none");

        $("#directorat").attr("required", true);
        $("#division").attr("required", true);
        $("#unit").removeAttr("required");
      }
      else{
        $(".directorat_display").removeClass("d-none");
        $(".division_display").removeClass("d-none");
        $(".unit_display").removeClass("d-none");

        $("#directorat").attr("required", true);
        $("#division").attr("required", true);
        $("#unit").attr("required", true);
      }
    }

  });

  $(".select2").select2();

  $("#directorat").on("change", function(e) {
    if($(this).val() > 0){
      getDivision();
    }
  });
  $("#division").on("change", function(e) {
    if($(this).val() > 0){
      getUnit();
    }
  });


  function getDivision(id_selected = 0) {

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

              selected = (id_selected == obj.id_division) ? ' selected' : '';

              division_opt += '<option value="'+ obj.id_division +'" data-name ="'+ obj.division +'"'+selected+'>'+ obj.division +'</option>';
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


});



</script>