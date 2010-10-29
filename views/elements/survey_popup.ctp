<?php echo $this->Html->css('/survey/css/popup.css'); ?>
<?php echo $this->Html->css('/survey/css/style.css'); ?>
<?php echo $this->Html->css('/survey/css/sidebar.css'); ?>
<!--[if IE 6]>
<?php echo $this->Html->css('/survey/css/ie6.css'); ?>
<![endif]-->
<!--[if IE]>
<?php echo $this->Html->css('/survey/css/ie.css'); ?>
<![endif]-->
<?php echo $this->Html->script('/survey/js/jquery.class.js'); ?>
<?php echo $this->Html->script('/survey/js/jq_survey_popup.js'); ?>
<?php echo $this->Html->script('/survey/js/jq_survey_sidebar.js'); ?>
<div id="survey_popup_wrapper">

  <div class="white_content">
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
            array('update' => '#survey_popup_wrapper', 'escape' => false)
          ); 
          ?>
        </p>
        <p class="popup-small">
          <?php echo $this->Html->link('Maybe Later','#', array('id' => 'close_popup')); ?>
        </p>
        <p class="popup-small popup-policy">
          <?php echo $this->Html->link('Terms of Use - Privacy Policy', '/privacy-policy', array('target' => '_blank')); ?>
        </p>
      </div>
    </div>
  </div>

  <div class="black_overlay"></div>
  
</div>

<div id="survey_sidebar" style="display:none;">
  <div id="survey_sidebar_body" style="display:none;">
    <h2>Help Us Cure Hearing Loss.</h2>
    <p>Participate in a brief three question survey and we'll donate $1 to:</p>
    <p class="img">
      <?php echo $this->Html->image('/survey/img/house_ear_institute_sidebar.png'); ?>
    </p>
    <p class="img">
      <?php echo $this->Js->link(
         $this->Html->image('/survey/img/btn_i_want_to_help_sidebar.png'),
         array('plugin' => 'survey', 'controller' => 'surveys', 'action' => 'first'),
         array(
           'update' => '#survey_popup_wrapper', 
           'escape' => false,
           'complete' => $this->Js->get('#survey_popup_wrapper')->effect('show', array('buffer' => false))
         )
       ); 
       ?>
     </p>
     <p class="sidebar_policy">
       <?php echo $this->Html->link('Terms of Use - Privacy Policy', '/privacy-policy', array('target' => '_blank')); ?>
     </p>
  </div>
  <div id="survey_sidebar_button">
    <?php echo $this->Html->image('/survey/img/sidebar_button.png') ?>
  </div>
</div>

<?php 
echo $this->Html->scriptBlock("SB = new SurveySidebar();"); 
echo $this->Js->writeBuffer(array('safe' => false));
?>