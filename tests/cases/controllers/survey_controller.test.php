<?php
/* Surveys Test cases generated on: 2010-05-06 18:05:13 : 1273192873*/
App::import('Controller', 'Survey.Surveys');
App::import('Component', 'Auth');
App::import('Component', 'Email');
App::import('Component', 'Session');
App::import('Model', 'User');
class TestSurveysController extends SurveysController {
	var $autoRender = false;
	var $redirectUrl = null;
	var $renderedAction = null;
	var $stopped = null;
	
	function redirect($url, $status = null, $exit = true) {
    $this->redirectUrl = $url;
  }
  
  function render($action = null, $layout = null, $file = null) {
    $this->renderedAction = $action;
  }

  function _stop($status = 0) {
    $this->stopped = $status;
  }
}

Mock::generate('AuthComponent');
Mock::generate('EmailComponent');
Mock::generate('SessionComponent');
class SurveysControllerTestCase extends CakeTestCase {
  var $fixtures = array(
    'plugin.survey.survey_contact',
    'plugin.survey.survey_answer',
  );
  
	function startTest() {
		$this->Surveys = new TestSurveysController();
		$this->Surveys->SurveyContact = ClassRegistry::init('Survey.SurveyContact');
		$this->Surveys->SurveyAnswer = ClassRegistry::init('Survey.SurveyAnswer');
		$this->Surveys->Auth = new MockAuthComponent();
		$this->Surveys->Email = new MockEmailComponent();
		$this->Surveys->Session = new MockSessionComponent();
	}
	
	function testFirstShouldEmailWithValidEmail(){
	  $this->Surveys->data = array(
	    'SurveyContact' => array(
	      'email' => 'test@example.com'
	    ),
	    'SurveyAnswer' => array(
	      array(
	        'question' => '1_age',
	        'answer' => '80plus'
	      ),
	      array(
	        'question' => '2_likely',
	        'answer' => '7'
	      ),
	    )
	  );
	  
	  $this->Surveys->Email->expectOnce('send');
	  $this->Surveys->Session->expectOnce('setFlash', array('Thank you message', 'goodFlash'));
	  $this->Surveys->first();
	  $this->assertEqual(array('action' => 'thanks'), $this->Surveys->redirectUrl);
	  $this->assertEqual('test@example.com', $this->Surveys->Email->to);
	  $this->assertEqual('survey_thanks', $this->Surveys->Email->template);
	}
	
	function testFirstShouldNotEmail(){
	  $this->Surveys->data = array(
	    'SurveyContact' => array(
	      'email' => ''
	    ),
	    'SurveyAnswer' => array(
	      array(
	        'question' => '1_age',
	        'answer' => '80plus'
	      ),
	      array(
	        'question' => '2_likely',
	        'answer' => '7'
	      ),
	    )
	  );
	  
	  $this->Surveys->Email->expectNever('send');
	  $this->Surveys->Session->expectOnce('setFlash', array('Thank you message', 'goodFlash'));
	  $this->Surveys->first();
	  $this->assertEqual(array('action' => 'thanks'), $this->Surveys->redirectUrl);
	  $this->assertFalse($this->Surveys->Email->to);
	  $this->assertFalse($this->Surveys->Email->template);
	}

	function endTest() {
		unset($this->Surveys);
		ClassRegistry::flush();
	}

}
?>