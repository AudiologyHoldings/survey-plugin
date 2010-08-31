<?php
  $base_path = "http://{$_SERVER['HTTP_HOST']}";
?>
<table width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="margin: auto;">
  <tr>
    <td colspan="2"><?php echo $this->Html->image($base_path . '/survey/img/email_top.png') ?></td>
  </tr>
  <tr>
    <td><?php echo $this->Html->image($base_path . '/survey/img/healthy_hearing.png') ?></td>
    <td><?php echo $this->Html->image($base_path . '/survey/img/gift_card.png') ?></td>
  </tr>
  <tr>
    <td colspan="2" cellpadding="15">
      <h1>Thank you!</h1>
      <p>
      Thank you for filling out the survey you recently took at HealthyHearing.com.
      The donation we sent on your behalf to the House Ear Institute will truly make
      a difference in the fight to end hearing loss.<br /><br />
      
      <b>In 30 days we will send you a follow-up survey (don't worry it's only three short
      questions).</b>  This survey is for research purposes only and we will not share your
      information with any third party, nor will we send future surveys or unsolicited
      mail as a result of your participation.<br /><br />
      
      Don't forget, when you complete the follow-up survey we'll enter you in a drawing to
      <b>win a $500 gift card.</b> To review the survey drawing rules and eligibility, just
      visit the following link: <?php echo $this->Html->link('http://www.healthyhearing.com/privacy-policy')?><br /><br />
      
      Thanks again for agreeing to help.  Your support and participation is very much appreciated.<br /><br /><br />
      
      
      Kindest regards,<br />
      The Healthy Hearing Team
      </p><br />
      <p style="font-size: small">
        Ensure our messages always go straight to your inbox.
        Add [<?php echo Configure::read('Survey.email') ?>] to your Address Book or Safe List.<br /><br />
        
        <b>HealthyHearing</b><br />
        5282 Medical Drive, Suite 150<br />
        San Antonio, TX 78229<br />
        800-567-1692<br /><br />
        
        Copyright <?php echo date('Y') ?> HealthyHearing.com All rights reserved.
      </p>
    </td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $this->Html->image($base_path . '/survey/img/email_bottom.png') ?></td>
  </tr>
</table>