<?php
class SurveyOptIn extends SurveyAppModel {
	var $name = 'SurveyOptIn';
	var $displayField = 'id';
	
	/**
	  * Add a single optin
	  * @param string parsable by str2time to be used as created
	  * @return result of save
	  */
	function add($date = 'now'){
	  $this->create();
	  return $this->save(array(
	    'created' => $this->str2datetime($date)
	  ));
	}
}
?>