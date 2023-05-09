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
                <?php if(isset($notes)): ?>
                <br>
                Remark: <?=  $notes; ?>
                <br>
                <?php endif; ?>
                <br>                
                Please go through link below to see all details, and confirm your approval.
                <br>
                <a href="<?= $approval_link ?>" title="Finance Tool DPL Verification" ><?= $approval_link?></a>
                <br>
                <br>
                You can also review a list of all Penunjukan Langsung Document pending on your approval at Finance Tool DPL
                <br>
                <a href="<?= $approval_link_all ?>" title="Finance Tool DPL Verification" ><?= $approval_link_all?></a>
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
