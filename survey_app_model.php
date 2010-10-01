<?php
class SurveyAppModel extends AppModel {
  
  var $actsAs = array('Containable');

  /**
    * Overwrite find so I can do some nice things with it.
    * @param string find type
    * - last : find last record by created date
    * @param array of options
    */
  function find($type, $options = array()){
    switch($type){
      case 'last':
        $options = array_merge(
          $options,
          array('order' => "{$this->alias}.id DESC")
        );
        return parent::find('first', $options);    
      default: 
        return parent::find($type, $options);
    }
  }
  
  /**
     * String to datetime stamp
     * @param string that is parsable by str2time
     * @param boolean entire_day, if true an additonal 86399 seconds will be appened to the day. 23:59:59
     * @return date time string for MYSQL
     */
   function str2datetime($str, $entire_day = false){
     $time = $entire_day ? strtotime($str) + '86399' : strtotime($str);
     return date("Y-m-d H:i:s", $time);
   }
   
   /**
     * time to datetime stamp
     * @param int timestamp
     * @return date time string for MYSQL
     */
   function time2datetime($time){
     return date("Y-m-d H:i:s", $time);
   }
   
   /**
	  * Export the current model table into a csv file.
	  */
	function export($options = array()){
	  $default_options = array(
	    'contain' => array()
	  );
	  
	  $options = array_merge($default_options, $options);
	  
	  $columns = array_keys($this->schema());
	  $headers = array();
	  foreach($columns as $column){
	    $headers[$this->alias][$column] = $column;
	  }
	  $data = $this->find('all', $options);
	  
	  array_unshift($data, $headers);
	  return $data;
	}
	
	/**
	  * Helper util to retrieve the ignore list.
	  */
	function getIgnoreList(){
	  App::import('Lib','Survey.SurveyUtil');
	  return SurveyUtil::getConfig('ignore');
	}
	
	/**
	  * Get the ignore conditions for ignoreing the email setup by config
	  * @return array of email ignore conditions.
	  */
	function getIgnoreConditions(){
	  $retval = array();
	  foreach($this->getIgnoreList() as $email){
	    $retval[]['SurveyContact.email NOT LIKE'] = $email;
	  }
	  return $retval;
	}
}

?>