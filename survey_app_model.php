<?php

class SurveyAppModel extends AppModel {

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
          array('order' => "{$this->alias}.created DESC")
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
}

?>