<div class="row">

  <div class="white-box boxshadow">  

    <form role="form" id="form-save" data-toggle="validator">   

      <div class="white-box boxshadow">

        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label class="form-control-label" for="lblBudgetYear">Budget Year</label>
              <select class="form-control" id="ddlBudgetYear" name="ddlBudgetYear" data-toggle="validator" data-error="Please choose one" required>
                <option value="">-- Choose Year --</option> 
              </select> 
              <div class="help-block with-errors"></div>
            </div>
          </div>

          <div class="col-sm-3">
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <label>Redis Date</label>
              <div class="input-group">
                <input type="text" class="form-control mydatepicker" id="ddlRedisDate" data-toggle="validator" data-error="Date Cannot less than Today" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
                <span class="input-group-addon"><i class="icon-calender"></i></span>
              </div>
            </div>
          </div>

          <div class="col-sm-1">

            <div class="form-group">

              <label>&nbsp;</label>

              <button id="btn_save" class="btn btn-info waves-effect custom-input-width btn-block" type="submit"><i class="fa fa-save"></i> <span>Save</span></button>

            </div>

          </div>

        </div>
      </div>

      <div class="row"> <!--  start row redistribution -->

        <div class="col-sm-3"> <!--  start redis source -->

          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
               <label class="form-control-label" for="lblDirectorate">Directorate</label>
               <select class="form-control" id="ddlDirectorate" name="ddlDirectorate" data-toggle="validator" data-error="Please choose one" required disabled="true">
                <option value="">-- Choose Directorate -- </option> 
              </select> 
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label class="form-control-label" for="lblDivision">Division</label>
              <select class="form-control" id="ddlDivision" name="ddlDivision" data-toggle="validator" data-error="Please choose one" required disabled="true">
                <option value="">-- Choose Division -- </option> 
              </select> 
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label class="form-control-label" for="lblUnit">Unit</label>
              <select class="form-control" id="ddlUnit" name="ddlUnit" data-toggle="validator" data-error="Please choose one" required disabled="true">
                <option value="">-- Choose Unit -- </option> 
              </select> 
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label class="form-control-label" for="lblTribeUsecase">Tribe / Usecase</label>
              <select class="form-control" id="ddlTribeUsecase" name="ddlTribeUsecase" data-toggle="validator" data-error="Please choose one" required disabled="true">
                <option value="">-- Choose Tribe / Usecase -- </option> 
              </select> 
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>

         

        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label class="form-control-label" for="lblRKAPname">RKAP Name</label>
              <select class="form-control" id="ddlRKAPName" name="ddlRKAPName" data-toggle="validator" data-error="Please choose one" required disabled="true">
                <option value="">-- Choose RKAP Name -- </option> 
              </select> 
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>

        <!-- <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label class="form-control-label" for="lblFSName">FS Name</label>
              <select class="form-control" id="ddlFSName" name="ddlFSName" data-toggle="validator" data-error="Please choose one" required disabled="true">
                <option value="">-- Choose FS Name --</option> 
              </select> 
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div> -->

        <!-- <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label class="form-control-label" for="lblTier">Program ID</label>
              <select class="form-control" id="ddlTier" name="ddlTier" data-toggle="validator" data-error="Please choose one" required disabled="true">
                <option value="">-- Choose Program ID -- </option> 
              </select> 
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div> -->

       
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label class="form-control-label" for="lblFundAvailable">Fund Available</label>
              <input type="text" class="form-control" id="txtFundAvailable" name="txtFundAvailable" placeholder="0" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" value="0" required readonly/>
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>

      </div> <!--  end redis source -->

      <div class="col-sm-3">  <!--  start div button redis -->

        <div class="row">
          <div class="col-sm-3"></div>
          <div class="col-sm-6" style="padding-top: 200px;">
            <div class="form-group">
              <label>&nbsp;</label>
              <button id="btn_redis" class="btn btn-success waves-effect custom-input-width btn-block" type="button"> <span> Redis To </span> <i class="fa fa-arrow-right"></i></button>
            </div>
          </div>
          <div class="col-sm-3"></div> 
        </div>

      </div> <!--  end div button redis -->

      <div class="col-sm-3"> <!--  start redis destination -->

        <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label class="form-control-label" for="lblRKAPname2">RKAP Name</label>
            <select class="form-control" id="ddlRKAPName2" name="ddlRKAPName2" data-toggle="validator" data-error="Please choose one" required disabled="true">
              <option value="">-- Choose RKAP Name -- </option> 
            </select> 
            <div class="help-block with-errors"></div>
          </div>
        </div>
      </div>

      <!-- <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label class="form-control-label" for="lblFSName2">FS Name</label>
            <select class="form-control" id="ddlFSName2" name="ddlFSName2" data-toggle="validator" data-error="Please choose one" required disabled="true">
              <option value="">-- Choose FS Name --</option> 
            </select> 
            <div class="help-block with-errors"></div>
          </div>
        </div>
      </div> -->

      <!-- <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label class="form-control-label" for="lblTier2">Program ID</label>
            <select class="form-control" id="ddlTier2" name="ddlTier2" data-toggle="validator" data-error="Please choose one" required disabled="true">
              <option value="">-- Choose Program ID -- </option> 
            </select> 
            <div class="help-block with-errors"></div>
          </div>
        </div>
      </div> -->

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label class="form-control-label" for="lblFundAvailable2">Fund Available</label>
            <input type="text" class="form-control" id="txtFundAvailable2" name="txtFundAvailable2" placeholder="0" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" value="0" required readonly/>
            <div class="help-block with-errors"></div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label class="form-control-label" for="lblFundAvailableWillBe">Fund Available Will Be</label>
            <input type="text" class="form-control" id="txtFundAvailableWillBe" name="txtFundAvailableWillBe" placeholder="0" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" value="0" required disabled="true"/>
            <div class="help-block with-errors"></div>
          </div>
        </div>
      </div>

    </div> <!--  end redis destination -->



  </div> <!--  end row redistribution -->

