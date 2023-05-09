<div class="row">

  <div class="white-box boxshadow">     

    <div class="row">



      <form   id="form-import"  method="post" action="<?=site_url()?>master/save_import_upload_vendor" class="form-horizontal" enctype="multipart/form-data">  

        <label class="form-control-label">Import File</label>
        <div class="form-group">
          <div class="col-sm-3 m-b-10">
            <input class="custom-input-file" type="file" name="file" accept=".xls,.xlsx" id="fileToUpload" required="required">
          </div>
          <div class="col-md-2 col-sm-12 m-b-10">
            <input type="submit" class="btn btn-info btn-rounded btn-block" id="import" value="Import" name="import">
          </div>
        </div>
      </form>   



    </div>



  </div>

  <div class="row">   
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
          <table width="100%" class="table display table-bordered table-striped table-responsive" id="table_data"> 
            <thead>
              <tr>
                <th class="text-center">NO.</th>
                <th class="text-center">NAMA VENDOR</th>
                <th class="text-center">NPWP</th>
                <th class="text-center">ALAMAT</th>
                <th class="text-center">NO TELPN</th>
                <th class="text-center">NAMA PIC VENDOR</th>
                <th class="text-center">ALAMAT EMAIl</th>
                <th class="text-center">NAMA REKENING</th>
                <th class="text-center">NAMA BANK</th>
                <th class="text-center">ACCT NUMBER</th>
                <th class="text-center">ACTION</th>
              </tr>
            </thead>
          </table>
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
          <div class="col-md-offset-5 col-md-2 m-b-10">
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
      <form role="form" id="form-edit" data-toggle="validator">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white" id="modal-edit-label">Edit Data</h2>
        </div>
        <div class="modal-body"> 

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtNamaRekanan">Nama Vendor</label>
                  <input type="text" class="form-control" id="txtNamaRekanan" name="txtNamaRekanan" placeholder="Nama Vendor" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required />
                  <div class="help-block with-errors"></div>
                  <input type="hidden" name="isNewRecord" id="isNewRecord" val="0">
                  <input type="hidden" name="id_rekanan" id="id_rekanan">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtNPWP">NPWP</label>
                  <input type="text" class="form-control" id="txtNPWP" name="txtNPWP" placeholder="NPWP" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtAlamat">Alamat</label>
                  <textarea name="txtAlamat" id="txtAlamat" class="form-control" rows="3" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" placeholder="Alamat" required ></textarea>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtNoTelpWeb">No Telp</label>
                  <textarea name="txtNoTelpWeb" id="txtNoTelpWeb" class="form-control" rows="3" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" placeholder="No Telp" required ></textarea>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtPICVendor">Nama PIC Vendor</label>
                  <textarea name="txtPICVendor" id="txtPICVendor" class="form-control" rows="3" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" placeholder="Nama PIC Vendor" required ></textarea>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtEmail">Alamat Email</label>
                  <textarea name="txtEmail" id="txtEmail" class="form-control" rows="3" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" placeholder="Alamat Email" required ></textarea>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtNoRekening">Nama Rekening</label>
                  <input type="text" class="form-control" id="txtNoRekening" name="txtNoRekening" placeholder="Nama Rekening" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtNamaBank">Nama Bank</label>
                  <input type="text" class="form-control" id="txtNamaBank" name="txtNamaBank" placeholder="Nama Bank" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtAcctNumber">Acct Number</label>
                  <textarea name="txtAcctNumber" id="txtAcctNumber" class="form-control" rows="3" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" placeholder="Acct Number" required ></textarea>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-default"><i class="fa fa-trash-o"></i> Clear</button>
          <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> CANCEL</button>
          <button type="submit" class="btn btn-info waves-effect" id="btnSave"><i class="fa fa-save"></i> Save</button>
        </div>
      </form>  
    </div>
  </div>
</div>

