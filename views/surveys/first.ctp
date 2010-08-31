<?php/* echo $this->Html->script('/survey/js/Class-0.0.2.min.js', array('inline' => false)); */?>
<?php echo $this->Html->script('/survey/js/jquery.class.js', array('inline' => false)); ?>
<?php echo $this->Html->script('/survey/js/jq_survey_popup.js', array('inline' => false)); ?>

<div class="white_content white_content_full">
  <div id="popup" class="popup popup_block_full">
    <div class="wrapper wrapper-popup">
      <div id="pop_top">
        <span>
        <?php echo $this->Html->link($this->Html->image('/survey/img/link_close.gif'), '#', array('escape' => false, 'class' => 'btn_close')); ?>
        </span>
      </div>
      
      <div class="pop_body" id="thanks" style="display:none;">
        <h1><?php echo $this->Html->image('/survey/img/healthy_hearing.png') ?></h1>
        <h2>Thank you for your help!</h2>
        <p class="pop_text">
          <strong>$1 has been donated to the House Ear Institute on your behalf.</strong>
          <br /><br />
          <strong>Be sure to check your inbox.</strong> You will get confirmation of your donation<br />
          and it will also tell you how you can enter to <strong>win $500</strong> by participating<br />
          in a short 3 question follow-up survey.
        </p>
        <div class="white_box pop_thanks center">
          <h3>Who is the House Ear Institute?</h3>
          <div class="float_right"><?php echo $this->Html->image('/survey/img/house_ear_institute.png'); ?></div>
          <p class="q_text">
            The House Ear Institute is a non-profit organization<br />
            dedicated to advancing hearing science through<br />
            research and education to improve quality of life.
          </p>
        </div>
        
        <div class="pop_button">
          <?php echo $this->Html->image('/survey/img/btn_close.png', array('class' => 'hand', 'class' => 'btn_close hand')) ?>
        </div>
      </div>
    
      <div class="pop_body" id="survey">
        <h1><?php echo $this->Html->image('/survey/img/healthy_hearing.png') ?></h1>
        <?php echo $this->Form->create('SurveyContact', array('url' => array('plugin' => 'survey', 'controller' => 'surveys', 'action' => 'first'))); ?>
        <div id="one">
          <h2>Thanks for helping!</h2>
          <p class="pop_text">
            <b>Complete our short three question survey and<br />
            we'll donate $1 to the House Ear Institute on your behalf.</b>
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
            <b>Enter your email</b> address below and we'll send you an email <b>confirming<br />
            the donation.</b> As a thank you, we'll also give you the opportunity to register<br />
            to <b>win $500*</b> by answering a three question follow-up survey in about 30 days
          </p>
          <div class="pop_questions">
            <div class="gift_card">
              <?php echo $this->Html->image('/survey/img/gift_card.png') ?>
            </div>
            <div class="email">
              <span class="vcard">
                <?php echo $this->Html->image('/survey/img/popup_contact.png', array('id' => 'img_roledex')); ?>
                <p>
                  &nbsp;&nbsp;&nbsp;<?php echo $this->Html->link('Click here to add us to your address book.', array('action' => 'reply_email', 'ext' => 'vcf')) ?><br />
                  &nbsp;&nbsp;&nbsp;Our email is <?php echo Configure::read('Survey.email'); ?>
                </p>
              </span>
              <div class="clear_left">
                <?php echo $this->Form->input('SurveyContact.email', array('label' => 'Email Address:')); ?>
              </div>
              <div class="error-message" id="EmailError" style="display:none;"><!-- Update with error messages with javascript --></div>
            </div>
            <h4>This survey is for research purposes only</h4>
            <h4>We will not share your information or send unsolicited emails.</h4>
          </div>
          
          <div class="pop_button">
            <?php echo $this->Form->submit('/survey/img/btn_submit_survey.png', array('class' => 'hand', 'id' => 'btn_submit')); ?>
          </div>
        </div>
        <?php echo $this->Form->end(); ?>
      </div>
      
    </div>
  </div>
</div>

<div class="black_overlay"></div>

<?php echo $this->Html->scriptBlock("SP = new SurveyPopup('$start_page');"); ?>