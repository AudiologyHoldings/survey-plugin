<?php
/* Surveys Test cases generated on: 2010-05-06 18:05:13 : 1273192873*/
App::import('Controller', 'Survey.Surveys');
App::import('Component', 'Auth');
App::import('Model', 'User');
class TestSurveysController extends SurveysController {
	var $autoRender = false;
	var $redirectUrl = null;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

Mock::generatePartial('TestSurveysController', 'MockTestSurveysController', array('__linkAccount'));
Mock::generate('AuthComponent');
class SurveysControllerTestCase extends CakeTestCase {
  var $fixtures = array(
    'plugin.survey.survey_contact',
    'plugin.survey.survey_answer',
  );
  
	function startTest() {
		$this->Surveys = new MockTestSurveysController();
		$this->Surveys->SurveyContact = ClassRegistry::init('Survey.SurveyContact');
		$this->Surveys->SurveyAnswer = ClassRegistry::init('Survey.SurveyAnswer');
		$this->Surveys->Auth = new MockAuthComponent();
	}
	
	function testInstance(){
	  
	}

	function endTest() {
		unset($this->Surveys);
		ClassRegistry::flush();
	}

}
?>