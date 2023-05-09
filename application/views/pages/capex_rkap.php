<div class="row">

<style>
  .custom-input-file{
    width: 100%;
    padding: 5px 10px;
    border-radius: 5px;
    background: #edf1f5;
    border: 1px solid #e4e7ea;
    outline: none;
  }
  .custom-input-file{
    width: 100%;
    padding: 5px 10px;
    border-radius: 5px;
    background: #edf1f5;
    border: 1px solid #e4e7ea;
    outline: none !important;
  }
</style>


  <div class="white-box boxshadow">
  <div class="row">
    <div class="col-md-2">
      <div class="form-group">
          <label>Budget Year</label>
          <select class="form-control" id="year">
            <option value="0">--Pilih--</option>
              <?php 
                  foreach($get_exist_year as $value):
                      echo "<option value='".$value['TAHUN']."'>".$value['TAHUN']."</option>";
                  endforeach
              ?>
          </select>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
      <label>&nbsp;</label>
        <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block custom-input-file" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>
      </div>
    </div>
    <form method="post" action="<?=site_url()?>capex/saveimport" class="form-horizontal" enctype="multipart/form-data">  
       <label class="form-control-label">Import File</label>
      <div class="form-group">
         <div class="col-md-3 m-b-10">
            <!-- <input class="custom-input-file" type="file" name="file" accept=".xls,.xlsx" id="fileToUpload"> -->
            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-outline btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
                <input id="fileToUpload" type="file" name="file" accept=".xls,.xlsx" required="required"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
            </div>
         </div>
          <div class="col-md-2 m-b-10">
            <input type="submit" class="btn btn-info btn-rounded btn-block" value="Import" name="import" id="btnImport">
          </div>
          <div class="col-md-2 m-b-10">
            <button id="btnPrint" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa fa-file-excel-o"></i> <span>Download</span></button>
          </div>
      </div>
    </form>   
  </div>

</div>

</div>

<div class="row" id="tblData">

	<div class="col-md-12">

      <div class="white-box">
	        <table id="table_data" class="table table-hover small display table-striped table-responsive dataTable no-footer select">
	          <thead>
	            <tr>
                <th>Direktorat</th>
                <th>PIC</th>
                <th>No</th>
                <th>Division</th>
                <th>Unit</th>
                <th>Tribe/Usecase</th>
                <th>Capex/Opex</th>
                <th>B2B Arragement with Tsel (Yes/No)</th>
                <th>Sales chanel</th>
                <th>Parent Account</th>
                <th>Sub Parent</th>
                <th>Proc Type</th>
                <th>Month</th>
                <th>Periode</th>
                <th>RKAP Name (Description)</th>
                <th>Nominal</th>
                <th>Target Group</th>
                <th>Target Quantity</th>
                <th>Program Type</th>
                <th>Detail Activity</th>
                <th>Vendor (if possible)</th>
                <th>Entry Optimize Monitize</th>
      				</tr>
	          </thead>
	        </table>
      </div>

    </div>

</div>



