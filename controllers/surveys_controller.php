<?php
class SurveysController extends SurveyAppController {
  var $name = 'Surveys';
  var $layout = 'survey';
  
  /**
    * Uses is usually dirty, but we're going to want access to these models in almost every action
    * So might as well load them the CakePHP way.
    */
  var $uses = array('Survey.SurveyContact');
  
  var $components = array('RequestHandler','Session','Security','Email');
  
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
    $start_page = 'one';
    if(!empty($this->data)){
      if($this->SurveyContact->saveFirst($this->data)){
        $this->goodFlash('Thank you message');
        $this->__sendEmail($this->SurveyContact->id);
        if($this->RequestHandler->isAjax()){
          $this->autoRender = false;
          return true;
        }
        $this->redirect(array('action' => 'thanks'));
      }
      else {
        if($this->RequestHandler->isAjax()){
          $this->autoRender = false;
          return array_shift(array_values($this->SurveyContact->validationErrors));
        }
        $this->badFlash('Email not valid, please try again.');
        $start_page = 'two';
      }
    }
    $this->set('start_page', $start_page);
  }
  
  /**
    * The second survey
    * Needs a contact email
    *
    * @param string email of survey_contact (required)
    */
  function second($email = null){
    $contact = $this->SurveyContact->findByEmailForSecond($email);
    
    if(!$email){
      $this->badFlash('Email required.');
      $this->redirect('/');
    }
    elseif(empty($contact)){
      $this->badFlash('Invalid Email.');
      $this->redirect('/');
    }
    elseif(!empty($this->data)){
      $this->SurveyContact->saveSecond($this->data);
      $this->redirect(array('action' => 'give_away', $email));
    }
    
    $this->set('contact', $contact);
  }
  
  /**
    * Give Away page, coolect name, and phone number
    * Only show this page if contact by email is finished_survey
    *
    * @paran string email (required)
    */
  function give_away($email = null){
    $contact = $this->SurveyContact->findByEmailForGiveAway($email);
    if(!$email){
      $this->badFlash('Email required.');
      $this->redirect('/');
    }
    elseif(empty($contact)){
      $this->badFlash('Invalid Email.');
      $this->redirect('/');
    }
    elseif(!empty($this->data)){
      if($this->SurveyContact->enterGiveAway($this->data)){
        $this->goodflash('Thank you. You have been entered for the drawing.');
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
    * Set the contact's resend email date to 30 days from now
    * This is meant to be called via ajax
    * @param email of contact
    */
  function resend($email = null){
    if($id = $this->SurveyContact->idByEmail($email)){
      return $this->SurveyContact->setFinalEmailDate($id);
    }
    return false;
  }
  
  /**
    * Show thanks page
    */ 
  function thanks(){
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
    $this->autoRender = true;
    $options = array_merge(
      array(
        'template' => 'survey_thanks',
        'subject' => 'Healthy Hearing - Thank You and Confirmation'
      ),
      $options
    );
    $this->SurveyContact->contain();
    $contact = $this->SurveyContact->findById($id);
    if($contact && isset($contact['SurveyContact']['email'])){
      $this->log("Sending {$options['template']} to {$contact['SurveyContact']['email']}", 'email');
      $this->Email->reset();
      $this->Email->to = $contact['SurveyContact']['email'];
      $this->Email->subject = $options['subject'];
      $this->Email->template = $options['template'];
      $this->Email->sendAs = 'html';
      $this->Email->from = SurveyUtil::getConfig('email');
      $this->Email->bcc = array('pdybala@healthyhearing.com');
      $this->set('contact', $contact);
      return $this->Email->send();
    }
  }
  
  /**
    * Send a test follow up email, this will be disabled in production
    */
  function send_test_follow($email = null){
    if(Configure::read() > 0){
      $contact_id = $this->SurveyContact->field('id', array('email' => $email));
      if($contact_id){
        $this->__sendEmail($contact_id, array(
          'subject' => 'Healthy Hearing Follow Up Survey',
          'template' => 'survey_follow_up'
        ));
      }
    }
    $this->redirect('/');
  }
  
  /**
    * Go through the databse and send the follow up email if needed.
    */
  function send_follow(){
    $contacts = $this->SurveyContact->findAllToNotify();
    if(!empty($contacts)){
      foreach($contacts as $contact){
        $sent = $this->__sendEmail($contact['SurveyContact']['id'], array(
          'subject' => 'Healthy Hearing Follow Up Survey',
          'template' => 'survey_follow_up'
        ));
        if($sent){
          $this->SurveyContact->finalEmailSent($contact['SurveyContact']['id']);
        }
      }
    }
    $this->redirect('/');
  }
  
  /**
	  * Vcard for the user to add to their address book.
	  */
	function reply_email(){
	  $this->helpers[] = 'Survey.Vcf';
	  $this->layout = 'vcf';
	  //If we don't have the extension vcf, force it
	  //this is needed for some browsers to prompt the download
	  if($this->RequestHandler->ext != 'vcf'){
	    $this->redirect(array('action' => 'reply_email', 'ext' => 'vcf'));
	  }
	}
  
  /**
    * Show reports based on survey
    */
  function admin_report(){
    if(!empty($this->data)){
      $results = ClassRegistry::init('SurveyAnswer')->findReport($this->data);
      $this->set('results', $results);
    }
  }
}
?>
