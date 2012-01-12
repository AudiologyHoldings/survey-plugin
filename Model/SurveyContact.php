<?php
App::import('Lib','Survey.SurveyUtil');
class SurveyContact extends SurveyAppModel {
	var $name = 'SurveyContact';
	var $displayField = 'email';
	var $validate = array(
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
		* Save first, once saved attempt to update the previous answers with the created id
		* Once that's done, create the contact in the Contact model and return true
		*
		* @param array data to save
		* @param array survey_answer_ids to update with created contact
		* @param array of options
		* @return boolean success
		*/
	function saveData($data, $survey_answer_ids = array(), $options = array()){
		$retval = false;
		if($this->save($data, $options)){
			//subscribe to the contact table
			$this->saveContact($data);
			if(is_array($survey_answer_ids) && !empty($survey_answer_ids)){
				foreach($survey_answer_ids as $id){
					if($id){
						$this->log("saving answerId: $id with survey_contact_id: {$this->id}", 'survey_debug');
						$this->SurveyAnswer->id = $id;
						$retval = $this->SurveyAnswer->saveField('survey_contact_id', $this->id);
					}
				}
			}
			else {
				$retval = true;
			}
		}
		return $retval;
	}
	
	/**
	* Save the current contact to the App Contact model
	* @param array of data
	* @return boolean success
	*/
	function saveContact($data){
		return ClassRegistry::init('Contact')->save($data);
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
	  * Take in a value and strip the quotes
	  */
	function clearQuotes($value){
	  return str_replace('"','', $value);
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
	  * Since the table is customizable, verify we have all the fields
	  * required for this plugin to function properly
	  * @return boolean success
	  */
	function __hasRequiredFields(){
	  $fields = array(
	    'first_name',
	    'last_name',
	    'email',
	    'zip',
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
	    $this->deleteAll(array('SurveyContact.email LIKE' => $email));
	    $count += $this->getAffectedRows();
	  }
	  return $count;
	}
}
?>