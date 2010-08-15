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
	  parent::__construct($id, $table, $ds);
	  $this->useTable = SurveyUtil::getConfig('table');
	  if(!$this->useTable){
	    $this->useTable = 'survey_contacts';
	  }
	  if(!$this->__hasRequiredFields()){
	    trigger_error("Required fields not present in {$this->useTable}.");
	  }
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
	  $retval = $this->saveAll($data);
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
	  * @param int id of contact to alter
	  * @param int days from now (default 30)
	  * @return boolean success
	  */
	function setFinalEmailDate($id = null, $days_from_now = 30){
	  if($id) $this->id = $id;
	  $datetime = $this->time2datetime(mktime(0, 0, 0, date("m")  , date("d")+$days_from_now, date("Y")));
	  return $this->saveField('final_email_sent_date', $datetime);
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
	      "{$this->alias}.final_email_sent_date >=" => $start,
	      "{$this->alias}.final_email_sent_date <=" => $end,
	    ),
	    'recursive' => -1
	  ));
	}

}
?>