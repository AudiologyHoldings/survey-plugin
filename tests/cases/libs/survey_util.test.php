<?php
App::import('Lib', 'Survey.SurveyUtil');
//Mock::generatePartial('SurveyUtil', 'MockSurveyUtil', array('__getDataSource'));
class SurveyUtilTestCase extends CakeTestCase {
  var $SurveyUtil = null;

  function startTest(){
    $this->SurveyUtil = new SurveyUtil();
    Configure::write('Survey', null);
  }
  
  function testGetConfig(){
    $expected = array(
      'table' => 'survey_contact',
      'email' => 'no_reply@webtechnick.com'
    );
    $this->assertEqual($expected, $this->SurveyUtil->getConfig());
    
    $expected = 'survey_contact';
    $this->assertEqual($expected, $this->SurveyUtil->getConfig('table'));
  }
  
  function endTest(){
    unset($this->SurveyUtil);
  }
}
?>