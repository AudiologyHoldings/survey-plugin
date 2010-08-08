<?php
/* SurveyContact Test cases generated on: 2010-07-27 22:07:48 : 1280292888*/
App::import('Model', 'survey.SurveyContact');

class SurveyContactTestCase extends CakeTestCase {
	var $fixtures = array('plugin.survey.survey_contact', 'plugin.survey.survey_answer');

	function startTest() {
		$this->SurveyContact =& ClassRegistry::init('SurveyContact');
	}
	
	function testGenerateToken(){
	  $this->assertEqual('108dc0e4a4973926f890206ee5bb46db', $this->SurveyContact->__generateToken('nurvzy@gmail.com'));
	  
	  $this->SurveyContact->data = array('SurveyContact' => array('email' => 'nurvzy@gmail.com'));
	  $this->assertEqual('108dc0e4a4973926f890206ee5bb46db', $this->SurveyContact->__generateToken());
	}
	
	function testFindByTokenForGiveAaway(){
	  $this->assertTrue($this->SurveyContact->findByTokenForGiveAway('token'));
	  $this->assertFalse($this->SurveyContact->findByTokenForGiveAway('token2'));
	}
	
	function testIsFinished(){
	  $this->assertTrue($this->SurveyContact->isFinished(1));
	  $this->assertTrue($this->SurveyContact->isFinished('token'));
	  $this->assertFalse($this->SurveyContact->isFinished(2));
	  $this->assertFalse($this->SurveyContact->isFinished('token2'));
	}
	
	function testFinishSurvey(){
	  $this->assertFalse($this->SurveyContact->isFinished(2));
	  $this->assertTrue($this->SurveyContact->finishSurvey(2));
	  $this->assertTrue($this->SurveyContact->isFinished(2));
	}
	
	function testIdByToken(){
	  $this->assertEqual(1, $this->SurveyContact->idByToken('token'));
	  $this->assertEqual(2, $this->SurveyContact->idByToken('token2'));
	  $this->assertEqual(0, $this->SurveyContact->idByToken('not_exist'));
	}	
	
	function testSaveFirstShouldSaveWithContact(){
	  $data = array(
	    'SurveyContact' => array(
	      'email' => 'test@example.com'
	    ),
	    'SurveyAnswer' => array(
	      array(
	        'question' => 'q1',
	        'answer' => 'answer 1'
	      ),
	      array(
	        'question' => 'q2',
	        'answer' => 'answer 2'
	      ),
	    )
	  );
	  
	  $this->assertTrue($this->SurveyContact->saveFirst($data));
	  $contact = $this->SurveyContact->find('last');
	  $this->assertEqual('test@example.com', $contact['SurveyContact']['email']);
	  $this->assertFalse(empty($contact['SurveyContact']['token']));
	  $this->assertEqual(2, count($contact['SurveyAnswer']));
	  foreach($contact['SurveyAnswer'] as $answer){
	    $this->assertEqual($contact['SurveyContact']['id'], $answer['survey_contact_id']);
	  }
	  $this->assertEqual(2, count($contact['SurveyAnswer']));
	}
	
	function testSaveFirstShouldSaveAnswerButNotContact(){
	  $data = array(
	    'SurveyContact' => array(
	      'email' => '' // empty
	    ),
	    'SurveyAnswer' => array(
	      array(
	        'question' => 'q1',
	        'answer' => 'answer 1'
	      ),
	      array(
	        'question' => 'q2',
	        'answer' => 'answer 2'
	      ),
	    )
	  );
	  $contact_count = $this->SurveyContact->find('count');
	  $answer_count = $this->SurveyContact->SurveyAnswer->find('count');
	  $this->assertTrue($this->SurveyContact->saveFirst($data));
	  $this->assertEqual($answer_count + 2, $this->SurveyContact->SurveyAnswer->find('count'));
	  $this->assertEqual($contact_count, $this->SurveyContact->find('count'));
	  $answer = $this->SurveyContact->SurveyAnswer->find('last');
	  $this->assertFalse($answer['SurveyAnswer']['survey_contact_id']);
	}
	
	function testSaveSecondShouldSetSurveyToFinished(){
	  $this->assertFalse($this->SurveyContact->isFinished(2));
	  
	  $data = array(
	    'SurveyContact' => array(
	      'id' => 2
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
	  
	  $this->SurveyContact->saveSecond($data);
	  
	  $this->assertTrue($this->SurveyContact->isFinished(2));
	}
	
	function testGiveAwayShouldNotSaveIfNot18(){
	  $data = array(
	    'SurveyContact' => array('is_18' => false)
	  );
	  
	  $this->assertFalse($this->SurveyContact->enterGiveAway($data));
	  $this->assertTrue(!empty($this->SurveyContact->validationErrors['is_18']));
	}
	
	function testGiveAwayShouldSaveIfAllDataPresent(){
	  $data = array(
	    'SurveyContact' => array(
	      'id' => 2,
	      'is_18' => true,
	      'first_name' => 'Nick',
	      'last_name' => 'Baker',
	      'phone' => '5057023639'
	    )
	  );
	  
	  $this->assertTrue($this->SurveyContact->enterGiveAway($data));
	  $this->assertTrue($this->SurveyContact->field('entered_give_away'));
	}
	
	function testBeforeSave(){
	  $data = array(
	    'SurveyContact' => array(
	      'email' => 'new_email@example.com'
	    )
	  );
	  
	  $this->SurveyContact->save($data);
	  $contact = $this->SurveyContact->find('last');
	  $this->assertEqual('new_email@example.com', $contact['SurveyContact']['email']);
	  $this->assertTrue(!empty($contact['SurveyContact']['token']));
	}

	function endTest() {
		unset($this->SurveyContact);
		ClassRegistry::flush();
	}

}
?>