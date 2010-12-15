<?php
App::import('Lib','Survey.SurveyUtil');
class SurveysController extends SurveyAppController {
  var $name = 'Surveys';
  var $layout = 'survey';
  
  /**
    * Uses is usually dirty, but we're going to want access to these models in almost every action
    * So might as well load them the CakePHP way.
    */
  var $uses = array('Survey.SurveyAnswer','Contact');
  
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
    $this->Security->requireLogin('export','admin_delete');
    if($this->RequestHandler->isAjax()){
      Configure::write('debug', 0);
    }
  }
  
  /**
  	* Save the second half of the survey, email, name, zip, etc. and send thanks
  	* @TODO SEND THE HAPPY EMAIL
  	*/
  function save_email(){
  	if(!empty($this->data)){
  		if($this->Contact->save($this->data)){
  			$this->__sendEmail($this->Contact->id); //Send the email
  			if($this->RequestHandler->isAjax()){
  				$this->autoRender = false;
  				return true;
  			}
  			$this->redirect(array('action' => 'thanks'));
  		}
  		else {
  			if($this->RequestHandler->isAjax()){
  				$this->autoRender = false;
  				return array_shift(array_values($this->Contact->validationErrors));
  			}
  		}
  	}
  }
  
  /**
  	* Save the survey 2 questions.
  	*/
	function first(){
		if(!empty($this->data)){
			$this->SurveyAnswer->saveData($this->data['SurveyAnswer']);
			if($this->RequestHandler->isAjax()){
				$this->autoRender = false;
				return true;
			}
		}
		else {
			$this->__saveOptIn();
		}
		$this->set('start_page', 'one');
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
    $this->Contact->contain();
    $contact = $this->Contact->findById($id);
    if($contact && isset($contact['Contact']['email'])){
    	$locations = $this->Contact->findLocations($id);
      $this->log("Sending {$options['template']} to {$contact['Contact']['email']}", 'email');
      $this->Email->reset();
      $this->Email->to = $contact['Contact']['email'];
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
  function export($type = 'answers'){
    $this->helpers[] = 'Survey.Csv';
    $this->layout = 'csv';
    if($this->RequestHandler->ext != 'csv'){
	    $this->redirect(array('action' => 'export', 'ext' => 'csv', $type));
	  }
	  
	  switch($type){
	    case 'answers':
        $model = 'SurveyAnswer';
        $data = ClassRegistry::init('Survey.SurveyAnswer')->export();
	      $filename = 'survey_answers.csv';
	      break;
	    case 'participants':
        $model = 'SurveyParticpant';
        $data = ClassRegistry::init('Survey.SurveyParticpant')->export();
	      $filename = 'survey_particpants.csv';
	      break;
	    case 'opt_ins':
        $model = 'SurveyOptIn';
        $data = ClassRegistry::init('Survey.SurveyOptIn')->export();
	      $filename = 'survey_opt_ins.csv';
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
      $results = $this->SurveyAnswer->findReport($this->data);
      $this->set('results', $results);
    }
  }
}
?>
