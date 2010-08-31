<?php
App::import('Component', 'Cookie');
App::import('Helper', 'Survey.Survey');
App::import('Core', 'View');
Mock::generate('CookieComponent');
Mock::generate('View');
class SurveyHelperTest extends CakeTestCase {
  var $Survey = null;

  function startTest(){
    $this->Survey = new SurveyHelper(array(
      'Cookie' => new MockCookieComponent()
    ));
    $this->Survey->View = new MockView();
    Configure::write('Survey.debug', false);
  }
  
  function testpopupDisplayed(){
    $this->Survey->Cookie->setReturnValue('read', false);
    $this->assertFalse($this->Survey->popupDisplayed());
  }
  
  function testpopupDisplayedTrue(){
    $this->Survey->Cookie->setReturnValue('read', true);
    $this->assertTrue($this->Survey->popupDisplayed());
  }
  
  function testShowPopupShouldShowPopup(){
    $this->Survey->View->expectOnce('element', array('survey_popup', array('plugin' => 'survey')));
    $this->Survey->Cookie->expectOnce('write');
    $this->Survey->Cookie->setReturnValue('read', false);
    $this->Survey->View->setReturnValue('element', true);
    
    $this->assertTrue($this->Survey->showPopup());
  }
  
  function testShowPopupShouldNotShowPopup(){
    $this->Survey->View->expectNever('element');
    $this->Survey->Cookie->expectNever('write');
    $this->Survey->Cookie->setReturnValue('read', true);
    
    $this->assertFalse($this->Survey->showPopup());
  }
  
  function endTest(){
    unset($this->Survey);
  }
}
?>