<?php
/* SurveyAnswer Test cases generated on: 2010-07-27 22:07:40 : 1280292760*/
App::import('Model', 'survey.SurveyAnswer');

class SurveyAnswerTestCase extends CakeTestCase {
	var $fixtures = array('app.survey_answer', 'app.survey_contact');

	function startTest() {
		$this->SurveyAnswer =& ClassRegistry::init('SurveyAnswer');
	}

	function endTest() {
		unset($this->SurveyAnswer);
		ClassRegistry::flush();
	}

}
?>