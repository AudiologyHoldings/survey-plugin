<?php echo $this->Html->css('/survey/css/report.css') ?>
<div id="survey_report">
  <div class="wrapper wrapper-spiral">
    <div id="pop_top">
    </div>
    
    <div id="pop_body">
      <h1><?php echo $this->Html->image('/survey/img/healthy_hearing.png') ?></h1><br /><br />
      <?php echo $this->Form->create('SurveyAnswer', array(
        'url' => array('plugin' => 'survey', 'prefix' => 'admin', 'controller' => 'surveys', 'action' => 'report'),
        'id' => 'final_form'
      )); ?>
      <?php echo $this->Form->input('start_month', array('label' => 'Start Month (eg Aug 2010, 2010-08-01, etc..)'));?>
      <?php echo $this->Form->input('end_month', array('label' => 'End Month (eg Sep 2010, 2010-09-01, etc..)'));?>
      <?php echo $this->Form->input('page_views', array('label' => 'FAP Unique User Sessions Within Above Timeline'));?>
      
      <?php echo $this->Form->end('Get Report'); ?>
      
      <?php if(isset($results)): ?>
        <h1 class="report"><?php echo $this->data['SurveyAnswer']['start_month'] ?> to <?php echo $this->data['SurveyAnswer']['end_month'] ?></h1> 
        <table class="report">
          <tr>
            <th class="left">Label</th>
            <th class="right">Current</th>
          </tr>
          <tr>
            <td>Traffic</td>
            <td class="right"><?php echo $this->data['SurveyAnswer']['page_views'] ?></td>
          </tr>
          <tr>
            <td colspan="2" class="spacer">&nbsp;</td>
          </tr>
          <?php echo $this->element('report_key', array('title' => 'Opt-in', 'key' => 'opt_in', 'suffix' => 'of Traffic', 'results' => $results, 'plugin' => 'survey')); ?>
          <?php echo $this->element('report_key', array('title' => 'Continue Clicks', 'key' => 'continue', 'suffix' => 'of Opt-in', 'results' => $results, 'plugin' => 'survey')); ?>
          <?php echo $this->element('report_key', array('title' => 'Participation', 'key' => 'participation', 'suffix' => 'of Opt-in', 'results' => $results, 'plugin' => 'survey')); ?>
          <?php echo $this->element('report_key', array('title' => 'Provided Email', 'key' => 'with_email', 'suffix' => 'of Opt-in', 'results' => $results, 'plugin' => 'survey')); ?>
          <?php echo $this->element('report_key', array('title' => 'Completed Final 30 Day Survey', 'key' => 'completed_survey', 'suffix' => 'of Provided Email', 'results' => $results, 'plugin' => 'survey')); ?>
          
          <?php echo $this->element('report_key', array('title' => 'Not Visit Clinic', 'key' => 'not_visit_clinic', 'suffix' => 'of Completed Final', 'results' => $results, 'plugin' => 'survey')); ?>
          <?php echo $this->element('report_key', array('title' => 'Have Clinic Apointment Clinic', 'key' => 'have_apt_visit_clinic', 'suffix' => 'of Completed Final', 'results' => $results, 'plugin' => 'survey')); ?>
          <?php echo $this->element('report_key', array('title' => 'Visit Clinic', 'key' => 'visit_clinic', 'suffix' => 'of Completed Final', 'results' => $results, 'plugin' => 'survey')); ?>
          <?php echo $this->element('report_key', array('title' => 'Visited Clinic But No Purchase', 'key' => 'visit_clinic_no_purchase', 'suffix' => 'of Completed Final', 'results' => $results, 'plugin' => 'survey')); ?>
          
          <?php echo $this->element('report_key', array('title' => 'Purchases', 'key' => 'purchases', 'suffix' => 'of Completed Final', 'results' => $results, 'plugin' => 'survey')); ?>
          <?php echo $this->element('report_key', array('title' => 'Oticon Purchases', 'key' => 'oticon_purchases', 'suffix' => 'of Completed Final', 'results' => $results, 'plugin' => 'survey')); ?>
          <?php echo $this->element('report_key', array('title' => 'Beltone Purchases', 'key' => 'beltone_purchases', 'suffix' => 'of Completed Final', 'results' => $results, 'plugin' => 'survey')); ?>
          <?php echo $this->element('report_key', array('title' => 'Phonak Purchases', 'key' => 'phonak_purchases', 'suffix' => 'of Completed Final', 'results' => $results, 'plugin' => 'survey')); ?>
          <?php echo $this->element('report_key', array('title' => 'Miracle Ear Purchases', 'key' => 'miracle_ear_purchases', 'suffix' => 'of Completed Final', 'results' => $results, 'plugin' => 'survey')); ?>
          <?php echo $this->element('report_key', array('title' => 'Other Purchases', 'key' => 'other_purchases', 'suffix' => 'of Completed Final', 'results' => $results, 'plugin' => 'survey')); ?>
          <?php echo $this->element('report_key', array('title' => 'Entered Give Away', 'key' => 'entered_give_away', 'suffix' => 'of Completed Final', 'results' => $results, 'plugin' => 'survey')); ?>
        </table>
        
        <h1 class="report">Age Range (<?php echo $results['age_range']['total'] ?>)</h1>
        <table class="report">
          <tr>
            <th class="center">&lt;18</th>
            <th class="center">18-39</th>
            <th class="center">40-49</th>
            <th class="center">50-59</th>
            <th class="center">60-69</th>
            <th class="center">70-79</th>
            <th class="center">80+</th>
          </tr>
          <tr>
            <td class="center"><?php echo $results['age_range']['under-18'] ?><br />(<?php echo $this->Number->toPercentage($results['age_range']['under-18'] / $results['age_range']['total'] * 100); ?>)</td>
            <td class="center"><?php echo $results['age_range']['18-39'] ?><br />(<?php echo $this->Number->toPercentage($results['age_range']['18-39'] / $results['age_range']['total'] * 100); ?>)</td>
            <td class="center"><?php echo $results['age_range']['40-49'] ?><br />(<?php echo $this->Number->toPercentage($results['age_range']['40-49'] / $results['age_range']['total'] * 100); ?>)</td>
            <td class="center"><?php echo $results['age_range']['50-59'] ?><br />(<?php echo $this->Number->toPercentage($results['age_range']['50-59'] / $results['age_range']['total'] * 100); ?>)</td>
            <td class="center"><?php echo $results['age_range']['60-69'] ?><br />(<?php echo $this->Number->toPercentage($results['age_range']['60-69'] / $results['age_range']['total'] * 100); ?>)</td>
            <td class="center"><?php echo $results['age_range']['70-79'] ?><br />(<?php echo $this->Number->toPercentage($results['age_range']['70-79'] / $results['age_range']['total'] * 100); ?>)</td>
            <td class="center"><?php echo $results['age_range']['80plus'] ?><br />(<?php echo $this->Number->toPercentage($results['age_range']['80plus'] / $results['age_range']['total'] * 100); ?>)</td>
          </tr>
        </table>
        
        <h1 class="report">Likely To Visit (<?php echo $results['likely']['total'] ?>)</h1>
        <table class="report">
          <tr>
            <th class="center">Not</th>
            <th class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th class="center">Somewhat</th>
            <th class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th class="center">Very</th>
          </tr>
          <tr>
            <td class="center"><?php echo $results['likely']['0'] ?><br />(<?php echo $this->Number->toPercentage($results['likely']['0'] / $results['likely']['total'] * 100); ?>)</td>
            <td class="center"><?php echo $results['likely']['1'] ?><br />(<?php echo $this->Number->toPercentage($results['likely']['1'] / $results['likely']['total'] * 100); ?>)</td>
            <td class="center"><?php echo $results['likely']['2'] ?><br />(<?php echo $this->Number->toPercentage($results['likely']['2'] / $results['likely']['total'] * 100); ?>)</td>
            <td class="center"><?php echo $results['likely']['3'] ?><br />(<?php echo $this->Number->toPercentage($results['likely']['3'] / $results['likely']['total'] * 100); ?>)</td>
            <td class="center"><?php echo $results['likely']['4'] ?><br />(<?php echo $this->Number->toPercentage($results['likely']['4'] / $results['likely']['total'] * 100); ?>)</td>
            <td class="center"><?php echo $results['likely']['5'] ?><br />(<?php echo $this->Number->toPercentage($results['likely']['5'] / $results['likely']['total'] * 100); ?>)</td>
            <td class="center"><?php echo $results['likely']['6'] ?><br />(<?php echo $this->Number->toPercentage($results['likely']['6'] / $results['likely']['total'] * 100); ?>)</td>
          </tr>
        </table>
      <?php endif; ?>
            
    </div>
    
  </div>
  <div class="final_survey_footer">
    <?php echo $this->Html->image('/survey/img/popup_bottom.gif') ?>
  </div>
</div>