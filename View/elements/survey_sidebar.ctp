<?php
$show = isset($show) ? $show : true;
$wrapper = isset($wrapper) ? $wrapper : true;
echo $this->Html->css('/survey/css/sidebar.css');
echo $this->Html->script('/survey/js/jquery.class.js');
echo $this->Html->script('/survey/js/jq_survey_sidebar.js'); 
?>
<!--[if IE 6]>
<?php echo $this->Html->css('/survey/css/ie6.css'); ?>
<![endif]-->

<?php if($wrapper): ?>
<div id="survey_popup_wrapper">
</div>
<?php endif;?>

<div id="survey_sidebar" class="close" <?php if(!$show): ?>style="display:none;"<?php endif;?>>
  <div id="survey_sidebar_body">
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
    <!-- button to show -->
  </div>
</div>

<?php 
echo $this->Js->writeBuffer(array('safe' => false));
?>