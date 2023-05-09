<?php
header("X-Frame-Options: SAMEORIGIN");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?= $this->config->item('site_description') ?>">
  <meta name="author" content="">
  <link rel="shortcut icon" href="<?= base_url('assets/custom/img/') ?>favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" href="<?= base_url('assets/custom/img/') ?>apple-touch-icon.png">
  <title><?= (isset($title)) ? $title . " |" : "" ?> <?= $this->config->item('site_title') ?></title>
  <!-- Bootstrap Core CSS -->
  <link href="<?= base_url('assets/') ?>bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Menu CSS -->
  <link href="<?= base_url('assets/') ?>plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/bower_components/dropify/dist/css/dropify.min.css"> -->
  <!-- toast CSS -->
  <link href="<?= base_url('assets/') ?>plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
  <link href="<?= base_url('assets/') ?>plugins/bower_components/custom-select/custom-select.css" rel="stylesheet" type="text/css" />
  <link href="<?= base_url('assets/') ?>plugins/bower_components/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
  <link href="<?= base_url('assets/') ?>plugins/bower_components/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
  <!-- animation CSS -->
  <link href="<?= base_url('assets/') ?>css/animate.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="<?= base_url('assets/') ?>css/style.css" rel="stylesheet">
  <!-- color CSS -->
  <link href="<?= base_url('assets/') ?>css/colors/default-dark.css" id="theme" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="<?= base_url('assets/') ?>custom/css/custom.min.css" rel="stylesheet">
  <!-- Sweet Alert CSS -->
  <link href="<?= base_url('assets/') ?>plugins/bower_components/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
  <!-- Date Picker -->
  <link href="<?= base_url('assets/') ?>plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
  <link href="<?= base_url('assets/') ?>plugins/bower_components/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/') ?>plugins/bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <?php if($module == "datatable"): ?>
  <!-- Datatables CSS -->
  <link href="<?= base_url('assets/') ?>custom/css/datatable.css" rel="stylesheet">
  <link href="<?= base_url(); ?>assets/plugins/bower_components/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
  <link href="<?= base_url() ;?>assets/plugins/bower_components/datatables-plugins/buttons/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
  <link href="<?= base_url(); ?>assets/vendor/datatables/css/fixedColumns.dataTables.min.css" rel="stylesheet">
  <link href="<?= base_url(); ?>assets/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
  <?php endif; ?>
  <!-- JQuery -->
  <script src="<?= base_url('assets/') ?>plugins/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script>
    /*var csfrData = {};
    csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
    $.ajaxSetup({
      headers: {
          csfrData
      },
      data: csfrData
    });
    const CSRF_NAME = '<?= $this->security->get_csrf_token_name(); ?>';
    const ACCESS_TOKEN = '<?= $this->security->get_csrf_hash(); ?>';*/

    /*let csrf_name = '<?= $this->security->get_csrf_token_name(); ?>';
    let access_token = '<?= $this->security->get_csrf_hash(); ?>';

     $.ajaxSetup({
        headers: {
            <?= $this->security->get_csrf_token_name(); ?> : access_token
        }
      });
*/
  </script>
</head>

