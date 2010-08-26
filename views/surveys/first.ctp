<?php echo $this->Html->script('/survey/js/Class-0.0.2.min.js', array('inline' => false)); ?>
<?php echo $this->Html->script('/survey/js/jq_survey_popup.js', array('inline' => false)); ?>
<div class="hideshow">
  <div class="fade"></div>
  <div id="popup" class="popup popup_block_full">
    <div class="wrapper wrapper-popup">
      <div id="pop_top">
        <span>
        <?php echo $this->Html->link($this->Html->image('/survey/img/link_close.png'), '#', array('escape' => false, 'class' => 'btn_close')); ?>
        </span>
      </div>
      <div id="pop_body">
        <h1><?php echo $this->Html->image('/survey/img/healthy_hearing.png') ?></h1>
        <?php echo $this->Form->create('SurveyContact', array('url' => array('plugin' => 'survey', 'controller' => 'surveys', 'action' => 'first'))); ?>
        <div id="one">
          <h2>Thanks for helping!</h2>
          <p class="pop_text">
            Answer the following questions and we'll<br />
            donate $1 to the House Ear Institute on your behalf.
          </p>  
          <table class="pop_questions">
            <tr>
              <td>
                <div class="white_box pop_question">
                  <h3>Question #1</h3>
                  <p class="q_text">Which range below best describes your age?</p>
                  <?php echo $this->Form->input('SurveyAnswer.0.question', array('type' => 'hidden', 'value' => '1_age')) ?>
                  <?php echo $this->Form->input('SurveyAnswer.0.answer', array(
                    'type' => 'radio',
                    'before' => '<ul class="question"><li>',
                    'separator' => '</li><li>',
                    'after' => '</li></ul>',
                    'legend' => false,
                    'options' => array(
                      'under-18' => 'under 18',
                      '18-39' => '18-39',
                      '40-49' => '40-49',
                      '50-59' => '50-59',
                      '60-69' => '60-69',
                      '70-79' => '70-79',
                      '80plus' => '80+',
                    )
                  )) ?>
                </div>
              </td>
              <td>
                <div class="white_box pop_question">
                  <h3>Question #2</h3>
                  <p class="q_text">How likely are you to schedule an appointment with a hearing professional in the next 30 days?</p>
                  <?php echo $this->Form->input('SurveyAnswer.1.question', array('type' => 'hidden', 'value' => '2_likely_to_schedule')) ?>
                  <?php echo $this->Form->input('SurveyAnswer.1.answer', array(
                    'type' => 'radio',
                    'before' => '<ul class="question"><li>',
                    'separator' => '</li><li>',
                    'after' => '</li></ul>',
                    'legend' => false,
                    'options' => array(
                      '0' => 'Not Very Likely',
                      '1' => '',
                      '2' => '',
                      '3' => 'Somewhat Likely',
                      '4' => '',
                      '5' => '',
                      '6' => 'Very Likely',
                    )
                  )) ?>
                </div>
              </td>
            </tr>
          </table>
          
          <div class="pop_button">
            <?php echo $this->Html->image('/survey/img/btn_continue.png', array('class' => 'hand', 'id' => 'btn_continue')) ?>
          </div>
        </div>
        <div id="two" style="display:none;">
          <h2>You're almost done!</h2>
          <p class="pop_text">
            Enter your email address below and we'll send you an email confirming<br />
            the donation. As a thank you, we'll also give you the opportunity to register<br />
            to win $500* by answering a three question follow-up survey in about 30 days
          </p>
          <div class="pop_questions">
            <div class="gift_card">
              <?php echo $this->Html->image('/survey/img/gift_card.png') ?>
            </div>
            <div class="email">
              <span class="vcard">
                <?php echo $this->Html->image('/survey/img/popup_contact.png'); ?>
                &nbsp;&nbsp;&nbsp;<?php echo $this->Html->link('Click here to add us to your address book.', array('action' => 'reply_email', 'ext' => 'vcf')) ?>
              </span>
              <?php echo $this->Form->input('SurveyContact.email', array('label' => 'Email Address:')); ?>
            </div>
            <h4>This survey is for research purposes only</h4>
            <h4>We will not share your information or send unsolicited emails.</h4>
          </div>
          
          <div class="pop_button">
            <?php echo $this->Form->submit('/survey/img/btn_submit_survey.png', array('class' => 'hand', 'id' => 'btn_submit')) ?>
          </div>
        </div>
        <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</div>
<?php echo $this->Html->scriptBlock("SP = new SurveyPopup('$start_page');"); ?>