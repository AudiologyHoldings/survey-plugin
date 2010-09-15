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
	  
	  $conditions = array(
	    "created >=" => $start_date,
	    "created <=" => $end_date,
	  );
	  
	  //Not all answers have a contact, so we must pull them in separately.
	  $answers = $this->find('all', array(
	    'conditions' => $conditions,
	    'recursive' => -1
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
        'opt_in' => 0, //TODO how to calculate that? click of I'll Help button
        'participation' => 0,
        'completed_survey' => 0,
        'entered_give_away' => 0,
        'purchases' => 0,
        'oticon_purchases' => 0,
	    ),
	    'percent' => array(
	      'with_email' => 0,
        'opt_in' => 0, //TODO how to calculate that? click of I'll Help button
        'participation' => 0,
        'completed_survey' => 0,
        'entered_give_away' => 0,
        'purchases' => 0,
        'oticon_purchases' => 0,
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
	  $retval['total']['purchases'] = count(Set::extract('/SurveyAnswer[question=4_purchase_hearing_aid][answer=Yes]', $answers));
	  
	  //Percents
	  $retval['percent']['opt_in'] = $this->__calculatePercent($retval['total']['participation'], $retval['total']['opt_in']);
	  $retval['percent']['with_email'] = $this->__calculatePercent($retval['total']['with_email'], $retval['total']['participation']);
	  $retval['percent']['completed_survey'] = $this->__calculatePercent($retval['total']['completed_survey'], $retval['total']['participation']);
	  $retval['percent']['entered_give_away'] = $this->__calculatePercent($retval['total']['entered_give_away'], $retval['total']['participation']);
	  $retval['percent']['purchases'] = $this->__calculatePercent($retval['total']['purchases'], $retval['total']['participation']);
	  $retval['percent']['oticon_purchases'] = $this->__calculatePercent($retval['total']['oticon_purchases'], $retval['total']['participation']);
	  $retval['percent']['participation'] = $this->__calculatePercent($retval['total']['participation'], $data['SurveyAnswer']['page_views']);
	  	  
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
}
?>