<body class="fix-sidebar fix-header">

  <!-- Preloader -->
  <div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
      <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
    </svg>
  </div>

  <div id="wrapper">
    <?php 
      $total_request  = 0;
      $is_delegate    = $this->session->userdata('is_delegate');
      $email_approver = ($is_delegate !== false) ? $is_delegate : $this->session->userdata('email');

      $check_is_approver = ($this->session->userdata('is_approver')) ? $this->session->userdata('is_approver') : check_is_approver($email_approver);

      if($email_approver != $this->session->userdata('email')){
        $check_is_approver = check_is_approver($email_approver);
      }

      if($check_is_approver['is_fs'] > 0 || $check_is_approver['is_pr'] > 0 || $check_is_approver['is_fpjp'] > 0 || $check_is_approver['is_dpl_verifier'] > 0 || $check_is_approver['is_pr_assign'] > 0 || $check_is_approver['is_coa_review'] > 0 || $check_is_approver['is_dpl_approval'] > 0){

        if($check_is_approver['is_fs']):
          $total_approval_budget = count_all_request($email_approver, "budget_approval");
          $total_request += $total_approval_budget;
        endif;
        if($check_is_approver['is_fpjp']):
          $total_approval_fpjp = count_all_request($email_approver, "fpjp_approval");
          $total_request += $total_approval_fpjp;
        endif;
        if($check_is_approver['is_pr']):
          $total_approval_pr = count_all_request($email_approver, "pr_approval");
          $total_request += $total_approval_pr;
        endif;
        if($check_is_approver['is_pr_assign']):
          $total_assign_pr = count_all_request($email_approver, "pr_assign");
          $total_request += $total_assign_pr;
        endif;
        if($check_is_approver['is_coa_review']):
          $total_coa_review = count_all_request($email_approver, "coa_review");
          $total_request += $total_coa_review;
        endif;
        if($check_is_approver['is_dpl_verifier']):
          $total_verification_dpl = count_all_request($email_approver, "verification_dpl");
          $total_request += $total_verification_dpl;
        endif;
        if($check_is_approver['is_dpl_approval']):
          $total_approval_dpl = count_all_request($email_approver, "dpl_approval");
          $total_request += $total_approval_dpl;
        endif;
      }
    ?>
    <!-- Top Navigation -->
    <nav class="navbar navbar-default navbar-static-top m-b-0">
      <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
        <div class="top-left-part">
          <div class="h-15"></div>
          <a class="logo" href="javascript:void(0)"><span class="hidden-xs m-l-10 font-weight-bold">FINANCE TOOL</span></a>
        </div>
        <ul class="nav navbar-top-links navbar-left hidden-xs">
          <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
        </ul>
        <ul class="nav navbar-top-links navbar-right pull-right">
          <li class="dropdown"> <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"><b><?= get_user_data($this->session->userdata('user_id'))?></b><?= ($total_request > 0) ? '<div class="notify"><span class="heartbit"></span><span class="point"></span></div>' : ''?></a>
            <ul class="dropdown-menu dropdown-user scale-up">
              <?php if($check_is_approver['is_fs'] > 0):?>
              <li><a href="<?= base_url('budget/approval') ?>"><i class="fa fa-check"></i> Budget Approval <?= ($total_approval_budget > 0) ? ' <span class="label py-5 px-10 label-danger">'.$total_approval_budget.'</span>' : ''?></a></li>
              <?php endif;?>
              <?php if($check_is_approver['is_dpl_approval'] > 0):?>
              <li><a href="<?= base_url('dpl/approval') ?>"><i class="fa fa-check"></i> DPL Approval <?= ($total_approval_dpl > 0) ? ' <span class="label py-5 px-10 label-danger">'.$total_approval_dpl.'</span>' : ''?></a></li>
              <?php endif;?>
              <?php if($check_is_approver['is_dpl_verifier'] > 0):?>
              <li><a href="<?= base_url('dpl/verification') ?>"><i class="fa fa-check"></i> DPL Verification <?= ($total_verification_dpl > 0) ? ' <span class="label py-5 px-10 label-danger">'.$total_verification_dpl.'</span>' : ''?></a></li>
              <?php endif;?>
              <?php if($check_is_approver['is_fpjp'] > 0):?>
              <li><a href="<?= base_url('fpjp/approval') ?>"><i class="fa fa-check"></i> FPJP Approval <?= ($total_approval_fpjp > 0) ? ' <span class="label py-5 px-10 label-danger">'.$total_approval_fpjp.'</span>' : ''?></a></li>
              <?php endif;?>
              <?php if($check_is_approver['is_pr'] > 0):?>
              <li><a href="<?= base_url('pr/approval') ?>"><i class="fa fa-check"></i> PR Approval <?= ($total_approval_pr > 0) ? ' <span class="label py-5 px-10 label-danger">'.$total_approval_pr.'</span>' : ''?></a></li>
              <?php endif;?>
              <?php if($check_is_approver['is_pr_assign'] > 0):?>
              <li><a href="<?= base_url('pr/assign') ?>"><i class="fa fa-check"></i> PR Assign <?= ($total_assign_pr > 0) ? ' <span class="label py-5 px-10 label-danger">'.$total_assign_pr.'</span>' : ''?></a></li>
              <?php endif;?>
              <?php if($check_is_approver['is_coa_review'] > 0):?>
              <li><a href="<?= base_url('coa-review') ?>"><i class="fa fa-check"></i> COA Review <?= ($total_coa_review > 0) ? ' <span class="label py-5 px-10 label-danger">'.$total_coa_review.'</span>' : ''?></a></li>
              <?php endif;?>
              <li><a href="<?= base_url('account/edit') ?>"><i class="ti-settings"></i> Account Setting</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="<?= base_url('logout') ?>"><i class="fa fa-power-off"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
    <!-- End Top Navigation -->

    <div class="navbar-default sidebar" role="navigation">
      <div class="sidebar-nav navbar-collapse slimscrollsidebar" style="padding-bottom: 50px;">
          <ul class="nav" id="side-menu">
            <li class="nav-small-cap m-t-10">&nbsp;&nbsp;Main Menu</li>
            <?php
              $activepage = (isset($activepage)) ? $activepage : '';
              $menuList   = ($this->session->userdata('menu_id')) ? $this->session->userdata('menu_id') : array(1);
            ?>
            <li><a href="<?= base_url() ?>" class="nav_home"><i class="fa fa-home fa-fw"></i><span class="hide-menu">Home</span></a></li>
            <?= $this->dynamic_menu->build_menu($activepage, $menuList);?>
          </ul>

          <div class="sidebar-bottom-content text-center w-full hidden-sm">
            <p class="font-size-11 p-10 text-grey font-weight-400"><?php echo date('Y') ?> &copy; Finance Tools by FinOps</p>
          </div>
      </div>
    </div>
    <!-- Left navbar-header end -->

    <!-- Page Content -->
    <div id="page-wrapper">
      <div class="container-fluid">
        <div class="row">
          <?php if(isset($breadcrumb)): ?>
          <div class="col-md-8">
            <ol class="breadcrumb">
              <?php foreach ($breadcrumb as $key => $value):?>
              <li><a <?= ($value['link'] != "") ? ' href="'.$value['link'].'"' : ''?><?= ($value['class'] != "") ? ' class="active"' : ''?>><?= (strtolower($value ['name']) == "home") ? '<i class="fa fa-home fa-fw" data-icon="v"></i> Beranda ' : $value ['name']?></a></li>
              <?php endforeach; ?>
            </ol>
          </div>
          <?php endif; ?>
        </div>
        <?php if(isset($title)): ?>
        <div class="row<?= (strtolower($title) == 'dashboard') ? ' mt-20' : '' ?>">
            <h4 class="font-weight-700"><?= (strtolower($title) == "dashboard") ? "Hello " . get_user_data($this->session->userdata('user_id')). "," : $title ?></h4>
        </div>
        <?php endif; ?>
        <?= $content ?>
      </div>

      <!-- <footer class="footer text-center"><?php echo date('Y') ?> &copy; <?= $this->config->item('site_title') ?> by FinOps</footer> -->

    </div>
    <!-- /#page-wrapper -->
    
    <div class="modal fade" id="modal-delete" role="dialog" aria-labelledby="modal-delete">
      <div class="modal-dialog">
        <div class="modal-content">
           <form role="form" id="form-delete">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-white">Confirmation</h4>
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
  <!-- /#wrapper -->

