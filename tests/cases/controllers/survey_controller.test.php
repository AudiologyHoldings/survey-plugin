<?php
/* Surveys Test cases generated on: 2010-05-06 18:05:13 : 1273192873*/
App::import('Controller', 'Survey.Surveys');
App::import('Component', 'Auth');
App::import('Component', 'Email');
App::import('Component', 'Session');
App::import('Component', 'RequestHandler');
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
  
  function __cronEnd(){
    /* Ignore */
  }
}

Mock::generate('AuthComponent');
Mock::generate('EmailComponent');
Mock::generate('SessionComponent');
Mock::generate('RequestHandlerComponent');
class SurveysControllerTestCase extends CakeTestCase {
  var $fixtures = array(
    'plugin.survey.survey_contact',
    'plugin.survey.survey_answer',
    'plugin.survey.survey_opt_in',
  );
  
	function startTest() {
		$this->Surveys = new TestSurveysController();
		$this->Surveys->SurveyContact = ClassRegistry::init('Survey.SurveyContact');
		$this->Surveys->SurveyAnswer = ClassRegistry::init('Survey.SurveyAnswer');
		$this->Surveys->SurveyOptIn = ClassRegistry::init('Survey.SurveyOptIn');
		$this->Surveys->Auth = new MockAuthComponent();
		$this->Surveys->Email = new MockEmailComponent();
		$this->Surveys->Session = new MockSessionComponent();
		$this->Surveys->RequestHandler = new MockRequestHandlerComponent();
	}
	
	function testSaveOptIn(){
	  $count = $this->Surveys->SurveyOptIn->find('count');
	  $this->Surveys->__saveOptIn();
	  $this->assertEqual($count + 1, $this->Surveys->SurveyOptIn->find('count'));
	}
	
	function testFirstShouldSaveOptIfClicked(){
	  $count = $this->Surveys->SurveyOptIn->find('count');
	  $this->Surveys->data = array();
	  $this->Surveys->first();
	  $this->assertEqual($count + 1, $this->Surveys->SurveyOptIn->find('count'));
	  $this->assertEqual('one', $this->Surveys->viewVars['start_page']);
	}
	
	function testSendTestFollow(){
	  if(Configure::read() > 0){
	    $this->Surveys->Email->expectOnce('send');
	    $this->Surveys->send_test_follow('nick@example.com');
	    $this->assertEqual('nick@example.com', $this->Surveys->Email->to);
	    $this->assertEqual('survey_follow_up', $this->Surveys->Email->template);
	  }
	}
	
	function testSendFollow(){
	  $this->Surveys->SurveyContact->setFinalEmailDate(1, 0);
	  $this->Surveys->SurveyContact->setFinalEmailDate(2, 0);
	  $this->Surveys->Email->setReturnValue('send', true);
	  $this->Surveys->Email->expectOnce('send');
	  
	  $this->assertFalse($this->Surveys->SurveyContact->field('final_email_sent', array('SurveyContact.id' => 2)));
	  
	  $this->Surveys->send_follow();

	  $this->assertTrue($this->Surveys->SurveyContact->field('final_email_sent', array('SurveyContact.id' => 2)));
	}
	
	function testGiveAway(){
	  //TODO
	}
	
	function testSecondShouldRedirectIfNoEmail(){
	  //$this->Surveys->Session->expectOnce('setFlash', array('Token required.', 'badFlash'));
	  $this->Surveys->second();
	  $this->assertEqual('/', $this->Surveys->redirectUrl);
	}
	
	function testSecondShouldRedirectIfContactByEmail(){
	  //$this->Surveys->Session->expectOnce('setFlash', array('Invalid Token.', 'badFlash'));
	  $this->Surveys->second('no_exist_email');
	  $this->assertEqual('/', $this->Surveys->redirectUrl);
	}
	
	function testSecondShouldShowSecondSurveyIfValidEmail(){
	  $email = 'nick@example.com';
	  $this->Surveys->Session->expectNever('setFlash');
	  $this->Surveys->second($email);
	  $this->assertFalse($this->Surveys->redirectUrl);
	  $this->assertEqual($email, $this->Surveys->viewVars['contact']['SurveyContact']['email']);
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
	  
	  $this->Surveys->second('nick@example.com');
	  
	  
	  $this->assertEqual(array('action' => 'give_away', 'nick@example.com'), $this->Surveys->redirectUrl);
	  $this->assertEqual($answer_count + 3, $this->Surveys->SurveyAnswer->find('count'));
	  $lastanswer = $this->Surveys->SurveyAnswer->find('last');
	  $this->assertEqual($contact_id, $lastanswer['SurveyAnswer']['survey_contact_id']);
	  
	  $contact = $this->Surveys->SurveyContact->findById($contact_id);
	  $this->assertTrue($contact['SurveyContact']['finished_survey']);
	}
	
	function testShouldReturnTrueIfAjax(){
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
	  $this->Surveys->RequestHandler->setReturnValue('isAjax', true);
	  $this->Surveys->Email->expectOnce('send');
	  $this->assertTrue($this->Surveys->first());
	  $this->assertFalse($this->Surveys->redirectUrl);
	  $this->assertEqual('test@example.com', $this->Surveys->Email->to);
	  $this->assertEqual('survey_thanks', $this->Surveys->Email->template);
	}
	
	function testShouldReturnValidationErrorIfAjaxAndNotValid(){
	  $this->Surveys->data = array(
	    'SurveyContact' => array(
	      'email' => 'asdasd'
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
	  $this->Surveys->RequestHandler->setReturnValue('isAjax', true);
	  $this->Surveys->Email->expectNever('send');
	  $this->assertEqual('Must be a valid email.', $this->Surveys->first());
	  $this->assertFalse($this->Surveys->redirectUrl);
	}
	
	function testFirstShouldEmailWithValidEmail(){
	  $count = $this->Surveys->SurveyOptIn->find('count');
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
	  $this->Surveys->first();
	  $this->assertEqual(array('action' => 'thanks'), $this->Surveys->redirectUrl);
	  $this->assertEqual('test@example.com', $this->Surveys->Email->to);
	  $this->assertEqual('survey_thanks', $this->Surveys->Email->template);
	  $this->assertEqual($count, $this->Surveys->SurveyOptIn->find('count'));
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