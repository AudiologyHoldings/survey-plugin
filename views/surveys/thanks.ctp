<div id="popup">
  <div class="wrapper">
    <div id="pop_top">
      <span>
        <?php echo $this->Html->link($this->Html->image('/survey/img/link_close.png'), '#', array('escape' => false)); ?>
      </span>
    </div>
    
    <div id="pop_body">
      <h1><?php echo $this->Html->image('/survey/img/healthy_hearing.png') ?></h1>
      <div id="one">
        <h2>Thank you for your help!</h2>
        <p class="pop_text">
          <strong>$1 has been donated to the House Ear Institute on your behalf.</strong>
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
          <?php echo $this->Html->image('/survey/img/btn_close.png', array('class' => 'hand', 'id' => 'btn_continue')) ?>
        </div>
      </div>
      
    </div>
  </div>
</div>