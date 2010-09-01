<?php
class ImportShell extends Shell {
  var $uses = array('Survey.SurveyContact');

  function main(){
    $this->out('Survey Plugin Importer');
    $count = $this->SurveyContact->import(true);
    $this->out("\r\n");
    $this->hr();
    $this->out("Import finished. $count imported.");
  }
}
?>
