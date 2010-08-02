<?php
class SurveysController extends SurveyAppController {
  var $name = 'Surveys';
  
  /**
    * Uses is usually dirty, but we're going to want access to these models in almost every action
    * So might as well load them the CakePHP way.
    */
  var $uses = array('Survey.SurveyAnswer', 'Survey.SurveyContact');
  
  /**
    * Load any custom settings here
    */
  function beforeFilter(){
    parent::beforeFilter();
  }
  
  /**
    * The first survey 
    * 2 questions + email contact
    */
  function first(){
    if(!empty($this->data)){
      if($this->SurveyContact->saveFirst($this->data)){
        $this->goodFlash('Thank you message');
        $this->redirect(array('action' => 'thanks'));
      }
      else {
        $this->badFlash('Email not valid, please try again.');
      }
    }    
  }
  
  /**
    * The second survey
    * Needs a contact token
    *
    * @param string token of survey_contact (required)
    */
  function second($token = null){
    //TODO
  }
  
  /**
    * Give Away page, coolect name, and phone number
    * Only show this page if contact by token is finished_survey
    *
    * @paran string token (required)
    */
  function give_away($token = null){
    //TODO
  }
  
  /**
    * Show thanks page
    */ 
  function thanks(){
    //TODO
  }
  
  /**
    * Show reports based on survey
    */
  function admin_report(){
    //TODO
  }
}
?>
