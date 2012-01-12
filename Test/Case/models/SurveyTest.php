<?php
/* Survey Test cases generated on: 2011-01-11 11:01:08 : 1294772288*/
App::import('Model', 'survey.Survey');

class SurveyTest extends CakeTestCase {
	var $fixtures = array(
		'plugin.survey.survey'
	);

	function startTest() {
		$this->Survey =& ClassRegistry::init('Survey');
	}
	
	function testSaveFirst(){
		$data = array(
			'Survey' => array(
				'1_age' => '18-30',
				'2_likely_to_schedule' => 6
			)
		);
		
		$this->assertTrue($this->Survey->saveFirst($data));
		$result = $this->Survey->find('last');
		$this->assertEqual('18-30', $result['Survey']['1_age']);
		$this->assertEqual('6', $result['Survey']['2_likely_to_schedule']);
		$this->assertTrue(empty($this->Survey->validate));
	}
	
	function testSaveFirstValidationFail(){
		$data = array(
			'Survey' => array(
				'1_age' => '18-30',
				'2_likely_to_schedule' => ''
			)
		);
		
		$this->assertFalse($this->Survey->saveFirst($data));
		$this->assertTrue(!empty($this->Survey->validationErrors['2_likely_to_schedule']));
		$this->assertTrue(empty($this->Survey->validate));
	}
	
	function testSaveSecond(){
		$data = array(
			'Survey' => array(
				'id' => 1,
				'first_name' => 'Nick',
				'last_name' => 'Baker',
				'email' => 'nick@example.com',
				'zip' => '87123',
			)
		);
		
		$this->assertTrue($this->Survey->saveSecond($data));
		$result = $this->Survey->find('last');
		$this->assertEqual('Nick', $result['Survey']['first_name']);
		$this->assertEqual('Baker', $result['Survey']['last_name']);
		$this->assertEqual('nick@example.com', $result['Survey']['email']);
		$this->assertEqual('87123', $result['Survey']['zip']);
		$this->assertTrue(empty($this->Survey->validate));
	}
	
	function testSaveSecondValidationFail(){
		$data = array(
			'Survey' => array(
				'id' => 1,
				'first_name' => 'Nick',
				'last_name' => 'Baker',
				'email' => 'nick', //fail
				'zip' => '87123',
			)
		);
		
		$this->assertFalse($this->Survey->saveSecond($data));
		$this->assertTrue(!empty($this->Survey->validationErrors['email']));
		$this->assertTrue(empty($this->Survey->validate));
	}
	
	function testSaveSecondNoIdFail(){
		$data = array(
			'Survey' => array(
				'id' => '',
				'first_name' => 'Nick',
				'last_name' => 'Baker',
				'email' => 'nick@example.com',
				'zip' => '87123',
			)
		);
		
		$this->assertFalse($this->Survey->saveSecond($data));
		$this->assertTrue(!empty($this->Survey->validationErrors['no_session']));
		$this->assertTrue(empty($this->Survey->validate));
	}

	function endTest() {
		unset($this->Survey);
		ClassRegistry::flush();
	}

}
?>