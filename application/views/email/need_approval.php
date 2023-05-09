<?php $this->load->view('email/layout/header') ?>
    <table role="presentation" class="main">
      <tr>
        <td class="wrapper">
          <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td>
                Dear Sir/Madam <?= (isset($email_recipient)) ? $email_recipient : "" ?>
                <br>
                <br>
                <?= (isset($email_body)) ? $email_body : "" ?>
                <br>
                <br>
                You can also review a list of all requests pending your approval at <a href="<?= $approval_link_all ?>" title="Finance Tool Justification" >Finance Tool Justification</a>
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
