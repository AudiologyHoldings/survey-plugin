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
      <h1>Thanks again!</h1>
      <p>
      Thank you again for your recent survey participation at HealthyHearing.com.
      You, and others like you, are helping the House Ear Institute in their goal of
      advancing hearing science.<br /><br />
      
      The link below will take you to our three question follow-up survey. After
      completing the survey you will be given a chance to <b>enter a $500 drawing</b>.
      Just like before, the survey is for research purposes only.  We will not share
      your information with any third party, nor will we send future surveys or
      unsolicited mail as a result of your participation<br /><br />
      
      <?php echo $this->Html->image($base_path . '/survey/img/btn_take_follow_up_survey.png', array('url' => "$base_path/final_survey/{$contact['SurveyContact']['email']}")); ?>
      <br /><br />
      
      If nothing happens when clicking on the link above, you can copy and paste
      the following URL in your browser: <?php echo "$base_path/final_survey/{$contact['SurveyContact']['email']}" ?><br /><br />
      
      Thank you again for your support and participation<br /><br />
      
      <b>Don't forget to enter the drawing!</b><br /><br /><br />
      
      
      Kindest regards,<br />
      The Healthy Hearing Team
      </p>
    </td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $this->Html->image($base_path . '/survey/img/email_bottom.png') ?></td>
  </tr>
</table>