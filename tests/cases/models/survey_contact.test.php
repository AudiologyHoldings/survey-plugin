<?php
/* SurveyContact Test cases generated on: 2010-07-27 22:07:48 : 1280292888*/
App::import('Model', 'survey.SurveyContact');

class SurveyContactTestCase extends CakeTestCase {
	var $fixtures = array('plugin.survey.survey_contact', 'plugin.survey.survey_answer');

	function startTest() {
		$this->SurveyContact =& ClassRegistry::init('SurveyContact');
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

	function endTest() {
		unset($this->SurveyContact);
		ClassRegistry::flush();
	}

}
?>