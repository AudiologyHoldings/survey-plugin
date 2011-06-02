<?php
App::import('Lib','Survey.SurveyUtil');
class SurveyHelper extends AppHelper {
	var $helpers = array('Number');
  /**
    * Time (in seconds) from when the first survey is show,
    * to the time the second and final one should be shown.
    */
  var $firstCookieLength = 21600; //6 hours
  
  /**
  * Timer to show survey.
  */
  var $timer = 0;
  
  /**
    * Load the view object so we can dynamically load an element into it
    * Load the Cookie Component so we can read from the cookie the CakePHP way
    * Overwrite firstCookieLength if we have a setting for it in config
    */
  function __construct($settings = array()){
    $this->View = ClassRegistry::getObject('view');
    if(isset($settings['Cookie'])){
      $this->Cookie = $settings['Cookie'];
    }
    else {
      $this->__setupCookie();
    }
    
    if(SurveyUtil::getConfig('cookietime')){
      $this->firstCookieLength = SurveyUtil::getConfig('cookietime');
    }
    if(SurveyUtil::getConfig('surveyDelay')){
      $this->timer = SurveyUtil::getConfig('surveyDelay');
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
    * Write the cookie, first or second one depending on certain conditions
    * @param int number (default 1)
    * @return void
    */
  function __writeCookie($number = 1){
    $time = $number == 1 ? time() + $this->firstCookieLength : time();
    $this->Cookie->write('Survey', array(
      'number' => $number,
      'time' => $time
    ), false);
  }
  
  /**
  	* Convert to percentage
  	* @param float value
  	* @param float total
  	* @return string percent of value / total * 100
  	*/
  function toPercent($value, $total = 0){
  	if($total){
  		return $this->Number->toPercentage($value / $total * 100);
  	}
  	return '0%';
  }
  
  /**
    * Decides if the popup should be shown or not
    * If we're in debug mode, always show the popup.
    * @return boolean
    */
  function shouldDisplayPopup(){
  	//never show popup if not active
  	if(SurveyUtil::getConfig('active') === false){
  		return false;
  	}
    //always show popup if 
    if(SurveyUtil::getConfig('debug')){
      return true;
    }
    
    $cookie = $this->Cookie->read('Survey');
    if($cookie){
      if($cookie['number'] == 1 && $cookie['time'] <= time()){
        $this->__writeCookie(2);
        return true;
      }
    }
    else {
      $this->__writeCookie(1);
      return true;
    }
    
    return false;
  }
  
  /**
    * Show the popup element if we haven't displayed it to the user before
    * @param boolean log (default true)
    * @return mixed popup element or null
    */
  function showPopup($log = true){
    if($this->shouldDisplayPopup()){
      if($log){
        $this->log("Survey Popup shown to: {$_SERVER['REMOTE_ADDR']} on page: {$this->View->here}", 'survey_popup');
      }
      return $this->View->element('survey_popup', array('plugin' => 'survey', 'timer' => $this->timer * 1000)); 
    }
    /**
    	* Survey sidebar is no longer needed, but keeping around in case we ever want to
    	* bring it back.
    	*/
    //return $this->View->element('survey_sidebar', array('plugin' => 'survey'));
  }
}
?>