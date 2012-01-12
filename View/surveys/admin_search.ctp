<?php echo $this->Html->css('/survey/css/report.css') ?>
<div id="survey_search">
  <div class="wrapper wrapper-spiral">
    <div id="pop_top">
    </div>
    
    <div id="pop_body">
      <h1><?php echo $this->Html->image('/survey/img/healthy_hearing.png') ?></h1><br /><br />
      <?php echo $this->Form->create('Survey', array(
        'url' => array('plugin' => 'survey', 'prefix' => 'admin', 'controller' => 'surveys', 'action' => 'search'),
        'id' => 'final_form'
      )); ?>
      <?php echo $this->Form->input('email', array('label' => 'Email (% is a wildcard)'));?>
      <?php echo $this->Form->end('Find Contact'); ?>
      
      <?php if(isset($contacts) && !empty($contacts)): ?>
        <h1 class="report"><?php echo count($contacts); ?> Contact(s) Found</h1>
        <table class="report">
          <tr>
            <th class="left">Email</th>
            <th class="right">Action</th>
          </tr>
          <?php foreach($contacts as $contact): ?>
            <tr>
              <td class="left"><?php echo $contact['Survey']['email'] ?></td>
              <td class="right">
                <?php echo $this->Html->link('Delete', 
                  array('action' => 'delete', $contact['Survey']['id']),
                  array(),
                  "Are you sure you want to delete this survey contact and all his/her answers?"
                ); ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      <?php elseif(isset($contacts)): ?>
        <h1 class="report">No Contacts Found</h1>
      <?php endif; ?>
            
    </div>
    
  </div>
  <div class="final_survey_footer">
    <?php echo $this->Html->image('/survey/img/popup_bottom.gif') ?>
  </div>
</div>