</form> 

</div>


<div class="white-box boxshadow">     

  <div class="row">

    <div class="col-sm-3">
      <div class="form-group">
        <label>Redis Date From</label>
        <div class="input-group">
          <input type="text" class="form-control mydatepicker" id="ddlRedisDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
          <span class="input-group-addon"><i class="icon-calender"></i></span>
        </div>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label>Redis Date To</label>
        <div class="input-group">
          <input type="text" class="form-control mydatepicker" id="ddlRedisDateTo" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
          <span class="input-group-addon"><i class="icon-calender"></i></span>
        </div>
      </div>
    </div>

    <div class="col-sm-3">

      <div class="form-group">

        <label>&nbsp;</label>

        <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>

      </div>

    </div> 

    <div class="col-sm-3">

      <div class="form-group">

        <label>&nbsp;</label>

        <button id="btnDownload" class="btn btn-success btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-file-excel-o"></i> <span>Download</span></button>

      </div>

    </div>

  </div>

</div>


</div>

<div class="row" id="tblDataBudgetRedistribution">

  <div class="col-md-12">

    <div class="white-box">

      <table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover dataTable small">

        <thead>

          <tr>

            <th class="text-center">No.</th>

            <th class="text-center">Date Redis</th>

            <th class="text-center">Directorate</th>

            <th class="text-center">Division</th>

            <th class="text-center">Unit</th>

            <th class="text-center">RKAP Name Source</th>

             <th class="text-center">RKAP Name Target</th>

            <th class="text-center">Tribe Usecase Source</th>

            <!-- <th class="text-center">FS Name Source</th>

            <th class="text-center">FS Name Destination</th> -->

            <th class="text-center">Amount Redis</th>

          </tr>

        </thead>

      </table>

    </div>

  </div>

</div>

