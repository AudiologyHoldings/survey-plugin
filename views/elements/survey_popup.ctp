<?php echo $this->Html->css('/survey/css/popup.css'); ?>
<?php echo $this->Html->css('/survey/css/style.css'); ?>
<?php echo $this->Html->css('/survey/css/sidebar.css'); ?>
<!--[if IE 6]>
<?php echo $this->Html->css('/survey/css/ie6.css'); ?>
<![endif]-->
<!--[if IE]>
<?php echo $this->Html->css('/survey/css/ie.css'); ?>
<![endif]-->
<?php 
echo $this->Html->script('/survey/js/jquery.class.js');
echo $this->Html->script('/survey/js/jq_survey_popup.js');
echo $this->Html->script('/survey/js/jq_survey_sidebar.js');
?>
<?php $timer = isset($timer) ? $timer : 0; ?>
<div id="survey_popup_wrapper" style="display:none;">

  <div class="white_content">
    <div class="popup popup_background">
      <div id="popup-content">
        <p class="popup-header">
          Help Us<br />
          Help Others.
        </p>
        <p class="text">
          Participate in a short online survey<br />
          to improve the services we offer.<br />
          It will take less than a minute<br /> of your time.<br /><br />
          Will you help us out?
        </p>
        <p>
          <?php echo $this->Js->link(
            $this->Html->image('/survey/img/btn_i_want_to_help.png'),
            array('plugin' => 'survey', 'controller' => 'surveys', 'action' => 'first'),
            array('update' => '#survey_popup_wrapper', 'escape' => false, 'buffer' => false)
          ); 
          ?>
          <br />
          <?php echo $this->Html->link('Maybe Later','#', array('id' => 'close_popup', 'class' => 'btn_close')); ?>
        </p>
        <p class="popup-small popup-policy">
          <?php echo $this->Html->link('Terms of Use - Privacy Policy', '/privacy-policy', array('target' => '_blank')); ?>
        </p>
      </div>
    </div>
  </div>

  <div class="black_overlay"></div>
  
</div>



<?php
//echo $this->element('survey_sidebar', array('plugin' => 'survey', 'show' => false, 'wrapper' => false));
echo $this->Html->scriptBlock("SB = new SurveySidebar();");
echo $this->Html->scriptBlock("
	setTimeout('$(\"#survey_popup_wrapper\").fadeIn(\"slow\")',$timer);
");
?>