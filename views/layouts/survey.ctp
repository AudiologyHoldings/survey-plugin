<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <?php
      echo $this->Html->css('/survey/css/style.css');
      echo $this->Html->script('/survey/js/prototype.js');
      echo $this->Html->script('/survey/js/scriptaculous.js');
      echo $this->Html->script('/survey/js/effects.js');
      echo $this->Html->script('/survey/js/controls.js');
      
      echo $this->Html->script('/survey/js/jquery-1.4.2.js');
      echo $this->Html->scriptBlock('$.noConflict()');
      
      echo $scripts_for_layout;
    ?>
    <title>Healthy Hearing Survey :: <?php echo $title_for_layout ?></title>
  </head>
  <body>
    <div id="header">
    </div>
    <div id="content">
      <div id="content_for_layout">
        <?php echo $content_for_layout ?>
      </div>
    </div>
    <div id="footer">
    </div>
    <?php echo $this->Js->writeBuffer(); ?>
  </body>
</html>