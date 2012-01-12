<?php
App::import('Lib', 'Survey.SurveyUtil');
//Mock::generatePartial('SurveyUtil', 'MockSurveyUtil', array('__getDataSource'));
class SurveyUtilTest extends CakeTestCase {
  var $SurveyUtil = null;

  function startTest(){
    $this->SurveyUtil = new SurveyUtil();
    Configure::write('Survey', null);
  }
  
  function testGetConfig(){
    $expected = array(
      'table',// => 'survey_contacts',
      'email',// => 'no_reply@webtechnick.com',
      'name',//  => 'Healthy Hearing'
      'debug',//  => false
      'cookietime',//  => 21600
      'httpauth', // => array('user' => 'pass')
      'ignore', // => array('nurvzy@gmail.com')
    );
    $this->assertEqual($expected, array_keys($this->SurveyUtil->getConfig()));
    
    $expected = 'survey_contacts';
    $this->assertEqual($expected, $this->SurveyUtil->getConfig('table'));
  }
  
  function testWriteConfig(){
    $this->SurveyUtil->writeConfig('foo', 'bar');
    $this->assertEqual('bar', $this->SurveyUtil->getConfig('foo'));
  }
  
  function endTest(){
    unset($this->SurveyUtil);
  }
}
?>