<?php
/* SurveyOptIn Test cases generated on: 2010-07-27 22:07:40 : 1280292760*/
App::import('Model', 'survey.SurveyOptIn');

class SurveyOptInTestCase extends CakeTestCase {
	var $fixtures = array(
	  'plugin.survey.survey_contact', 
	  'plugin.survey.survey_answer',
	  'plugin.survey.survey_opt_in',
	);

	function startTest() {
		$this->SurveyOptIn =& ClassRegistry::init('SurveyOptIn');
	}
	
	function testAdd(){
	  $count = $this->SurveyOptIn->find('count');
	  $this->assertTrue($this->SurveyOptIn->add());
	  $this->assertEqual($count + 1, $this->SurveyOptIn->find('count'));
	  $result = $this->SurveyOptIn->find('last');
	  $this->assertEqual($this->SurveyOptIn->str2datetime('now'), $result['SurveyOptIn']['created']); 
	}

	function endTest() {
		unset($this->SurveyOptIn);
		ClassRegistry::flush();
	}

}
?>