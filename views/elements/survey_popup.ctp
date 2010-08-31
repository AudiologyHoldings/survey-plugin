<?php echo $this->Html->css('/survey/css/popup.css'); ?>
<?php echo $this->Html->css('/survey/css/style.css'); ?>
<!--[if IE 6]>
<?php echo $this->Html->css('/survey/css/ie6.css'); ?>
<![endif]-->
<?php echo $this->Html->script('/survey/js/jquery.class.js'); ?>
<?php echo $this->Html->script('/survey/js/jq_survey_popup.js'); ?>
<div id="survey_popup_wrapper">

  <div class="white_content" style="display: none;">
    <div class="popup popup_background">
      <div id="popup-content">
        <p class="popup-header">
          Help Us Cure<br />
          Hearing Loss.
        </p>
        <p class="text">
          Participate in a brief three question<br />
          survey and we'll donate $1 to:
        </p>
        <p><?php echo $this->Html->image('/survey/img/popup_house_ear_institute.png') ?></p>
        <p>
          <?php echo $this->Js->link(
            $this->Html->image('/survey/img/btn_i_want_to_help.png'),
            array('plugin' => 'survey', 'controller' => 'surveys', 'action' => 'first'),
            array('update' => '#survey_popup_wrapper', 'escape' => false, 'buffer' => false)
          ); 
          ?>
        </p>
        <p class="popup-small">
          <?php echo $this->Html->link('No Thanks','#', array('id' => 'close_popup')); ?><br /><br />
          <?php echo $this->Html->link('Privacy Policy', '/privacy-policy', array('target' => '_blank')); ?></p>
      </div>
    </div>
  </div>

  <div class="black_overlay"></div>
  
</div>

<?php
$js->get('#survey_popup_wrapper');
$hideIt = $js->effect('hide');

$js->get('#close_popup');
$js->event('click', $hideIt);

echo $js->writeBuffer();
?>