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
     * @return date time string for MYSQL
     */
   function str2datetime($str){
     return date("Y-m-d H:i:s", strtotime($str));
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
	function export(){
	  $columns = array_keys($this->schema());
	  $headers = array();
	  foreach($columns as $column){
	    $headers[$this->alias][$column] = $column;
	  }
	  $data = $this->find('all', array(
	    'contain' => array()
	  ));
	  
	  array_unshift($data, $headers);
	  return $data;
	}
}

?>