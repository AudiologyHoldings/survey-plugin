<?php
class SurveyAnswer extends SurveyAppModel {
	var $name = 'SurveyAnswer';
	var $displayField = 'question';
	var $validate = array(
		'question' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Question key must not be empty.',
			),
		),
		'answer' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Answer is required.',
			),
		),
	);
	
	var $belongsTo = array(
		'SurveyContact' => array(
			'className' => 'Survey.SurveyContact',
			'foreignKey' => 'survey_contact_id',
		)
	);
	
	/**
	  * Find the report based on data based in
	  *
	  * @param array of data to base report on
	  * - start_month (month to base report on)
	  * - end_month (month to base report on)
	  * - page_views (total page views that month)
	  * @return array of results formatted for easy viewing.
	  */
	function findReport($data = array()){
	  $start_date = $this->str2datetime($data[$this->alias]['start_month']);
	  $end_date = $this->str2datetime($data[$this->alias]['end_month']);
	  $page_views = str_replace(",","",$data[$this->alias]['page_views']);
	  
	  $conditions = array(
	    "created >=" => $start_date,
	    "created <=" => $end_date,
	  );
	  
	  //Not all answers have a contact, so we must pull them in separately.
	  $answers = $this->find('all', array(
	    'conditions' => array(
	      'OR' => array(
	        array( //notice the extra space so we don't clobber the next 'AND' clause
	          'SurveyAnswer.created >=' => $start_date,
	          'SurveyAnswer.created <=' => $end_date,
	          'SurveyAnswer.survey_contact_id' => '0'
	        ),
	        array(
	          'SurveyContact.created >=' => $start_date,
	          'SurveyContact.created <=' => $end_date
	        )
	      )
	    ),
	    'contain' => array('SurveyContact.id')
	  ));
	  	  
	  $contacts = $this->SurveyContact->find('all', array(
	    'conditions' => $conditions,
	    'recursive' => -1
	  ));
	  
	  $opt_ins = ClassRegistry::init('Survey.SurveyOptIn')->find('count', array(
	    'conditions' => $conditions,
	    'recursive' => -1
	  ));
	  
	  $retval = array(
	    'total' => array(
        'with_email' => 0,
        'without_email' => 0,
        'opt_in' => 0,
        'participation' => 0,
        'completed_survey' => 0,
        'entered_give_away' => 0,
        'purchases' => 0,
        'oticon_purchases' => 0,
        'beltone_purchases' => 0,
        'phonak_purchases' => 0,
        'miracle_ear_purchases' => 0,
        'other_purchases' => 0,
        'visit_clinic' => 0,
        'not_visit_clinic' => 0,
        'visit_clinic_no_purchase' => 0
	    ),
	    'percent' => array(
	      'with_email' => 0,
        'opt_in' => 0,
        'participation' => 0,
        'completed_survey' => 0,
        'entered_give_away' => 0,
        'purchases' => 0,
        'oticon_purchases' => 0,
        'beltone_purchases' => 0,
        'phonak_purchases' => 0,
        'miracle_ear_purchases' => 0,
        'other_purchases' => 0,
        'visit_clinic' => 0,
        'not_visit_clinic' => 0,
        'visit_clinic_no_purchase' => 0,
	    ),
	    'age_range' => array(
	      'under-18' => 0,
	      '18-39' => 0,
	      '40-49' => 0,
	      '50-59' => 0,
	      '60-69' => 0,
	      '70-79' => 0,
	      '80plus' => 0,
	    ),
	    'likely' => array(
	      '0' => 0,
	      '1' => 0,
	      '2' => 0,
	      '3' => 0,
	      '4' => 0,
	      '5' => 0,
	      '6' => 0,
	    )
	  );
	  
	  //Totals
	  $retval['total']['opt_in'] = $opt_ins;
	  $retval['total']['with_email'] = count($contacts);
	  $retval['total']['without_email'] = count(Set::extract('/SurveyAnswer[survey_contact_id=0]', $answers)) / 2;
	  $retval['total']['participation'] = $retval['total']['with_email'] + $retval['total']['without_email'];
	  $retval['total']['completed_survey'] = count(Set::extract('/SurveyContact[finished_survey=1]', $contacts));
	  $retval['total']['entered_give_away'] = count(Set::extract('/SurveyContact[entered_give_away=1]', $contacts));
	  $retval['total']['oticon_purchases'] = count(Set::extract('/SurveyAnswer[question=5_what_brand][answer=Oticon]', $answers));
	  $retval['total']['beltone_purchases'] = count(Set::extract('/SurveyAnswer[question=5_what_brand][answer=Beltone]', $answers));
	  $retval['total']['phonak_purchases'] = count(Set::extract('/SurveyAnswer[question=5_what_brand][answer=Phonak]', $answers));
	  $retval['total']['miracle_ear_purchases'] = count(Set::extract('/SurveyAnswer[question=5_what_brand][answer=MiracleEar]', $answers));
	  $retval['total']['other_purchases'] = count(Set::extract('/SurveyAnswer[question=5_what_brand][answer=Other]', $answers));
	  $retval['total']['purchases'] = count(Set::extract('/SurveyAnswer[question=4_purchase_hearing_aid][answer=Yes]', $answers));
	  $retval['total']['visit_clinic'] = count(Set::extract('/SurveyAnswer[question=3_visit_clinic][answer=Yes]', $answers));
	  $retval['total']['not_visit_clinic'] = count(Set::extract('/SurveyAnswer[question=3_visit_clinic][answer=No]', $answers));
	  $retval['total']['have_apt_visit_clinic'] = count(Set::extract('/SurveyAnswer[question=3_visit_clinic][answer=Appointment]', $answers));
	  $retval['total']['visit_clinic_no_purchase'] = $retval['total']['visit_clinic'] - $retval['total']['purchases'];
	  
	  //Percents
	  $retval['percent']['opt_in'] = $this->__calculatePercent($retval['total']['opt_in'], $page_views);
	  $retval['percent']['with_email'] = $this->__calculatePercent($retval['total']['with_email'], $retval['total']['opt_in']);
	  $retval['percent']['participation'] = $this->__calculatePercent($retval['total']['participation'], $retval['total']['opt_in']);
	  $retval['percent']['completed_survey'] = $this->__calculatePercent($retval['total']['completed_survey'], $retval['total']['with_email']);
	  $retval['percent']['entered_give_away'] = $this->__calculatePercent($retval['total']['entered_give_away'], $retval['total']['completed_survey']);
	  $retval['percent']['purchases'] = $this->__calculatePercent($retval['total']['purchases'], $retval['total']['completed_survey']);
	  $retval['percent']['oticon_purchases'] = $this->__calculatePercent($retval['total']['oticon_purchases'], $retval['total']['completed_survey']);
	  $retval['percent']['beltone_purchases'] = $this->__calculatePercent($retval['total']['beltone_purchases'], $retval['total']['completed_survey']);
	  $retval['percent']['phonak_purchases'] = $this->__calculatePercent($retval['total']['phonak_purchases'], $retval['total']['completed_survey']);
	  $retval['percent']['miracle_ear_purchases'] = $this->__calculatePercent($retval['total']['miracle_ear_purchases'], $retval['total']['completed_survey']);
	  $retval['percent']['other_purchases'] = $this->__calculatePercent($retval['total']['other_purchases'], $retval['total']['completed_survey']);
	  $retval['percent']['visit_clinic'] = $this->__calculatePercent($retval['total']['visit_clinic'], $retval['total']['completed_survey']);
	  $retval['percent']['not_visit_clinic'] = $this->__calculatePercent($retval['total']['not_visit_clinic'], $retval['total']['completed_survey']);
	  $retval['percent']['have_apt_visit_clinic'] = $this->__calculatePercent($retval['total']['have_apt_visit_clinic'], $retval['total']['completed_survey']);
	  $retval['percent']['visit_clinic_no_purchase'] = $this->__calculatePercent($retval['total']['visit_clinic_no_purchase'], $retval['total']['completed_survey']);
	  
	  
	  //Age Range
	  $retval['age_range']['under-18'] = count(Set::extract('/SurveyAnswer[answer=under-18]', $answers));
	  $retval['age_range']['18-39'] = count(Set::extract('/SurveyAnswer[answer=18-39]', $answers));
	  $retval['age_range']['40-49'] = count(Set::extract('/SurveyAnswer[answer=40-49]', $answers));
	  $retval['age_range']['50-59'] = count(Set::extract('/SurveyAnswer[answer=50-59]', $answers));
	  $retval['age_range']['60-69'] = count(Set::extract('/SurveyAnswer[answer=60-69]', $answers));
	  $retval['age_range']['70-79'] = count(Set::extract('/SurveyAnswer[answer=70-79]', $answers));
	  $retval['age_range']['80plus'] = count(Set::extract('/SurveyAnswer[answer=80plus]', $answers));
	  $retval['age_range']['total'] = 0;
	  foreach($retval['age_range'] as $key => $value){
	    $retval['age_range']['total'] += $value;
	  }
	  
	  //Likely
	  $retval['likely']['0'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=0]', $answers));
	  $retval['likely']['1'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=1]', $answers));
	  $retval['likely']['2'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=2]', $answers));
	  $retval['likely']['3'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=3]', $answers));
	  $retval['likely']['4'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=4]', $answers));
	  $retval['likely']['5'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=5]', $answers));
	  $retval['likely']['6'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=6]', $answers));
	  $retval['likely']['total'] = 0;
	  foreach($retval['likely'] as $key => $value){
	    $retval['likely']['total'] += $value;
	  }
	  	  
	  return $retval;
	}
	
	/**
	  * Calculate the percentage of two numbers to the 2nd degree
	  * @param int numerator
	  * @param int denominator
	  * @return string percentage
	  */
	function __calculatePercent($num, $denom = 100){
	  return (!$denom) ? "0%" : round($num / $denom, 4) * 100 . '%';
	}
	
	/**
	  * Fix the import
	  */
	function fixImport(){
	  $answers = $this->find('all', array(
	    'contain' => array(
	      'SurveyContact' => array('fields' => array('id','email'))
	    )
	  ));
	  foreach($answers as $answer){
	    $this->id = $answer['SurveyAnswer']['id'];
	    
	    if(empty($answer['SurveyContact']['email']) && $answer['SurveyAnswer']['survey_contact_id']){
        if(!empty($answer['SurveyContact']['id'])){
          $this->SurveyContact->delete($answer['SurveyContact']['id'], false);
        }
        $this->saveField('survey_contact_id', false);
	    }
	    if($answer['SurveyAnswer']['question'] == '1_age'){
	      switch(strtolower(substr(strip_tags($answer['SurveyAnswer']['answer']), 0, 1))){
	        case 'u': $this->saveField('answer', 'under-18'); break;
	        case '1': 
	        case '2': 
	        case '3': $this->saveField('answer', '18-39'); break;
	        case '4': $this->saveField('answer', '40-49'); break;
	        case '5': $this->saveField('answer', '50-59'); break;
	        case '6': $this->saveField('answer', '60-69'); break;
	        case '7': $this->saveField('answer', '70-79'); break;
	        default : $this->saveField('answer', '80plus'); break;
	      }
	    }
	    if($answer['SurveyAnswer']['question'] == '2_likely_to_schedule'){
	      $this->saveField('answer', strip_tags($answer['SurveyAnswer']['answer']));
	    }
	  }
	}
	
	/**
	  * Fix the import data from before September.
	  * This is due to the fact the likely scale was from 1-7
	  * and now it's between 0-6.
	  * @see https://audiologyonline.basecamphq.com/projects/4564701/todo_items/68522763/comments
	  */
	function fixLikelyVisit(){
	  $answers = $this->find('all', array(
	    'conditions' => array(
	      'created <' => '2010-09-01 00:00:00',
	      'question' => '2_likely_to_schedule'
	    ),
	    'recursive' => -1
	  ));
	  foreach($answers as $answer){
	    $this->id = $answer['SurveyAnswer']['id'];
	    $this->saveField('answer', $answer['SurveyAnswer']['answer'] - 1);
	  }
	}
}
?>