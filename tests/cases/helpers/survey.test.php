<?php
App::import('Component', 'Cookie');
App::import('Helper', 'Survey.Survey');
App::import('Core', 'View');
Mock::generate('CookieComponent');
Mock::generate('View');
App::import('Lib','Survey.SurveyUtil');
class SurveyHelperTest extends CakeTestCase {
  var $Survey = null;

  function startTest(){
    $this->Survey = new SurveyHelper(array(
      'Cookie' => new MockCookieComponent()
    ));
    $this->Survey->View = new MockView();
    SurveyUtil::writeConfig('debug', false);
  }
  
  function testshouldDisplayPopupIfNoCookie(){
    $this->Survey->Cookie->setReturnValue('read', false);
    $this->assertTrue($this->Survey->shouldDisplayPopup());
  }
  
  function testshouldDisplayPopupIfFirstCookieButNextDay(){
    $this->Survey->Cookie->setReturnValue('read',
      array(
        'number' => 1,
        'time' => time()
      )
    );
    $this->assertTrue($this->Survey->shouldDisplayPopup());
  }
  
  function testshouldDisplayPopupIfFirstCookieButNotNextDay(){
    $this->Survey->Cookie->setReturnValue('read',
      array(
        'number' => 1,
        'time' => time() + 1000 //do not display
      )
    );
    $this->assertFalse($this->Survey->shouldDisplayPopup());
  }
  
  function testshouldDisplayPopupIfSecondCookie(){
    $this->Survey->Cookie->setReturnValue('read',
      array(
        'number' => 2,
        'time' => time()
      ),
      false
    );
    $this->assertFalse($this->Survey->shouldDisplayPopup());
  }
  
  function testshouldDisplayPopupTrue(){
    $this->Survey->Cookie->setReturnValue('read', true);
    $this->assertFalse($this->Survey->shouldDisplayPopup());
  }
  
  function testWriteSecondCookie(){
    $this->Survey->Cookie->expectOnce('write', array(
      'Survey',
      array(
        'number' => 2,
        'time' => time(),
      ),
      false
    ));
    $this->Survey->__writeCookie(2);
  }
  
  function testWriteFirstCookie(){
    $this->Survey->Cookie->expectOnce('write', array(
      'Survey',
      array(
        'number' => 1,
        'time' => time() + $this->Survey->firstCookieLength
      ),
      false
    ));
    $this->Survey->__writeCookie(1);
  }
  
  /*function testshouldDisplayPopupIfDebug(){
    $this->Survey->Cookie->setReturnValue('read', false);
    $this->assertFalse($this->Survey->shouldDisplayPopup());
    SurveyUtil::writeConfig('debug', true);
    $this->assertTrue($this->Survey->shouldDisplayPopup());
  }*/
  
  function testShowPopupShouldShowPopup(){
    $this->Survey->View->expectOnce('element', array('survey_popup', array('plugin' => 'survey')));
    $this->Survey->Cookie->expectOnce('write');
    $this->Survey->Cookie->setReturnValue('read', false);
    $this->Survey->View->setReturnValue('element', true);
    $log = false;
    $this->assertTrue($this->Survey->showPopup($log));
  }
  //Depreciated
  /*function testShowPopupShouldNotShowPopup(){
    $this->Survey->View->expectOnce('element', array('survey_sidebar', array('plugin' => 'survey')));
    $this->Survey->Cookie->expectNever('write');
    $this->Survey->Cookie->setReturnValue('read', true);
    $log = false;
    $this->assertFalse($this->Survey->showPopup($log));
  }*/
  
  function endTest(){
    unset($this->Survey);
  }
}
?>