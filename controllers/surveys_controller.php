<?php
class SurveysController extends SurveyAppController {
  var $name = 'Surveys';
  var $layout = 'survey';
  
  /**
    * Uses is usually dirty, but we're going to want access to these models in almost every action
    * So might as well load them the CakePHP way.
    */
  var $uses = array('Survey.SurveyAnswer', 'Survey.SurveyContact');
  
  var $components = array('Session','Security','Email');
  
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
        $this->__sendEmail($this->SurveyContact->id);
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
    $contact = $this->SurveyContact->findByToken($token);
    
    if(!$token){
      $this->badFlash('Token required.');
      $this->redirect('/');
    }
    elseif(empty($contact)){
      $this->badFlash('Invalid Token.');
      $this->redirect('/');
    }
    elseif(!empty($this->data)){
      $this->SurveyContact->saveSecond($this->data);
      $this->redirect(array('action' => 'give_away', $token));
    }
    
    $this->set('contact', $contact);
  }
  
  /**
    * Give Away page, coolect name, and phone number
    * Only show this page if contact by token is finished_survey
    *
    * @paran string token (required)
    */
  function give_away($token = null){
    $contact = $this->SurveyContact->findByTokenForGiveAway($token);
    if(!$token){
      $this->badFlash('Token required.');
      $this->redirect('/');
    }
    elseif(empty($contact)){
      $this->badFlash('Invalid Token.');
      $this->redirect('/');
    }
    elseif(!empty($this->data)){
      if($this->SurveyContact->enterGiveAway($this->data)){
        $this->goodFlash('Thank you.');
        $this->redirect('/');
      }
      else {
        //Unset the id so the form helper doesn't append it to the form action
        unset($this->data['SurveyContact']['id']);
        $this->badFlash('Please fill out every field.');
      }
    }
    
    $this->set('contact', $contact);
  }
  
  /**
    * Show thanks page
    */ 
  function thanks(){
    //TODO
  }
  
  /**
    * Send an email to the SurveyContact
    *
    * @param id of SurveyContact
    * @param array of options
    * - template (default thanks)
    * - subject
    * @return mixed result of send, or void
    */  
  private function __sendEmail($id = null, $options = array()){
    $options = array_merge(
      array(
        'template' => 'survey_thanks',
        'subject' => 'Thanks for participating in the survey!'
      ),
      $options
    );
    $contact = $this->SurveyContact->findById($id);
    if($contact && isset($contact['SurveyContact']['email'])){
      $this->log("Sending {$options['template']} to {$contact['SurveyContact']['email']}");
      $this->Email->reset();
      $this->Email->to = $contact['SurveyContact']['email'];
      $this->Email->subject = $options['subject'];
      $this->Email->template = $options['template'];
      $this->Email->sendAs = 'html';
      $this->Email->from = SurveyUtil::getConfig('email');
      $this->set('contact', $contact);
      return $this->Email->send();
    }
  }
  
  /**
    * Show reports based on survey
    */
  function admin_report(){
    //TODO
  }
}
?>
