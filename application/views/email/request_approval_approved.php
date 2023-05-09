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
                A new justification request <?= $fs_number ?> has been <b>approved</b> by <?= $action_name ?> and need for your approval. You can see all the details about this justification by clicking the link below.
                <br>
                <br>
                Please go through the <a href="<?= $approval_link ?>">link</a> and confirm your approval.
                <br>
                <br>
                <?= (isset($fs_detail)) ? $fs_detail : "" ?>
                <br>
                <?= (isset($approval_remark)) ? "Remark : <br>".$approval_remark."<br><br>" : "" ?>
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
