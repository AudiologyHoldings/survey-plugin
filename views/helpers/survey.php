<?php
class SurveyHelper extends AppHelper {
  /**
    * Load the view object so we can dynamically load an element into it
    * Load the Cookie Component so we can read from the cookie the CakePHP way
    */
  function __construct($settings = array()){
    $this->View = ClassRegistry::getObject('view');
    if(isset($settings['Cookie'])){
      $this->Cookie = $settings['Cookie'];
    }
    else {
      $this->__setupCookie();
    }
  }
  
  /**
    * Setup the cookie component to work in a helper.
    * we need this so we can write a cookie if we show the popup.
    * @access private
    */
  function __setupCookie(){
    App::import('Component', 'Cookie');
    $this->Cookie = new CookieComponent();
    $this->Cookie->initialize(new Object()/* ignored */, array(
      'time' => '1 Month'
    )); //init so we can write to the cookie
    $this->Cookie->startup(); //setup so we can read from the cookie
  }
  
  /**
    * Decides if the popup should be shown or not
    * If we're in debug mode, always show the popup.
    * @return boolean
    */
  function popupDisplayed(){
    return Configure::read('Survey.debug') ? false : !!$this->Cookie->read('Survey.token');  
  }
  
  /**
    * Show the popup element if we haven't displayed it to the user before
    * @return mixed popup element or null
    */
  function showPopup(){
    if(!$this->popupDisplayed()){
      $this->Cookie->write('Survey.token', md5(date('Y-m-d H:i:s')));
      return $this->View->element('survey_popup', array('plugin' => 'survey')); 
    }
    return null;
  }
}
?>