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
	
	function testGiveAway(){
	  //TODO
	}
	
	function testSecondShouldRedirectIfNoToken(){
	  $this->Surveys->Session->expectOnce('setFlash', array('Token required.', 'badFlash'));
	  $this->Surveys->second();
	  $this->assertEqual('/', $this->Surveys->redirectUrl);
	}
	
	function testSecondShouldRedirectIfContactByToken(){
	  $this->Surveys->Session->expectOnce('setFlash', array('Invalid Token.', 'badFlash'));
	  $this->Surveys->second('no_exist_token');
	  $this->assertEqual('/', $this->Surveys->redirectUrl);
	}
	
	function testSecondShouldShowSecondSurveyIfValidToken(){
	  $token = 'token';
	  $this->Surveys->Session->expectNever('setFlash');
	  $this->Surveys->second($token);
	  $this->assertFalse($this->Surveys->redirectUrl);
	  $this->assertEqual($token, $this->Surveys->viewVars['contact']['SurveyContact']['token']);
	}
	
	function testSecondShouldRedirecToGiveAway(){
	  $contact_id = 2;
	  $this->Surveys->data = array(
	    'SurveyContact' => array(
	      'id' => $contact_id
	    ),
	    'SurveyAnswer' => array(
	      array(
	        'question' => '3_visit_clinic',
	        'answer' => 'Yes'
	      ),
	      array(
	        'question' => '4_purchase_hearing_id',
	        'answer' => 'Yes'
	      ),
	      array(
	        'question' => '5_what_brand',
	        'answer' => 'Oticon'
	      ),
	    )
	  );
	  $this->Surveys->Session->expectNever('setFlash');
	  $answer_count = $this->Surveys->SurveyAnswer->find('count');
	  $contact = $this->Surveys->SurveyContact->findById($contact_id);
	  $this->assertFalse($contact['SurveyContact']['finished_survey']);
	  
	  $this->Surveys->second('token2');
	  
	  
	  $this->assertEqual(array('action' => 'give_away', 'token2'), $this->Surveys->redirectUrl);
	  $this->assertEqual($answer_count + 3, $this->Surveys->SurveyAnswer->find('count'));
	  $lastanswer = $this->Surveys->SurveyAnswer->find('last');
	  $this->assertEqual($contact_id, $lastanswer['SurveyAnswer']['survey_contact_id']);
	  
	  $contact = $this->Surveys->SurveyContact->findById($contact_id);
	  $this->assertTrue($contact['SurveyContact']['finished_survey']);
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