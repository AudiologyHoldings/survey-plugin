<?php echo $this->Html->css('/survey/css/popup.css'); ?>
<?php echo $this->Html->css('/survey/css/style.css'); ?>
<?php echo $this->Html->script('/survey/js/Class-0.0.2.min.js'); ?>
<?php echo $this->Html->script('/survey/js/jq_survey_popup.js'); ?>
<div id="survey_popup_wrapper">
  <div class="hideshow">
    <div class="fade"></div>
    <div id="dragable_popup" class="popup_block">
      <div class="popup">
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
              array('update' => '#survey_popup_wrapper', 'escape' => false)
            ); 
            ?>
            <?php /*echo $this->Html->image('/survey/img/btn_i_want_to_help.png', array(
              'url' => array('plugin' => 'survey', 'controller' => 'surveys', 'action' => 'first')
            )) */?>
          </p>
          <p class="popup-small"><?php echo $this->Html->link('No Thanks','#', array('id' => 'close_popup')); ?></p>
          <p class="popup-small popup-policy"><?php echo $this->Html->link('Privacy Policy', '/privacy-policy'); ?></p>
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