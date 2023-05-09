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
                You can see all the details and adjust your DPL by clicking the link below.
                <br>
                <br>
                <a href="<?= $dpl_link ?>" title="Finance Tool DPL" ><?= $dpl_link?></a>
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
