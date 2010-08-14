<?php
/* SurveyContact Test cases generated on: 2010-07-27 22:07:48 : 1280292888*/
App::import('Model', 'survey.SurveyContact');

class SurveyContactTestCase extends CakeTestCase {
	var $fixtures = array('plugin.survey.survey_contact', 'plugin.survey.survey_answer');

	function startTest() {
		$this->SurveyContact =& ClassRegistry::init('SurveyContact');
	}
	
	function testSetFinalEmailDate(){
	  $id = 1;
	  $this->SurveyContact->id = $id;
	  $this->assertEqual('0000-00-00 00:00:00', $this->SurveyContact->field('final_email_sent_date'));
	  $this->assertTrue($this->SurveyContact->setFinalEmailDate($id));
	  $this->assertNotEqual('0000-00-00 00:00:00', $this->SurveyContact->field('final_email_sent_date'));
	}
	
	function testFindByEmailForSecond(){
	  $this->assertFalse($this->SurveyContact->findByEmailForSecond('example@example.com'));
	  $this->assertTrue($this->SurveyContact->findByEmailForSecond('nick@example.com'));
	}
	
	function testGenerateEmail(){
	  $this->assertEqual('108dc0e4a4973926f890206ee5bb46db', $this->SurveyContact->__generateToken('nurvzy@gmail.com'));
	  
	  $this->SurveyContact->data = array('SurveyContact' => array('email' => 'nurvzy@gmail.com'));
	  $this->assertEqual('108dc0e4a4973926f890206ee5bb46db', $this->SurveyContact->__generateToken());
	}
	
	function testFindByEmailForGiveAaway(){
	  $this->assertTrue($this->SurveyContact->findByEmailForGiveAway('example@example.com'));
	  $this->assertFalse($this->SurveyContact->findByEmailForGiveAway('nick@example.com'));
	}
	
	function testIsFinished(){
	  $this->assertTrue($this->SurveyContact->isFinished(1));
	  $this->assertTrue($this->SurveyContact->isFinished('example@example.com'));
	  $this->assertFalse($this->SurveyContact->isFinished(2));
	  $this->assertFalse($this->SurveyContact->isFinished('nick@example.com'));
	}
	
	function testFinishSurvey(){
	  $this->assertFalse($this->SurveyContact->isFinished(2));
	  $this->assertTrue($this->SurveyContact->finishSurvey(2));
	  $this->assertTrue($this->SurveyContact->isFinished(2));
	}
	
	function testIdByEmail(){
	  $this->assertEqual(1, $this->SurveyContact->idByEmail('example@example.com'));
	  $this->assertEqual(2, $this->SurveyContact->idByEmail('nick@example.com'));
	  $this->assertEqual(0, $this->SurveyContact->idByEmail('not_exist'));
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
	  $this->assertFalse(empty($contact['SurveyContact']['email']));
	  $this->assertEqual(2, count($contact['SurveyAnswer']));
	  $this->assertTrue($contact['SurveyContact']['final_email_sent_date']);
	  foreach($contact['SurveyAnswer'] as $answer){
	    $this->assertEqual($contact['SurveyContact']['id'], $answer['survey_contact_id']);
	  }
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
	
	function testHasRequiredFields(){
	  $this->assertTrue($this->SurveyContact->__hasRequiredFields());
	}
	
	function testFindAllToNotify(){
	  $date = $this->SurveyContact->str2datetime('today');
	  $this->SurveyContact->id = 2;
	  $this->SurveyContact->saveField('final_email_send_date', $date);
	  
	  $results = $this->SurveyContact->findAllToNotify();
	  $this->assertEqual(1, count($results));
	}
	

	function endTest() {
		unset($this->SurveyContact);
		ClassRegistry::flush();
	}

}
?>