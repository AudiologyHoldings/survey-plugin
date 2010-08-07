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
		'phone' => array(
			'phone' => array(
				'rule' => array('phone'),
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
	  return parent::saveAll($data, array('validate' => 'first'));
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
	  * TODO
	  */
	function finishSurvey($id = null){
	}

}
?>