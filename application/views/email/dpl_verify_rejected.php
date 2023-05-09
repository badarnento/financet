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
                Your DPL request <?= $dpl_number ?> has been <b>rejected</b> by <?= $action_name ?>. You can see all the details about this DPL by clicking the link below.
                <br>
                <br>
                <a href="<?= $dpl_link ?>" title="Finance Tool FPJP" >Finance Tool DPL</a>
                <br>
                <br>
                <?php if(isset($remark)): ?>
                Rejection remark:
                <br>
                <?= $remark ?>
                <br>
                <br>
                <?php endif; ?>
                <?= (isset($dpl_detail)) ? $dpl_detail : "" ?>
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
