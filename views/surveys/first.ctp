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
          <strong>You are now signed up for our email Newsletters and we will be sending you a <br />
          copy of our Free Consumer's Guide To Hearing Aids! Please click the CLOSE<br />
          button below to close this window and continue browsing.</strong>
        </p>
        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
        
        <div class="pop_button">
          <?php echo $this->Html->image('/survey/img/btn_close.png', array('class' => 'hand', 'class' => 'btn_close hand')) ?>
        </div>
      </div>
    
      <div class="pop_body" id="survey">
        <h1><?php echo $this->Html->image('/survey/img/healthy_hearing.png') ?></h1>
        <div id="one">
        	<?php echo $this->Form->create('Survey', array('url' => array('plugin' => 'survey', 'controller' => 'surveys', 'action' => 'first'), 'onsubmit' => 'SP.nextPage(); return false;')); ?>
          <h2>Thanks for helping!</h2>
          <p class="pop_text">
            <b>Complete our short 3 question survey and we will use the results to<br />
            help us give you better service in the future.<br /><br />
            We apprecate your feedback!
            </b>
          </p>  
          <table class="pop_questions">
            <tr>
              <td>
                <div class="white_box pop_question">
                  <h3>Question #1</h3>
                  <p class="q_text">Which range below best describes your age?</p>
                  <?php echo $this->Form->input('Survey.1_age', array(
                    'type' => 'radio',
                    'before' => '<ul class="question age"><li>',
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
                  <?php echo $this->Form->input('Survey.2_likely_to_schedule', array(
                    'type' => 'radio',
                    'before' => '<ul class="question likely"><li>Very Unlikely</li><li>',
                    'separator' => '</li><li>',
                    'after' => '</li><li>Very Likely</li></ul>',
                    'legend' => false,
                    'options' => array(
                      '0' => '1',
                      '1' => '2',
                      '2' => '3',
                      '3' => '4',
                      '4' => '5',
                      '5' => '6',
                      '6' => '7',
                    )
                  )) ?>
                </div>
              </td>
            </tr>
          </table>
          <div class="error-message float_left" id="QuestionError">&nbsp;<!-- Update with error messages with javascript --></div>
          
          <div class="pop_button_error pop_submit">
          	<?php echo $this->Form->submit('/survey/img/btn_continue.png', array('class' => 'hand', 'id' => 'btn_continue')); ?>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
        <div id="two" style="display:none;">
        	<?php echo $this->Form->create('Survey', array('url' => array('plugin' => 'survey', 'controller' => 'surveys', 'action' => 'save_email'))); ?>
          <h2>You're almost done!</h2>
          <p class="pop_text">
            <b>Enter your email</b> address below and we will sign you up for our eNewletter subscription<br />
            and send you a copy of our Free Comprehensive Guide to Hearing Aids and Hearing Loss.<br /><br />
            <b>* All fields required.</b><br /><br />
          </p>
          <div class="pop_questions">
            <div class="email">
              <div class="clear_left">
                <?php echo $this->Form->input('Survey.id', array('type' => 'hidden', 'id' => 'SurveyId')); ?>
                <?php echo $this->Form->input('Survey.first_name', array('label' => 'First Name: *')); ?>
                <?php echo $this->Form->input('Survey.last_name', array('label' => 'Last Name: *')); ?>
                <?php echo $this->Form->input('Survey.email', array('label' => 'Email Address: *')); ?>
                <?php echo $this->Form->input('Survey.zip', array('label' => 'Zip Code: *')); ?>
              </div>
              <div class="error-message" id="EmailError" style="display:none;"><!-- Update with error messages with javascript --></div>
            </div>
            <h4>We will not share your information or send unsolicited emails.</h4><br />
          </div>
          
          <div class="pop_button pop_submit">
            <?php echo $this->Form->submit('/survey/img/btn_submit_survey.png', array('class' => 'hand', 'id' => 'btn_submit')); ?>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
      
    </div>
  </div>
</div>

<div class="black_overlay"></div>

<?php echo $this->Html->scriptBlock("SP = new SurveyPopup('$start_page');"); ?>