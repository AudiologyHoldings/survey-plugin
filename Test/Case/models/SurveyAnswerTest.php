<?php
/* SurveyAnswer Test cases generated on: 2010-07-27 22:07:40 : 1280292760*/
App::import('Model', 'survey.SurveyAnswer');

class SurveyAnswerTest extends CakeTestCase {
	var $fixtures = array(
	  'plugin.survey.survey_contact',
	  'plugin.survey.survey_answer',
	  'plugin.survey.survey_opt_in',
	  'plugin.survey.survey_participant',
	);

	function startTest() {
		$this->SurveyAnswer =& ClassRegistry::init('SurveyAnswer');
	}
	
	function testExportFinal(){
		$results = $this->SurveyAnswer->exportFinal();
		$this->assertEqual(5, count($results));
		$keys = array(
			'first_name',
			'last_name',
			'zip',
			'email',
			'created',
			'1_age',
			'2_likely_to_schedule',
		);
		foreach($results as $result){
			$this->assertEqual($keys, array_keys($result['Survey']));
		}
	}
	
	function testSaveDataShouldReturnAllIdsCreated(){
		$data = array(
			array(
				'question' => '1_age',
				'answer' => '80plus'
			),
			array(
				'question' => '2_likely_to_schedule',
				'answer' => '6'
			),
		);
		$count = $this->SurveyAnswer->find('count');
		$this->assertTrue($this->SurveyAnswer->saveData($data));
		
		$expected = array(16,15);
		$results = $this->SurveyAnswer->getLastTwoInsertedIDs();
		$this->assertEqual($expected, $results);
		
		$this->assertEqual($count + 2, $this->SurveyAnswer->find('count'));
	}
	
	function testSaveDataShouldRequireBothData(){
		$data = array(
			array(
				'question' => '1_age',
				'answer' => '80plus'
			),
			array(
				'question' => '2_likely_to_schedule',
				'answer' => ''
			),
		);
		$count = $this->SurveyAnswer->find('count');
		$this->assertFalse($this->SurveyAnswer->saveData($data));
		$this->assertEqual($count, $this->SurveyAnswer->find('count'));
	}
	
	function testFindReport(){
	  $data = array(
	    'SurveyAnswer' => array(
	      'start_month' => 'July 2010',
	      'end_month' => 'Aug 2010',
	      'page_views' => '10'
	    )
	  );
	  $results = $this->SurveyAnswer->findReport($data);
	  	  
	  $this->assertEqual(9, $results['total']['opt_in']);
	  $this->assertEqual(7, $results['total']['continue']);
	  $this->assertEqual('90%', $results['percent']['opt_in']);
	  $this->assertEqual('77.78%', $results['percent']['continue']);
	  $this->assertEqual('4', $results['likely']['6']);
	  $this->assertEqual('1', $results['likely']['3']);
	  $this->assertEqual('4', $results['age_range']['80plus']);
	}

	function endTest() {
		unset($this->SurveyAnswer);
		ClassRegistry::flush();
	}

}
?>