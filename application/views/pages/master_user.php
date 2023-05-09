      <div class="row">   
        <div class="col-lg-12">
          <div id="accordion" class="panel panel-info boxshadow animated slideInDown">
            <div class="panel-heading">
              <div class="row">
                <div class="col-lg-6">
                  <a id="aTitleList" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-data"></a>
                </div>
                <div class="col-lg-6">                                
                  <div class="navbar-right">                               
                    <button id="btnAdd" class="btn btn-default btn-rounded custom-input-width" data-toggle="modal" data-target="#modal-edit" type="button" ><i class="fa fa-pencil-square-o"></i> Add New</button>
                  </div>
                </div>
              </div>                             
            </div>
            <div id="collapse-data" class="panel-collapse collapse in">
              <div class="panel-body">
                <div class="table-responsive">
                  <table width="100%" class="table display table-bordered table-striped table-responsive hover" id="table_data"> 
                    <thead>
                      <tr>
                        <th style="vertical-align: middle;">No.</th>
                        <th class ="text-center" style="vertical-align: middle;">USERNAME</th>
                        <th class ="text-center" style="vertical-align: middle;">DISPLAY NAME</th>
                        <th class ="text-center" style="vertical-align: middle;">EMAIL</th>
                        <th class ="text-center" style="vertical-align: middle;">Action</th>
                      </tr>
                    </thead>
                  </table>
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
                <h2 class="modal-title" id="modal-edit-label" >Edit Data</h2>
              </div>

              <div class="modal-body">       
                <form role="form" id="form-edit" data-toggle="validator">

                  <div class="row">
                    <div class="col-lg-6">
                      <label class="form-control-label" for="lblusername">Username</label>
                      <input type="text" class="form-control" id="identity" name="identity" placeholder="Username"/>
                      <input type="hidden" name="isNewRecord" id="isNewRecord" val="0">
                      <input type="hidden" name="id" id="id">
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6">
                      <label class="form-control-label" for="lblusername">Name</label>
                      <input type="text" class="form-control" id="display_name" name="display_name" placeholder="Name"/>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <label class="form-control-label" for="lblemail">Email</label>
                      <input type="text" class="form-control" id="email" name="email" placeholder="Email"/>
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

        <div class="modal fade" id="modal-delete" role="dialog" aria-labelledby="modal-delete">
          <div class="modal-dialog">
            <div class="modal-content">
             <form role="form" id="form-delete">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confirmation</h4>
              </div>
              <div class="modal-body">
                Do you want to delete?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">CANCEL</button>
                <button type="button" class="btn btn-info" id="button-delete" name="confirm" value="Yes">YES</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
      $(document).ready(function () {
        url = baseURL + 'MasterUser/load_data';
        ajaxData = {
          "url"  : url,
          "type" : "POST",
          "data"    : function ( d ) {
          }
        }
        jsonData = [
        { "data": "NO", "width":"10px", "class":"text-center"},
        { "data": "USERNAME", "width":"200px", "class":"text-left"},
        { "data": "DISPLAY_NAME", "width":"200px", "class":"text-left"},
        { "data": "EMAIL", "width":"100px", "class":"text-center"},
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

       table = $('#table_data').DataTable();

       $('#btnAdd').on( 'click', function () {

        $("#modal-edit-label").html('Add New');
        $("#isNewRecord").val("1");

        form = $('#form-edit')[0];
        form.reset();

      });

       $('#form-edit').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {
          console.log('tidak valid');
        }
        else {
         $.ajax({
          url     : baseURL + 'MasterUser/save_user',
          type    : "POST",
          data    : $('#form-edit').serialize(),

          success : function(result){
            console.log(result);
            if (result==1) {
             table.ajax.reload(null, false);
             $("#modal-edit").modal('hide');
             customNotif('Success','Record changed!','success', 4000 );
           } 
           else if (result==0)
           {
            $("body").removeClass("loading");
            customNotif('Failed', result,'error', 4000 );
          } 
          else 
          {
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
        $("#id").val(data.ID);
        $("#identity").val(data.USERNAME);
        $("#display_name").val(data.DISPLAY_NAME);
        $("#email").val(data.EMAIL);
        $("#modal-edit-label").html('Edit : ' +  data.USERNAME);
        $("#modal-edit").modal('show');

      });


       $('#table_data ').on( 'click', 'a.action-delete', function () {

        data      = table.row( $(this).parents('tr') ).data();
        id_delete = data.ID;
        console.log('tes' + id_delete );

        $("#modal-delete-label").html('Delete Data : ' +  data.DISPLAY_NAME);
        $("#modal-delete").modal('show');

      });

       $('#button-delete').on( 'click', function () {

        $.ajax({
          url       : baseURL + 'MasterUser/delete_user/' + id_delete,
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

     });

   </script>