<script>

	

  $(document).ready(function(){



	let year = $("#year").val();
  $('#tblData').hide();
  $('#btnImport').attr('disabled', true);

	

	let url  = baseURL + 'capex/load_data_rkap';

    let ajaxData = {

	                  "url"  : url,

	                  "type" : "POST",

	                  "data"    : function ( d ) {

	                              d.year = year;

	                            }

	                }

    let jsonData = [

                    { "data": "direktorat", "width": "100px" },
                    { "data": "pic", "width": "150px" },
                    { "data": "no", "width": "50px", "class": "py-4 text-center" },
                    { "data": "division", "width": "150px" },
                    { "data": "unit", "width": "150px" },
                    { "data": "tribe_usecase", "width": "100px" },
                    { "data": "capex_opex", "width": "100px", "class": "py-4 text-center" },
                    { "data": "b2b_arragement", "width": "250px" },
                    { "data": "sales_chanel", "width": "100px" },
                    { "data": "parent_account", "width": "100px", "class": "py-4 text-center" },
                    { "data": "sub_parent", "width": "100px" },
                    { "data": "proc_type", "width": "100px", "class": "py-4 text-center" },
                    { "data": "month", "width": "100px", "class": "py-4 text-center" },
                    { "data": "periode", "width": "100px", "class": "py-4 text-center" },
                    { "data": "rkap_name", "width": "100px" },
                    { "data": "nominal", "class": "py-4 text-right", "width": "100px" },
                    { "data": "target_group", "width": "200px" },
                    { "data": "target_quantity", "width": "100px", "class": "py-4 text-center" },
                    { "data": "program_type", "width": "150px", "class": "py-4 text-center" },
                    { "data": "detail_activity", "width": "200px" },
                    { "data": "vendor", "width": "200px" },
                    { "data": "entry_optimize", "width": "200px" }

		            ];

	data_table(ajaxData,jsonData);

  table = $('#table_data').DataTable();



  $("#btnPrint").on("click", function(){

    var vyear = "";

    var url   ="<?php echo site_url(); ?>capex/cetak_data";

    var vyear  = $("#year").val();

    window.open(url+'?year='+vyear, '_blank');

    window.focus();

  });


    $('#btnView').on( 'click', function () {

      year = $("#year").val();

      table.draw();

      console.log('xx');
      $('#tblData').slideDown(700);

    });

    $("#fileinput_remove").on("click", function(e) {
      $('#btnImport').attr('disabled', true);
    });

    $("#fileToUpload").bind("click", function(e) {
      lastValue = $(this).val();
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

        extension_allow = ['xlsx','xls'];
        extension       = this.files[0].name.split('.').pop().toLowerCase();
        
        if (extension_allow.indexOf(extension) < 0) {
          upload = false;
          alert('Extention not allowed')
          $(this).val(lastValue);
        }
        
        if(fileSize > 5800){
          upload = false;
          alert('Max file size 5M')
          $(this).val(lastValue);
        }

        if(upload){
          $('#btnImport').removeAttr('disabled');
        }else{
          $('#btnImport').attr('disabled', true);
        }
              
      }
    });








	/* Uploaded */

    

   /* folder     = "documents";

    url_upload = baseURL + 'upload/do_upload';

    url_delete = baseURL + 'upload/delete';



    var jqXHR;

    function upload_to_temps(progress_id){

      file = $('#file_attachment')[0].files[0];

      data = new FormData();

      data.append('attachment', file);

      data.append('folder', folder);

      $('#submit_btn').attr("disabled",true);

      $('#cancel_file').removeClass("d-none");



      jqXHR = $.ajax({

          url: url_upload,

          type: 'POST',

          data: data,

          enctype   : 'multipart/form-data',

          processData : false,

          contentType : false,

          // cache : false,

          cache: true,

          xhr: function() {

              var xhr = new window.XMLHttpRequest();

              xhr.upload.addEventListener("progress", function(event) {

                $(progress_id).parent().removeClass('d-none');

                percent    = (event.loaded / event.total) * 100;

                round      = Math.round(percent);

                percentage = round;

                if(round == 100){

                  percentage = round-1;

                }

                $(progress_id).css('width', percentage+'%');

                $(progress_id).html(percentage+'%');



                if(event.loaded == event.total){

                  $('#cancel_file').addClass("d-none");

                }

              }, false);

              return xhr;

          },

          success: function(result) {

              $('#file_input').val(result);

              $('#submit_btn').removeAttr('disabled');

              $('#delete_file').removeClass("d-none");

              $(progress_id).addClass("progress-bar-success");

              $(progress_id).css('width', '100%');

              $(progress_id).html("Complete");

          }

      });

    }



    $('#cancel_file').on( 'click', function () {

      $('#submit_btn').removeAttr('disabled');

      jqXHR.abort();

      $(this).addClass("d-none");

      reset_proggress("#progress");

    });*/



    $('#file_attachment').bind('change', function() {



        var form = $('#form-import')[0];

        var data = new FormData(form);



        $.ajax({

            type: "POST",

            enctype: 'multipart/form-data',

            url: "<?php echo base_url('ppn_wapu/insert_CSV') ?>",

            data: data,

     /* beforeSend  : function(){

         $("body").addClass("loading");

      },*/

            processData: false,

            contentType: false,

            cache: false,

            success: function (result) {

              console.log(result);

             /* if (result==1) {

              table.ajax.reload();

              $("body").removeClass("loading");

              flashnotif('Sukses','Data Berhasil di Import!','success' ); 

              $("#file_csv").val("");

              getSummary();

              } else if(result==2){

              $("body").removeClass("loading");

              flashnotif('Info','File Import CSV belum dipilih!','warning' ); 

              } else if(result==3){

              $("body").removeClass("loading");

              flashnotif('Info','Format File Bukan CSV!','warning' ); 

              } else {

              $("body").removeClass("loading");

              flashnotif('Error','Data Gagal di Import!','error' );

              }*/

            }

        });



      console.log('changgge')

    });



   /* $('#file_attachment').on( 'click', function () {

      if( $(this).val() != "" ){

        reset_proggress("#progress");

      }

    });



    function reset_proggress(progress_id){

      $('#file_input').val("");

      $("#delete_file").addClass('d-none');

      $(progress_id).removeClass("progress-bar-success");

      $(progress_id).parent().addClass('d-none');

      $(progress_id).css('width', '0%');

      $(progress_id).html('0%');

    }



    $('#delete_file').on( 'click', function () {

      $.ajax({

          url: url_delete,

          type: 'POST',

          beforeSend  : function(){

                          customLoading('show');

                        },

          data  : ({ filename : $('#file_input').val(), folder : folder}),

          dataType : 'json',

          success: function(result) {

            customLoading('hide');

            if (result.status == false) {

              customNotif('Failed', result.messages, 'error');

            }

            else{

              reset_proggress("#progress");

            }

          }

      });



    });*/



    /* End Uploaded */

    <?php if($this->session->flashdata('messages') != ""): ?>
      customNotif('Success', '<?= $this->session->flashdata('messages') ?>', 'success');
    <?php elseif($this->session->flashdata('error') != ""): ?>
      customNotif('Error', '<?= $this->session->flashdata('messages') ?>', 'error');
    <?php endif; ?>






  });

</script>