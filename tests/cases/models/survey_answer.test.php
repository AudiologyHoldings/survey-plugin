<?php
/* SurveyAnswer Test cases generated on: 2010-07-27 22:07:40 : 1280292760*/
App::import('Model', 'survey.SurveyAnswer');

class SurveyAnswerTestCase extends CakeTestCase {
	var $fixtures = array(
	  'plugin.survey.survey_contact', 
	  'plugin.survey.survey_answer',
	  'plugin.survey.survey_opt_in',
	);

	function startTest() {
		$this->SurveyAnswer =& ClassRegistry::init('SurveyAnswer');
	}
	
	function testExport(){
	  $result = $this->SurveyAnswer->export();
	  foreach($result as $record){
	    //nurvzy@gmail.com is on the ignore, and in the database, export shouldn't include it.
	    if(isset($record['SurveyContact']['email'])){
	      $this->assertNotEqual('nurvzy@gmail.com', $record['SurveyContact']['email']);
	    }
	  }
	  $this->assertEqual(11, count($result)); //10 plus header.
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
	  $this->assertEqual(4, $results['total']['participation']);
	  $this->assertEqual(1, $results['total']['without_email']);
	  $this->assertEqual(3, $results['total']['with_email']);
	  $this->assertEqual(2, $results['total']['completed_survey']);
	  $this->assertEqual(0, $results['total']['entered_give_away']);
	  $this->assertEqual(1, $results['total']['purchases']);
	  $this->assertEqual(1, $results['total']['oticon_purchases']);
	  $this->assertEqual(1, $results['total']['visit_clinic']);
	  $this->assertEqual(1, $results['total']['not_visit_clinic']);
	  $this->assertEqual(0, $results['total']['visit_clinic_no_purchase']);
	  $this->assertEqual('90%', $results['percent']['opt_in']);
	  $this->assertEqual('44.44%', $results['percent']['participation']);
	  $this->assertEqual('33.33%', $results['percent']['with_email']);
	  $this->assertEqual('66.67%', $results['percent']['completed_survey']);
	  $this->assertEqual('0%', $results['percent']['entered_give_away']);
	  $this->assertEqual('50%', $results['percent']['purchases']);
	  $this->assertEqual('50%', $results['percent']['oticon_purchases']);
	  $this->assertEqual('50%', $results['percent']['visit_clinic']);
	  $this->assertEqual('50%', $results['percent']['not_visit_clinic']);
	  $this->assertEqual('0%', $results['percent']['visit_clinic_no_purchase']);
	  $this->assertEqual('2', $results['likely']['6']);
	  $this->assertEqual('1', $results['likely']['3']);
	  $this->assertEqual('3', $results['age_range']['80plus']);
	}

	function endTest() {
		unset($this->SurveyAnswer);
		ClassRegistry::flush();
	}

}
?>