<?php
  header("X-Frame-Options: SAMEORIGIN");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="<?= base_url('assets/custom/img/') ?>favicon.ico" type="image/x-icon">
<link rel="apple-touch-icon" href="<?= base_url('assets/custom/img/') ?>apple-touch-icon.png">
<title><?= $this->config->item('site_title') ?> | 401 NOT AUTHORIZED</title>
<!-- Bootstrap Core CSS -->
<link href="<?= base_url('assets/') ?>bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- animation CSS -->
<link href="<?= base_url('assets/') ?>css/animate.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="<?= base_url('assets/') ?>css/style.css" rel="stylesheet">
<!-- color CSS -->
<link href="<?= base_url('assets/') ?>css/colors/default.css" id="theme"  rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
<!-- Preloader -->
<div class="preloader">
  <svg class="circular" viewBox="25 25 50 50">
      <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
  </svg>
</div>
<section id="wrapper" class="error-page">
  <div class="error-box">
    <div class="error-body text-center">
      <h1>401</h1>
      <h3 class="text-uppercase">Not Authorized !</h3>
      <p class="text-muted m-t-30 m-b-30">UNAUTHORIZED</p>
      <a href="<?= base_url() ?>" class="btn btn-danger btn-rounded waves-effect waves-light m-b-40">Back to home</a> </div>
      <footer class="footer text-center"> <?= date("Y") ?> &copy; <?= $this->config->item('site_title') ?> Created by <?= $this->config->item('author') ?> </footer>
  </div>
</section>
<!-- jQuery -->
<script src="<?= base_url('assets/') ?>plugins/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?= base_url('assets/') ?>bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="<?= base_url('assets/') ?>js/custom.min.js"></script>

</body>
</html>
