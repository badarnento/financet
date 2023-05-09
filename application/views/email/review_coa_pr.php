<?php $this->load->view('email/layout/header_email') ?>
    <table role="presentation" class="main">
      <tr>
        <td class="wrapper">
          <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td>
                Dear Sir/Madam <?= (isset($email_recipient)) ? $email_recipient."," : "" ?>
                <br>
                <br>
                <?= (isset($email_body)) ? $email_body : "" ?>
                <br>
                Click this <a href="<?= $link_confirm_coa ?>" title="Edit CoA of this FPJP"><b>link</b></a> to <b>confirm</b> CoA of this FPJP.
                <br>
                <br>
                Thank you for your confirmation.
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
<?php $this->load->view('email/layout/footer') ?>
