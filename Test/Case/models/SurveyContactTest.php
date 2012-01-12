<?php
/* SurveyContact Test cases generated on: 2010-07-27 22:07:48 : 1280292888*/
App::import('Model', 'survey.SurveyContact');

class SurveyContactTest extends CakeTestCase {
	var $fixtures = array(
		/* Contact */
    'app.contact',
    'app.contact_detail',
    /* SurveyContact */
	  'plugin.survey.survey_contact', 
	  'plugin.survey.survey_answer',
	  'plugin.survey.survey_opt_in',
	  'plugin.survey.survey_participant',
	);

	function startTest() {
		$this->SurveyContact =& ClassRegistry::init('SurveyContact');
	}
	
	function testSaveDataWithIDs(){
		$data = array(
			'SurveyContact' => array(
				'first_name' => 'Nick',
				'last_name' => 'Baker',
				'zip' => '65784',
				'email' => 'nick@newemail.com'
			)
		);
		$answer_ids = array(9,8);
		
		$answers = $this->SurveyContact->SurveyAnswer->find('all', array(
			'conditions' => array(
				'SurveyAnswer.id' => $answer_ids
			),
			'contain' => array()
		));
		foreach($answers as $answer){
			$this->assertFalse($answer['SurveyAnswer']['survey_contact_id']);
		}
		
		$this->assertTrue($this->SurveyContact->saveData($data, $answer_ids));
		
		$answers = $this->SurveyContact->SurveyAnswer->find('all', array(
			'conditions' => array(
				'SurveyAnswer.id' => $answer_ids
			),
			'contain' => array()
		));
		foreach($answers as $answer){
			$this->assertEqual($this->SurveyContact->id, $answer['SurveyAnswer']['survey_contact_id']);
		}
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
	
	function testExport(){
	  $count = $this->SurveyContact->find('count');
	  $data = $this->SurveyContact->export();
	  foreach($data as $record){
	    //nurvzy@gmail.com is on the ignore, and in the database, export shouldn't include it.
	    $this->assertNotEqual('nurvzy@gmail.com', $record['SurveyContact']['email']);
	  }
	  $this->assertEqual($count, count($data));
	}
	
	
	function testIdByEmail(){
	  $this->assertEqual(1, $this->SurveyContact->idByEmail('example@example.com'));
	  $this->assertEqual(2, $this->SurveyContact->idByEmail('nick@example.com'));
	  $this->assertEqual(0, $this->SurveyContact->idByEmail('not_exist'));
	}
	
	function testHasRequiredFields(){
	  $this->assertTrue($this->SurveyContact->__hasRequiredFields());
	}

	function endTest() {
		unset($this->SurveyContact);
		ClassRegistry::flush();
	}

}
?>