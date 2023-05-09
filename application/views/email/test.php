<?php $this->load->view('email/layout/header') ?>

            <!-- START CENTERED WHITE CONTAINER -->
            <table role="presentation" class="main">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper">
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td class="custom">
                        <div class="custom">
                          <!-- <img class="custom" src="<?= $this->config->item('logo_url')?>" alt="genesa-logo"> -->
                        </div>
                        <p><?= (isset($messages)) ? $messages : "Hello Safety,<br>You have new one Report about hazard." ?></p>
                        <p class="mb0"><b>Date/Time</b> : <?= (isset($date)) ? $date : "23th August 2019 / 08.00" ?></p>
                        <p class="mb0"><b>Location</b> : <?= (isset($location)) ? $location : "Halim Perdana Kusuma" ?></p>
                        <p class="mb0"><b>Phone/Email</b> : <?= (isset($phone_email)) ? $phone_email : "badar.nento@gmail.com" ?></p>
                        <br>
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                          <tbody>
                            <tr>
                              <td align="left">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                  <tbody>
                                    <tr>
                                      <br>
                                      <td> <a href="<?= base_url("websoul/hazard-report")?>" target="_blank">View</a> </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <!-- <p>Good luck! Hope it works.</p> -->
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>
            <!-- END CENTERED WHITE CONTAINER -->
            
<?php $this->load->view('email/layout/footer') ?>