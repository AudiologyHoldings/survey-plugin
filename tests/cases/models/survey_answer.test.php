<?php
/* SurveyAnswer Test cases generated on: 2010-07-27 22:07:40 : 1280292760*/
App::import('Model', 'survey.SurveyAnswer');

class SurveyAnswerTestCase extends CakeTestCase {
	var $fixtures = array('plugin.survey.survey_contact', 'plugin.survey.survey_answer');

	function startTest() {
		$this->SurveyAnswer =& ClassRegistry::init('SurveyAnswer');
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

	  $this->assertEqual(3, $results['total']['participation']);
	  $this->assertEqual(1, $results['total']['without_email']);
	  $this->assertEqual(2, $results['total']['with_email']);
	  $this->assertEqual(1, $results['total']['completed_survey']);
	  $this->assertEqual(0, $results['total']['entered_give_away']);
	  $this->assertEqual(1, $results['total']['purchases']);
	  $this->assertEqual(1, $results['total']['oticon_purchases']);
	  $this->assertEqual('30%', $results['percent']['participation']);
	  $this->assertEqual('66.67%', $results['percent']['with_email']);
	  $this->assertEqual('33.33%', $results['percent']['completed_survey']);
	  $this->assertEqual('0%', $results['percent']['entered_give_away']);
	  $this->assertEqual('33.33%', $results['percent']['purchases']);
	  $this->assertEqual('33.33%', $results['percent']['oticon_purchases']);
	}

	function endTest() {
		unset($this->SurveyAnswer);
		ClassRegistry::flush();
	}

}
?>