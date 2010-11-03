<?php
/* SurveyContact Test cases generated on: 2010-07-27 22:07:48 : 1280292888*/
App::import('Model', 'survey.SurveyContact');

class SurveyContactTestCase extends CakeTestCase {
	var $fixtures = array(
	  'plugin.survey.survey_contact', 
	  'plugin.survey.survey_answer',
	  'plugin.survey.survey_opt_in',
	  'plugin.survey.survey_participant',
	);

	function startTest() {
		$this->SurveyContact =& ClassRegistry::init('SurveyContact');
	}
	
	function testResend(){
	  $this->SurveyContact->id = 2;
	  $this->assertFalse($this->SurveyContact->field('made_appointment'));
	  $this->assertEqual('2010-08-13 00:00:00', $this->SurveyContact->field('final_email_sent_date'));
	  
	  $this->assertTrue($this->SurveyContact->resend('nick@example.com'));
	  
	  $this->assertTrue($this->SurveyContact->field('made_appointment'));
	  $this->assertNotEqual('2010-08-13 00:00:00', $this->SurveyContact->field('final_email_sent_date'));
	}
	
	function testPurgeIgnoreList(){
	  $results = $this->SurveyContact->findById(4);
	  $this->assertTrue(!empty($results));
	  $this->assertEqual(1, $this->SurveyContact->purgeIgnoreList());
	  $results = $this->SurveyContact->findById(4);
	  $this->assertTrue(empty($results));
	  $results = $this->SurveyContact->SurveyAnswer->findById(11);
	  $this->assertTrue(empty($results));
	}
	
	function testFindAllByEmail(){
	  $data = array(
	    'SurveyContact' => array(
	      'email' => 'nick@example.com'
	    )
	  );
	  $results = $this->SurveyContact->findAllByEmail($data);
	  $this->assertTrue(!empty($results));
	  $this->assertEqual(1, count($results));
	  
	  $data = array(
	    'SurveyContact' => array(
	      'email' => '%@example.com'
	    )
	  );
	  $results = $this->SurveyContact->findAllByEmail($data);
	  $this->assertTrue(!empty($results));
	  $this->assertEqual(4, count($results));
	  
	  $data = array(
	    'SurveyContact' => array(
	      'email' => 'No Exist'
	    )
	  );
	  $results = $this->SurveyContact->findAllByEmail($data);
	  $this->assertTrue(empty($results));
	}
	
	function testExportFinal(){
	  $data = $this->SurveyContact->exportFinal();
	  $keys = array(
      'id',
      'first_name',
      'last_name',
      'phone',
      'entered_give_away',
      'made_appointment',
      'email',
      'created',
      'final_email_sent_date',
	    'final_questions_answered_date',
      '1_age',
      '2_likely_to_schedule',
      '3_visit_clinic',
      '4_purchase_hearing_aid',
      '5_what_brand',
    );
	  foreach($data as $record){
	    $this->assertEqual($keys, array_keys($record['SurveyContact']));
	  }
	}
	
	function testExport(){
	  $count = $this->SurveyContact->find('count');
	  $data = $this->SurveyContact->export();
	  foreach($data as $record){
	    //nurvzy@gmail.com is on the ignore, and in the database, export shouldn't include it.
	    $this->assertNotEqual('nurvzy@gmail.com', $record['SurveyContact']['email']);
	  }
	  $this->assertEqual($count, count($data));
	}
	
	function testImport(){
	  if(env('RUN_SURVEY_IMPORT')){
	    $count = $this->SurveyContact->find('count');
      $this->SurveyContact->import(true, '2010-07-27');
      $this->assertEqual($count + 673, $this->SurveyContact->find('count'));
	  }
	}
	
	function testfinalEmailSent(){
	  $this->SurveyContact->id = 1;
	  $this->assertFalse($this->SurveyContact->field('final_email_sent'));
	  $this->assertTrue($this->SurveyContact->finalEmailSent(1));
	  $this->assertTrue($this->SurveyContact->field('final_email_sent'));
	}
	
	function testSetFinalEmailDate(){
	  $id = 1;
	  $this->SurveyContact->id = $id;
	  $this->assertEqual('0000-00-00 00:00:00', $this->SurveyContact->field('final_email_sent_date'));
	  $this->SurveyContact->saveField('final_email_sent', true);
	  $this->assertTrue($this->SurveyContact->field('final_email_sent'));
	  $this->assertTrue($this->SurveyContact->setFinalEmailDate($id));
	  $this->assertNotEqual('0000-00-00 00:00:00', $this->SurveyContact->field('final_email_sent_date'));
	  $this->assertFalse($this->SurveyContact->field('final_email_sent'));
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
	
	function testSaveSecondShouldOnlySaveAnswers(){
	  $this->assertFalse($this->SurveyContact->isFinished(2));
	  
	  $data = array(
	    'SurveyContact' => array(
	      'id' => 2
	    ),
	    'SurveyAnswer' => array(
	      array(
	        'question' => '3_visit_clinic',
	        'answer' => 'No'
	      ),
	      array(
	        'question' => '4_purchase_hearing_id',
	        'answer' => ''
	      ),
	      array(
	        'question' => '5_what_brand',
	        'answer' => ''
	      ),
	    )
	  );
	  $answer_count = $this->SurveyContact->SurveyAnswer->find('count');
	  
	  $this->SurveyContact->saveSecond($data);
	  
	  $this->assertTrue($this->SurveyContact->isFinished(2));
	  $this->assertEqual($answer_count + 1, $this->SurveyContact->SurveyAnswer->find('count'));
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
	  $answer_count = $this->SurveyContact->SurveyAnswer->find('count');
	  $this->SurveyContact->saveSecond($data);
	  
	  $this->assertTrue($this->SurveyContact->isFinished(2));
	  $this->assertEqual($answer_count + 3, $this->SurveyContact->SurveyAnswer->find('count'));
	  
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
	  $this->SurveyContact->setFinalEmailDate(2, 0);
	  
	  $results = $this->SurveyContact->findAllToNotify();
	  $this->assertEqual(1, count($results));
	}
	

	function endTest() {
		unset($this->SurveyContact);
		ClassRegistry::flush();
	}

}
?>