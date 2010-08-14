<?php
App::import('Lib','SurveyUtil');
class SurveyAppController extends AppController {
  
  var $helpers = array('Session','Html','Form', 'Js' => array('Prototype'));

  /**
    * Pass the message to session flash with goodFlash wrapper
    * @param string message to flash
    */
  function goodFlash($message = null){
    //$this->Session->setFlash($message, 'goodFlash');
  }
  
  /**
    * Pass the message to session flash with badFlash wrapper
    * @param string message to flash
    */
  function badFlash($message = null){
    //$this->Session->setFlash($message, 'badFlash');
  }
}

?>