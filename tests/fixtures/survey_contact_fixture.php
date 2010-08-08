<?php
/* SurveyContact Fixture generated on: 2010-07-27 22:07:48 : 1280292888 */
class SurveyContactFixture extends CakeTestFixture {
	var $name = 'SurveyContact';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'token' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'phone' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20),
		'is_18' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'entered_give_away' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'finished_survey' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'first_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'email' => 'example@example.com',
			'token' => 'token',
			'phone' => 'Lorem ipsum dolor ',
			'is_18' => 1,
			'entered_give_away' => 0,
			'finished_survey' => 1,
			'created' => '2010-07-27 22:54:48'
		),
		array(
			'id' => 2,
			'first_name' => 'Nick',
			'last_name' => 'Baker',
			'email' => 'nick@example.com',
			'token' => 'token2',
			'phone' => 'Lorem ipsum dolor ',
			'is_18' => 0,
			'entered_give_away' => 0,
			'finished_survey' => 0,
			'created' => '2010-07-27 22:54:48'
		),
	);
}
?>