<script>
 $(document).ready(function () {

  let url = baseURL + 'master/load_data_vendor';
  let ajaxData = {
    "url"  : url,
    "type" : "POST"
  }
  let jsonData = [
  { "data": "no", "width":"10px", "class":"text-center"},
  { "data": "nama_rekanan", "width":"200px", "class":"text-left"},
  { "data": "npwp", "width":"150px", "class":"text-left"},
  { "data": "alamat", "width":"300px", "class":"text-left"},
  { "data": "no_tlp", "width":"200px", "class":"text-left"},
  { "data": "pic_vendor", "width":"200px", "class":"text-left"},
  { "data": "alamat_email", "width":"100px", "class":"text-left"},
  { "data": "nama_rekening", "width":"300px", "class":"text-left"},
  { "data": "nama_bank", "width":"200px", "class":"text-left"},
  { "data": "acct_number", "width":"200px", "class":"text-left"},
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

  form = $('#form-edit')[0];
  form.reset();

});

 $('#form-edit').validator().on('submit', function(e) {
  if (!e.isDefaultPrevented()){
    $.ajax({
      url     : baseURL + 'master/save_vendor',
      type    : "POST",
      data    : $('#form-edit').serialize(),
      success : function(result){
        if (result==1) {
          table.ajax.reload(null, false);
          $("#modal-edit").modal('hide');
          customNotif('Success','Record changed!','success', 4000 );
        }
        else if (result==0) {
          $("body").removeClass("loading");
          customNotif('Failed', result,'error', 4000 );
        }
        else{
          $("body").removeClass("loading");
          customNotif('Failed', result,'error', 4000 );
        }
      }
    });
  }
  e.preventDefault();
});

 $('#table_data').on( 'click', 'a.action-edit', function () {

  data = table.row( $(this).parents('tr') ).data();

  $("#isNewRecord").val("0");
  $("#id_rekanan").val(data.id_rekanan);
  $("#txtNamaRekanan").val(data.nama_rekanan);
  $("#txtNPWP").val(data.npwp);
  $("#txtAlamat").val(data.alamat);
  $("#txtNoTelpWeb").val(data.no_tlp);
  $("#txtPICVendor").val(data.pic_vendor);
  $("#txtEmail").val(data.alamat_email);
  $("#txtNamaBank").val(data.nama_bank);
  $("#txtNoRekening").val(data.nama_rekening);
  $("#txtAcctNumber").val(data.acct_number);
  $("#modal-edit-label").html('Edit : ' +  data.nama_rekanan );
  $("#modal-edit").modal('show');
});

 $('#table_data ').on( 'click', 'a.action-delete', function () {

  data      = table.row( $(this).parents('tr') ).data();
  id_delete = data.id_rekanan;

  $("#modal-delete-label").html('Delete Data : ' + data.nama_rekanan );
  $("#modal-delete").modal('show');
});

 $('#button-delete').on( 'click', function () {

  $.ajax({
    url       : baseURL + 'master/delete_vendor/' + id_delete,
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
  var url   ="<?php echo site_url(); ?>Master/cetak_data_vendor";
  window.open(url,'_blank');
  window.focus();
});

 $('#form-import').validator().on('submit', function(e) {
  if (!e.isDefaultPrevented()){
    $.ajax({
      url     : baseURL + 'master/save_import_upload_vendor',
      type    : "POST",
      data:  new FormData(this),
      contentType: false,
      cache: false,
      processData:false,
      dataType:'json',
      beforeSend  : function()
      {
        customLoading('show');
      },
      success : function(result){
        console.log(result);
        if (result.status == true) {
          $("#fileToUpload").val("");
          table.ajax.reload(null, false);
          customLoading('hide');
          customNotif('Success', result.messages, 'success');
        } else {
           $("#fileToUpload").val("");
          customNotif('Failed', result.messages, 'error');
          customLoading('hide');
        }
      }
    });
  }
  e.preventDefault();
});


 $('#modal-edit').on('hidden.bs.modal', function () {
  if ($('#form-edit').find('.has-error, .has-danger').length > 0) {
    $(".help-block").html('');
    $('.has-error').removeClass('has-error');
  }

});


 // <?php if($this->session->flashdata('messages') != ""): ?>
 //  customNotif('Success', '<?= $this->session->flashdata('messages') ?>', 'success');
 //  table.draw();
 //  <?php elseif($this->session->flashdata('error') != ""): ?>
 //    customNotif('Error', '<?= $this->session->flashdata('error') ?>', 'error');
 //    <?php elseif($this->session->flashdata('warning') != ""): ?>
 //      customNotif('Warning', '<?= $this->session->flashdata('warning') ?>', 'warning');
 //    <?php endif; ?>

});



</script>