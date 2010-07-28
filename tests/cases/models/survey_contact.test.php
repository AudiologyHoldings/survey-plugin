<?php
/* SurveyContact Test cases generated on: 2010-07-27 22:07:48 : 1280292888*/
App::import('Model', 'survey.SurveyContact');

class SurveyContactTestCase extends CakeTestCase {
	var $fixtures = array('app.survey_contact', 'app.survey_answer');

	function startTest() {
		$this->SurveyContact =& ClassRegistry::init('SurveyContact');
	}

	function endTest() {
		unset($this->SurveyContact);
		ClassRegistry::flush();
	}

}
?>