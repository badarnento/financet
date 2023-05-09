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
                <br>
                You can validate this journal at this link
                <a href="<?= $link ?>" ><?= $link_display ?></a>
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
