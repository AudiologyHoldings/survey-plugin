<div id="survey_report">
  <div class="wrapper wrapper-spiral">
    <div id="pop_top">
    </div>
    
    <div id="pop_body">
      <h1><?php echo $this->Html->image('/survey/img/healthy_hearing.png') ?></h1>
      <?php echo $this->Form->create('SurveyAnswer', array(
        'url' => array('plugin' => 'survey', 'admin' => true, 'controller' => 'surveys', 'action' => 'report'),
        'id' => 'final_form'
      )); ?>
      <?php echo $this->Form->input('start_month');?>
      <?php echo $this->Form->input('end_month');?>
      <?php echo $this->Form->input('page_views');?>
      
      <?php echo $this->Form->end('Submit'); ?>
      
      <?php if(isset($results)): ?>
        <?= debug($results); ?>
      <?php endif; ?>
            
    </div>
    
  </div>
  <div class="final_survey_footer">
    <?php echo $this->Html->image('/survey/img/popup_bottom.gif') ?>
  </div>
</div>