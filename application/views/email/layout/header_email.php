<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?= (isset($subject)) ? $subject : "" ?></title>
    <?php $this->load->view('email/layout/style_email') ?>
  </head>
  <body class="">
    <span class="preheader"><?= (isset($email_preview)) ? $email_preview : "" ?></span>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">