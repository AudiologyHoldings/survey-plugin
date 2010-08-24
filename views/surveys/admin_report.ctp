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
      <?php echo $this->Form->input('page_views', array('label' => 'Page Views Within Above Timeline'));?>
      
      <?php echo $this->Form->end('Get Report'); ?>
      
      <?php if(isset($results)): ?>
        <h1 class="report"><?php echo $this->data['SurveyAnswer']['start_month'] ?> to <?php echo $this->data['SurveyAnswer']['end_month'] ?></h1> 
        <table class="report">
          <tr>
            <th class="left">Label</th>
            <th class="right">Model</th>
            <th class="right">Current</th>
          </tr>
          <tr>
            <td>Traffic</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $this->data['SurveyAnswer']['page_views'] ?></td>
          </tr>
          <tr>
            <td colspan="3" class="spacer">&nbsp;</td>
          </tr>
          <tr>
            <td>Opt-in Percentage</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['percent']['opt_in'] ?></td>
          </tr>
          <tr>
            <td>Opt-in Number</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['total']['opt_in'] ?></td>
          </tr>
          <tr>
            <td colspan="3" class="spacer">&nbsp;</td>
          </tr>
          <tr>
            <td>Participation Percentage</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['percent']['participation'] ?></td>
          </tr>
          <tr>
            <td>Participation Number</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['total']['participation'] ?></td>
          </tr>
          <tr>
            <td colspan="3" class="spacer">&nbsp;</td>
          </tr>
          <tr>
            <td>Provided Email Percentage</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['percent']['with_email'] ?></td>
          </tr>
          <tr>
            <td>Provided Email Number</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['total']['with_email'] ?></td>
          </tr>
          <tr>
            <td colspan="3" class="spacer">&nbsp;</td>
          </tr>
          <tr>
            <td>Completed Survey Percentage</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['percent']['completed_survey'] ?></td>
          </tr>
          <tr>
            <td>Completed Survey Number</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['total']['completed_survey'] ?></td>
          </tr>
          <tr>
            <td colspan="3" class="spacer">&nbsp;</td>
          </tr>
          <tr>
            <td>Entered Give Away Percentage</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['percent']['entered_give_away'] ?></td>
          </tr>
          <tr>
            <td>Entered Give Away Number</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['total']['entered_give_away'] ?></td>
          </tr>
          <tr>
            <td colspan="3" class="spacer">&nbsp;</td>
          </tr>
          <tr>
            <td>Purchases Percentage</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['percent']['purchases'] ?></td>
          </tr>
          <tr>
            <td>Purchases Number</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['total']['purchases'] ?></td>
          </tr>
          <tr>
            <td colspan="3" class="spacer">&nbsp;</td>
          </tr>
          <tr>
            <td>Oticon Purchases Percentage</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['percent']['oticon_purchases'] ?></td>
          </tr>
          <tr>
            <td>Oticon Purchases Number</td>
            <td class="right">&nbsp;</td>
            <td class="right"><?php echo $results['total']['oticon_purchases'] ?></td>
          </tr>
        </table>
      <?php endif; ?>
            
    </div>
    
  </div>
  <div class="final_survey_footer">
    <?php echo $this->Html->image('/survey/img/popup_bottom.gif') ?>
  </div>
</div>