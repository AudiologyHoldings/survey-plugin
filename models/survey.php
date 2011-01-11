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
		* @param id of record to update (required)
		* @return boolean of success.
		*/
	function saveSecond($data, $survey_id_to_edit = null){
		if(!$survey_id_to_edit){
			$this->validationErrors['no_session'] = 'Session Expired. Please try again.';
			return false;
		};
		$data['Survey']['id'] = $survey_id_to_edit;
		
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
}
?>