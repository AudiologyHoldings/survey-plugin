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
class SurveysControllerTest extends CakeTestCase {
  var $fixtures = array(
  	/* Contact */
    'app.contact',
    'app.contact_detail',
    /* Location */
    'app.location',
    'app.call_source',
		'app.hour',
		'app.staff',
		'app.site',
		'app.corp',
		'app.user',
		'app.group',
		'app.groups_users_join',
		'app.product',
		'app.content',
		'app.c_category',
		'app.c_cat_join',
		'app.products_content_join',
		'app.s_category',
		'app.s_cat_subscription',
		'app.s_corp_subscription',
		'app.su_join',
		'app.zipcode',
		'app.zip',
		/* Plugin */
    'plugin.survey.survey',
    'plugin.survey.survey_opt_in',
    'plugin.survey.survey_participant',
  );
  
	function startTest() {
		$this->Surveys = new TestSurveysController();
		$this->Surveys->Contact = ClassRegistry::init('Contact');
		$this->Surveys->Survey = ClassRegistry::init('Survey.Survey');
		$this->Surveys->SurveyOptIn = ClassRegistry::init('Survey.SurveyOptIn');
		$this->Surveys->SurveyParticipant = ClassRegistry::init('Survey.SurveyParticipant');
		$this->Surveys->Auth = new MockAuthComponent();
		$this->Surveys->Email = new MockEmailComponent();
		$this->Surveys->Session = new MockSessionComponent();
		$this->Surveys->RequestHandler = new MockRequestHandlerComponent();
	}
	
	function test_sendEmail(){
		$contact_id = 1;
		$this->Surveys->__sendEmail($contact_id);
		$this->assertTrue(!empty($this->Surveys->viewVars['locations_split']));
	}
	
	function testFindReport(){
		$this->Surveys->data = array(
			'Survey' => array(
				'start_month' => 'Jan 2011',
				'end_month' => 'Feb 2011',
				'page_views' => '5000'
			)
		);
		$this->Surveys->admin_report();
		$results = $this->Surveys->viewVars['results'];
		$this->assertTrue($results);
	}
	
	function testSaveParticipantShouldSaveIfAjax(){
	  $count = $this->Surveys->SurveyParticipant->find('count');
	  $this->Surveys->RequestHandler->setReturnValue('isAjax', true);
	  $this->Surveys->save_participant();
	  $this->assertEqual($count + 1, $this->Surveys->SurveyParticipant->find('count'));
	}
	
	function testSaveParticipantShouldNotSaveRegularRequest(){
	  $count = $this->Surveys->SurveyParticipant->find('count');
	  $this->Surveys->RequestHandler->setReturnValue('isAjax', false);
	  $this->Surveys->save_participant();
	  $this->assertEqual($count, $this->Surveys->SurveyParticipant->find('count'));
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
	
	function testShouldReturnTrueIfAjax(){
	  $this->Surveys->data = array(
	    'Survey' => array(
	    	'id' => 1,
	    	'first_name' => 'Nick',
	    	'last_name' => 'Nick',
	    	'email' => 'not@taken.com',
	    	'zip' => '90210',
	    )
	  );
	  $this->Surveys->RequestHandler->setReturnValue('isAjax', true);
	  $this->Surveys->Email->expectOnce('send');
	  $this->assertTrue($this->Surveys->second());
	  $this->assertFalse($this->Surveys->redirectUrl);
	  $this->assertEqual('not@taken.com', $this->Surveys->Email->to);
	  $this->assertEqual('survey_thanks', $this->Surveys->Email->template);
	}
	
	function testShouldReturnValidationErrorIfAjaxAndNotValid(){
	  $this->Surveys->data = array(
	    'Survey' => array(
	    	'id' => 1,
	    	'first_name' => '',
	    	'last_name' => '',
	    	'email' => '',
	    	'zip' => '',
	    )
	  );
	  $this->Surveys->RequestHandler->setReturnValue('isAjax', true);
	  $this->Surveys->Email->expectNever('send');
	  $this->assertEqual('ERROR: Please enter a first name.', $this->Surveys->second());
	  $this->assertFalse($this->Surveys->redirectUrl);
	}
	
	function testShouldReturnValidationErrorIfAjaxAndNotValidEmail(){
	  $this->Surveys->data = array(
	    'Survey' => array(
	    	'id' => 2,
	    	'first_name' => 'Nick',
	    	'last_name' => 'Baker',
	    	'email' => 'nurvzy@gmail.com', //fail
	    	'zip' => '90210',
	    )
	  );
	  $this->Surveys->RequestHandler->setReturnValue('isAjax', true);
	  $this->Surveys->Email->expectNever('send');
	  $this->assertEqual('ERROR: Unique Email must be present.', $this->Surveys->second());
	  $this->assertFalse($this->Surveys->redirectUrl);
	}
	
	function endTest() {
		unset($this->Surveys);
		ClassRegistry::flush();
	}

}
?>