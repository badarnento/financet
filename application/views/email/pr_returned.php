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
                Your PR request <?= $pr_number ?> has been <b>returned</b> by <?= $action_name ?>. You can see all the details about this PR by clicking the link below.
                <br>
                <br>
                <a href="<?= $pr_link ?>" title="Finance Tool PR" >Finance Tool PR</a>
                <br>
                <br>
                <?php if(isset($approval_remark)): ?>
                Reason:
                <br>
                <?= $approval_remark ?>
                <br>
                <br>
                <?php endif; ?>
                <?= (isset($email_body)) ? $email_body : "" ?>
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
