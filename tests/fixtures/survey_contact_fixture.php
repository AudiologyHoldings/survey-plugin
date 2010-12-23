<?php
/* SurveyContact Fixture generated on: 2010-07-27 22:07:48 : 1280292888 */
class SurveyContactFixture extends CakeTestFixture {
	var $name = 'SurveyContact';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'zip' => array('type' => 'string', 'null' => false, 'default' => NULL),
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
			'zip' => '11111',
			'created' => '2010-07-27 22:54:48'
		),
		array(
			'id' => 2,
			'first_name' => '',
			'last_name' => '',
			'email' => 'nick@example.com',
			'zip' => '12345',
			'created' => '2010-07-27 22:54:48'
		),
		array(
			'id' => 3,
			'first_name' => '',
			'last_name' => '',
			'email' => 'nbaker@example.com',
			'zip' => '12345',
			'created' => '2010-07-27 22:54:48'
		),
		array(
			'id' => 4,
			'first_name' => '',
			'last_name' => '',
			'email' => 'nurvzy@gmail.com', //ignored by settings
			'zip' => '12345',
			'created' => '2010-07-27 22:54:48'
		),
		array(
			'id' => 5,
			'first_name' => '',
			'last_name' => '',
			'email' => 'paul@example.com',
			'zip' => '12345',
			'created' => '2010-07-27 22:54:48'
		),
	);
}
?>