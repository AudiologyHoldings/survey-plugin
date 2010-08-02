<?php
App::import('Lib', 'Survey.SurveyUtil');
//Mock::generatePartial('SurveyUtil', 'MockSurveyUtil', array('__getDataSource'));
class SurveyUtilTestCase extends CakeTestCase {
  var $SurveyUtil = null;

  function startTest(){
    $this->SurveyUtil = new SurveyUtil();
  }
  
  function testGetConfig(){
    $expected = array(
      'model' => 'SurveyContact'
    );
    $this->assertEqual($expected, $this->SurveyUtil->getConfig());
    
    $expected = 'SurveyContact';
    $this->assertEqual($expected, $this->SurveyUtil->getConfig('model'));
  }
  
  function endTest(){
    unset($this->SurveyUtil);
  }
}
?>