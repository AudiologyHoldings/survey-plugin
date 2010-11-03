<?php
/* SurveyParticipant Test cases generated on: 2010-11-02 14:11:59 : 1288729919*/
App::import('Model', 'survey.SurveyParticipant');

class SurveyParticipantTestCase extends CakeTestCase {
	function startTest() {
		$this->SurveyParticipant =& ClassRegistry::init('SurveyParticipant');
	}

	function endTest() {
		unset($this->SurveyParticipant);
		ClassRegistry::flush();
	}

}
?>