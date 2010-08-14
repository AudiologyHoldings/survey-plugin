<?php 
/* SVN FILE: $Id$ */
/* TestApp schema generated on: 2010-07-27 22:07:11 : 1280292971*/
class TestAppSchema extends CakeSchema {
	var $name = 'TestApp';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $survey_answers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'question' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'answer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'survey_contact_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $survey_contacts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'phone' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20),
		'is_18' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'entered_give_away' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'finished_survey' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
}
?>