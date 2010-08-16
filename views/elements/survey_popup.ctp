<?php echo $this->Html->css('/survey/css/popup.css'); ?>
<div id="survey_popup_wrapper">
  <div class="hideshow">
    <div class="fade"></div>
    <div id="dragable_popup" class="popup_block">
      <div class="popup">
        <div id="popup-content">
          <p class="header">
            Help Us Cure<br />
            Hearing Loss.
          </p>
          <p class="text">
            Participate in a brief three question<br />
            survey and we'll donate $1 to:
          </p>
          <p><?php echo $this->Html->image('/survey/img/popup_house_ear_institute.png') ?></p>
          <p><?php echo $this->Html->image('/survey/img/btn_i_want_to_help.png', array(
            'url' => array('plugin' => 'survey', 'controller' => 'surveys', 'action' => 'first')
          )) ?></p>
          <p><?php echo $this->Html->link('No Thanks','#', array('id' => 'close_popup')); ?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$js->get('#survey_popup_wrapper');
$hideIt = $js->effect('hide');

$js->get('#close_popup');
$js->event('click', $hideIt);

echo $js->writeBuffer();
?>