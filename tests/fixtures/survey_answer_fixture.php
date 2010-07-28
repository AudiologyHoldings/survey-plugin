<?php
/* SurveyAnswer Fixture generated on: 2010-07-27 22:07:40 : 1280292760 */
class SurveyAnswerFixture extends CakeTestFixture {
	var $name = 'SurveyAnswer';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'question' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'answer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'survey_contact_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'question' => 'Lorem ipsum dolor sit amet',
			'answer' => 'Lorem ipsum dolor sit amet',
			'survey_contact_id' => 1,
			'created' => '2010-07-27 22:52:40'
		),
	);
}
?>