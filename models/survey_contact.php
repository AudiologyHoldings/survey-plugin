<?php
App::import('Lib','Survey.SurveyUtil');
class SurveyContact extends SurveyAppModel {
	var $name = 'SurveyContact';
	var $displayField = 'email';
	var $validate = array(
		'email' => array(
			'email' => array(
				'rule' => array('email'),
			),
			'unique' => array(
			  'rule' => array('isUnique')
			)
		),
		'token' => array(
			'notempty' => array(
				'rule' => array('notempty'),				
			),
			'unique' => array(
			  'rule' => array('isUnique')
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
	}
	
	/**
	  * Build the token if we need to.
	  */
	function beforeSave(){
	  if(!isset($this->data[$this->alias]['token']) || empty($this->data[$this->alias]['token'])){
	    $this->data[$this->alias]['token'] = $this->__generateToken();
	  }
	  return true;
	}
	
	/**
	  * Generate the token from an or if no email supplied, try $this->data[$this->alias]['email']
	  *
	  * @param string email to generate hash from (optional)
	  * @return mixed token if successful, false if unable to determin string to generate token from
	  */
	function __generateToken($email = null){
	  $base = $email ? $email : isset($this->data[$this->alias]['email']) ? $this->data[$this->alias]['email'] : null;
	  if(!$base){
	    return false;
	  }
	  return md5($base);
	}
	
	/**
	  * Save the first survey, no answer is truely required, unless we have
	  * an email address associated with it.  
	  * If we have an email address, be sure to save contact.
	  * @param array of data to save, contact and answers
	  * @return mixed result of saveAll
	  */
	function saveFirst($data){
	  //do not attempt to save the contact data if we don't have an email address,
	  if(isset($data[$this->alias]['email']) && empty($data[$this->alias]['email'])){
	    return $this->SurveyAnswer->saveAll($data['SurveyAnswer']);
	  }
	  return $this->saveAll($data, array('validate' => 'first'));
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
	function enterGiveAaway($data){
	  if(!isset($data[$this->alias]['is_18']) || !$data[$this->alias]['is_18']){
	    $this->invalidate('is_18', 'You must be 18 years old or older to enter drawing.');
	    return false;
	  }
	  $data[$this->alias]['entered_give_away'] = true;
	  return $this->save($data, array('validate' => 'first'));
	}
	
	/**
	  * Get the id of a contact by its token
	  * @param string token
	  * @return mixed id of contact with matching token or null
	  */
	function idByToken($token = null){
	  return $this->field('id', array("{$this->alias}.token" => $token));
	}
	
	/**
	  * @param mixed id or token of contact
	  * @return boolean true if contact has finished the entire survey
	  */
	function isFinished($id_or_token = null){
	  if($id_or_token) $this->id = $id_or_token;
	  $retval = $this->field('finished_survey');
	  if($retval){
	    return $retval;
	  }
	  
	  $this->id = $this->idByToken($id_or_token);
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

}
?>