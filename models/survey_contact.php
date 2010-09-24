<?php
App::import('Lib','Survey.SurveyUtil');
class SurveyContact extends SurveyAppModel {
	var $name = 'SurveyContact';
	var $displayField = 'email';
	var $validate = array(
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Must be a valid email.'
			),
			'unique' => array(
			  'rule' => array('isUnique'),
			  'message' => 'Email already taken'
			)
		),
		'first_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a first name'
			),
		),
		'last_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a last name'
			),
		),
		'phone' => array(
			'phone' => array(
				'rule' => array('phone'),
				'message' => 'Please enter a valid phone number'
			),
		),
		'is_18' => array(
			'boolean' => array(
				'rule' => array('boolean'),
			),
		),
		'finished_survey' => array(
			'boolean' => array(
				'rule' => array('boolean'),
			),
		),
	);
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
		'SurveyAnswer' => array(
			'className' => 'Survey.SurveyAnswer',
			'foreignKey' => 'survey_contact_id',
			'dependent' => true,
		)
	);
		
	/**
	  * Load custom table.  Defaults to survey_contacts
	  */
	function __construct($id = false, $table = null, $ds = null){
	  $this->table = $this->useTable = SurveyUtil::getConfig('table');
	  if(!$this->useTable){
	    $this->table = $this->useTable = 'survey_contacts';
	  }
	  if(!$this->__hasRequiredFields()){
	    trigger_error("Required fields not present in {$this->useTable}.");
	  }
	  parent::__construct($id, $table, $ds);
	}
	
	/**
	  * Take in a value and strip the quotes
	  */
	function clearQuotes($value){
	  return str_replace('"','', $value);
	}
	
	/**
	  * Import from csv files that have been converted to csv files and stored in 
	  * survey/vendors/
	  * @param boolean verbose, if true echo out . to show progress
	  */
	function import($verbose = false, $savefrom = 'now'){
	  App::import('Vendor','Survey.Csv');
	  $SurveyOptIn = ClassRegistry::init('Survey.SurveyOptIn');
	  
	  $backupContacts = $this->find('all', array(
	    'conditions' => array('SurveyContact.created >=' => $this->str2datetime($savefrom)),
	    'contain' => 'SurveyAnswer'
	  ));
	  
	  //clear everything from savefrom
	  $SurveyOptIn->deleteAll(
	    array('SurveyOptIn.created <' => $this->str2datetime($savefrom))
	  );
	  
	  //Truncate contacts and answers
	  $tables = array(
	    'survey_contacts',
	    'survey_answers'
	  );
	  foreach($tables as $table){
	    $this->query("TRUNCATE TABLE $table");
	  }
	  	  
	  //re-insert backup contacts and answers
	  foreach($backupContacts as $import){
	    foreach($import['SurveyAnswer'] as $answer){
	      $this->SurveyAnswer->save($answer);
	    }
	    $this->save($import);
	  }

	  $Export = new Csv(dirname(__FILE__) . DS . '../vendors/export_survey.csv');
	  $contacts = $Export->readAllToArrayWithHeader();
	  
	  //------------------
	  //Done with setup, now run the import.
	  //Run the import.
	  $count = 0;
	  foreach($contacts as $import){
	    $save_data = array();
	    //Set the default created for all records associated with this import
	    $default_values = array(
	      'created' => $this->clearQuotes($import['"Start"'])
	    );
	    
	    //Add Opt In
	    $SurveyOptIn->add($default_values['created']);
	    
      //Contact import
	    if(!empty($import['"Email"'])){
	      $save_data['SurveyContact'] = array_merge(array(
	        'email' => $this->clearQuotes($import['"Email"']),
	      ), $default_values);
	    }
	    
	    //Answer import
	    if(!empty($import['"age_range"'])){
	      $save_data['SurveyAnswer'][] = array_merge(array(
	        'question' => '1_age', 
	        'answer' => $this->clearQuotes($import['"age_range"']),
	      ), $default_values);
	    }
	    if(!empty($import['"Likely"'])){
	      $save_data['SurveyAnswer'][] = array_merge(array(
	        'question' => '2_likely_to_schedule', 
	        'answer' => $this->clearQuotes($import['"Likely"'])
	      ), $default_values);
	    }
	    if(!empty($import['"VisitClinic"'])){
	      $save_data['SurveyAnswer'][] = array_merge(array(
	        'question' => '3_visit_clinic', 
	        'answer' => $this->clearQuotes($import['"VisitClinic"'])
	      ), $default_values);
	      //If we got this far this import has completed the survey
	      $save_data['SurveyContact']['finished_survey'] = true;
	      $save_data['SurveyContact']['final_email_sent'] = true;
	    }
	    if(!empty($import['"FollowUpPurchase"'])){
	      $save_data['SurveyAnswer'][] = array_merge(array(
	        'question' => '4_purchase_hearing_aid', 
	        'answer' => $this->clearQuotes($import['"FollowUpPurchase"'])
	      ), $default_values);
	    }
	    if(!empty($import['"Brand"'])){
	      $save_data['SurveyAnswer'][] = array_merge(array(
	        'question' => '5_what_brand', 
	        'answer' => $this->clearQuotes($import['"Brand"'])
	      ), $default_values);
	    }
	    
	    if($this->saveFirst($save_data)){
	      $count++;
	      if($verbose){
	        echo '.';
	      }
	    }
	  }
	  return $count;
	}
	
	/**
	  * Generate the token from an or if no email supplied, try $this->data[$this->alias]['email']
	  *
	  * @param string email to generate hash from (optional)
	  * @return mixed token if successful, false if unable to determin string to generate email from
	  */
	function __generateToken($email = null){
	  $base = null;
	  if($email){
	    $base = $email;
	  }
	  elseif(isset($this->data[$this->alias]['email'])){
	    $base = $this->data[$this->alias]['email'];
	  }
	  if(!$base){
	    return false;
	  }
	  return md5($base);
	}
	
	/**
	  * Save the first survey, no answer is truely required, unless we have
	  * an email address associated with it.  
	  * If we have an email address, be sure to save contact.
	  *
	  * @param array of data to save, contact and answers
	  * @return mixed result of saveAll
	  */
	function saveFirst($data){
	  //do not attempt to save the contact data if we don't have an email address,
	  if(isset($data[$this->alias]['email']) && empty($data[$this->alias]['email'])){
	    return $this->SurveyAnswer->saveAll($data['SurveyAnswer']);
	  }
	  
	  //Aftersave method specific for the first survey save.
	  $retval = $this->saveAll($data, array('validate' => 'first'));
	  if($retval){
	    $this->setFinalEmailDate();
	  }
	  return $retval;
	}
	
	/**
	  * Save the second survey, no answer is truly required.
	  * be sure to link the answers to the contact as we absolutely 
	  * have a contact.  Also upon success, make sure to set the survey
	  * to finished
	  * @param array of data to save
	  * @return mixed result of saveAll
	  */
	function saveSecond($data){
	  //Clean out blank answers
	  foreach($data['SurveyAnswer'] as $key => $answer){
	    if(!$answer['answer']){
	      unset($data['SurveyAnswer'][$key]);
	    }
	  }
	  
	  $retval = $this->saveAll($data, array('validate' => 'first'));
	  if($retval){
	    $this->finishSurvey();
	  }
	  return $retval;
	}
	
	/**
	  * Append entered_give_away to the data
	  * and pass to save
	  * Make sure we are at least 18
	  *
	  * @param array of data to save
	  * @return mixed results of save
	  */
	function enterGiveAway($data){
	  if(!isset($data[$this->alias]['is_18']) || !$data[$this->alias]['is_18']){
	    $this->invalidate('is_18', 'You must be 18 years old or older to enter drawing.');
	    return false;
	  }
	  $data[$this->alias]['entered_give_away'] = true;
	  return $this->save($data, array('validate' => 'first'));
	}
	
	/**
	  * Get the id of a contact by its email
	  * @param string email
	  * @return mixed id of contact with matching email or null
	  */
	function idByEmail($email = null){
	  return $this->field('id', array("{$this->alias}.email" => $email));
	}
	
	/**
	  * @param mixed id or email of contact
	  * @return boolean true if contact has finished the entire survey
	  */
	function isFinished($id_or_email = null){
	  if($id_or_email) $this->id = $id_or_email;
	  $retval = $this->field('finished_survey');
	  if($retval){
	    return $retval;
	  }
	  
	  $this->id = $this->idByEmail($id_or_email);
	  if($this->id){
	    return $this->field('finished_survey');
	  }
	  
	  return false;
	}
	
	/**
	  * Set the contact's survey to finished
	  * @param id of contact
	  * @return boolean success
	  */
	function finishSurvey($id = null){
	  if($id) $this->id = $id;
	  return $this->saveField('finished_survey', true);
	}
	
	/**
	  * Find a giveaway contact by their email
	  * a contact that qualifies for the give_away
	  * is a contact that has finished their survey
	  * @param email of contact
	  * @return mixed result of find
	  */
	function findByEmailForGiveAway($email){
	  return $this->find('first', array(
	    'conditions' => array(
	      "{$this->alias}.finished_survey" => true,
	      "{$this->alias}.email" => $email,
	    ),
	    'recursive' => -1
	  ));
	}
	
	/**
	  * Find a second survey contact by their email
	  * a contact that qualifies for the second survey
	  * is a contact that has not finished their survey
	  * @param email of contact
	  * @return mixed result of find
	  */
	function findByEmailForSecond($email){
	  return $this->find('first', array(
	    'conditions' => array(
	      "{$this->alias}.finished_survey" => false,
	      "{$this->alias}.email" => $email,
	    ),
	    'recursive' => -1
	  ));
	}
	
	/**
	  * Since the table is customizable, verify we have all the fields
	  * required for this plugin to function properly
	  * @return boolean success
	  */
	function __hasRequiredFields(){
	  $fields = array(
	    'first_name',
	    'last_name',
	    'email',
	    'phone',
	    'is_18',
	    'entered_give_away',
	    'finished_survey',
	    'final_email_sent_date',
	    'final_email_sent',
	    'created',
	  );
	  
	  foreach($fields as $field){
	    if(!$this->hasField($field)){
	      return false;
	    }
	  }
	  
	  return true;
	}
	
	/**
	  * Set the final_email_send_date of a spacific contact
	  * to days from now (default 30)
	  * This will also set the final_email_sent token to false
	  * @param int id of contact to alter
	  * @param int days from now (default 30)
	  * @return boolean success
	  */
	function setFinalEmailDate($id = null, $days_from_now = 30){
	  if($id) $this->id = $id;
	  $datetime = $this->time2datetime(mktime(0, 0, 0, date("m")  , date("d")+$days_from_now, date("Y")));
	  $retval = $this->saveField('final_email_sent_date', $datetime);
	  if($retval){
	    $this->saveField('final_email_sent', false);
	  }
	  return $retval;
	}
	
	/**
	  * Set the final_email_send of a spacific contact to true
	  *
	  * @param int id of contact to alter
	  * @return boolean success
	  */
	function finalEmailSent($id = null){
	  if($id) $this->id = $id;
	  return $this->saveField('final_email_sent', true);
	}
	
	/**
	  * Find all the contacts in which to send a final survey email
	  *
	  * @return mixed result of find
	  */
	function findAllToNotify(){
	  $start = $this->str2datetime('yesterday');
	  $end = $this->str2datetime('today');
	  
	  return $this->find('all', array(
	    'fields' => array(
	      "{$this->alias}.id",
	      "{$this->alias}.email",
	    ),
	    'conditions' => array(
	      "{$this->alias}.finished_survey" => false,
	      "{$this->alias}.final_email_sent" => false,
	      "{$this->alias}.final_email_sent_date >=" => $start,
	      "{$this->alias}.final_email_sent_date <=" => $end,
	    ),
	    'recursive' => -1
	  ));
	}
	
	/**
	  * Fix the import.
	  * Go through and make minor changes to the database, this is 
	  * used as a one time execute.
	  */
	function fixImport(){
	  $this->updateAll(
	    array('final_email_sent' => true),
	    /* Where */
	    array('final_email_sent' => false, 'finished_survey' => true)
	  );
	}

	/**
	  * Get the contact and answers of the final contact data organized for csv
	  */
	function exportFinal(){
	  $headers = array(
	    'SurveyContact' => array(
	      'id' => 'id',
	      'first_name' => 'first_name',
	      'last_name' => 'last_name',
	      'phone' => 'phone',
	      'entered_give_away' => 'entered_give_away',
	      'email' => 'email',
	      'created' => 'created',
	      '1_age' => '1_age',
	      '2_likely_to_schedule' => '2_likely_to_schedule',
	      '3_visit_clinic' => '3_visit_clinic',
	      '4_purchase_hearing_aid' => '4_purchase_hearing_aid',
	      '5_what_brand' => '5_what_brand',
	    )
	  );
	  
	  $contacts = $this->find('all', array(
	    'conditions' => array(
	      'SurveyContact.finished_survey' => true
	    ),
	    'contain' => array('SurveyAnswer')
	  ));
	  
	  $data = array($headers);
	  
	  foreach($contacts as $record){
	    $row = array(
	      'SurveyContact' => array(
	        'id' => $record['SurveyContact']['id'],
	        'first_name' => $record['SurveyContact']['first_name'],
	        'last_name' => $record['SurveyContact']['last_name'],
	        'phone' => $record['SurveyContact']['phone'],
	        'entered_give_away' => $record['SurveyContact']['entered_give_away'],
	        'email' => $record['SurveyContact']['email'],
	        'created' => $record['SurveyContact']['created'],
	        '1_age' => '',
	        '2_likely_to_schedule' => '',
	        '3_visit_clinic' => '',
	        '4_purchase_hearing_aid' => '',
	        '5_what_brand' => '',
	      )
	    );
	    foreach($record['SurveyAnswer'] as $answer){
	      $row['SurveyContact'][$answer['question']] = $answer['answer'];
	    }
	    $data[] = $row;
	  }
	  
	  return $data;
	}
}
?>