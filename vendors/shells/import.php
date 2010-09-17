<?php
class ImportShell extends Shell {
  var $uses = array('Survey.SurveyContact');

  function main(){
    $startfrom = isset($this->params['startfrom']) ? $this->params['startfrom'] : 'now'; 
    $this->out('Survey Plugin Importer');
    $count = $this->SurveyContact->import(true, $startfrom);
    $this->out("\r\n");
    $this->hr();
    $this->out("Import finished. $count imported.");
  }
  
  function help(){
    $this->out("Survey Plugin Helper");
    $this->hr();
    $this->out("Usage:");
    $this->out("   cake import                         Run importer truncate all surveys");
    $this->out("   cake import -startfrom <datetime>   Preserver all records from date specified");
    $this->hr();
    $this->out("Example:");
    $this->out("   cake import");
    $this->out("   cake import -startfrom 2010-09-01");
  }
}
?>
