<?php
class Survey extends SurveyAppModel {
	var $name = 'Survey';
	var $displayField = 'email';
	
	var $validate_on_answers = array(
		'1_age' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please specify an age range.',
			),
		),
		'2_likely_to_schedule' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please specify the likelyhood to visit a clinic.',
			),
		),
	);
	
	var $validate_on_email = array(
		'first_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a first name.'
			),
		),
		'last_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a last name.'
			),
		),
		'email' => array(
			'empty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Email must be present'
			),
			'email' => array(
				'rule' => array('email'),
				'message' => 'Email Must be a valid.'
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'Unique Email must be present.'
			)
		),
		'zip' => array(
			'empty' => array(
				'rule' => array('postal'),
				'message' => 'Zip code must be present and valid.'
			)
		),
	);
	
	/**
		* Save the first round of data
		*/
	function saveFirst($data, $options = array()){
		$this->validate = $this->validate_on_answers;
		$retval = $this->save($data, $options);		
		$this->resetValidate();
		return $retval;
	}
	
	/**
		* Save the second round of data
		* @param data to save array
		* @return boolean of success.
		*/
	function saveSecond($data){
		if(!$data['Survey']['id']){
			$this->validationErrors['no_session'] = 'Session Expired. Please try again.';
			return false;
		}
		
		$this->validate = $this->validate_on_email;
		$retval = $this->save($data);
		$this->resetValidate();
		return $retval;
	}
	
	/**
		* Reset validations to empty
		*/
	function resetValidate(){
		$this->validate = array();
	}
	
	/**
	  * Set the options and pass them into parent::export
	  * @return array of data to be displayed as csv file
	  */
	function export(){
	  $options = array(
	    'conditions' => $this->getIgnoreConditions()
	  );
	  return parent::export($options);
	}
	
	/**
	  * Purge the ignore list from the database.
	  * This is safe to do at anytime as we do not 
	  * report this data to safedata 3rd party backup.
	  * @param boolean verbose
	  * @return count of delets made.
	  */
	function purgeIgnoreList($verbose = false){
	  $count = 0;
	  foreach($this->getIgnoreList() as $email){
	    if($verbose) echo "Purging $email...\r\n";
	    $this->deleteAll(array("{$this->alias}.email LIKE" => $email));
	    $count += $this->getAffectedRows();
	  }
	  return $count;
	}
	
	/**
	  * Named scope to find all contacts by an email
	  * @param array of this->data from controller
	  * @return array of results
	  */
	function findAllByEmail($data){
	  $email = isset($data[$this->alias]['email']) ? $data[$this->alias]['email'] : '%'; 
	  return $this->find('all', array(
	    'conditions' => array(
	      "{$this->alias}.email LIKE" => $email
	    ),
	    'contain' => array(),
	    'order' => "{$this->alias}.email ASC"
	  ));
	}
	
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
	  $end_date = $this->str2datetime($data[$this->alias]['end_month'], true);
	  $page_views = str_replace(",","",$data[$this->alias]['page_views']);
	  
	  $email_conditions = $this->getIgnoreConditions();
	  
	  $conditions = array(
	    "created >=" => $start_date,
	    "created <=" => $end_date,
	  );
	  
	  
	  //Not all answers have a contact, so we must pull them in separately.
	  $answers = $this->find('all', array(
	    'conditions' => array(
	      'OR' => array(
	        array( //answers that don't have an email
	          'Survey.created >=' => $start_date,
	          'Survey.created <=' => $end_date,
	        )
	      )
	    ),
	    'contain' => array()
	  ));
	  
	  $opt_ins = ClassRegistry::init('Survey.SurveyOptIn')->find('count', array(
	    'conditions' => $conditions,
	    'recursive' => -1
	  ));
	  
	  $continue_clicks = ClassRegistry::init('Survey.SurveyParticipant')->find('count', array(
	    'conditions' => $conditions,
	    'recursive' => -1
	  ));
	  
	  $subscribed_count = $this->find('count', array(
	  	'conditions' => array(
	  		'Survey.created >=' => $start_date,
	  		'Survey.created <=' => $end_date,
	  		'Survey.email !=' => '',
	  	)
	  ));
	  
	  $retval = array(
	    'total' => array(
        'opt_in' => 0,
        'continue' => 0,
        'subscribed' => 0,
	    ),
	    'percent' => array(
        'opt_in' => 0,
        'continue' => 0,
        'subscribed' => 0,
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
	  $retval['total']['continue'] = $continue_clicks;
	  $retval['total']['subscribed'] = $subscribed_count;

	  //Percents
	  $retval['percent']['opt_in'] = $this->__calculatePercent($retval['total']['opt_in'], $page_views);
	  $retval['percent']['continue'] = $this->__calculatePercent($retval['total']['continue'], $retval['total']['opt_in']);
	  $retval['percent']['subscribed'] = $this->__calculatePercent($retval['total']['subscribed'], $retval['total']['continue']);
	  
	  //Age Range and Likely
	  foreach($answers as $answer){
	  	$retval['age_range'][$answer['Survey']['1_age']] += 1;
	  	$retval['likely'][$answer['Survey']['2_likely_to_schedule']] += 1;
	  }

	  //Age Range
	  $retval['age_range']['total'] = 0;
	  foreach($retval['age_range'] as $value){
	    $retval['age_range']['total'] += $value;
	  }
	  
	  //Likely
	  $retval['likely']['total'] = 0;
	  foreach($retval['likely'] as $value){
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
}
?>