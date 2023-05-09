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
                <?= $email_body ?>
                <br>
                <br>
                <a href="<?= $pr_link ?>" title="<?= $pr_number ?>" ><?= $pr_link ?></a>
                <br>
                <br>
                <?php if(isset($remark)): ?>
                Reason:
                <br>
                <?= $remark ?>
                <br>
                <br>
                <?php endif; ?>
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
