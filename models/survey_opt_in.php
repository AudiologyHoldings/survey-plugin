<?php
class SurveyOptIn extends SurveyAppModel {
	var $name = 'SurveyOptIn';
	var $displayField = 'id';
	
	/**
	  * Add a single optin
	  */
	function add(){
	  $data = array();
	  return $this->save($data);
	}
}
?>