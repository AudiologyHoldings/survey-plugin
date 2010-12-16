<?php
  $base_path = "http://{$_SERVER['HTTP_HOST']}";
  
  $host = '';
  switch(Configure::read('env')){
		case 'dev':
			$host = 'dev.healthyhearing.com';
			break;
		default:
			$host = 'www.healthyhearing.com';
			break;
  }
?>
<table width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="margin: auto;">
  <tr>
    <td colspan="2"><?php echo $this->Html->image($base_path . '/survey/img/email_top.png') ?></td>
  </tr>
  <tr>
    <td><?php echo $this->Html->image($base_path . '/survey/img/healthy_hearing.png') ?></td>
  </tr>
  <tr>
    <td colspan="2" cellpadding="15">
      <h1>Thank you!</h1>
      <p>
      Thank you for filling out the survey you recently took at HealthyHearing.com.  The information you provided will help us improve the services that we offer on the website.<br /><br />
      
      Thanks again for agreeing to help.  Your support and participation is very much appreciated.<br /><br /><br />
      
      As promised, we are attaching our Comprehensive Guide to Hearing Loss and Hearing Aids.  For ease of viewing, the guide is in PDF format and attached to this email. If you cannot view the guide on your computer, you may need to install the free PDF Reader from http://get.adobe.com/reader/<br /><br />
      
      <?php if(!empty($locations)): ?>
      	<h4>Hearing Care Professionals in your Area</h4>
      	<p>
      		You listed your zip code as <?php echo $contact['Contact']['zip']; ?>, below is a list of <?php echo count($locations); ?> hearing care professionals in your area.<br />
      		<?php echo $this->element('findaprofessional/location_list',array('locations'=>$locations, 'host' => $host)); ?>
      	</p>
      	<br /><br />
      <?php endif; ?>
      
      Kindest regards,<br />
      The Healthy Hearing Team
      </p><br />
      <p style="font-size: small">
        Ensure our messages always go straight to your inbox.
        Add [<?php echo Configure::read('Survey.email') ?>] to your Address Book or Safe List.<br /><br />
        
        Copyright <?php echo date('Y') ?> HealthyHearing.com All rights reserved.
      </p>
    </td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $this->Html->image($base_path . '/survey/img/email_bottom.png') ?></td>
  </tr>
</table>