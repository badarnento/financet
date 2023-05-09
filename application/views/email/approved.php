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
                Your justification request <?= $fs_number ?> has been <b>approved</b> by <?= $action_name ?>. You can see all the details about this justification by clicking the link below.
                <br>
                <br>
                <a href="<?= $fs_link ?>" title="Finance Tool Justification" >Finance Tool Justification</a>
                <br>
                <br>
                <?= (isset($approval_remark)) ? "Approver Comment : <br>".$approval_remark."<br><br>" : "" ?>
                <br>
                <?= (isset($fs_detail)) ? $fs_detail : "" ?>
                <br>
                <br>
                Thank you.
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
<?php $this->load->view('email/layout/footer') ?>
