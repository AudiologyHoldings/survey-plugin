<div id="give_away">
  <div class="wrapper wrapper-spiral">
    <div id="pop_top">
    </div>
    
    <div id="pop_body">
      <h1><?php echo $this->Html->image('/survey/img/healthy_hearing.png') ?></h1>
      <?php echo $this->Form->create('SurveyContact', array('url' => array('plugin' => 'survey', 'controller' => 'surveys', 'action' => 'give_away', $contact['SurveyContact']['email']))); ?>
      <?php echo $this->Form->input('SurveyContact.id', array('type' => 'hidden', 'value' => $contact['SurveyContact']['id'])); ?>
      <div class="gift_card give_away">
        <?php echo $this->Html->image('/survey/img/gift_card.png') ?>
      </div>
      <h2>Thank you!</h2>
      <p class="pop_text">
        We appreciate your participation.<br />
        To be entered into the $500 monthly drawing, please complete the form below:<br /><br />
        <span class="required">All fields are required.</span>
      </p>
      
      <div class="checkbox">
        <?php echo $this->Form->input('is_18', array('type' => 'checkbox', 'label' => 'I am 18 years of age or older.')) ?>
      </div>
      <?php echo $this->Form->input('first_name', array('div' => 'give_away_input text required')) ?>
      <?php echo $this->Form->input('last_name', array('div' => 'give_away_input text required')) ?>
      <?php echo $this->Form->input('phone', array('div' => 'give_away_input text required')) ?>
      
      <div class="pop_button">
        <?php echo $this->Form->submit('/survey/img/btn_enter_drawing.png', array('class' => 'hand', 'id' => 'btn_submit')) ?>
      </div>
      
      <?php echo $this->Form->end(); ?>
    </div>
    
  </div>
  <div class="final_survey_footer">
    <?php echo $this->Html->image('/survey/img/popup_bottom.gif') ?>
  </div>
</div>