<!-- Bootstrap Core JavaScript -->
<script src="<?= base_url('assets/') ?>bootstrap/dist/js/bootstrap.min.js"></script>
<?php if($module == "datatable"): ?>
<!-- Pace -->
<script src="<?= base_url('assets/')?>plugins/bower_components/pace/js/pace.min.js"></script>
<!-- Datatables -->
<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables-plugins/buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables-plugins/buttons/js/buttons.flash.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables-plugins/buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables-plugins/buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url(); ?>assets/vendor/datatables-responsive/dataTables.responsive.js"></script>
<script src="<?= base_url('assets/') ?>js/dataTables.rowsGroup.js"></script>
<?php endif; ?>
<!-- Menu Plugin JavaScript -->
<script src="<?= base_url('assets/') ?>plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
<!--slimscroll JavaScript -->
<script src="<?= base_url('assets/') ?>js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<script src="<?= base_url('assets/') ?>js/waves.js"></script>
<!-- Toast -->
<script src="<?= base_url('assets/') ?>plugins/bower_components/toast-master/js/jquery.toast.js"></script>
<!-- Datepicker -->
<script src="<?= base_url('assets/') ?>plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!-- Date range Plugin JavaScript -->
<script src="<?= base_url('assets/') ?>plugins/bower_components/moment/moment.js"></script>
<script src="<?= base_url('assets/') ?>plugins/bower_components/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- Custom Theme JavaScript -->
<script src="<?= base_url('assets/') ?>js/custom.min.js"></script>
<script src="<?= base_url('assets/') ?>js/jquery.mask.min.js"></script>
<script src="<?= base_url('assets/') ?>custom/js/custom.min.js"></script>
<script src="<?= base_url('assets/') ?>js/validator.js"></script>
<script src="<?= base_url('assets/') ?>plugins/bower_components/custom-select/custom-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/js/bootstrap-select.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/bower_components/multiselect/js/jquery.multi-select.js"></script>
<script src="<?= base_url('assets/') ?>plugins/bower_components/waypoints/lib/jquery.waypoints.js"></script>
<script src="<?= base_url('assets/') ?>plugins/bower_components/counterup/jquery.counterup.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/bower_components/sweetalert/sweetalert.min.js"></script>
<script src="<?= base_url('assets/') ?>js/jasny-bootstrap.js"></script>
<!-- <script src="<?= base_url('assets/') ?>plugins/bower_components/dropify/dist/js/dropify.min.js"></script> -->
<!-- Custom Theme JavaScript -->
<script type="text/javascript">
  const baseURL           = '<?= base_url() ?>';
  const imageLoading      = baseURL + 'assets/img/loading.gif';
  const imageLoadingSmall = baseURL + 'assets/img/loading-small.gif';
  let orderBy   = '';
  let filterBy  = '';
  $('.money-format').mask('000.000.000.000.000', { reverse: true });

  $('input.money-format-negative').mask('#,##0', {
  reverse: true,
  translation: {
    '#': {
      pattern: /-|\d/,
      recursive: true
    }
  },
  onChange: function(value, e) {      
    e.target.value = value.replace(/(?!^)-/g, '').replace(/^,/, '').replace(/^-,/, '-');
  }
});

     $(document).ready(function(){
    <?php if (strtolower($title) != "dashboard"):?>
        setTimeout(function(){
          $(".nav_home").removeClass('active');
        }, 100);
    <?php endif; ?>

      $(".open-close").click(function(){
        if($(this).find('i.icon-arrow-left-circle').length !== 0){
          $(".sidebar-bottom-content").removeClass('d-none');
        }else{
          $(".sidebar-bottom-content").addClass('d-none');
        }
      })

      <?php if($module == "datatable"): ?>
        $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) { 
            console.log(message);
        };
      <?php endif; ?>

      $('.input-daterange-datepicker').daterangepicker({
          locale: {
            format: 'DD/MM/YYYY'
          },
          "autoApply": true,
          maxDate: new Date,
      });

     /* var idleTime = 0;
      //Increment the idle time counter every minute.
      var idleInterval = setInterval(timerIncrement, 60000); // 1 minute
      //Zero the idle timer on mouse movement.
      $(this).mousemove(function (e) {
          idleTime = 0;
      });
      $(this).keypress(function (e) {
          idleTime = 0;
      });

      function timerIncrement() {
          idleTime = idleTime + 1;
          if (idleTime > 1) { // 20 minutes
              // window.location.reload();
          }
      }*/
    });

</script>
</body>
</html>