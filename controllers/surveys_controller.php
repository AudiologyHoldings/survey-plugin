<?php
class SurveysController extends SurveyAppController {
  var $name = 'Surveys';
  
  var $uses = array('Survey.SurveyAnswer');
  
  /**
    * Load the custom SurveyContact into the controller
    */
  function beforeFilter(){
    parent::beforeFilter();
    $this->SurveyContact = SurveyUtil::getModel();
  }
  
  /**
    * The first survey 
    * 2 questions + email contact
    */
  function first(){
    //TODO
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
