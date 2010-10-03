<?php
class PurgeShell extends Shell {
  var $uses = array('Survey.SurveyContact');

  function main(){
    $this->out('Survey Plugin Purge Ignore Data');
    $count = $this->SurveyContact->purgeIgnoreList(true);
    $this->out("\r\n");
    $this->hr();
    $this->out("Purge finished. $count purged.");
  }
}
?>
