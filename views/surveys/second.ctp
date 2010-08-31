<?php echo $this->Html->script('/survey/js/survey_final.js', array('inline' => false)); ?>
<div id="final_survey">
  <div class="wrapper wrapper-spiral">
    <div id="pop_top">
    </div>
    
    <div id="pop_body">
      <h1><?php echo $this->Html->image('/survey/img/healthy_hearing.png') ?></h1>
      <?php echo $this->Form->create('SurveyContact', array(
        'url' => array('plugin' => 'survey', 'controller' => 'surveys', 'action' => 'second', $contact['SurveyContact']['email']),
        'id' => 'final_form'
      )); ?>
      <?php echo $this->Form->input('SurveyContact.id', array('type' => 'hidden', 'value' => $contact['SurveyContact']['id'])); ?>
      <h2>Here is your follow-up survey!</h2>
      <p class="pop_text">
        Please respond to the following questions based on your recent experience.
      </p>
      
      <div id="one">
        <div class="white_box survey_question center">
          <h3>Question #1</h3>
          <p class="q_text">
            Did you visit a clinic that you found in the Healthy Hearing directory?
          </p>
          <?php echo $this->Form->input('SurveyAnswer.0.question', array('type' => 'hidden', 'value' => '3_visit_clinic')) ?>
          <?php echo $this->Form->input('SurveyAnswer.0.answer', array(
            'type' => 'radio',
            'before' => '<ul class="survey_question"><li>',
            'separator' => '</li><li>',
            'after' => '</li></ul>',
            'legend' => false,
            'options' => array(
              'Yes' => 'Yes',
              'Appointment' => 'Not yet, but I have an appointment',
              'No' => 'No',
            ),
            'class' => 'question_1'
          )) ?>
        </div>
      </div>
      
      <div id="two" style="display:none;">
        <div class="white_box survey_question center">
          <h3>Question #2</h3>
          <p class="q_text">
            Did you purchase a hearing aid as a result of that visit?
          </p>
          <?php echo $this->Form->input('SurveyAnswer.1.question', array('type' => 'hidden', 'value' => '4_purchase_hearing_aid')) ?>
          <?php echo $this->Form->input('SurveyAnswer.1.answer', array(
            'type' => 'radio',
            'before' => '<ul class="survey_question"><li>',
            'separator' => '</li><li>',
            'after' => '</li></ul>',
            'legend' => false,
            'options' => array(
              'Yes' => 'Yes',
              'No' => 'No',
            ),
            'class' => 'question_2'
          )) ?>
        </div>
      </div>
      
      <div id="three" style="display:none;">
        <div class="white_box survey_question center">
          <h3>Question #3</h3>
          <p class="q_text">
            Which brand of hearing aid did you purchase?
          </p>
          <?php echo $this->Form->input('SurveyAnswer.2.question', array('type' => 'hidden', 'value' => '5_what_brand')) ?>
          <?php echo $this->Form->input('SurveyAnswer.2.answer', array(
            'type' => 'radio',
            'before' => '<ul class="survey_question"><li>',
            'separator' => '</li><li>',
            'after' => '</li></ul>',
            'legend' => false,
            'options' => array(
              'Oticon' => 'Oticon',
              'Beltone' => 'Beltone',
              'Phonak' => 'Phonak',
              'MiracleEar' => 'Miracle Ear',
              'Other' => 'Other',
            ),
            'class' => 'question_3'
          )) ?>
        </div>
      </div>
      
      <div id="resend" style="display:none;">
        <div class="white_box survey_question center">
          <h3 class="green">Thanks.</h3>
          <p class="q_text">
            We'll send you a follow-up email in about 30 days.<br />
            You will be able to enter the drawing at that time.<br />
            Please visit our <?php echo $this->Html->link('Find A Professional', '/hearing-aids') ?> section.
          </p>
        </div>
      </div>
      
      <div id="thanks" style="display:none;">
        <div class="white_box survey_question center">
          <h3 class="green">That's all. Click Submit Survey to finish.</h3>
          <p class="q_text">
            Thanks, that's all the questions we have. You can enter your drawing<br />
            information on the next screen.  Thanks again for participating.
          </p>
          <div class="pop_button">
            <?php echo $this->Form->submit('/survey/img/btn_submit_survey.png', array('class' => 'hand', 'id' => 'btn_submit')) ?>
          </div>
        </div>
      </div>
      
      <?php echo $this->Form->end(); ?>
    </div>
    
  </div>
  <div class="final_survey_footer">
    <?php echo $this->Html->image('/survey/img/popup_bottom.gif') ?>
  </div>
</div>
<?php echo $this->Html->scriptBlock("SF = new SurveyFinal('{$contact['SurveyContact']['email']}');"); ?>