<script>

 $(document).ready(function(){

  const opt_default = '<option value="0" data-name="">-- Choose --</option>';
  getBudgetYear();
  getDirectorate();
  $('#tblDataBudgetRedistribution').hide();

  let redis_date_from = $("#ddlRedisDateFrom").val();
  let redis_date_to = $("#ddlRedisDateTo").val();
  let url  = baseURL + 'budgetredistribution/load_data_budget_redistribution';

  let ajaxData = {

    "url"  : url,

    "type" : "POST",

    "data"    : function ( d ) {

      d.redis_date_from = redis_date_from;
      d.redis_date_to   = redis_date_to;
    }

  }

  let jsonData = [
  { "data": "no", "width": "10px", "class": "text-center" },
  { "data": "date_redis", "width": "150px", "class": "text-left" },
  { "data": "directorate", "width": "250px", "class": "text-left" },
  { "data": "division", "width": "250px", "class": "text-left" },
  { "data": "unit", "width": "250px", "class": "text-left" },
  { "data": "rkap_name_source", "width": "300px", "class": "text-left" },
  { "data": "rkap_name_target", "width": "300px", "class": "text-left" },
  { "data": "tribe_usecase_source", "width": "200px", "class": "text-left" },
  // { "data": "fs_name_source", "width": "200px", "class": "text-left" },
  // { "data": "fs_name_target", "width": "200px", "class": "text-left" },
  { "data": "amount_redis", "width": "150px", "class": "text-left" }
  ];

  data_table(ajaxData,jsonData);

  table = $('#table_data').DataTable();

  $('.mydatepicker').datepicker({
    format: 'dd/mm/yyyy',
    todayHighlight:'TRUE',
    autoclose: true,
  });

  function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
  }

  function getBudgetYear(){
    $.ajax({
      url   : baseURL + 'budgetrelocation/load_ddl_budget_year',
      type  : "POST",
      dataType: "html",
      success : function(result){
        $("#ddlBudgetYear").html("");       
        $("#ddlBudgetYear").html(result);         
      }
    });     
  }

  function getDirectorate(){
    $.ajax({
      url   : baseURL + 'Master/load_ddl_directorat',
      type  : "POST",
      dataType: "html",
      success : function(result){
        $("#ddlDirectorate").html("");       
        $("#ddlDirectorate").html(result);         
      }
    });     
  }

  function getDivision()
  {
    let param_id_dir_code = $("#ddlDirectorate").val();

    $.ajax({
      url   : baseURL + 'Master/load_ddl_division',
      type  : "POST",
      data  : {param_id_dir_code :  param_id_dir_code},
      dataType: "html",
      success : function(result){
        $("#ddlDivision").html("");       
        $("#ddlDivision").html(result);          
      }
    });     
  }

  function getUnit()
  {
    let param_id_division = $("#ddlDivision").val();

    $.ajax({
      url   : baseURL + 'Master/load_ddl_unit',
      type  : "POST",
      data  : {param_id_division :  param_id_division},
      dataType: "html",
      success : function(result){
        $("#ddlUnit").html("");       
        $("#ddlUnit").html(result);          
      }
    });     
  }

  function getTribeUsecase()
  {
    let param_directorat = $("#ddlDirectorate").find(':selected').attr('data-name');
    let param_division = $("#ddlDivision").find(':selected').attr('data-name');
    let param_unit_name = $("#ddlUnit").find(':selected').attr('data-name');
    let param_year = $("#ddlBudgetYear").val();

    $.ajax({
      url   : baseURL + 'budgetrelocation/load_ddl_tribe',
      type  : "POST",
      data  : {param_directorat_names :  param_directorat, param_division_names :  param_division, param_unit_names :  param_unit_name, param_year : param_year},
      dataType: "html",
      success : function(result){
        $("#ddlTribeUsecase").html("");       
        $("#ddlTribeUsecase").html(result);          
      }
    });     
  }

  function getRKAPName()
  {
    let param_directorat = $("#ddlDirectorate").find(':selected').attr('data-name');
    let param_division = $("#ddlDivision").find(':selected').attr('data-name');
    let param_unit_name = $("#ddlUnit").find(':selected').attr('data-name');
    let param_tribe_usecase = $("#ddlTribeUsecase").val();
    let param_year = $("#ddlBudgetYear").val();
    // let param_fs_name = $("#ddlFSName").val();

    $.ajax({
      url   : baseURL + 'budgetrelocation/load_ddl_rkap_name',
      type  : "POST",
      // data  : {param_directorat_names :  param_directorat, param_division_names :  param_division, param_unit_names :  param_unit_name, param_tribe_usecases : param_tribe_usecase, param_year : param_year},
      data  : {'param_directorat_names' :  param_directorat, 'param_division_names' :  param_division, 'param_unit_names' :  param_unit_name, 'param_tribe_usecases' : param_tribe_usecase, 'param_year' : param_year},
      dataType: "html",
      success : function(result){
        $("#ddlRKAPName").html("");       
        $("#ddlRKAPName").html(result);          
      }
    });     
  }

  // function getFSName()
  // {
  //   let param_directorat = $("#ddlDirectorate").find(':selected').attr('data-name');
  //   let param_division = $("#ddlDivision").find(':selected').attr('data-name');
  //   let param_unit_name = $("#ddlUnit").find(':selected').attr('data-name');
  //   let param_tribe_usecase = $("#ddlTribeUsecase").val();
  //   // let param_rkap_name = $("#ddlRKAPName").val();
  //   // let param_program_id = $("#ddlTier").val();
  //   let param_year = $("#ddlBudgetYear").val();

  //   $.ajax({
  //     url   : baseURL + 'budgetrelocation/load_ddl_fs_name',
  //     type  : "POST",
  //     // data  : { param_directorat_names :  param_directorat, param_division_names :  param_division, param_unit_names :  param_unit_name, param_rkap_names :  param_rkap_name, param_program_ids :  param_program_id, param_year : param_year },
  //     data  : { 'param_directorat_names' :  param_directorat, 'param_division_names' :  param_division, 'param_unit_names' :  param_unit_name, 'param_tribe_usecases' : param_tribe_usecase, 'param_year' : param_year },
  //     dataType: "html",
  //     success : function(result){
  //       $("#ddlFSName").html("");       
  //       $("#ddlFSName").html(result);          
  //     }
  //   });     
  // }

  // function getProgramID()
  // {
  //   let param_directorat = $("#ddlDirectorate").find(':selected').attr('data-name');
  //   let param_division = $("#ddlDivision").find(':selected').attr('data-name');
  //   let param_unit_name = $("#ddlUnit").find(':selected').attr('data-name');
  //   let param_rkap_name = $("#ddlRKAPName").val();
  //   let param_year = $("#ddlBudgetYear").val();

  //   $.ajax({
  //     url   : baseURL + 'budgetrelocation/load_ddl_program_id',
  //     type  : "POST",
  //     data  : { param_directorat_names :  param_directorat, param_division_names :  param_division, param_unit_names :  param_unit_name, param_rkap_names :  param_rkap_name, param_year : param_year },
  //     dataType: "html",
  //     success : function(result){
  //       $("#ddlTier").html("");       
  //       $("#ddlTier").html(result);          
  //     }
  //   });     
  // }

  function getRKAPName2()
  {
    let param_directorat = $("#ddlDirectorate").find(':selected').attr('data-name');
    let param_division = $("#ddlDivision").find(':selected').attr('data-name');
    let param_unit_name = $("#ddlUnit").find(':selected').attr('data-name');
    let param_tribe_usecase = $("#ddlTribeUsecase").val();
    let param_ga = $("#ddlRKAPName").find(':selected').attr('data-ga');
    let param_proc_type = $("#ddlRKAPName").find(':selected').attr('data-proc');
    let param_year = $("#ddlBudgetYear").val();
    // let param_fs_name = $("#ddlFSName2").val();
    let param_rkap_name = $("#ddlRKAPName").val();
    // let param_rkap_name_description = $("#ddlRKAPName").find(':selected').attr('data-name'); // dilepas dulu

    $.ajax({
      url   : baseURL + 'budgetrelocation/load_ddl_rkap_name',
      type  : "POST",
      // data  : { param_directorat_names :  param_directorat, param_division_names :  param_division, param_unit_names :  param_unit_name, param_tribe_usecases : param_tribe_usecase, param_year : param_year, param_proc_types : param_proc_type, param_gas : param_ga },
      data  : { 'param_directorat_names' :  param_directorat, 'param_division_names' :  param_division, 'param_unit_names' :  param_unit_name, 'param_year' : param_year, 'param_tribe_usecases' : param_tribe_usecase, 'param_proc_types' : param_proc_type, 'param_gas' : param_ga, 'param_rkap_names' : param_rkap_name },
      dataType: "html",
      success : function(result){
        $("#ddlRKAPName2").html("");       
        $("#ddlRKAPName2").html(result);          
      }
    });     
  }

  //  function getFSName2()
  // {
  //   let param_directorat = $("#ddlDirectorate").find(':selected').attr('data-name');
  //   let param_division = $("#ddlDivision").find(':selected').attr('data-name');
  //   let param_unit_name = $("#ddlUnit").find(':selected').attr('data-name');
  //   let param_tribe_usecase = $("#ddlTribeUsecase").val();
  //   // let param_program_id = $("#ddlTier2").val();
  //   let param_year = $("#ddlBudgetYear").val();
    

  //   $.ajax({
  //     url   : baseURL + 'budgetrelocation/load_ddl_fs_name',
  //     type  : "POST",
  //     // data  : { param_directorat_names :  param_directorat, param_division_names :  param_division, param_unit_names :  param_unit_name, param_rkap_names :  param_rkap_name, param_program_ids :  param_program_id, param_year : param_year },
  //     data  : { 'param_directorat_names' :  param_directorat, 'param_division_names' :  param_division, 'param_unit_names' :  param_unit_name,  'param_tribe_usecases' : param_tribe_usecase,  'param_year' : param_year },
  //     dataType: "html",
  //     success : function(result){
  //       $("#ddlFSName2").html("");       
  //       $("#ddlFSName2").html(result);          
  //     }
  //   });     
  // }

  // , 'param_rkap_name_descriptions' : param_rkap_name_description di lepas dulu

  // function getProgramID2()
  // {
  //   let param_directorat = $("#ddlDirectorate").find(':selected').attr('data-name');
  //   let param_division = $("#ddlDivision").find(':selected').attr('data-name');
  //   let param_unit_name = $("#ddlUnit").find(':selected').attr('data-name');
  //   let param_rkap_name = $("#ddlRKAPName2").val();
  //   let param_year = $("#ddlBudgetYear").val();

  //   $.ajax({
  //     url   : baseURL + 'budgetrelocation/load_ddl_program_id',
  //     type  : "POST",
  //     data  : { param_directorat_names :  param_directorat, param_division_names :  param_division, param_unit_names :  param_unit_name, param_rkap_names :  param_rkap_name, param_year : param_year },
  //     dataType: "html",
  //     success : function(result){
  //       $("#ddlTier2").html("");       
  //       $("#ddlTier2").html(result);          
  //     }
  //   });     
  // }

  

  function getAvaFund()
  {
    let paramzz_directorat = $("#ddlDirectorate").find(':selected').attr('data-name');
    let paramzz_division = $("#ddlDivision").find(':selected').attr('data-name');
    let paramzz_unit_name = $("#ddlUnit").find(':selected').attr('data-name');
    let paramzz_tribe_usecase = $("#ddlTribeUsecase").val();
    let paramzz_year = $("#ddlBudgetYear").val();
    // let paramzz_fs_name = $("#ddlFSName").val();
    let paramzz_rkap_name = $("#ddlRKAPName").val();
    // let paramzz_program_id = $("#ddlTier").val();

    // console.log(paramzz_rkap_name);
    // console.log(paramzz_year);

    $.ajax({
      url   : baseURL + 'budgetrelocation/get_fund_available',
      type  : "POST",
      // data  : { param_directorat_namez :  paramzz_directorat, param_division_namez :  paramzz_division, param_unit_namez :  paramzz_unit_name, param_rkap_namez :  paramzz_rkap_name , param_program_idz :  paramzz_program_id , param_fs_namez :  paramzz_fs_name , param_yearz : paramzz_year  },
       data  : { 'param_directorat_namez' :  paramzz_directorat, 'param_division_namez' :  paramzz_division, 'param_unit_namez' :  paramzz_unit_name, 'param_tribe_usecasez' :  paramzz_tribe_usecase ,  'param_rkap_namez' :  paramzz_rkap_name , 'param_yearz' : paramzz_year  },
      dataType: "json",
      success : function(result){
        if(result != null)
        {

          console.log(result.FA_FS);

          // val_nominal = numberWithCommas(result.NOMINAL);
          val_ava = numberWithCommas(result.FA_FS);

          console.log(val_ava);

          document.getElementById("txtFundAvailable").value = val_ava;  
        }       
      }
    });     
  }
  

  function getAvaFund2()
  {
    let paramzz_directorat = $("#ddlDirectorate").find(':selected').attr('data-name');
    let paramzz_division = $("#ddlDivision").find(':selected').attr('data-name');
    let paramzz_unit_name = $("#ddlUnit").find(':selected').attr('data-name');
    // let paramzz_fs_name = $("#ddlFSName2").val();
    let paramzz_rkap_name = $("#ddlRKAPName2").val();
    let paramzz_tribe_usecase = $("#ddlTribeUsecase").val();
    // let paramzz_program_id = $("#ddlTier2").val();
    let paramzz_year = $("#ddlBudgetYear").val();

    // console.log(paramzz_rkap_name);
    // console.log(paramzz_year);

    $.ajax({
      url   : baseURL + 'budgetrelocation/get_fund_available',
      type  : "POST",
      // data  : { param_directorat_namez :  paramzz_directorat, param_division_namez :  paramzz_division, param_unit_namez :  paramzz_unit_name, param_rkap_namez :  paramzz_rkap_name , param_program_idz :  paramzz_program_id , param_fs_namez :  paramzz_fs_name , param_yearz : paramzz_year  },
      data  : { 'param_directorat_namez' :  paramzz_directorat, 'param_division_namez' :  paramzz_division, 'param_unit_namez' :  paramzz_unit_name, 'param_tribe_usecasez' :  paramzz_tribe_usecase , 'param_rkap_namez' :  paramzz_rkap_name ,'param_yearz' : paramzz_year  },
      dataType: "json",
      success : function(result){
        if(result != null)
        {

          console.log(result.FA_FS);

          // val_nominal = numberWithCommas(result.NOMINAL);
          val_ava = numberWithCommas(result.FA_FS);

          console.log(val_ava);

          document.getElementById("txtFundAvailable2").value = val_ava;  
        }       
      }
    });     
  }


  let directorat = $("#ddlDirectorate").find(':selected').attr('data-name');
  let division = $("#ddlDivision").find(':selected').attr('data-name');
  let unit = $("#ddlUnit").find(':selected').attr('data-name');
  let year = $("#ddlBudgetYear").val();
  // let tribe_usecase = $("#ddlTribeUsecase").val();
  let rkap_name = $("#ddlRKAPName").val();
  $('#tblDataBudgetRedistribution').hide();


  $("#ddlBudgetYear").on("change", function(){
    $("#ddlDirectorate").attr("disabled",false); 
    $("#ddlDirectorate2").attr("disabled",false); 
  });

  $("#ddlDirectorate").on("change", function(){
    getDivision();
    $("#ddlUnit").html(opt_default); 
    $("#ddlTribeUsecase").html(opt_default);
    $("#ddlRKAPName").html(opt_default);
    // $("#ddlTier").html(opt_default);
    $("#ddlFSName").html(opt_default);

    $("#ddlRKAPName2").html(opt_default);
    // $("#ddlTier2").html(opt_default);
    $("#ddlFSName2").html(opt_default);

    $("#ddlDivision").attr("disabled",false);
    $("#btn_redis").attr("disabled",false);
    $('#btn_redis').removeClass('btn-default');
    $('#btn_redis').addClass('btn-success');
    document.getElementById("txtFundAvailableWillBe").value = 0;
    document.getElementById("txtFundAvailable").value = 0; 
    document.getElementById("txtFundAvailable2").value = 0; 
  });

  $("#ddlDivision").on("change", function(){
    getUnit();
    $("#ddlTribeUsecase").html(opt_default);
    $("#ddlRKAPName").html(opt_default);
    // $("#ddlTier").html(opt_default);
    $("#ddlFSName").html(opt_default);

    $("#ddlRKAPName2").html(opt_default);
    // $("#ddlTier2").html(opt_default);
    $("#ddlFSName2").html(opt_default);

    $("#ddlUnit").attr("disabled",false); 
    $("#btn_redis").attr("disabled",false);
    $('#btn_redis').removeClass('btn-default');
    $('#btn_redis').addClass('btn-success');
    document.getElementById("txtFundAvailableWillBe").value = 0;
    document.getElementById("txtFundAvailable").value = 0;
    document.getElementById("txtFundAvailable2").value = 0; 
  });

  $("#ddlUnit").on("change", function(){
    getTribeUsecase();
    // getFSName();
    $("#ddlRKAPName").html(opt_default);
    // $("#ddlTier").html(opt_default);
    $("#ddlFSName").html(opt_default);

    $("#ddlRKAPName2").html(opt_default);
    // $("#ddlTier2").html(opt_default);
    $("#ddlFSName2").html(opt_default);

    $("#ddlFSName").attr("disabled",false); 
    $("#ddlTribeUsecase").attr("disabled",false); 
    $("#btn_redis").attr("disabled",false);
    $('#btn_redis').removeClass('btn-default');
    $('#btn_redis').addClass('btn-success');
    document.getElementById("txtFundAvailableWillBe").value = 0;
    document.getElementById("txtFundAvailable").value = 0;
    document.getElementById("txtFundAvailable2").value = 0; 
  });

  $("#ddlTribeUsecase").on("change", function(){
    getRKAPName();
   
    $("#ddlRKAPName2").html(opt_default);
    $("#ddlTier2").html(opt_default);
    $("#ddlFSName2").html(opt_default);

    $("#ddlRKAPName").attr("disabled",false); 
    $("#btn_redis").attr("disabled",false);
    $('#btn_redis').removeClass('btn-default');
    $('#btn_redis').addClass('btn-success');
    document.getElementById("txtFundAvailableWillBe").value = 0;
    document.getElementById("txtFundAvailable").value = 0;
    document.getElementById("txtFundAvailable2").value = 0; 
  });

  $("#ddlRKAPName").on("change", function(){
    
    getAvaFund();
    getRKAPName2();

    $("#ddlRKAPName2").attr("disabled",false);
    $("#btn_redis").attr("disabled",false);
    $('#btn_redis').removeClass('btn-default');
    $('#btn_redis').addClass('btn-success');
    document.getElementById("txtFundAvailableWillBe").value = 0;
    document.getElementById("txtFundAvailable2").value = 0;
  });

//   $("#ddlFSName2").on("change", function(){
//     getRKAPName2();
//     $("#ddlRKAPName2").attr("disabled",false); 

    
//     $("#btn_redis").attr("disabled",false);
//     $('#btn_redis').removeClass('btn-default');
//     $('#btn_redis').addClass('btn-success');
//     // document.getElementById("txtAmountToReloc").value = 0;
   

//     document.getElementById("txtFundAvailableWillBe").value = 0;
//     document.getElementById("txtFundAvailable2").value = 0;
    
// });

  $("#ddlRKAPName2").on("change", function(){

    getAvaFund2();
    $("#btn_redis").attr("disabled",false);
    $('#btn_redis').removeClass('btn-default');
    $('#btn_redis').addClass('btn-success');

    document.getElementById("txtFundAvailableWillBe").value = 0;
  });



  $('#form-save').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) 
    {
      console.log('tidak valid');
    }
    else 
    {

      let val_amount_to_redis = document.getElementById("txtFundAvailable").value;
      let number_amount_to_redis = parseInt(val_amount_to_redis.replace(/[^a-zA-Z0-9]/g, ""));

      let val_fund_amount_willbe = document.getElementById("txtFundAvailableWillBe").value;
      let number_fund_amount_willbe = parseInt(val_fund_amount_willbe.replace(/[^a-zA-Z0-9]/g, ""));

      let redis_date = document.getElementById("ddlRedisDate").value;
      let today = new Date();
      let date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();

      let redis = redis_date.split("/");
      let convredis = redis[2] + "-" + redis[1] + "-" + redis[0];

      let now = date.split("/");
      let convnow = now[2] + "-" + now[1] + "-" + now[0];

      let fixredis = new Date(convredis.toString());
      let fixnow = new Date(convnow.toString());


      console.log(fixredis);
      console.log(fixnow);


      if(number_amount_to_redis > 0 && number_fund_amount_willbe > 0)
      {

       $.ajax({
        url     : baseURL + 'budgetredistribution/save_budget_redis',
        type    : "POST",
        data    : $('#form-save').serialize(),

        success : function(result){
          console.log(result);
          if (result==1) {
       // table.ajax.reload(null, false);
       customNotif('Success','Budget success fully redistributed','success', 4000 );
     } 
     else if (result==0)
     {
      $("body").removeClass("loading");
      customNotif('Failed', 'Budget failed to redistribute','error', 4000 );
    } 
    else 
    {
     $("body").removeClass("loading");
     customNotif('Failed', 'No Budget redistributed','error', 4000 );  
   }
 }
});

     }
     else
     {
      $("body").removeClass("loading");
      customNotif('Failed', 'Amount Cannot be 0 !!','error', 4000 );
    }

  }
  e.preventDefault();
});

  $("#btn_redis").on('click', function () 
  {

    let val_amount_to_redis = document.getElementById("txtFundAvailable").value;
    let val_fund_available_resource = document.getElementById("txtFundAvailable").value;
    let val_fund_available = document.getElementById("txtFundAvailable2").value;

    let number_amount_to_redis = parseInt(val_amount_to_redis.replace(/[^a-zA-Z0-9]/g, ""));
    let number_fund_available_resource = parseInt(val_fund_available_resource.replace(/[^a-zA-Z0-9]/g, ""));
    let number_fund_available = parseInt(val_fund_available.replace(/[^a-zA-Z0-9]/g, ""));
    let number_fund_available_willbe = number_fund_available + number_amount_to_redis;

    if(number_amount_to_redis > 0)
    {

      if(number_amount_to_redis > number_fund_available_resource)
      {
        customNotif('Failed', "Amount to Redis is more than Fund Available !!!", 'error');
      }
      else
      {
        let val_willbe = numberWithCommas(number_fund_available_willbe);
        document.getElementById("txtFundAvailableWillBe").value = val_willbe;
        $("#btn_redis").attr("disabled",true);
        $('#btn_redis').removeClass('btn-success');
        $('#btn_redis').addClass('btn-default');
      }

    }
    else
    {
      customNotif('Failed', "Amount To Redis is 0!!!", 'error');
    }


  });

  $("#btnDownload").on("click", function(){

    let vredis_date_from = '';
    let vredis_date_to = '';

    let url   ="<?php echo site_url(); ?>budgetredistribution/download_data_budget_redis";

    vredis_date_from = $("#ddlRedisDateFrom").val();
    vredis_date_to = $("#ddlRedisDateTo").val();

    window.open(url+'?date_from='+vredis_date_from +"&date_to="+ vredis_date_to, '_blank');

    window.focus();

  });

  $('#btnView').on( 'click', function () {

    redis_date_from = $("#ddlRedisDateFrom").val();
    redis_date_to = $("#ddlRedisDateTo").val();
    table.draw();

    $('#tblDataBudgetRedistribution').slideDown(700);
    $('#tblDataBudgetRedistribution').css( 'display', 'block' );
    table.columns.adjust().draw();
  });

});

</script>