<?php
App::import('Lib','Survey.SurveyUtil');
class SurveysController extends SurveyAppController {
  var $name = 'Surveys';
  var $layout = 'survey';
  
  var $components = array('RequestHandler','Session','Security','Email');
  
  var $helpers = array('Survey.Survey');
  
  /**
    * Load any custom settings here
    */
  function beforeFilter(){
    parent::beforeFilter();
    $this->Security->loginOptions = array(
      'type' => 'basic',
      'realm' => 'Survey'
    );
    $this->Security->loginUsers = SurveyUtil::getConfig('httpauth'); //users are set in config/survey.php
    if(!empty($this->Security->loginUsers)){
    	$this->Security->requireLogin('export','admin_delete');
    }
    if($this->RequestHandler->isAjax()){
      Configure::write('debug', 0);
    }
  }
  
  /**
  	* Save the survey 2 questions.
  	*/
	function first(){
		if(!empty($this->data)){
			if($this->Survey->saveFirst($this->data)){
				if($this->RequestHandler->isAjax()){
					$this->autoRender = false;
					return $this->Survey->id;
				}
			}
			else {
				if($this->RequestHandler->isAjax()){
					$this->autoRender = false;
					return 'ERROR: ' . array_shift(array_values($this->Survey->validationErrors));
				}
			}
		}
		else {
			$this->__saveOptIn();
		}
		$this->set('start_page', 'one');
	}
  
  /**
  	* Save the second half of the survey, email, name, zip, etc. and send thanks
  	*/
  function save_email(){
  	if(!empty($this->data)){
  		if($this->Survey->saveSecond($this->data)){
  			$this->__sendEmail($this->Survey->id); //Send the email
  			if($this->RequestHandler->isAjax()){
  				$this->autoRender = false;
  				return true;
  			}
  			$this->redirect(array('action' => 'thanks'));
  		}
  		else {
  			if($this->RequestHandler->isAjax()){
  				$this->autoRender = false;
  				return 'ERROR: ' . array_shift(array_values($this->Survey->validationErrors));
  			}
  		}
  	}
  }
	
  /**
    * Save a participant (Continue click)
    */
  function save_participant(){
    if($this->RequestHandler->isAjax()){
      ClassRegistry::init('Survey.SurveyParticipant')->add();
      return true;
    }
    $this->setAction('first');
  }
  
  
  /**
    * Show thanks page
    */ 
  function thanks(){
  }
  
  /**
    * Send an email to the Contact
    *
    * @param id of Contact
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
    $this->Survey->contain();
    $contact = $this->Survey->findById($id);
    if($contact && isset($contact['Survey']['email'])){
    	$locations = ClassRegistry::init('Location')->findAllByZip($contact['Survey']['zip']);
      $this->log("Sending {$options['template']} to {$contact['Survey']['email']}", 'email');
      $this->Email->reset();
      $this->Email->to = $contact['Survey']['email'];
      $this->Email->subject = $options['subject'];
      $this->Email->template = $options['template'];
      $this->Email->sendAs = 'html';
      $this->Email->from = SurveyUtil::getConfig('email');
      $this->Email->bcc = array('pdybala@healthyhearing.com');
      $this->Email->attachments = array(WWW_ROOT.'files/pdf/happy/healthyhearing_comprehensive_guide_get_happy_0710.pdf');
      $this->set(compact('contact', 'locations'));
      return $this->Email->send();
    }
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
    * CSV export system.
    */
    function export($type = 'finals'){
    	$this->helpers[] = 'Survey.Csv';
    	$this->layout = 'csv';
    	if($this->RequestHandler->ext != 'csv'){
    		$this->redirect(array('action' => 'export', 'ext' => 'csv', $type));
    	}
    	
    	switch($type){
				case 'participants':
					$model = 'SurveyParticpant';
					$data = ClassRegistry::init('Survey.SurveyParticpant')->export();
					$filename = 'particpants.csv';
					break;
				case 'opt_ins':
					$model = 'SurveyOptIn';
					$data = ClassRegistry::init('Survey.SurveyOptIn')->export();
					$filename = 'opt_ins.csv';
					break;
				case 'finals':
					$model = 'Survey';
					$data = $this->Survey->export();
					$filename = 'finals.csv';
					break;
    		default: $this->redirect('/');
    	}
    	
    	$this->set(compact('data','filename','model'));
    }
	
	/**
	  * This will create a new record of the day/time the "I WILL HELP" button was clicked
	  * @return void
	  * @access private
	  */
	function __saveOptIn(){
	  ClassRegistry::init('Survey.SurveyOptIn')->add();
	}
  
  /**
    * Show reports based on survey
    */
  function admin_report(){
    if(!empty($this->data)){
      $results = $this->Survey->findReport($this->data);
      $this->set('results', $results);
    }
  }
  
  /**
    * Search for contacts by their email.
    */
  function admin_search(){
    if(!empty($this->data)){
      $contacts = $this->Survey->findAllByEmail($this->data);
      $this->set('contacts', $contacts);
    }
  }
  
  /**
    * Delete a specific contact and their answers.
    */
  function admin_delete($id = null){
    $this->Survey->contain();
    if($id && $this->Survey->findById($id)){
      $this->Survey->delete($id);
    }
    $this->redirect(array('prefix' => 'admin', 'action' => 'search'));
  }
}
?>
